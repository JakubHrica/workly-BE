<?php namespace AppUser\Profile\Updates;

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
        Schema::create('appuser_profile_users', function(Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('surname');
            $table->string('email');
            $table->string('password');
            $table->string('token')->nullable();
            $table->dateTime('token_expiration')->nullable();

            $table->timestamps();
        });
    }

    /**
     * down reverses the migration
     */
    public function down()
    {
        Schema::dropIfExists('appuser_profile_profiles');
    }
};
