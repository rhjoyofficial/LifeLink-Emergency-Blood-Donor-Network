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
        Schema::create('recipient_profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('district');
            $table->string('upazila');
            $table->enum('blood_group', [
                'A+',
                'A-',
                'B+',
                'B-',
                'O+',
                'O-',
                'AB+',
                'AB-'
            ])->nullable();

            $table->enum('status', [
                'pending',
                'approved',
                'fulfilled',
                'cancelled'
            ])->default('pending');
            
            $table->timestamps();

            $table->unique('user_id');
            $table->index(['blood_group', 'district', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipient_profiles');
    }
};
