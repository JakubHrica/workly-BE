<?php namespace AppUser\Profile\Models;

use Model;
use October\Rain\Database\Traits\Hashable;

/**
 * User Model
 *
 * @link https://docs.octobercms.com/3.x/extend/system/models.html
 */
class User extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use Hashable;
    
    public $table = 'appuser_profile_users';

    public $rules = [
        'name' => 'required|string|max:255',
        'surname' => 'required|string|max:255',
        'email' => 'required|email|unique:appuser_profile_users,email',
        'password' => 'string|min:6' // Mal som to required, ale nefungovalo mi to pri logine
    ];

    protected $fillable = [
        'name',
        'surname'
    ];

    protected $hashable = ['password'];
}
