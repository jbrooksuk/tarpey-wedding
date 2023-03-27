<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ResetFamilyMembersData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('family_members')->update([
            'attending' => null,
            'attending_evening' => null,
            'food_choice' => null,
            'dietary_requirements' => null,
        ]);
    }
}
