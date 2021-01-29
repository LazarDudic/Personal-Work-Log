<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')
                ->constrained()
                ->onDelete('cascade');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->unsignedInteger('break_minutes')->nullable();
            $table->unsignedDecimal('total_earnings', 8, 2)->nullable();
            $table->unsignedInteger('total_working_minutes')->nullable();
            $table->unsignedDecimal('regular_earnings', 8, 2)->nullable();
            $table->unsignedDecimal('overtime_earnings', 8, 2)->nullable();
            $table->unsignedInteger('overtime_minutes')->nullable();
            $table->unsignedDecimal('shift_differential_earnings', 8, 2)->nullable();
            $table->unsignedInteger('shift_differential_minutes')->nullable();
            $table->unsignedDecimal('tips', 8, 2)->nullable();
            $table->unsignedDecimal('bonuses', 8, 2)->nullable();
            $table->unsignedDecimal('expenses', 8, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shifts');
    }
}
