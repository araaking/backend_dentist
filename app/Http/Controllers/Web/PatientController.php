<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function create()
    {
        if (Auth::user()->patient) {
            return redirect()->route('dashboard')->with('error', 'Profil pasien Anda sudah ada.');
        }

        return view('patients.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->patient) {
            return redirect()->route('dashboard')->with('error', 'Profil pasien Anda sudah ada.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female',
            'phone_number' => 'nullable|string|max:15',
        ]);

        $user->patient()->create($data);

        return redirect()->route('dashboard')->with('success', 'Profil pasien berhasil dibuat.');
    }

    public function edit(Patient $patient)
    {
        if ((int) $patient->user_id !== (int) Auth::id()) {
            abort(403);
        }
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        if ((int) $patient->user_id !== (int) Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female',
            'phone_number' => 'nullable|string|max:15',
        ]);

        $patient->update($data);

        return redirect()->route('dashboard')->with('success', 'Profil berhasil diperbarui.');
    }
}
