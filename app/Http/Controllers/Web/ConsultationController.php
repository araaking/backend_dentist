<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // TODO: Implement logic to show consultation history
        return "Consultation History Page";
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // TODO: Implement logic to show self-diagnosis form
        return "Self Diagnosis Page";
    }
}
