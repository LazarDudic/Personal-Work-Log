<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftDifferentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shift_differentials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')
                ->constrained()
                ->onDelete('cascade');
            $table->time('start_at')->nullable();
            $table->time('finish_at')->nullable();
            $table->string('differential_days')->nullable();
            $table->unsignedSmallInteger('percentage')->nullable();
            $table->unsignedDecimal('currency_amount', 8, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shift_differentials');
    }
}
