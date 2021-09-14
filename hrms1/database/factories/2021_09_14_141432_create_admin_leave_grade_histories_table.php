<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminLeaveGradeHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_leave_grade_histories', function (Blueprint $table) {
            $table->id();
            $table->string('admin');
            $table->string('leave_grade');
            $table->DateTime('effective_from');
            $table->DateTime('effective_until')->nullable();$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_leave_grade_histories');
    }
}
