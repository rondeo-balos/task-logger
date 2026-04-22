<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('user_workspace_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shared_workspace_id')->constrained('workplaces')->cascadeOnDelete();
            $table->foreignId('personal_workspace_id')->constrained('workplaces')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'shared_workspace_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('user_workspace_preferences');
    }
};
