<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('evaluation_questions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('content');
            $table->string('category')->nullable();
            $table->enum('type', ['rating', 'text'])->default('rating');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('evaluation_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('evaluation_template_question', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('evaluation_templates')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('evaluation_questions')->onDelete('cascade');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('evaluation_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('evaluation_templates')->onDelete('cascade');
            $table->foreignId('evaluatee_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('evaluator_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['draft', 'submitted', 'completed', 'cancelled'])->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('final_comments')->nullable();
            $table->timestamps();
        });

        Schema::create('evaluation_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_session_id')->constrained('evaluation_sessions')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('evaluation_questions')->onDelete('cascade');
            $table->integer('score')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_answers');
        Schema::dropIfExists('evaluation_sessions');
        Schema::dropIfExists('evaluation_template_question');
        Schema::dropIfExists('evaluation_templates');
        Schema::dropIfExists('evaluation_questions');
    }
};
