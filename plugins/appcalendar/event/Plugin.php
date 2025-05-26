<?php namespace AppCalendar\Event;

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
            'name' => 'Event',
            'description' => 'No description provided yet...',
            'author' => 'AppCalendar',
            'icon' => 'icon-leaf'
        ];
    }

    /**
     * registerNavigation used by the backend.
     */
    public function registerNavigation()
    {
        return [
            'event' => [
                'label' => 'Event',
                'url' => Backend::url('appcalendar/event/events'),
                'icon' => 'icon-calendar-3',
                'permissions' => ['appcalendar.event.*'],
                'order' => 1002,
            ],
        ];
    }

    /**
     * @var array Plugin dependencies
     */
    public $require = [
        'AppUser.Profile'
    ];
}
