<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Dokumen;
use App\Models\Guru;
use App\Models\GuruActivation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use ZipArchive;

class GuruActivationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_links_to_activation_and_activation_page_has_required_fields(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Aktivasi Akun')
            ->assertSee(route('guru.activation.create'));

        $this->get(route('guru.activation.create'))
            ->assertOk()
            ->assertSee('name="nip"', false)
            ->assertSee('name="kode_aktivasi"', false)
            ->assertDontSee('name="password"', false)
            ->assertSee('Verifikasi Kode');
    }

    public function test_admin_export_contains_ice_matuah_and_only_hashes_the_activation_code(): void
    {
        $guru = $this->makeIceMatuah();
        $admin = $this->makeAdmin();

        $response = $this->withSession($this->adminSession($admin))
            ->post(route('admin.aktivasi-guru.export-one', $guru))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $code = $this->readCodeFromWorkbook($response->getContent());
        $normalizedCode = str_replace('-', '', $code);
        $activation = GuruActivation::where('guru_id', $guru->id)->firstOrFail();

        $this->assertStringStartsWith('PK', $response->getContent());
        $this->assertMatchesRegularExpression('/^[A-Z0-9]{5}$/', $code);
        $this->assertTrue(Hash::check($normalizedCode, $activation->code_hash));
        $this->assertSame(hash_hmac('sha256', $normalizedCode, config('app.key')), $activation->code_fingerprint);
        $this->assertDatabaseHas('guru_activations', [
            'guru_id' => $guru->id,
            'attempts' => 0,
        ]);
        $this->assertSame('198703152012022002', $guru->nip);
        $this->assertSame('Ice Matuah', $guru->nama_lengkap);
        $this->assertFalse($guru->fresh()->is_active);
    }

    public function test_regenerating_an_unused_code_invalidates_the_previous_code(): void
    {
        $guru = $this->makeIceMatuah();
        $admin = $this->makeAdmin();

        $firstResponse = $this->withSession($this->adminSession($admin))
            ->post(route('admin.aktivasi-guru.export-one', $guru));
        $firstCode = $this->readCodeFromWorkbook($firstResponse->getContent());

        $secondResponse = $this->post(route('admin.aktivasi-guru.export-one', $guru));
        $secondCode = $this->readCodeFromWorkbook($secondResponse->getContent());

        $this->assertNotSame($firstCode, $secondCode);
        $this->assertFalse(Hash::check(
            str_replace('-', '', $firstCode),
            GuruActivation::where('guru_id', $guru->id)->value('code_hash')
        ));
        $this->assertTrue(Hash::check(
            str_replace('-', '', $secondCode),
            GuruActivation::where('guru_id', $guru->id)->value('code_hash')
        ));
    }

    public function test_admin_bulk_export_generates_a_workbook_for_all_inactive_gurus(): void
    {
        $ice = $this->makeIceMatuah();
        $anotherGuru = Guru::create([
            'nip' => '198501012010011001',
            'nama_lengkap' => 'Meerqeen de valorant',
            'golongan' => 'III/b',
            'mata_pelajaran' => 'Matematika',
            'password' => Hash::make('password-lama'),
            'is_active' => false,
        ]);
        $admin = $this->makeAdmin();

        $response = $this->withSession($this->adminSession($admin))
            ->post(route('admin.aktivasi-guru.export'))
            ->assertOk();

        $iceCode = $this->readCodeFromWorkbook($response->getContent());
        $this->assertDatabaseHas('guru_activations', ['guru_id' => $ice->id]);
        $this->assertDatabaseHas('guru_activations', ['guru_id' => $anotherGuru->id]);
        $this->assertTrue(Hash::check(
            str_replace('-', '', $iceCode),
            GuruActivation::where('guru_id', $ice->id)->value('code_hash')
        ));
    }

    public function test_activation_list_searches_teacher_name_and_nip(): void
    {
        $this->makeIceMatuah();
        Guru::create([
            'nip' => '198501012010011001',
            'nama_lengkap' => 'Meerqeen de valorant',
            'golongan' => 'III/b',
            'mata_pelajaran' => 'Matematika',
            'password' => Hash::make('password-lama'),
            'is_active' => false,
        ]);
        $admin = $this->makeAdmin();

        $this->withSession($this->adminSession($admin))
            ->get(route('admin.aktivasi-guru.index', ['search' => 'Ice Matuah']))
            ->assertOk()
            ->assertSee('Ice Matuah')
            ->assertDontSee('Meerqeen de valorant')
            ->assertSee('name="search"', false);

        $this->get(route('admin.aktivasi-guru.index', ['search' => '198703152012022002']))
            ->assertOk()
            ->assertSee('Ice Matuah')
            ->assertDontSee('Meerqeen de valorant');
    }

    public function test_activation_list_shows_account_and_code_statuses_for_active_and_pending_gurus(): void
    {
        $pendingWithoutCode = $this->makeIceMatuah();
        $pendingWithCode = Guru::create([
            'nip' => '198501012010011002',
            'nama_lengkap' => 'Guru Dengan Kode',
            'golongan' => 'III/b',
            'mata_pelajaran' => 'Matematika',
            'password' => Hash::make('password-lama'),
            'is_active' => false,
        ]);
        Guru::create([
            'nip' => '198501012010011003',
            'nama_lengkap' => 'Guru Sudah Aktif',
            'golongan' => 'III/b',
            'mata_pelajaran' => 'Matematika',
            'password' => Hash::make('password-lama'),
            'is_active' => true,
        ]);
        $admin = $this->makeAdmin();

        $this->withSession($this->adminSession($admin))
            ->post(route('admin.aktivasi-guru.export-one', $pendingWithCode))
            ->assertOk();

        $this->get(route('admin.aktivasi-guru.index'))
            ->assertOk()
            ->assertSee($pendingWithoutCode->nama_lengkap)
            ->assertSee($pendingWithCode->nama_lengkap)
            ->assertSee('Guru Sudah Aktif')
            ->assertSee('Belum diaktivasi')
            ->assertSee('Sudah diaktivasi')
            ->assertSee('Belum dibuat')
            ->assertSee('Sudah dibuat');
    }

    public function test_kurikulum_admin_creates_a_teacher_account_that_must_be_activated(): void
    {
        $admin = $this->makeAdmin('kurikulum');

        $this->withSession($this->adminSession($admin))
            ->post(route('admin.guru.store'), [
                'nip' => '199001012020011001',
                'nama_lengkap' => 'Guru Baru',
                'golongan' => 'Tidak ada',
                'mata_pelajaran' => 'Tidak ada',
            ])
            ->assertRedirect(route('admin.guru.index'));

        $guru = Guru::where('nip', '199001012020011001')->firstOrFail();

        $this->assertFalse($guru->is_active);
        $this->assertFalse(Hash::check('123456', $guru->password));
        $this->assertDatabaseHas('gurus', [
            'nip' => '199001012020011001',
            'nama_lengkap' => 'Guru Baru',
            'is_active' => false,
        ]);
    }

    public function test_ice_matuah_can_activate_once_and_log_in_with_the_new_password(): void
    {
        $guru = $this->makeIceMatuah();
        $admin = $this->makeAdmin();
        $this->withSession($this->adminSession($admin));

        $codeResponse = $this->post(route('admin.aktivasi-guru.export-one', $guru));
        $code = $this->readCodeFromWorkbook($codeResponse->getContent());

        $this->post(route('login.process'), [
            'nip' => $guru->nip,
            'password' => 'password-lama',
        ])->assertSessionHasErrors('nip');

        $this->post(route('guru.activation.store'), [
            'nip' => $guru->nip,
            'kode_aktivasi' => $code.'A',
        ])->assertSessionHasErrors('kode_aktivasi');

        $this->assertSame(0, GuruActivation::where('guru_id', $guru->id)->value('attempts'));

        $this->post(route('guru.activation.store'), [
            'nip' => $guru->nip,
            'kode_aktivasi' => $code,
        ])
            ->assertRedirect(route('guru.activation.password'));

        $this->assertFalse($guru->fresh()->is_active);
        $this->assertTrue(Hash::check('password-lama', $guru->fresh()->password));

        $this->get(route('guru.activation.password'))
            ->assertOk()
            ->assertSee('name="password"', false)
            ->assertSee('name="password_confirmation"', false)
            ->assertDontSee('name="kode_aktivasi"', false);

        $this->post(route('guru.activation.complete'), [
            'password' => 'IceMatuah2026!',
            'password_confirmation' => 'IceMatuah2026!',
        ])
            ->assertRedirect(route('login'))
            ->assertSessionHas('activation_success');

        $this->post(route('guru.activation.complete'), [
            'password' => 'IceMatuah2026!',
            'password_confirmation' => 'IceMatuah2026!',
        ])
            ->assertRedirect(route('login'))
            ->assertSessionHas('activation_success')
            ->assertSessionMissing('errors');

        $this->assertTrue($guru->fresh()->is_active);
        $this->assertTrue(Hash::check('IceMatuah2026!', $guru->fresh()->password));
        $this->assertDatabaseMissing('guru_activations', ['guru_id' => $guru->id]);

        $this->post(route('guru.activation.store'), [
            'nip' => $guru->nip,
            'kode_aktivasi' => $code,
        ])->assertSessionHasErrors('kode_aktivasi');

        $this->post(route('login.process'), [
            'nip' => $guru->nip,
            'password' => 'IceMatuah2026!',
        ])->assertRedirect(route('dashboard'));
    }

    public function test_expired_activation_code_is_rejected_and_removed(): void
    {
        $guru = $this->makeIceMatuah();
        $admin = $this->makeAdmin();
        $this->withSession($this->adminSession($admin));
        $code = $this->readCodeFromWorkbook(
            $this->post(route('admin.aktivasi-guru.export-one', $guru))->getContent()
        );

        GuruActivation::where('guru_id', $guru->id)->update([
            'expires_at' => now()->subMinute(),
        ]);

        $this->post(route('guru.activation.store'), [
            'nip' => $guru->nip,
            'kode_aktivasi' => $code,
        ])->assertSessionHasErrors('kode_aktivasi');

        $this->assertDatabaseMissing('guru_activations', ['guru_id' => $guru->id]);
        $this->assertFalse($guru->fresh()->is_active);
    }

    public function test_admin_can_reset_an_expired_activation_code(): void
    {
        $guru = $this->makeIceMatuah();
        $admin = $this->makeAdmin();
        $this->withSession($this->adminSession($admin));

        $oldCode = $this->readCodeFromWorkbook(
            $this->post(route('admin.aktivasi-guru.export-one', $guru))->getContent()
        );
        GuruActivation::where('guru_id', $guru->id)->update([
            'expires_at' => now()->subMinute(),
        ]);

        $this->get(route('admin.aktivasi-guru.index'))
            ->assertOk()
            ->assertSee('Reset Kode & Ekspor', false);

        $newCode = $this->readCodeFromWorkbook(
            $this->post(route('admin.aktivasi-guru.export-one', $guru))->getContent()
        );

        $this->assertNotSame($oldCode, $newCode);
        $this->assertTrue(GuruActivation::where('guru_id', $guru->id)->value('expires_at')->isFuture());
        $this->assertSame(0, GuruActivation::where('guru_id', $guru->id)->value('attempts'));
    }

    public function test_invalid_code_consumes_attempts_and_revokes_after_five_tries(): void
    {
        $guru = $this->makeIceMatuah();
        $admin = $this->makeAdmin();
        $this->withSession($this->adminSession($admin));
        $code = $this->readCodeFromWorkbook(
            $this->post(route('admin.aktivasi-guru.export-one', $guru))->getContent()
        );
        $invalidCode = $code === 'AAAAA' ? 'BBBBB' : 'AAAAA';

        for ($attempt = 1; $attempt <= 5; $attempt++) {
            $this->post(route('guru.activation.store'), [
                'nip' => $guru->nip,
                'kode_aktivasi' => $invalidCode,
            ])->assertSessionHasErrors('kode_aktivasi');
        }

        $this->assertDatabaseMissing('guru_activations', ['guru_id' => $guru->id]);
        $this->assertFalse($guru->fresh()->is_active);
    }

    public function test_admin_can_deactivate_teacher_without_deleting_documents_and_login_is_blocked(): void
    {
        $guru = Guru::create([
            'nip' => '198501012010011004',
            'nama_lengkap' => 'Guru Aktif',
            'golongan' => 'III/b',
            'mata_pelajaran' => 'Matematika',
            'password' => Hash::make('password-lama'),
            'is_active' => true,
        ]);
        $document = Dokumen::create([
            'guru_id' => $guru->id,
            'jenis_dokumen' => 'Modul Ajar',
            'kelas' => 'VII',
            'mata_pelajaran' => 'Matematika',
            'semester' => 1,
            'tahun_ajaran' => '2026/2027',
            'nama_file' => 'modul.pdf',
            'file_path' => 'dokumen/modul.pdf',
        ]);
        $admin = $this->makeAdmin();

        $this->withSession($this->adminSession($admin))
            ->post(route('admin.aktivasi-guru.deactivate', $guru))
            ->assertSessionHas('success');

        $this->assertFalse($guru->fresh()->is_active);
        $this->assertDatabaseHas('dokumens', ['id' => $document->id, 'guru_id' => $guru->id]);

        $this->post(route('login.process'), [
            'nip' => $guru->nip,
            'password' => 'password-lama',
        ])->assertSessionHasErrors('nip');
    }

    public function test_admin_linked_teacher_account_is_shown_activated_and_cannot_be_deactivated(): void
    {
        $guru = $this->makeIceMatuah();
        $admin = Admin::create([
            'username' => $guru->nip,
            'nip' => $guru->nip,
            'nama_lengkap' => 'Rahmi Nur Salamah',
            'role' => 'kurikulum',
            'password' => Hash::make('admin-password'),
        ]);

        $this->withSession($this->adminSession($admin))
            ->get(route('admin.aktivasi-guru.index'))
            ->assertOk()
            ->assertSee('badge-light-primary">Akun Admin', false);

        $this->post(route('admin.aktivasi-guru.deactivate', $guru))
            ->assertNotFound();
    }

    public function test_deactivated_teacher_is_logged_out_on_the_next_teacher_request(): void
    {
        $guru = Guru::create([
            'nip' => '198501012010011005',
            'nama_lengkap' => 'Guru Aktif',
            'golongan' => 'III/b',
            'mata_pelajaran' => 'Matematika',
            'password' => Hash::make('password-lama'),
            'is_active' => true,
        ]);

        $this->post(route('login.process'), [
            'nip' => $guru->nip,
            'password' => 'password-lama',
        ])->assertRedirect(route('dashboard'));

        Guru::whereKey($guru->id)->update(['is_active' => false]);

        $this->get(route('dashboard'))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('nip');
    }

    private function makeIceMatuah(): Guru
    {
        return Guru::create([
            'nip' => '198703152012022002',
            'nama_lengkap' => 'Ice Matuah',
            'golongan' => 'III/c',
            'mata_pelajaran' => 'Bahasa Indonesia',
            'password' => Hash::make('password-lama'),
        ]);
    }

    private function makeAdmin(string $role = 'kepala_sekolah'): Admin
    {
        return Admin::create([
            'username' => 'activation-admin-'.$role,
            'role' => $role,
            'password' => Hash::make('admin-password'),
        ]);
    }

    private function adminSession(Admin $admin): array
    {
        return [
            'admin_id' => $admin->id,
            'admin_role' => $admin->role,
        ];
    }

    private function readCodeFromWorkbook(string $workbook): string
    {
        $temporaryPath = tempnam(sys_get_temp_dir(), 'siguru-activation-test-');
        $this->assertNotFalse($temporaryPath);
        file_put_contents($temporaryPath, $workbook);

        try {
            $zip = new ZipArchive;
            $this->assertSame(true, $zip->open($temporaryPath));
            $sheet = $zip->getFromName('xl/worksheets/sheet1.xml');
            $zip->close();

            $this->assertIsString($sheet);
            $this->assertStringContainsString('198703152012022002', $sheet);
            $this->assertStringContainsString('Ice Matuah', $sheet);
            $this->assertSame(1, preg_match(
                '/<c r="C2" t="inlineStr"><is><t xml:space="preserve">([A-Z0-9]{5})<\/t><\/is><\/c>/',
                $sheet,
                $matches
            ));

            return html_entity_decode($matches[1], ENT_QUOTES | ENT_XML1, 'UTF-8');
        } finally {
            if (is_file($temporaryPath)) {
                unlink($temporaryPath);
            }
        }
    }
}
