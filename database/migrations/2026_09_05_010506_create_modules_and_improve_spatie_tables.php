<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('key')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->string('description')->nullable()->after('guard_name');
            $table->string('module_key')->nullable()->after('description');
            
            $table->foreign('module_key')->references('key')->on('modules')->nullOnDelete();
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->string('description')->nullable()->after('guard_name');
        });
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('description');
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->dropForeign(['module_key']);
            $table->dropColumn(['description', 'module_key']);
        });

        Schema::dropIfExists('modules');
    }
};