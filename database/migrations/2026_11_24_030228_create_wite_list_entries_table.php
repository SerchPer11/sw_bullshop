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
        Schema::create('wite_list_entries', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('email');
            $table->string('item_id')->nullable();
            $table->string('item_type')->nullable();
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();

            // Evita que la misma persona se registre 10 veces para el mismo artículo
            $table->unique(['email', 'item_id', 'item_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wite_list_entries');
    }
};
