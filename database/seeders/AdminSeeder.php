<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            [
                'username' => '196801261990032005',
                'legacy_username' => 'admin',
                'nama_lengkap' => 'Hj. ARLYDA SHOUNARA, S.Pd., M.Pd.',
                'nip' => '196801261990032005',
                'role' => 'kepala_sekolah',
                'initial_password' => 'admin123',
            ],
            [
                'username' => '198504162024212019',
                'legacy_username' => 'kurikulum',
                'nama_lengkap' => 'RAHMI NUR SALAMAH, S.Pd.Fis.',
                'nip' => '198504162024212019',
                'role' => 'kurikulum',
                'initial_password' => 'kurikulum123',
            ],
        ];

        foreach ($accounts as $account) {
            $existing = Admin::where('username', $account['username'])->first()
                ?? Admin::where('username', $account['legacy_username'])->first()
                ?? Admin::where('role', $account['role'])->first();

            $attributes = [
                'username' => $account['username'],
                'role' => $account['role'],
                'nama_lengkap' => $account['nama_lengkap'],
                'nip' => $account['nip'],
                'password' => $existing?->password ?? Hash::make($account['initial_password']),
            ];

            Admin::updateOrCreate(
                ['username' => $existing?->username ?? $account['username']],
                $attributes
            );
        }
    }
}
