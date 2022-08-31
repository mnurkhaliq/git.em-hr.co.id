<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToJobseekersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobseekers', function (Blueprint $table) {
            $table->date('input_date')->nullable()->after('id');
            $table->string('nik')->nullable()->after('input_date');
            $table->string('company')->nullable()->after('nik');
            $table->string('title')->nullable()->after('company');
            $table->string('source')->nullable()->after('title');
            $table->string('pic')->nullable()->after('source');
            $table->string('gender')->nullable()->after('name');
            $table->string('experience')->nullable()->after('photos');
            $table->string('bachelor')->nullable()->after('experience');
            $table->integer('born_year')->nullable()->after('bachelor');
            $table->string('vaccinated')->nullable()->after('born_year');
            $table->string('english')->nullable()->after('vaccinated');
            $table->date('join_date')->nullable()->after('english');
            $table->string('wfo')->nullable()->after('join_date');
            $table->biginteger('salary')->nullable()->after('wfo');
            $table->string('last_process')->nullable()->after('salary');
            $table->string('notes')->nullable()->after('last_process');
            $table->string('attitude')->nullable()->after('notes');
            $table->string('reason')->nullable()->after('attitude');
            $table->integer('updated_by')->nullable()->after('reason');

            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobseekers', function (Blueprint $table) {
            //
        });
    }
}
