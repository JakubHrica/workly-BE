<?php namespace AppCalendar\Event;

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
            'description' => 'Plugin for managing events in the calendar',
            'author' => 'AppCalendar',
            'icon' => 'icon-calendar'
        ];
    }
}
