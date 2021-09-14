<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_leaves', function (Blueprint $table) {
            $table->id();
            $table->string('admin');
            $table->string('leave_type');
            $table->string('total_days');
            $table->string('leaves_taken');
            $table->string('remaining_days');
            $table->string('year');
            $table->string('status');
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
        Schema::dropIfExists('admin_leaves');
    }
}
