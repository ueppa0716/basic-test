<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('workdate_id')->constrained()->cascadeOnDelete();
            $table->dateTime('work_start')->nullable();
            $table->dateTime('work_end')->nullable();
            $table->time('work_time')->nullable();
            $table->time('total_rest')->nullable();
            $table->time('total_work')->nullable();
            // $table->dateTime('break_start')->nullable();
            // $table->dateTime('break_end')->nullable();
            // $table->dateTime('break_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}
