<?php namespace AppCalendar\Calendar;

use Backend;
use System\Classes\PluginBase;

/**
 * Plugin Information File
 *
 * @link https://docs.octobercms.com/3.x/extend/system/plugins.html
 */
class Plugin extends PluginBase
{
    /**
     * pluginDetails about this plugin.
     */
    public function pluginDetails()
    {
        return [
            'name' => 'Calendar',
            'description' => 'No description provided yet...',
            'author' => 'AppCalendar',
            'icon' => 'icon-leaf'
        ];
    }

    /**
     * @var array Plugin dependencies
     */
    public $require = [
        'AppUser.Profile'
    ];
}
