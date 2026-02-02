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
        Schema::create('faiths', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('religion_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->date('date_converted');
            $table->text('reason_converted')->nullable();
            $table->date('date_reverted')->nullable();
            $table->text('reason_reverted')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faiths');
    }
};
