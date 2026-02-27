<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Application;
use App\Models\User;
use App\Models\Specialty;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', '!=', 'admin')->get();
        $specialties = Specialty::all();

        if ($users->isEmpty() || $specialties->isEmpty()) {
            return;
        }

        foreach ($users as $user) {
            // Создаем 1-2 случайные заявки для каждого пользователя
            $numApps = rand(1, 2);
            $selectedSpecialties = $specialties->random($numApps);

            foreach ($selectedSpecialties as $specialty) {
                $forms = array_keys($specialty->available_study_forms);
                $studyForm = !empty($forms) ? $forms[array_rand($forms)] : 'очная';

                Application::create([
                    'user_id' => $user->id,
                    'specialty_id' => $specialty->id,
                    'study_form' => $studyForm,
                    'full_name' => $user->surname . ' ' . $user->name,
                    'phone' => $user->phone ?? '+7(900)000-00-00',
                    'email' => $user->email,
                    'birthdate' => $user->birthdate ?? '2005-01-01',
                    'street' => $user->street ?? 'Центральная',
                    'house' => $user->house ?? '10',
                    'postal_code' => '123456',
                    'school' => $user->school ?? 'СОШ №1',
                    'graduation_year' => $user->graduation_year ?? 2023,
                    'certificate_file' => 'seed_certificate.pdf',
                    'ege_score' => rand(150, 280),
                    'certificate_score' => number_format(rand(35, 50) / 10, 1),
                    'has_achievements' => (bool)rand(0, 1),
                    'status' => 'На рассмотрении',
                    'rating' => 0,
                ]);
            }
        }
    }
}
