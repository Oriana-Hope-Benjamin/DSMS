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
        Schema::create('courses', function (Blueprint $table) {
            $table->id('id')->primary();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('total_hours')->comment('Total driving hours included');
            $table->decimal('total_price', 10, 2)->comment('The full price of the course');
            $table->decimal('initial_deposit_amount', 10, 2)->default(0)->comment('The minimum deposit required to enroll');
            $table->boolean('status_id')->default(true)->comment('Toggles if the course is visible for enrollment');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
