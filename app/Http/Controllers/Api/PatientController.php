<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function show(Request $request)
    {
        $patient = $request->user()->patient;

        if (! $patient) {
            return response()->json([
                'message' => 'Profil pasien belum dibuat.'
            ], 404);
        }

        return response()->json([
            'patient' => $patient,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        if ($user->patient) {
            return response()->json([
                'message' => 'Profil pasien sudah ada. Gunakan endpoint update untuk memperbarui.'
            ], 409);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female',
            'phone_number' => 'nullable|string|max:15',
        ]);

        $patient = $user->patient()->create([
            'name' => $data['name'],
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'gender' => $data['gender'] ?? null,
            'phone_number' => $data['phone_number'] ?? null,
        ]);

        return response()->json([
            'message' => 'Profil pasien berhasil dibuat.',
            'patient' => $patient,
        ], 201);
    }

    public function update(Request $request)
    {
        $patient = $request->user()->patient;

        if (! $patient) {
            return response()->json([
                'message' => 'Profil pasien belum dibuat.'
            ], 404);
        }

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'date_of_birth' => 'sometimes|nullable|date',
            'gender' => 'sometimes|nullable|in:Male,Female',
            'phone_number' => 'sometimes|nullable|string|max:15',
        ]);

        $patient->update($data);

        return response()->json([
            'message' => 'Profil pasien berhasil diperbarui.',
            'patient' => $patient->fresh(),
        ]);
    }
}
