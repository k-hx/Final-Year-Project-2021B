<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use DB;

class CreateLeaveTypesTestingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_types_testing', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('status');
            $table->unsignedInteger('min_num_of_days');
            $table->timestamps();
        });
    }

    DB::table('leave_types_testing')->insert(
        array(
            'name' => 'Unpaid Leave',
            'status' => 'added';
            'min_num_of_days' => '0';
        )
    );

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leave_types');
    }
}
