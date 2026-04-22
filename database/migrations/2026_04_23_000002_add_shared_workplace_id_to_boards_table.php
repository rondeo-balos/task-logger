<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('boards', function (Blueprint $table) {
            $table->foreignId('shared_workplace_id')
                  ->nullable()
                  ->after('workplace_id')
                  ->constrained('workplaces')
                  ->nullOnDelete();
        });
    }

    public function down(): void {
        Schema::table('boards', function (Blueprint $table) {
            $table->dropForeign(['shared_workplace_id']);
            $table->dropColumn('shared_workplace_id');
        });
    }
};
