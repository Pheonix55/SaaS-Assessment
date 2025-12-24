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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')->constrained()->cascadeOnDelete();

            // Actor
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Action metadata
            $table->string('action'); // created, updated, deleted, login, etc
            $table->string('auditable_type'); // Model class
            $table->unsignedBigInteger('auditable_id')->nullable();

            // State
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            // Context
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();

            $table->timestamps();

            $table->index(['company_id', 'auditable_type', 'auditable_id']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
