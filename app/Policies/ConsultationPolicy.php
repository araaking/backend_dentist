<?php

namespace App\Policies;

use App\Models\Consultation;
use App\Models\User;

class ConsultationPolicy
{
    /**
     * Konsultasi (dan foto-nya) hanya boleh dilihat oleh pasien pemiliknya atau admin.
     */
    public function view(User $user, Consultation $consultation): bool
    {
        if ($user->is_admin) {
            return true;
        }

        $patient = $user->patient;

        return $patient !== null && (int) $patient->id === (int) $consultation->patient_id;
    }
}
