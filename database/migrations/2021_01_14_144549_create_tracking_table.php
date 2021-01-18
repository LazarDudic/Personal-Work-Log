<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')
                ->constrained()
                ->onDelete('cascade');
            $table->boolean('wage')->default(0);
            $table->boolean('overtime')->default(0);
            $table->boolean('shift_differential')->default(0);
            $table->boolean('tips')->default(0);
            $table->boolean('bonuses')->default(0);
            $table->boolean('expenses')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trackings');
    }
}
