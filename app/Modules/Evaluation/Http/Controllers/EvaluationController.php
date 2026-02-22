<?php

namespace App\Modules\Evaluation\Http\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class EvaluationController extends Controller
{
    public function index()
    {
        return Inertia::render('Evaluation/Index', [
            'message' => 'Hello from Evaluation Module!',
        ]);
    }
}
