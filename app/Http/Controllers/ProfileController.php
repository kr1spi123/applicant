<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        $fields = [
            'name',
            'surname',
            'birthdate',
            'email',
            'phone',
            'street',
            'house',
            'city',
            'school',
            'graduation_year',
            'citizenship',
        ];

        $filled = 0;
        foreach ($fields as $field) {
            if (!empty($user->{$field})) {
                $filled++;
            }
        }

        $total = count($fields) ?: 1;
        $completion = (int) round($filled / $total * 100);

        return view('profile.edit', compact('user', 'completion'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:50'],
            'surname' => ['nullable', 'string', 'min:2', 'max:50'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:255'],
            'birthdate' => ['nullable', 'date'],
            'street' => ['nullable', 'string', 'max:255'],
            'house' => ['nullable', 'string', 'max:50'],
            'city' => ['nullable', 'string', 'max:255'],
            'citizenship' => ['nullable', 'string', 'max:255'],
            'school' => ['nullable', 'string', 'max:255'],
            'graduation_year' => ['nullable', 'integer', 'min:1900', 'max:' . now()->year],
        ]);

        try {
            $user->update($validated);
        } catch (\Throwable $e) {
            Log::error('Profile update failed', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
            ]);

            return back()
                ->withErrors(['profile' => 'Не удалось сохранить профиль. Попробуйте еще раз.'])
                ->withInput();
        }

        return redirect()->route('profile.edit')->with('success', 'Профиль успешно обновлен.');
    }
}
