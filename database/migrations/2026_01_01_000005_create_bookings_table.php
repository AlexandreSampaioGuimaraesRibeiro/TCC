<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('professional_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained();
            $table->date('scheduled_date');
            $table->time('scheduled_time');
            $table->json('address_snapshot')->nullable();
            $table->decimal('price', 8, 2)->default(0);
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected', 'completed', 'cancelled'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            $table->index(['professional_profile_id', 'status', 'scheduled_date'], 'booking_prof_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
