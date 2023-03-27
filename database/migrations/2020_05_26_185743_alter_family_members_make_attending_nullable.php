<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFamilyMembersMakeAttendingNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('family_members', function (Blueprint $table) {
            $table->boolean('attending')->nullable()->default(null)->change();
            $table->boolean('attending_evening')->nullable()->default(null)->change();
        });
    }
}
