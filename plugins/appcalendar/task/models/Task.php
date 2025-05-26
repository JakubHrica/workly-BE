<?php namespace AppCalendar\Task\Models;

use Model;

/**
 * Task Model
 *
 * @link https://docs.octobercms.com/3.x/extend/system/models.html
 */
class Task extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table name
     */
    public $table = 'appcalendar_task_tasks';

    /**
     * @var array rules for validation
     */
    public $rules = [
        'start_datetime' => 'date',
        'end_datetime' => 'date|after:start_datetime',
        'from_datetime' => 'date',
        'to_datetime' => 'date|after:from_datetime',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'tags' => 'nullable|string|max:1000',
        'priority' => 'required|in:Urgent & Important,Urgent & Not Important,Not Urgent & Important,Not Urgent & Not Important'
    ];

    /**
     * @var array fillable attributes
     */
    protected $fillable = ['user_id', 'title', 'description', 'tags', 'start_datetime', 'end_datetime', 'priority'];
}
