<?php namespace Jakub\Workly\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * CreateProfilesTable Migration
 *
 * @link https://docs.octobercms.com/3.x/extend/database/structure.html
 */
return new class extends Migration
{
    /**
     * up builds the migration
     */
    public function up()
    {
        Schema::create('jakub_workly_profiles', function(Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('surname');
            $table->string('email');
            $table->string('password');
            $table->string('token')->nullable();
            $table->string('token_expiration')->nullable();

            $table->timestamps();
        });
    }

    /**
     * down reverses the migration
     */
    public function down()
    {
        Schema::dropIfExists('jakub_workly_profiles');
    }
};
