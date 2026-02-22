<?php

namespace App\Modules\Evaluation\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Evaluation\Models\EvaluationSession;
use Inertia\Inertia;

class EvaluationController extends Controller
{
    public function index()
    {
        $sessions = EvaluationSession::with(['evaluatee', 'evaluator', 'template'])
            ->latest()
            ->get()
            ->map(fn($session) => [
                'id' => $session->getRouteKey(),
                'status' => $session->status,
                'evaluatee' => $session->evaluatee->name,
                'evaluator' => $session->evaluator->name,
                'template' => $session->template->name,
                'submitted_at' => $session->submitted_at?->format('Y-m-d H:i'),
                'completed_at' => $session->completed_at?->format('Y-m-d H:i'),
            ]);

        return Inertia::render('Evaluation/Index', [
            'sessions' => $sessions,
        ]);
    }
}
