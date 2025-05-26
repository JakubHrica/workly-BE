<?php namespace AppCalendar\Event\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * CreateEventsTable Migration
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
        Schema::create('appcalendar_event_events', function(Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('people')->nullable();
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');

            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('appuser_profile_users')
                  ->onDelete('cascade');
        });
    }

    /**
     * down reverses the migration
     */
    public function down()
    {
        Schema::dropIfExists('appcalendar_event_events');
    }
};
