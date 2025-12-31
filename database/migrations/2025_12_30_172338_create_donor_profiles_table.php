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
        Schema::create('donor_profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

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

            $table->string('district');
            $table->string('upazila');

            $table->date('last_donation_date')->nullable();

            $table->boolean('is_available')->default(true);
            $table->boolean('approved_by_admin')->default(false);

            $table->timestamps();

            $table->unique('user_id');
            $table->index(['blood_group', 'district', 'upazila']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donor_profiles');
    }
};
