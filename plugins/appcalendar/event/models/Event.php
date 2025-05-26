<?php namespace AppCalendar\Event\Models;

use Model;

/**
 * Event Model
 *
 * @link https://docs.octobercms.com/3.x/extend/system/models.html
 */
class Event extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table name
     */
    public $table = 'appcalendar_event_events';

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
        'people' => 'nullable|string|max:1000'
    ];

    /**
     * @var array fillable attributes
     */
    protected $fillable = ['user_id', 'title', 'description', 'people', 'start_datetime', 'end_datetime'];
}
