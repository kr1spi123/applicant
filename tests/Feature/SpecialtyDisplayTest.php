<?php

namespace Tests\Feature;

use App\Models\Specialty;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SpecialtyDisplayTest extends TestCase
{
    use RefreshDatabase;

    public function test_specialties_index_shows_costs_and_study_forms()
    {
        $specialty = Specialty::factory()->create([
            'name' => 'Информационные системы и программирование',
            'code' => '09.02.07',
            'cost_full_time' => 65000.00,
            'cost_part_time' => 58000.00,
            'cost_distance' => null,
        ]);

        $response = $this->get(route('specialties.index'));

        $response->assertStatus(200);
        $response->assertSee('Информационные системы и программирование');
        $response->assertSee('очная');
        $response->assertSee('заочная');
        $response->assertSee('65 000');
        $response->assertSee('58 000');
    }

    public function test_specialty_show_page_displays_costs_and_study_forms()
    {
        $specialty = Specialty::factory()->create([
            'name' => 'Сетевое и системное администрирование',
            'code' => '09.02.06',
            'cost_full_time' => 62000.00,
            'cost_part_time' => null,
            'cost_distance' => 55000.00,
        ]);

        $response = $this->get(route('specialties.show', $specialty));

        $response->assertStatus(200);
        $response->assertSee('Сетевое и системное администрирование');
        $response->assertSee('очная');
        $response->assertSee('дистанционная');
        $response->assertSee('62 000');
        $response->assertSee('55 000');
    }
}
