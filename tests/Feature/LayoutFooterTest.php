<?php

namespace Tests\Feature;

use App\Models\Guru;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LayoutFooterTest extends TestCase
{
    use RefreshDatabase;

    public function test_guru_layout_uses_guru_navigation_in_the_shared_footer(): void
    {
        $guru = Guru::create([
            'nip' => 'footer-guru',
            'nama_lengkap' => 'Guru Footer',
            'golongan' => 'III/a',
            'mata_pelajaran' => 'Matematika',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($guru)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('id="kt_footer"', false)
            ->assertSee('href="' . route('dashboard') . '"', false)
            ->assertSee('href="' . route('profil') . '"', false)
            ->assertSee('href="' . route('dokumen.index') . '"', false)
            ->assertDontSee('href="' . route('admin.dashboard') . '"', false);
    }

    public function test_admin_layout_uses_admin_navigation_in_the_shared_footer(): void
    {
        $this->withSession(['admin_id' => 1])
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('id="kt_footer"', false)
            ->assertSee('href="' . route('admin.dashboard') . '"', false)
            ->assertSee('href="' . route('admin.guru.index') . '"', false)
            ->assertSee('href="' . route('admin.mata-pelajaran.index') . '"', false)
            ->assertSee('href="' . route('admin.supervisi.index') . '"', false)
            ->assertSee('href="' . route('admin.agenda.index') . '"', false)
            ->assertDontSee('href="' . route('dashboard') . '"', false);
    }
}
