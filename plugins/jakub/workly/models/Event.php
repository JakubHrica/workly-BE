<?php namespace Jakub\Workly\Models;

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
    public $table = 'jakub_workly_events';

    /**
     * @var array rules for validation
     */
    public $rules = [];
}
