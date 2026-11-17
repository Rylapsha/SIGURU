<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Guru;
use Database\Seeders\AdminSeeder;
use Database\Seeders\GuruSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdministratorRolesAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_both_admin_roles_log_in_from_the_shared_login_page_and_access_admin_dashboard(): void
    {
        foreach ([
            [
                'username' => '196801261990032005',
                'role' => 'kepala_sekolah',
                'name' => 'Hj. ARLYDA SHOUNARA, S.Pd., M.Pd.',
            ],
            [
                'username' => '198504162024212019',
                'role' => 'kurikulum',
                'name' => 'RAHMI NUR SALAMAH, S.Pd.Fis.',
            ],
        ] as $account) {
            Admin::create([
                'username' => $account['username'],
                'nip' => $account['username'],
                'nama_lengkap' => $account['name'],
                'role' => $account['role'],
                'password' => Hash::make('password123'),
            ]);

            $this->post(route('login.process'), [
                'nip' => $account['username'],
                'password' => 'password123',
            ])
                ->assertRedirect(route('admin.dashboard'))
                ->assertSessionHas('admin_role', $account['role']);

            $this->get(route('admin.dashboard'))->assertOk();
            $this->post(route('logout'));
        }
    }

    public function test_teacher_logs_in_from_the_same_page_but_cannot_access_admin_routes(): void
    {
        Guru::create([
            'nip' => '198501012010011001',
            'nama_lengkap' => 'Guru Contoh',
            'golongan' => 'III/b',
            'mata_pelajaran' => 'Matematika',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        $this->post(route('login.process'), [
            'nip' => '198501012010011001',
            'password' => 'password123',
        ])
            ->assertRedirect(route('dashboard'))
            ->assertSessionMissing('admin_id');

        $this->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_admin_routes_reject_unsupported_roles(): void
    {
        $this->withSession([
            'admin_id' => 1,
            'admin_role' => 'superadmin',
        ])->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_guest_admin_routes_redirect_to_the_shared_login_page(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_admin_seeder_creates_two_hashed_accounts_with_profiles_and_is_idempotent(): void
    {
        $seeder = new AdminSeeder;
        $seeder->run();
        $seeder->run();

        $this->assertDatabaseCount('admins', 2);
        $this->assertDatabaseHas('admins', [
            'username' => '196801261990032005',
            'nip' => '196801261990032005',
            'nama_lengkap' => 'Hj. ARLYDA SHOUNARA, S.Pd., M.Pd.',
            'role' => 'kepala_sekolah',
        ]);
        $this->assertDatabaseHas('admins', [
            'username' => '198504162024212019',
            'nip' => '198504162024212019',
            'nama_lengkap' => 'RAHMI NUR SALAMAH, S.Pd.Fis.',
            'role' => 'kurikulum',
        ]);
        $this->assertTrue(Hash::check(
            'admin123',
            Admin::where('username', '196801261990032005')->value('password')
        ));
        $this->assertTrue(Hash::check(
            'kurikulum123',
            Admin::where('username', '198504162024212019')->value('password')
        ));
    }

    public function test_admin_seeder_updates_legacy_usernames_without_duplicating_accounts_or_resetting_passwords(): void
    {
        $kepalaSekolahPassword = Hash::make('existing-head-password');
        $kurikulumPassword = Hash::make('existing-curriculum-password');

        Admin::create([
            'username' => 'admin',
            'role' => 'kepala_sekolah',
            'password' => $kepalaSekolahPassword,
        ]);
        Admin::create([
            'username' => 'kurikulum',
            'role' => 'kurikulum',
            'password' => $kurikulumPassword,
        ]);

        $seeder = new AdminSeeder;
        $seeder->run();
        $seeder->run();

        $this->assertDatabaseCount('admins', 2);
        $this->assertSame(
            $kepalaSekolahPassword,
            Admin::where('username', '196801261990032005')->value('password')
        );
        $this->assertSame(
            $kurikulumPassword,
            Admin::where('username', '198504162024212019')->value('password')
        );
    }

    public function test_guru_seeder_is_idempotent_and_preserves_existing_teacher_data_and_password(): void
    {
        $customPassword = Hash::make('custom-guru-password');
        Guru::create([
            'nip' => '198501012010011001',
            'nama_lengkap' => 'Nama yang sudah diubah',
            'golongan' => 'IV/a',
            'mata_pelajaran' => 'IPA',
            'password' => $customPassword,
            'foto' => 'foto-guru.jpg',
        ]);

        $seeder = new GuruSeeder;
        $seeder->run();
        $seeder->run();

        $this->assertDatabaseCount('gurus', 2);
        $this->assertDatabaseHas('gurus', [
            'nip' => '198501012010011001',
            'nama_lengkap' => 'Nama yang sudah diubah',
            'golongan' => 'IV/a',
            'mata_pelajaran' => 'IPA',
            'foto' => 'foto-guru.jpg',
        ]);
        $this->assertSame($customPassword, Guru::where('nip', '198501012010011001')->value('password'));
        $this->assertTrue(Hash::check(
            '123456',
            Guru::where('nip', '198703152012022002')->value('password')
        ));
    }
}
