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
        Schema::create('survey_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained('surveys')->onDelete('cascade');
            $table->string('type', 30);
            // $table->enum('type', ['text', 'textarea', 'number', 'email', 'phone', 'date', 'radio', 'checkbox', 'dropdown', 'rating', 'repeater']);
            $table->string('question');
            $table->string('placeholder')->nullable();
            $table->string('code')->nullable();
            $table->jsonb('options')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('is_required')->default(false);
            $table->integer('order')->default(0);
            $table->string('validation_rules')->nullable()->default('nullable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_questions');
    }
};
