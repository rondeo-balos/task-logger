<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('workplaces', function (Blueprint $table) {
            $table->id();
            $table->string( 'name' );
            $table->unsignedBigInteger( 'user_id' )->nullable();
            $table->timestamps();
        });

        DB::table( 'workplaces' )->insert([
            'name' => 'Harvey',
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('workplaces');
    }
};
