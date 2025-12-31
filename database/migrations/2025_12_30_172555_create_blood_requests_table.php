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
        Schema::create('blood_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('recipient_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('patient_name');

            $table->enum('blood_group', [
                'A+',
                'A-',
                'B+',
                'B-',
                'O+',
                'O-',
                'AB+',
                'AB-'
            ]);

            $table->unsignedTinyInteger('bags_required')->default(1);

            $table->string('hospital_name');

            $table->string('district');
            $table->string('upazila');

            $table->string('contact_phone');

            $table->enum('urgency_level', [
                'low',
                'medium',
                'high',
                'critical'
            ])->default('medium');

            $table->enum('status', [
                'pending',
                'approved',
                'fulfilled',
                'cancelled'
            ])->default('pending');

            $table->dateTime('needed_at');

            $table->foreignId('approved_by_admin')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('blood_group');
            $table->index('status');;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_requests');
    }
};
