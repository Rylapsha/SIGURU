<?php

namespace Tests\Feature;

use App\Services\FileCompressionService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileCompressionServiceTest extends TestCase
{
    public function test_large_pdf_is_saved_safely_when_ghostscript_is_unavailable(): void
    {
        Storage::fake('public');
        Config::set('uploads.ghostscript_binary', 'siguru-test-ghostscript-not-installed');

        $contents = "%PDF-1.4\n".str_repeat('x', 6 * 1024 * 1024);
        $file = UploadedFile::fake()->createWithContent('dokumen.pdf', $contents);

        $stored = app(FileCompressionService::class)->store($file, 'dokumen');

        $this->assertIsString($stored['path']);
        $this->assertStringContainsString('Kompresi PDF tidak tersedia', $stored['notice']);
        Storage::disk('public')->assertExists($stored['path']);
        $this->assertSame(strlen($contents), Storage::disk('public')->size($stored['path']));
    }

    public function test_large_jpeg_is_compressed_and_keeps_its_extension(): void
    {
        Storage::fake('public');

        $image = imagecreatetruecolor(64, 64);
        $imagePath = tempnam(sys_get_temp_dir(), 'siguru-test-jpeg-');

        imagefilledrectangle($image, 0, 0, 63, 63, imagecolorallocate($image, 240, 120, 40));
        imagejpeg($image, $imagePath, 95);
        imagedestroy($image);

        $jpeg = file_get_contents($imagePath);
        unlink($imagePath);

        $jpeg = substr($jpeg, 0, -2);
        $jpeg .= str_repeat("\xFF\xFE\xFF\xFF".str_repeat('x', 65533), 82)."\xFF\xD9";
        $file = UploadedFile::fake()->createWithContent('foto-besar.jpg', $jpeg);

        $stored = app(FileCompressionService::class)->store($file, 'profil');

        $this->assertStringEndsWith('.jpg', $stored['path']);
        $this->assertStringContainsString('File berhasil dikompres', $stored['notice']);
        $this->assertLessThan(strlen($jpeg), Storage::disk('public')->size($stored['path']));
        $this->assertNotFalse(getimagesize(Storage::disk('public')->path($stored['path'])));
    }
}
