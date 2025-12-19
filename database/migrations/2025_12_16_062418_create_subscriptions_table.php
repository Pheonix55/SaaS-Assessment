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
        Schema::create('subscriptions_old', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->integer('plan_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status')->default('inactive')->only('active', 'inactive', 'cancelled');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
