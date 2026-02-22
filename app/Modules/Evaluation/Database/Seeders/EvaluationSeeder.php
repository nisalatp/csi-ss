<?php

namespace App\Modules\Evaluation\Database\Seeders;

use App\Models\Company;
use App\Models\User;
use App\Modules\Evaluation\Models\EvaluationQuestion;
use App\Modules\Evaluation\Models\EvaluationSession;
use App\Modules\Evaluation\Models\EvaluationTemplate;
use App\Modules\Evaluation\Models\Question;
use Illuminate\Database\Seeder;

class EvaluationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = Company::first();
        if (!$company) return;

        // Create Questions
        $questions = [
            ['name' => 'Technical Proficiency', 'content' => 'Rate the employee\'s ability to perform technical tasks.', 'category' => 'Core', 'type' => 'rating'],
            ['name' => 'Team Collaboration', 'content' => 'How well does the employee work with others?', 'category' => 'Soft Skills', 'type' => 'rating'],
            ['name' => 'Punctuality', 'content' => 'Evaluate the employee\'s attendance and time management.', 'category' => 'General', 'type' => 'rating'],
            ['name' => 'Overall Performance', 'content' => 'Provide a summary of the employee\'s overall performance this period.', 'category' => 'Summary', 'type' => 'text'],
        ];

        foreach ($questions as $q) {
            Question::create($q);
        }

        // Create Template
        $template = EvaluationTemplate::create([
            'name' => 'Annual Performance Review 2026',
            'description' => 'Standard annual review for all staff.',
            'company_id' => $company->id,
        ]);

        $template->questions()->attach(Question::all()->pluck('id'), ['order' => 1]);

        // Create Sessions
        $users = User::take(5)->get();
        if ($users->count() < 2) return;

        EvaluationSession::create([
            'template_id' => $template->id,
            'evaluatee_id' => $users[1]->id,
            'evaluator_id' => $users[0]->id,
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        EvaluationSession::create([
            'template_id' => $template->id,
            'evaluatee_id' => $users[0]->id,
            'evaluator_id' => $users[1]->id,
            'status' => 'draft',
        ]);
    }
}
