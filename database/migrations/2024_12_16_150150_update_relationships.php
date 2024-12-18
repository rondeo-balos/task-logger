<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        // Tasks
        Schema::table( 'tasks', function(Blueprint $table) {
            $table->unsignedBigInteger( 'workplace_id' )->nullable()->default(1);
        });

        // Tags
        Schema::table( 'tags', function(Blueprint $table) {
            $table->unsignedBigInteger( 'workplace_id' )->nullable()->default(1);
        });

        // Notes
        Schema::table( 'notes', function(Blueprint $table) {
            $table->unsignedBigInteger( 'workplace_id' )->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        // Tasks
        Schema::table( 'tasks', function(Blueprint $table) {
            $table->dropColumn( 'workplace_id' );
        });

        // Tags
        Schema::table( 'tags', function(Blueprint $table) {
            $table->dropColumn( 'workplace_id' );
        });

        // Notes
        Schema::table( 'notes', function(Blueprint $table) {
            $table->dropColumn( 'workplace_id' );
        });
    }
};
