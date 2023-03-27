<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFoodChoiceToFamilyMembers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('family_members', function (Blueprint $table) {
            $table->string('starter_food_choice')->nullable()->after('attending_evening');
            $table->string('main_food_choice')->nullable()->after('starter_food_choice');
            $table->string('dessert_food_choice')->nullable()->after('main_food_choice');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('family_members', function (Blueprint $table) {
            $table->dropColumn(['starter_food_choice', 'main_food_choice', 'dessert_food_choice']);
        });
    }
}
