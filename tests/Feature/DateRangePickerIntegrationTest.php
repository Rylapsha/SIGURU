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
                'jam' => '09:00',
                'lokasi' => 'Aula',
                'keterangan' => 'Keterangan agenda',
                'status' => 'terjadwal',
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
                'jam' => '09:00',
                'lokasi' => 'Aula',
                'keterangan' => 'Keterangan agenda',
                'status' => 'terjadwal',
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
            ->assertSee('window.initSiguruDatePicker', false)
            ->assertDontSee('cdn.jsdelivr.net/npm/daterangepicker', false);
    }

    public function test_supervision_creation_form_uses_a_single_date_picker(): void
    {
        $this->withSession(['admin_id' => 1])
            ->get(route('admin.supervisi.create'))
            ->assertOk()
            ->assertSee('name="tanggal"', false)
            ->assertSee('data-date-picker="single"', false)
            ->assertSee('window.initSiguruDatePicker', false)
            ->assertSee('plugins.bundle.js', false)
            ->assertDontSee('cdn.jsdelivr.net/npm/daterangepicker', false);
    }

    public function test_all_range_picker_pages_use_the_shared_picker_setup(): void
    {
        foreach ([
            [route('admin.dashboard'), 'id="supervisiDateRange"'],
            [route('admin.agenda.index'), 'id="agenda-filter-date-range"'],
            [route('admin.supervisi.index'), 'id="supervisi-filter-date-range"'],
        ] as [$url, $pickerInput]) {
            $this->withSession(['admin_id' => 1])
                ->get($url)
                ->assertOk()
                ->assertSee($pickerInput, false)
                ->assertSee('window.initSiguruDatePicker', false)
                ->assertSee('max-height: calc(100vh - 16px)', false);
        }
    }
}
