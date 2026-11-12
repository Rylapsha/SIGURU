<?php

namespace Tests\Feature;

use App\Models\AgendaKegiatan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAgendaValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_requires_every_agenda_field(): void
    {
        $this->withSession(['admin_id' => 1])
            ->post(route('admin.agenda.store'), [])
            ->assertRedirect(route('admin.agenda.index'))
            ->assertSessionHasErrors([
                'judul',
                'tanggal',
                'jam',
                'lokasi',
                'keterangan',
                'status',
            ]);
    }

    public function test_update_requires_every_agenda_field(): void
    {
        $agenda = AgendaKegiatan::create([
            'judul' => 'Agenda contoh',
            'tanggal' => '2026-11-12',
            'jam' => '09:00',
            'lokasi' => 'Aula',
            'keterangan' => 'Keterangan contoh',
            'status' => 'terjadwal',
        ]);

        $this->withSession(['admin_id' => 1])
            ->put(route('admin.agenda.update', $agenda), [])
            ->assertRedirect(route('admin.agenda.index'))
            ->assertSessionHasErrors([
                'judul',
                'tanggal',
                'jam',
                'lokasi',
                'keterangan',
                'status',
            ]);
    }

    public function test_agenda_page_places_alert_above_the_panel_and_requires_all_fields(): void
    {
        AgendaKegiatan::create([
            'judul' => 'Agenda contoh',
            'tanggal' => '2026-11-12',
            'jam' => '09:00',
            'lokasi' => 'Aula',
            'keterangan' => 'Keterangan contoh',
            'status' => 'terjadwal',
        ]);

        $response = $this->withSession([
            'admin_id' => 1,
            'success' => 'Agenda berhasil disimpan.',
        ])->get(route('admin.agenda.index'))
            ->assertOk()
            ->assertSeeInOrder([
                'Agenda berhasil disimpan.',
                '<section class="agenda-panel">',
            ], false)
            ->assertSee('#kt_page_content > .container-fluid', false);

        foreach (['judul', 'tanggal', 'jam', 'lokasi', 'keterangan', 'status'] as $field) {
            $this->assertMatchesRegularExpression(
                '/<(?:input|select|textarea)\b[^>]*\bname="' . $field . '"[^>]*\brequired\b/s',
                $response->getContent()
            );
        }
    }
}
