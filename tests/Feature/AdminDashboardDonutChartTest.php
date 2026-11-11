<?php

namespace Tests\Feature;

use App\Models\Dokumen;
use App\Models\Guru;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardDonutChartTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_donut_chart_uses_database_completion_counts(): void
    {
        $guruLengkap = $this->buatGuru('admin-chart-lengkap');
        $this->buatGuru('admin-chart-belum-lengkap');

        foreach ([
            'Modul Ajar',
            'ATP',
            'AP',
            'PROTA',
            'PROSEM',
            'Kisi-Kisi',
            'Naskah Soal',
            'Analisis Butir Soal',
        ] as $jenis) {
            Dokumen::create([
                'guru_id' => $guruLengkap->id,
                'jenis_dokumen' => $jenis,
                'kelas' => in_array($jenis, ['PROTA', 'PROSEM'], true) ? null : '10',
                'mata_pelajaran' => 'Matematika',
                'semester' => '1',
                'tahun_ajaran' => '2026/2027',
                'nama_file' => strtolower(str_replace(' ', '-', $jenis)).'.pdf',
                'file_path' => 'dokumen/'.strtolower(str_replace(' ', '-', $jenis)).'.pdf',
            ]);
        }

        $this->withSession(['admin_id' => 1])
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('id="dashboardDocumentChart"', false)
            ->assertSee('const completeDocuments = 1;', false)
            ->assertSee('const incompleteDocuments = 1;', false)
            ->assertSee('50%');
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
}
