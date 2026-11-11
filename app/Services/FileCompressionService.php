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

    private const MAX_IMAGE_PIXELS = 20_000_000;

    /**
     * @return array{path: string|false, notice: string|null}
     */
    public function store(UploadedFile $file, string $directory): array
    {
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
     * @return array{path: string|null, notice: string|null}
     */
    private function optimize(UploadedFile $file): array
    {
        if ($file->getSize() <= self::TARGET_BYTES) {
            return ['path' => null, 'notice' => null];
        }

        return match ($file->getMimeType()) {
            'application/pdf' => $this->compressPdf($file),
            'image/jpeg', 'image/png', 'image/webp' => $this->compressImage($file),
            default => ['path' => null, 'notice' => null],
        };
    }

    /**
     * @return array{path: string|null, notice: string|null}
     */
    private function compressPdf(UploadedFile $file): array
    {
        $binary = config('uploads.ghostscript_binary');
        $executable = (new ExecutableFinder)->find($binary);

        if (! $executable) {
            Log::warning('PDF compression skipped because Ghostscript is unavailable.');

            return [
                'path' => null,
                'notice' => 'Kompresi PDF tidak tersedia pada server; file asli disimpan.',
            ];
        }

        $bestPath = null;
        $bestSize = $file->getSize();

        foreach (['/printer', '/ebook'] as $setting) {
            $outputPath = tempnam(sys_get_temp_dir(), 'siguru-pdf-');

            if ($outputPath === false) {
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
            ]);
            $process->setTimeout(180);

            try {
                $process->run();
            } catch (ProcessTimedOutException $exception) {
                Log::warning('PDF compression timed out.', ['setting' => $setting]);
                unlink($outputPath);

                continue;
            }

            if (
                ! $process->isSuccessful()
                || ! is_file($outputPath)
                || filesize($outputPath) >= $bestSize
                || ! $this->isPdf($outputPath)
            ) {
                if (! $process->isSuccessful()) {
                    Log::warning('Ghostscript could not compress an uploaded PDF.', [
                        'exit_code' => $process->getExitCode(),
                    ]);
                }

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

        return $this->compressionResult($bestPath, $file->getSize(), $bestSize);
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
                'notice' => 'Ukuran file tidak dapat dikurangi dengan aman; file asli disimpan.',
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
