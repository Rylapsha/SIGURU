<?php

namespace Database\Seeders;

use App\Models\Guru;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teachers = [
            [
                'nip' => '198501012010011001',
                'nama_lengkap' => 'Meerqeen de valorant',
                'golongan' => 'III/b',
                'mata_pelajaran' => 'Matematika',
            ],
            [
                'nip' => '198703152012022002',
                'nama_lengkap' => 'Ice Matuah',
                'golongan' => 'III/c',
                'mata_pelajaran' => 'Bahasa Indonesia',
            ],
        ];

        foreach ($teachers as $teacher) {
            $existing = Guru::where('nip', $teacher['nip'])->first();

            if ($existing) {
                $attributes = $existing->only([
                    'nama_lengkap',
                    'golongan',
                    'mata_pelajaran',
                    'password',
                    'foto',
                ]);

                Guru::withoutTimestamps(fn () => Guru::updateOrCreate(
                    ['nip' => $teacher['nip']],
                    $attributes
                ));

                continue;
            }

            Guru::updateOrCreate(
                ['nip' => $teacher['nip']],
                array_merge($teacher, [
                    'password' => Hash::make('123456'),
                    'foto' => null,
                ])
            );
        }
    }
}
