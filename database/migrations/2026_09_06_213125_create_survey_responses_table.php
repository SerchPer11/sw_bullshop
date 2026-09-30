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
        Schema::create('survey_responses', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignId('survey_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUlid('lead_id')->nullable()->constrained()->cascadeOnDelete();
            $table->jsonb('responses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_responses');
    }
};
