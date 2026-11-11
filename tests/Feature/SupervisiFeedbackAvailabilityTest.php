<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Supervisi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupervisiFeedbackAvailabilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_feedback_requires_a_completed_supervision_and_shows_only_that_record(): void
    {
        $guru = $this->buatGuru('admin-feedback-guru');
        $terjadwal = $this->buatSupervisi($guru, 'terjadwal', 'Feedback jadwal');
        $selesai = $this->buatSupervisi($guru, 'selesai', 'Feedback supervisi terpilih');
        $lain = $this->buatSupervisi($guru, 'selesai', 'Feedback supervisi lain');

        $this->withSession(['admin_id' => 1])
            ->get(route('admin.supervisi.feedback', $terjadwal->id))
            ->assertForbidden();

        $this->get(route('admin.supervisi.feedback', $selesai->id))
            ->assertOk()
            ->assertSee('Feedback supervisi terpilih')
            ->assertDontSee('Feedback supervisi lain');

        $this->get(route('admin.supervisi.index'))
            ->assertOk()
            ->assertSee('Feedback tersedia setelah supervisi selesai')
            ->assertSee(route('admin.supervisi.feedback', $selesai->id));

        $this->assertNotSame($selesai->id, $lain->id);
    }

    public function test_admin_can_update_status_and_feedback_access_tracks_the_saved_status(): void
    {
        $guru = $this->buatGuru('admin-status-update');
        $supervisi = $this->buatSupervisi($guru, 'terjadwal', 'Feedback yang harus tetap tersimpan');

        $this->withSession(['admin_id' => 1])
            ->patch(route('admin.supervisi.status', $supervisi->id), [
                'status' => 'selesai',
            ])
            ->assertRedirect(route('admin.supervisi.index'));

        $this->assertDatabaseHas('supervisis', [
            'id' => $supervisi->id,
            'status' => 'selesai',
            'feedback_admin' => 'Feedback yang harus tetap tersimpan',
        ]);

        $this->get(route('admin.supervisi.index'))
            ->assertOk()
            ->assertSee(route('admin.supervisi.feedback', $supervisi->id));

        $this->get(route('admin.supervisi.feedback', $supervisi->id))
            ->assertOk()
            ->assertSee('Feedback yang harus tetap tersimpan');

        $this->patch(route('admin.supervisi.status', $supervisi->id), [
            'status' => 'terjadwal',
        ])
            ->assertRedirect(route('admin.supervisi.index'));

        $this->assertDatabaseHas('supervisis', [
            'id' => $supervisi->id,
            'status' => 'terjadwal',
            'feedback_admin' => 'Feedback yang harus tetap tersimpan',
        ]);

        $this->get(route('admin.supervisi.index'))
            ->assertOk()
            ->assertSee('Feedback tersedia setelah supervisi selesai')
            ->assertDontSee(route('admin.supervisi.feedback', $supervisi->id));

        $this->get(route('admin.supervisi.feedback', $supervisi->id))
            ->assertForbidden();

        $this->patch(route('admin.supervisi.status', $supervisi->id), [
            'status' => 'dibatalkan',
        ])
            ->assertRedirect(route('admin.supervisi.index'));

        $this->assertDatabaseHas('supervisis', [
            'id' => $supervisi->id,
            'status' => 'dibatalkan',
            'feedback_admin' => 'Feedback yang harus tetap tersimpan',
        ]);

        $this->get(route('admin.supervisi.index'))
            ->assertOk()
            ->assertSee('Feedback tersedia setelah supervisi selesai')
            ->assertDontSee(route('admin.supervisi.feedback', $supervisi->id));
    }

    public function test_guru_feedback_requires_completion_and_is_scoped_to_the_logged_in_guru(): void
    {
        $guru = $this->buatGuru('guru-feedback');
        $guruLain = $this->buatGuru('guru-feedback-lain');
        $terjadwal = $this->buatSupervisi($guru, 'terjadwal', 'Feedback belum tersedia');
        $selesai = $this->buatSupervisi($guru, 'selesai', 'Feedback milik guru');
        $supervisiGuruLain = $this->buatSupervisi($guruLain, 'selesai', 'Feedback milik guru lain');

        $this->actingAs($guru)
            ->get(route('guru.supervisi.feedback', $terjadwal->id))
            ->assertForbidden();

        $this->get(route('guru.supervisi.feedback', $selesai->id))
            ->assertOk()
            ->assertSee('Feedback milik guru')
            ->assertDontSee('Feedback milik guru lain');

        $this->get(route('guru.supervisi.feedback', $supervisiGuruLain->id))
            ->assertNotFound();
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

    private function buatSupervisi(Guru $guru, string $status, string $feedback): Supervisi
    {
        return Supervisi::create([
            'guru_id' => $guru->id,
            'nama_supervisor' => 'Supervisor',
            'tanggal' => now()->toDateString(),
            'jam' => '08:00:00',
            'mata_pelajaran' => 'Matematika',
            'semester' => '1',
            'status' => $status,
            'feedback_admin' => $feedback,
        ]);
    }
}
