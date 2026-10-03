<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Guru;
use Illuminate\Support\Facades\Hash;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Guru::create([
            'nip' => '198501012010011001',
            'nama_lengkap' => 'Oliver Rojali',
            'golongan' => 'III/b',
            'mata_pelajaran' => 'Matematika',
            'password' => Hash::make('123456'),
            'foto' => null,
        ]);

        Guru::create([
            'nip' => '198703152012022002',
            'nama_lengkap' => 'Ice Matuah',
            'golongan' => 'III/c',
            'mata_pelajaran' => 'Bahasa Indonesia',
            'password' => Hash::make('123456'),
            'foto' => null,
        ]);
    }
}
