<?php namespace AppCalendar\Task\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use AppUser\Profile\Models\User;

/**
 * CreateTasksTable Migration
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
        Schema::create('appcalendar_task_tasks', function(Blueprint $table) {
            $table->id();

            $table->foreignIdFor(User::class, 'user_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('tags')->nullable();
            $table->enum('priority', [
                'Urgent & Important',
                'Urgent & Not Important',
                'Not Urgent & Important',
                'Not Urgent & Not Important'
            ])->default('Not Urgent & Not Important');
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');

            $table->timestamps();
        });
    }

    /**
     * down reverses the migration
     */
    public function down()
    {
        Schema::dropIfExists('appcalendar_task_tasks');
    }
};
