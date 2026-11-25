<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\TmdQuestionnaire;
use Illuminate\Http\Request;

class QuestionsController extends Controller
{
    public function index(Request $request)
    {
        // Metadata kuesioner (sumber tunggal: App\Support\TmdQuestionnaire).
        return response()->json(TmdQuestionnaire::apiPayload());
    }
}
