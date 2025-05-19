<?php namespace AppCalendar\Event;

use System\Classes\PluginBase;
use Backend;

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
            'description' => 'Plugin for managing events in the calendar',
            'author' => 'AppCalendar',
            'icon' => 'icon-calendar'
        ];
    }

    /**
     * registerNavigation used by the backend.
     */
    public function registerNavigation()
    {
        return [
            'user' => [
                'label' => 'Event',
                'url' => Backend::url('appcalendar/event/event'),
                'icon' => 'icon-calendar-3',
                'permissions' => ['appuser.user.*'],
                'order' => 1002,
            ],
        ];
    }
}
