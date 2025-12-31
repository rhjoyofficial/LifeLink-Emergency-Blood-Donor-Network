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
        Schema::create('donor_responses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('blood_request_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('donor_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->enum('response_status', [
                'interested',
                'contacted',
                'donated'
            ])->default('interested');

            $table->timestamps();

            $table->unique(['blood_request_id', 'donor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donor_responses');
    }
};
