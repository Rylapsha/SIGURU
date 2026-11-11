<?php

namespace Tests\Feature;

use App\Models\Dokumen;
use App\Models\Guru;
use App\Models\MataPelajaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DokumenUploadMataPelajaranTest extends TestCase
{
    use RefreshDatabase;

    public function test_master_subject_is_offered_and_pdf_uploads_succeed_for_all_category_pages(): void
    {
        Storage::fake('public');

        $namaMataPelajaran = 'Sosiologi Terapan';
        MataPelajaran::create(['nama' => $namaMataPelajaran]);

        $guru = Guru::create([
            'nip' => 'guru-upload-1',
            'nama_lengkap' => 'Guru Upload',
            'golongan' => 'III/a',
            'mata_pelajaran' => $namaMataPelajaran,
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($guru);

        $kategori = [
            ['modul-ajar', 'Modul Ajar', true],
            ['atp-ap', 'ATP', true],
            ['prota-prosem', 'PROTA', false],
            ['kisi-kisi', 'Kisi-Kisi', true],
            ['naskah-soal', 'Naskah Soal', true],
            ['analisis-butir-soal', 'Analisis Butir Soal', true],
        ];

        foreach ($kategori as [$slug, $jenisDokumen, $kelasRequired]) {
            $this->get(route('dokumen.kategori', $slug))
                ->assertOk()
                ->assertSee($namaMataPelajaran);

            $data = [
                'file' => UploadedFile::fake()->create(
                    strtolower(str_replace(' ', '-', $jenisDokumen)) . '.pdf',
                    10,
                    'application/pdf'
                ),
                'mata_pelajaran' => $jenisDokumen === 'Modul Ajar'
                    ? $namaMataPelajaran . ' '
                    : $namaMataPelajaran,
                'semester' => '1',
                'tahun_ajaran' => '2026/2027',
            ];

            if ($kelasRequired) {
                $data['kelas'] = '10';
            }

            $this->post(route('dokumen.store', $slug), $data)
                ->assertRedirect(route('dokumen.kategori', $slug));

            $dokumen = Dokumen::query()
                ->where('jenis_dokumen', $jenisDokumen)
                ->latest('id')
                ->firstOrFail();

            $this->assertSame($namaMataPelajaran, $dokumen->mata_pelajaran);
            Storage::disk('public')->assertExists($dokumen->file_path);

            $this->get(route('dokumen.kategori', $slug))
                ->assertOk()
                ->assertSee($namaMataPelajaran);
        }

        $this->assertDatabaseCount('dokumens', count($kategori));
    }

    public function test_upload_rejects_a_subject_that_is_not_in_the_master_table(): void
    {
        Storage::fake('public');

        $guru = Guru::create([
            'nip' => 'guru-upload-2',
            'nama_lengkap' => 'Guru Upload',
            'golongan' => 'III/a',
            'mata_pelajaran' => 'Matematika',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($guru);

        $this->post(route('dokumen.store', 'modul-ajar'), [
            'file' => UploadedFile::fake()->create('modul.pdf', 10, 'application/pdf'),
            'kelas' => '10',
            'mata_pelajaran' => 'Mata Pelajaran Tidak Terdaftar',
            'semester' => '1',
            'tahun_ajaran' => '2026/2027',
        ])
            ->assertSessionHasErrors([
                'mata_pelajaran' => 'Mata pelajaran dokumen tidak valid.',
            ]);

        $this->assertDatabaseCount('dokumens', 0);
    }
}
