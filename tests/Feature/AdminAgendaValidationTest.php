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

    public function test_agenda_index_filters_by_inclusive_activity_date_range(): void
    {
        foreach ([
            ['judul' => 'Sebelum rentang', 'tanggal' => '2026-11-09'],
            ['judul' => 'Tanggal mulai', 'tanggal' => '2026-11-10'],
            ['judul' => 'Di dalam rentang', 'tanggal' => '2026-11-11'],
            ['judul' => 'Tanggal akhir', 'tanggal' => '2026-11-12'],
            ['judul' => 'Sesudah rentang', 'tanggal' => '2026-11-13'],
        ] as $data) {
            AgendaKegiatan::create(array_merge($data, [
                'jam' => '09:00',
                'lokasi' => 'Aula',
                'keterangan' => 'Keterangan contoh',
                'status' => 'terjadwal',
            ]));
        }

        $this->withSession(['admin_id' => 1])
            ->get(route('admin.agenda.index', [
                'tanggal_mulai' => '2026-11-10',
                'tanggal_akhir' => '2026-11-12',
            ]))
            ->assertOk()
            ->assertSeeInOrder([
                'Tanggal mulai',
                'Di dalam rentang',
                'Tanggal akhir',
            ])
            ->assertDontSee('Sebelum rentang')
            ->assertDontSee('Sesudah rentang');
    }

    public function test_agenda_index_without_date_range_shows_all_agendas(): void
    {
        AgendaKegiatan::create([
            'judul' => 'Agenda awal',
            'tanggal' => '2026-11-09',
            'jam' => '09:00',
            'lokasi' => 'Aula',
            'keterangan' => 'Keterangan contoh',
            'status' => 'terjadwal',
        ]);
        AgendaKegiatan::create([
            'judul' => 'Agenda akhir',
            'tanggal' => '2026-11-13',
            'jam' => '09:00',
            'lokasi' => 'Aula',
            'keterangan' => 'Keterangan contoh',
            'status' => 'terjadwal',
        ]);

        $this->withSession(['admin_id' => 1])
            ->get(route('admin.agenda.index'))
            ->assertOk()
            ->assertSee('Agenda awal')
            ->assertSee('Agenda akhir');
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
