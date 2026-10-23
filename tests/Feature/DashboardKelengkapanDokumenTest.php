<?php

namespace Tests\Feature;

use App\Models\Dokumen;
use App\Models\Guru;
use App\Models\Supervisi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DashboardKelengkapanDokumenTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_counts_only_complete_categories_for_selected_period_and_guru(): void
    {
        $guru = $this->buatGuru('guru-1');
        $guruLain = $this->buatGuru('guru-2');

        $this->actingAs($guru);

        $this->buatDokumen($guru, 'Modul Ajar');
        $this->buatDokumen($guru, 'ATP');
        $this->buatDokumen($guru, 'ATP');
        $this->buatDokumen($guru, 'PROTA');
        $this->buatDokumen($guru, 'Kisi-Kisi');
        $this->buatDokumen($guru, 'Naskah Soal');
        $this->buatDokumen($guru, 'Analisis Butir Soal');
        $this->buatDokumen($guru, 'PROSEM', semester: 2);
        $this->buatDokumen($guru, 'PROSEM', tahunAjaran: '2025/2026');
        $this->buatDokumen($guru, 'Kisi-Kisi', semester: 2);
        $this->buatDokumen($guru, 'Naskah Soal', semester: 2);
        $this->buatDokumen($guru, 'Analisis Butir Soal', semester: 2);
        $this->buatDokumen($guru, 'Kisi-Kisi', tahunAjaran: '2025/2026');
        $this->buatDokumen($guru, 'Naskah Soal', tahunAjaran: '2025/2026');
        $this->buatDokumen($guru, 'Analisis Butir Soal', tahunAjaran: '2025/2026');

        $this->buatDokumen($guruLain, 'PROTA');
        $this->buatDokumen($guruLain, 'PROSEM');

        $this->get(route('dashboard', [
            'semester' => 1,
            'tahun_ajaran' => '2026/2027',
        ]))
            ->assertOk()
            ->assertSee('3/4 (75%)')
            ->assertSee('Modul Ajar')
            ->assertSee('TP/ATP')
            ->assertSee('Prota/Prosem')
            ->assertSee('Bank Soal')
            ->assertSee('Semester 1')
            ->assertSee('2026/2027');

        $this->get(route('dashboard', [
            'semester' => 2,
            'tahun_ajaran' => '2026/2027',
        ]))
            ->assertOk()
            ->assertSee('1/4 (25%)');

        $this->get(route('dashboard', [
            'semester' => 1,
            'tahun_ajaran' => '2025/2026',
        ]))
            ->assertOk()
            ->assertSee('1/4 (25%)');
    }

    public function test_dashboard_supports_zero_and_full_completion_and_excludes_legacy_documents(): void
    {
        $guru = $this->buatGuru('guru-3');
        $this->actingAs($guru);

        $this->buatDokumen($guru, 'Modul Ajar', tahunAjaran: null);
        $this->get(route('dashboard', [
            'semester' => 1,
            'tahun_ajaran' => '2026/2027',
        ]))
            ->assertOk()
            ->assertSee('0/4 (0%)')
            ->assertSee('Belum tersedia');

        foreach ([
            'Modul Ajar',
            'AP',
            'PROTA',
            'PROSEM',
            'Kisi-Kisi',
            'Naskah Soal',
            'Analisis Butir Soal',
        ] as $jenis) {
            $this->buatDokumen($guru, $jenis);
        }

        $this->get(route('dashboard', [
            'semester' => 1,
            'tahun_ajaran' => '2026/2027',
        ]))
            ->assertOk()
            ->assertSee('4/4 (100%)')
            ->assertSee('Tersedia');
    }

    public function test_each_category_adds_only_twenty_five_percent_and_prota_requires_prosem(): void
    {
        $guru = $this->buatGuru('guru-5');
        $this->actingAs($guru);

        $this->buatDokumen($guru, 'Modul Ajar');
        $this->buatDokumen($guru, 'Modul Ajar');
        $this->assertProgress(25);

        $this->buatDokumen($guru, 'ATP');
        $this->assertProgress(50);

        $this->buatDokumen($guru, 'PROTA');
        $this->assertProgress(50);

        $this->buatDokumen($guru, 'PROSEM');
        $this->assertProgress(75);

        $this->buatDokumen($guru, 'Kisi-Kisi');
        $this->buatDokumen($guru, 'Naskah Soal');
        $this->assertProgress(75);

        $this->buatDokumen($guru, 'Analisis Butir Soal');
        $this->assertProgress(100);
    }

    public function test_upload_requires_and_saves_the_selected_academic_year(): void
    {
        Storage::fake('public');
        $guru = $this->buatGuru('guru-4');
        $this->actingAs($guru);

        $data = [
            'file' => UploadedFile::fake()->create('modul.pdf', 10, 'application/pdf'),
            'kelas' => '10',
            'mata_pelajaran' => 'Matematika',
            'semester' => '1',
        ];

        $this->post(route('dokumen.store', 'modul-ajar'), $data)
            ->assertSessionHasErrors('tahun_ajaran');

        $this->post(route('dokumen.store', 'modul-ajar'), $data + [
            'tahun_ajaran' => '2026/2028',
        ])
            ->assertSessionHasErrors('tahun_ajaran');

        $this->post(route('dokumen.store', 'modul-ajar'), $data + [
            'tahun_ajaran' => '2026/2027',
        ])
            ->assertRedirect(route('dokumen.kategori', 'modul-ajar'));

        $this->assertDatabaseHas('dokumens', [
            'guru_id' => $guru->id,
            'jenis_dokumen' => 'Modul Ajar',
            'semester' => 1,
            'tahun_ajaran' => '2026/2027',
        ]);

        $this->get(route('dokumen.kategori', 'modul-ajar'))
            ->assertOk()
            ->assertSee('Tahun Ajaran')
            ->assertSee('2026/2027');
    }

    public function test_upcoming_agenda_uses_only_current_gurus_scheduled_future_supervisions(): void
    {
        $guru = $this->buatGuru('guru-agenda');
        $guruLain = $this->buatGuru('guru-agenda-lain');
        $this->actingAs($guru);

        Supervisi::create([
            'guru_id' => $guru->id,
            'nama_supervisor' => 'Supervisor Mendatang',
            'tanggal' => now()->addDays(2)->toDateString(),
            'jam' => '08:30:00',
            'mata_pelajaran' => 'Matematika',
            'semester' => '1',
            'status' => 'terjadwal',
        ]);

        Supervisi::create([
            'guru_id' => $guru->id,
            'nama_supervisor' => 'Supervisor Selesai',
            'tanggal' => now()->addDays(3)->toDateString(),
            'jam' => '09:00:00',
            'mata_pelajaran' => 'IPA',
            'semester' => '1',
            'status' => 'selesai',
        ]);

        Supervisi::create([
            'guru_id' => $guru->id,
            'nama_supervisor' => 'Supervisor Lampau',
            'tanggal' => now()->subDay()->toDateString(),
            'jam' => '10:00:00',
            'mata_pelajaran' => 'IPS',
            'semester' => '1',
            'status' => 'terjadwal',
        ]);

        Supervisi::create([
            'guru_id' => $guruLain->id,
            'nama_supervisor' => 'Jadwal Guru Lain',
            'tanggal' => now()->addDay()->toDateString(),
            'jam' => '11:00:00',
            'mata_pelajaran' => 'Bahasa Indonesia',
            'semester' => '1',
            'status' => 'terjadwal',
        ]);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Agenda Mendatang')
            ->assertSee('Supervisor Mendatang')
            ->assertSee('Matematika')
            ->assertDontSee('Supervisor Selesai')
            ->assertDontSee('Supervisor Lampau')
            ->assertDontSee('Jadwal Guru Lain')
            ->assertDontSee('Pengumpulan Dokumen')
            ->assertDontSee('Rapat Guru');
    }

    private function buatGuru(string $nip): Guru
    {
        return Guru::create([
            'nip' => $nip,
            'nama_lengkap' => $nip,
            'golongan' => 'III/a',
            'mata_pelajaran' => 'Matematika',
            'password' => bcrypt('password'),
        ]);
    }

    private function buatDokumen(
        Guru $guru,
        string $jenis,
        int $semester = 1,
        ?string $tahunAjaran = '2026/2027'
    ): Dokumen {
        static $number = 0;
        $number++;

        return Dokumen::create([
            'guru_id' => $guru->id,
            'jenis_dokumen' => $jenis,
            'semester' => $semester,
            'tahun_ajaran' => $tahunAjaran,
            'nama_file' => "dokumen-{$number}.pdf",
            'file_path' => "dokumen/dokumen-{$number}.pdf",
        ]);
    }

    private function assertProgress(int $percentage): void
    {
        $this->get(route('dashboard', [
            'semester' => 1,
            'tahun_ajaran' => '2026/2027',
        ]))
            ->assertOk()
            ->assertSee((string) $percentage . '%');
    }
}
