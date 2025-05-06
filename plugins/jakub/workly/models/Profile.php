<?php namespace Jakub\Workly\Models;

use Model;

/**
 * Profile Model
 *
 * @link https://docs.octobercms.com/3.x/extend/system/models.html
 */
class Profile extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table name
     */
    public $table = 'jakub_workly_profiles';

    /**
     * @var array rules for validation
     */
    public $rules = [
        'name' => 'required|string|max:255',
        'surname' => 'required'|'string|max:255',
        'email' => 'required|email',
        'password' => 'required|min:6',
        'token' => 'required',
    ];

    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'token',
        'token_expires_at',
    ];
}
