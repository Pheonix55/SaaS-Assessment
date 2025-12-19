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
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invitation_from')->constrained('users')->cascadeOnDelete();
            $table->string('token')->unique();
            $table->string('new_role');
            $table->string('previous_role')->nullable();
            $table->enum('status', ['pending', 'accepted', 'expired', 'revoked'])->default('pending');
            $table->timestamp('expiry_date');
            $table->timestamps();

            $table->index(['email', 'company_id']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
