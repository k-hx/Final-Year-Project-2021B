<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminLeaveApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_leave_applications', function (Blueprint $table) {
            $table->id();
            $table->string('admin');
            $table->string('leave_type_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedInteger('num_of_days');
            $table->string('reason');
            $table->string('document');
            $table->string('status');
            $table->string('leave_approver');
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
        Schema::dropIfExists('admin_leave_applications');
    }
}
