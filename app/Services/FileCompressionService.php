<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

class FileCompressionService
{
    private const TARGET_BYTES = 5 * 1024 * 1024;

    private const PDF_COMPRESSION_THRESHOLD_BYTES = 2 * 1024 * 1024;

    private const MAX_IMAGE_PIXELS = 20_000_000;

    /**
     * @return array{path: string|false, notice: string|null}
     */
    public function store(UploadedFile $file, string $directory): array
    {
        if ($file->getMimeType() === 'application/pdf') {
            return $this->storePdf($file, $directory);
        }

        $optimization = $this->optimize($file);
        $extension = $this->extensionFor($file);

        try {
            $storedPath = Storage::disk('public')->putFileAs(
                $directory,
                $optimization['path']
                    ? new File($optimization['path'])
                    : $file,
                bin2hex(random_bytes(16)).'.'.$extension
            );
        } finally {
            if ($optimization['path'] && is_file($optimization['path'])) {
                unlink($optimization['path']);
            }
        }

        return [
            'path' => $storedPath,
            'notice' => $optimization['notice'],
        ];
    }

    /**
     * @return array{path: string|false, notice: string|null}
     */
    public function storePdf(UploadedFile $file, string $directory): array
    {
        if ($file->getSize() <= self::PDF_COMPRESSION_THRESHOLD_BYTES) {
            try {
                return [
                    'path' => $file->store($directory, 'public') ?: false,
                    'notice' => null,
                ];
            } catch (\Throwable $exception) {
                Log::error('Could not store an uploaded PDF.', [
                    'exception' => $exception::class,
                ]);

                return [
                    'path' => false,
                    'notice' => 'PDF gagal disimpan. Silakan coba kembali.',
                ];
            }
        }

        try {
            $optimization = $this->compressPdf($file);
        } catch (\Throwable $exception) {
            Log::error('PDF compression failed before storage.', [
                'exception' => $exception::class,
            ]);

            return [
                'path' => false,
                'notice' => 'PDF gagal dikompres; file tidak disimpan.',
            ];
        }

        $compressedPath = $optimization['path'];

        if (! $compressedPath || ! is_file($compressedPath)) {
            return [
                'path' => false,
                'notice' => $optimization['notice']
                    ?? 'PDF tidak dapat dikompres; file tidak disimpan.',
            ];
        }

        try {
            $compressedSize = filesize($compressedPath);

            if (
                $compressedSize === false
                || $compressedSize >= $file->getSize()
                || ! $this->isPdf($compressedPath)
            ) {
                return [
                    'path' => false,
                    'notice' => 'PDF hasil kompresi tidak valid atau tidak lebih kecil; file tidak disimpan.',
                ];
            }

            $storedPath = Storage::disk('public')->putFileAs(
                $directory,
                new \Illuminate\Http\File($compressedPath),
                bin2hex(random_bytes(16)).'.pdf'
            ) ?: false;
        } catch (\Throwable $exception) {
            Log::error('Could not store the compressed PDF.', [
                'exception' => $exception::class,
            ]);

            return [
                'path' => false,
                'notice' => 'PDF hasil kompresi gagal disimpan; file tidak disimpan.',
            ];
        } finally {
            if (is_file($compressedPath)) {
                unlink($compressedPath);
            }
        }

        return [
            'path' => $storedPath,
            'notice' => $optimization['notice'],
        ];
    }

    /**
     * @return array{path: string|null, notice: string|null}
     */
    private function optimize(UploadedFile $file): array
    {
        if ($file->getSize() <= self::TARGET_BYTES) {
            return ['path' => null, 'notice' => null];
        }

        return match ($file->getMimeType()) {
            'image/jpeg', 'image/png', 'image/webp' => $this->compressImage($file),
            default => ['path' => null, 'notice' => null],
        };
    }

    /**
     * @return array{path: string|null, notice: string|null}
     */
    private function compressPdf(UploadedFile $file): array
    {
        $executable = $this->findGhostscriptExecutable();

        if (! $executable) {
            Log::warning('PDF compression skipped because Ghostscript is unavailable.');

            return [
                'path' => null,
                'notice' => 'Kompresi PDF tidak tersedia pada server. File tidak disimpan.',
            ];
        }

        $bestPath = null;
        $bestSize = $file->getSize();
        $outputPath = null;
        $validPdfProduced = false;
        $processFailed = false;
        $invalidOutput = false;

        try {
            foreach (['/printer', '/ebook'] as $setting) {
                $outputPath = tempnam(sys_get_temp_dir(), 'siguru-pdf-');

                if ($outputPath === false) {
                    $outputPath = null;
                    throw new \RuntimeException('Tidak dapat membuat file sementara untuk kompresi PDF.');
                }

                $process = new Process([
                    $executable,
                    '-dSAFER',
                    '-dBATCH',
                    '-dNOPAUSE',
                    '-dQUIET',
                    '-sDEVICE=pdfwrite',
                    '-dCompatibilityLevel=1.5',
                    '-dPDFSETTINGS='.$setting,
                    '-sOutputFile='.$outputPath,
                    $file->getPathname(),
                ], null, $this->ghostscriptEnvironment(dirname($outputPath)));
                $process->setTimeout(180);

                try {
                    $process->run();
                } catch (ProcessTimedOutException $exception) {
                    Log::warning('PDF compression timed out.', ['setting' => $setting]);
                    $processFailed = true;
                    unlink($outputPath);
                    $outputPath = null;

                    continue;
                } catch (\Throwable $exception) {
                    Log::warning('Ghostscript could not start for an uploaded PDF.', [
                        'exception' => $exception::class,
                    ]);
                    $processFailed = true;
                    unlink($outputPath);
                    $outputPath = null;

                    continue;
                }

                $outputSize = is_file($outputPath) ? filesize($outputPath) : false;

                if (
                    ! $process->isSuccessful()
                    || $outputSize === false
                    || ! $this->isPdf($outputPath)
                ) {
                    if (! $process->isSuccessful()) {
                        $processFailed = true;
                        Log::warning('Ghostscript could not compress an uploaded PDF.', [
                            'exit_code' => $process->getExitCode(),
                            'error_output' => mb_substr(trim($process->getErrorOutput()), 0, 1000),
                        ]);
                    } else {
                        $invalidOutput = true;
                        Log::warning('Ghostscript produced an invalid PDF output.');
                    }

                    unlink($outputPath);
                    $outputPath = null;

                    continue;
                }

                $validPdfProduced = true;

                if ($outputSize >= $bestSize) {
                    unlink($outputPath);
                    $outputPath = null;

                    continue;
                }

                if ($bestPath && is_file($bestPath)) {
                    unlink($bestPath);
                }

                $bestPath = $outputPath;
                $outputPath = null;
                $bestSize = $outputSize;

                if ($bestSize <= self::TARGET_BYTES) {
                    break;
                }
            }

            if (! $bestPath) {
                $notice = match (true) {
                    $validPdfProduced => 'PDF tidak dapat diperkecil lebih lanjut; file tidak disimpan.',
                    $processFailed => 'PDF gagal diproses. Pastikan file tidak rusak atau terkunci; file tidak disimpan.',
                    $invalidOutput => 'Ghostscript tidak menghasilkan PDF yang valid; file tidak disimpan.',
                    default => 'PDF tidak berhasil dikompres menjadi lebih kecil; file tidak disimpan.',
                };

                Log::warning('PDF compression did not produce a smaller valid output.', [
                    'valid_pdf_produced' => $validPdfProduced,
                    'process_failed' => $processFailed,
                    'invalid_output' => $invalidOutput,
                ]);

                $result = ['path' => null, 'notice' => $notice];
            } else {
                $result = $this->compressionResult($bestPath, $file->getSize(), $bestSize);
            }

            // Transfer ownership of the successful temporary PDF to storePdf().
            $bestPath = null;

            return $result;
        } finally {
            if ($outputPath && is_file($outputPath)) {
                unlink($outputPath);
            }

            if ($bestPath && is_file($bestPath)) {
                unlink($bestPath);
            }
        }
    }

    private function findGhostscriptExecutable(): ?string
    {
        $finder = new ExecutableFinder;
        $binary = config('uploads.ghostscript_binary');
        $executable = $finder->find($binary);

        if ($executable || PHP_OS_FAMILY !== 'Windows') {
            return $executable;
        }

        $programFilesDirectories = array_unique(array_filter([
            getenv('ProgramFiles') ?: null,
            getenv('ProgramFiles(x86)') ?: null,
            'C:\\Program Files',
            'C:\\Program Files (x86)',
        ]));
        $candidates = [];

        foreach ($programFilesDirectories as $directory) {
            foreach (['gswin64c.exe', 'gswin32c.exe'] as $filename) {
                $matches = glob($directory.'\\gs\\gs*\\bin\\'.$filename);

                if ($matches !== false) {
                    $candidates = array_merge($candidates, $matches);
                }
            }
        }

        usort($candidates, static fn (string $a, string $b): int => strnatcasecmp($b, $a));

        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    /**
     * Keep the application environment while giving Ghostscript a writable
     * temporary directory for its own scratch files.
     *
     * @return array<string, string|false>
     */
    private function ghostscriptEnvironment(string $temporaryDirectory): array
    {
        $environment = getenv();

        if (! is_array($environment)) {
            $environment = [];
        }

        foreach (array_keys($environment) as $name) {
            if (in_array(strtoupper($name), ['TMPDIR', 'TEMP', 'TMP'], true)) {
                unset($environment[$name]);
            }
        }

        $environment['TMPDIR'] = $temporaryDirectory;
        $environment['TEMP'] = $temporaryDirectory;
        $environment['TMP'] = $temporaryDirectory;

        return $environment;
    }

    /**
     * @return array{path: string|null, notice: string|null}
     */
    private function compressImage(UploadedFile $file): array
    {
        $imageInfo = getimagesize($file->getPathname());

        if (
            ! $imageInfo
            || $imageInfo[0] * $imageInfo[1] > self::MAX_IMAGE_PIXELS
        ) {
            Log::warning('Image compression skipped because the image dimensions are unsafe.');

            return [
                'path' => null,
                'notice' => 'Gambar terlalu besar untuk diproses dengan aman; file asli disimpan.',
            ];
        }

        if (
            $file->getMimeType() === 'image/webp'
            && (! function_exists('imagecreatefromwebp') || ! function_exists('imagewebp'))
        ) {
            Log::warning('Image compression skipped because GD WebP support is unavailable.');

            return [
                'path' => null,
                'notice' => 'Kompresi gambar WebP tidak tersedia pada server; file asli disimpan.',
            ];
        }

        $image = match ($file->getMimeType()) {
            'image/jpeg' => imagecreatefromjpeg($file->getPathname()),
            'image/png' => imagecreatefrompng($file->getPathname()),
            'image/webp' => imagecreatefromwebp($file->getPathname()),
            default => false,
        };

        if (! $image) {
            Log::warning('Image compression could not decode an uploaded image.');

            return [
                'path' => null,
                'notice' => 'Gambar tidak dapat dikompres dengan aman; file asli disimpan.',
            ];
        }

        $mimeType = $file->getMimeType();

        if (in_array($mimeType, ['image/png', 'image/webp'], true)) {
            imagealphablending($image, false);
            imagesavealpha($image, true);
        }

        $settings = match ($mimeType) {
            'image/jpeg' => [85, 78, 72],
            'image/webp' => [85, 75, 65],
            'image/png' => [6, 8, 9],
        };
        $bestPath = null;
        $bestSize = $file->getSize();

        foreach ($settings as $quality) {
            $outputPath = tempnam(sys_get_temp_dir(), 'siguru-image-');

            if ($outputPath === false) {
                imagedestroy($image);
                throw new \RuntimeException('Tidak dapat membuat file sementara untuk kompresi gambar.');
            }

            $written = match ($mimeType) {
                'image/jpeg' => imagejpeg($image, $outputPath, $quality),
                'image/webp' => imagewebp($image, $outputPath, $quality),
                'image/png' => imagepng($image, $outputPath, $quality),
            };

            if (! $written || filesize($outputPath) >= $bestSize) {
                unlink($outputPath);

                continue;
            }

            if ($bestPath && is_file($bestPath)) {
                unlink($bestPath);
            }

            $bestPath = $outputPath;
            $bestSize = filesize($outputPath);

            if ($bestSize <= self::TARGET_BYTES) {
                break;
            }
        }

        imagedestroy($image);

        return $this->compressionResult($bestPath, $file->getSize(), $bestSize);
    }

    /**
     * @return array{path: string|null, notice: string|null}
     */
    private function compressionResult(?string $path, int $originalSize, int $compressedSize): array
    {
        if (! $path) {
            Log::warning('File compression did not produce a smaller safe output.');

            return [
                'path' => null,
                'notice' => 'PDF tidak berhasil dikompres menjadi lebih kecil; file tidak disimpan.',
            ];
        }

        $notice = 'File berhasil dikompres dari '
            .$this->formatSize($originalSize)
            .' menjadi '
            .$this->formatSize($compressedSize)
            .'.';

        if ($compressedSize > self::TARGET_BYTES) {
            $notice .= ' Ukuran terbaik masih di atas target 5 MB.';
        }

        return ['path' => $path, 'notice' => $notice];
    }

    private function isPdf(string $path): bool
    {
        $handle = fopen($path, 'rb');

        if ($handle === false) {
            return false;
        }

        $signature = fread($handle, 5);
        fclose($handle);

        return $signature === '%PDF-';
    }

    private function extensionFor(UploadedFile $file): string
    {
        $originalExtension = strtolower($file->getClientOriginalExtension());
        $allowedExtensions = match ($file->getMimeType()) {
            'application/pdf' => ['pdf'],
            'image/jpeg' => ['jpg', 'jpeg'],
            'image/png' => ['png'],
            'image/webp' => ['webp'],
            default => [],
        };

        if (in_array($originalExtension, $allowedExtensions, true)) {
            return $originalExtension;
        }

        return $allowedExtensions[0] ?? 'bin';
    }

    private function formatSize(int $bytes): string
    {
        return number_format($bytes / 1024 / 1024, 2, ',', '.').' MB';
    }
}
