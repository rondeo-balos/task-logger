<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('boards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workplace_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->json('description')->nullable();
            $table->string('status')->default('pending');
            $table->timestamp('due_date')->nullable();
            $table->json('attachments')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('boards');
    }
};
