<?php namespace AppUser\Profile\Models;

use Model;

/**
 * User Model
 *
 * @link https://docs.octobercms.com/3.x/extend/system/models.html
 */
class User extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table name
     */
    public $table = 'appuser_profile_users';

    /**
     * @var array rules for validation
     */
    public $rules = [
        'name' => 'required|string|max:255',
        'surname' => 'required|string|max:255',
        'email' => 'required|email|unique:appuser_profile_users,email',
        'password' => 'required|string|min:6'
    ];

    /**
     * @var array fillable attributes
     */
    protected $fillable = ['name', 'surname', 'email', 'password', 'token'];
}
