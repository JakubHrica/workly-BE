<?php namespace Jakub\Workly\Updates;

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
        Schema::create('jakub_workly_events', function(Blueprint $table) {
            $table->id();

            $table->string('user_id');
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->string('title');
            $table->string('description');
            $table->enum('type', ['event', 'meeting', 'task']);

            $table->timestamps();
        });
    }

    /**
     * down reverses the migration
     */
    public function down()
    {
        Schema::dropIfExists('jakub_workly_events');
    }
};
