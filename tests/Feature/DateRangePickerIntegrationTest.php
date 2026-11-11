<?php

namespace Tests\Feature;

use App\Models\AgendaKegiatan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DateRangePickerIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_agenda_uses_single_date_pickers_and_saves_iso_dates_for_create_and_update(): void
    {
        $this->withSession(['admin_id' => 1])
            ->post(route('admin.agenda.store'), [
                'judul' => 'Agenda tanggal picker',
                'tanggal' => '2026-11-12',
            ])
            ->assertRedirect(route('admin.agenda.index'));

        $agenda = AgendaKegiatan::where('judul', 'Agenda tanggal picker')->firstOrFail();

        $this->assertDatabaseHas('agenda_kegiatans', [
            'id' => $agenda->id,
            'tanggal' => '2026-11-12',
        ]);

        $this->withSession(['admin_id' => 1])
            ->put(route('admin.agenda.update', $agenda), [
                'judul' => 'Agenda tanggal picker',
                'tanggal' => '2026-11-13',
            ])
            ->assertRedirect(route('admin.agenda.index'));

        $this->assertDatabaseHas('agenda_kegiatans', [
            'id' => $agenda->id,
            'tanggal' => '2026-11-13',
        ]);

        $response = $this->withSession(['admin_id' => 1])
            ->get(route('admin.agenda.index'))
            ->assertOk();

        preg_match_all('/<input[^>]*data-date-picker="single"/s', $response->getContent(), $dateInputs);
        $this->assertCount(2, $dateInputs[0]);
        $response->assertSee('plugins.bundle.js', false)
            ->assertDontSee('cdn.jsdelivr.net/npm/daterangepicker', false);
    }

    public function test_supervision_creation_form_uses_a_single_date_picker(): void
    {
        $this->withSession(['admin_id' => 1])
            ->get(route('admin.supervisi.create'))
            ->assertOk()
            ->assertSee('name="tanggal"', false)
            ->assertSee('data-date-picker="single"', false)
            ->assertSee('plugins.bundle.js', false)
            ->assertDontSee('cdn.jsdelivr.net/npm/daterangepicker', false);
    }
}
