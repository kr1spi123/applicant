<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Specialty;
use App\Models\Application;
use App\Jobs\GeneratePdfJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ApplicationSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_submit_application_with_multiple_specialties()
    {
        Queue::fake();
        Storage::fake('public');

        $user = User::factory()->create();
        $specialties = Specialty::factory()->count(3)->create();

        $file = UploadedFile::fake()->create('certificate.pdf', 100);
        $benefitFile = UploadedFile::fake()->create('benefit.pdf', 100);

        $response = $this->actingAs($user)->post(route('applications.store'), [
            'name' => 'Иван',
            'surname' => 'Иванов',
            'phone' => '+7(999)123-45-67',
            'email' => 'ivan@example.com',
            'birthdate' => '2005-01-01',
            'street' => 'Ленина',
            'house' => '1',
            'school' => 'School #1',
            'graduation_year' => 2023,
            'certificate_file' => $file,
            'ege_score' => 250,
            'certificate_score' => 4.5,
            'citizenship' => 'Россия',
            'benefits' => ['orphan', 'disabled'],
            'benefit_proof' => $benefitFile,
            'has_achievements' => true,
            'specialty' => [$specialties[0]->id, $specialties[1]->id],
        ]);

        $response->assertRedirect(route('applications.index'));
        $response->assertSessionHas('success');

        $this->assertCount(2, Application::all());
        
        $this->assertDatabaseHas('applications', [
            'user_id' => $user->id,
            'specialty_id' => $specialties[0]->id,
            'full_name' => 'Иван Иванов',
            'ege_score' => 250,
        ]);

        $this->assertDatabaseHas('applications', [
            'user_id' => $user->id,
            'specialty_id' => $specialties[1]->id,
            'full_name' => 'Иван Иванов',
            'ege_score' => 250,
        ]);

        // Assert file was stored
        Storage::disk('public')->assertExists('certificates/' . $file->hashName());
        Storage::disk('public')->assertExists('benefits/' . $benefitFile->hashName());

        // PDF generation disabled: ensure no jobs were pushed
        Queue::assertNothingPushed();
    }

    public function test_duplicate_application_check()
    {
        $user = User::factory()->create();
        $specialty = Specialty::factory()->create();

        // Create existing application
        Application::create([
            'user_id' => $user->id,
            'specialty_id' => $specialty->id,
            'full_name' => 'Test User',
            'phone' => '123',
            'email' => 'test@test.com',
            'birthdate' => '2000-01-01',
            'street' => 'St',
            'house' => '1',
            'school' => 'Sch',
            'graduation_year' => 2020,
            'ege_score' => 200,
            'certificate_score' => 4.0,
            'status' => 'new',
            'rating' => 0,
        ]);

        $file = UploadedFile::fake()->create('certificate.pdf', 100);

        $response = $this->actingAs($user)->post(route('applications.store'), [
            'name' => 'Иван',
            'surname' => 'Иванов',
            'phone' => '+7(999)123-45-67',
            'email' => 'ivan@example.com',
            'birthdate' => '2005-01-01',
            'street' => 'Ленина',
            'house' => '1',
            'school' => 'School #1',
            'graduation_year' => 2023,
            'certificate_file' => $file,
            'ege_score' => 250,
            'certificate_score' => 4.5,
            'citizenship' => 'Россия',
            'specialty' => [$specialty->id], // Same specialty
        ]);

        $response->assertSessionHasErrors(['specialty']);
    }

    public function test_verify_route_works()
    {
        $user = User::factory()->create();
        $specialty = Specialty::factory()->create();
        
        $application = Application::create([
            'user_id' => $user->id,
            'specialty_id' => $specialty->id,
            'full_name' => 'Test User',
            'phone' => '123',
            'email' => 'test@test.com',
            'birthdate' => '2000-01-01',
            'street' => 'St',
            'house' => '1',
            'school' => 'Sch',
            'graduation_year' => 2020,
            'ege_score' => 200,
            'certificate_score' => 4.0,
            'status' => 'На рассмотрении',
            'rating' => 0,
        ]);

        $response = $this->get(route('applications.verify', ['id' => $application->id]));
        
        $response->assertStatus(200);
        $response->assertViewIs('applications.verify');
        $response->assertSee('Test User');
        $response->assertSee($specialty->name);
        $response->assertSee('На рассмотрении');
    }
}
