<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Consultation;
use App\Models\Question;
use App\Models\Answer;
use App\Models\ExaminationResult;
use App\Models\Diagnosis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::where('user_id', Auth::id())->get();
        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female',
            'phone_number' => 'nullable|string|max:15',
        ]);

        Patient::create([
            'name' => $request->name,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'phone_number' => $request->phone_number,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('patients.index');
    }

    public function show(Patient $patient)
    {
        if ($patient->user_id !== Auth::id()) {
            abort(403);
        }
        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        if ($patient->user_id !== Auth::id()) {
            abort(403);
        }
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        if ($patient->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female',
            'phone_number' => 'nullable|string|max:15',
        ]);

        $patient->update($request->all());

        return redirect()->route('dashboard')->with('success', 'Profile updated successfully.');
    }

    public function destroy(Patient $patient)
    {
        if ($patient->user_id !== Auth::id()) {
            abort(403);
        }
        $patient->delete();
        return redirect()->route('patients.index');
    }
}
