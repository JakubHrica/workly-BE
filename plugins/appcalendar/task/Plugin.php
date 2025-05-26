<?php namespace AppCalendar\Task;

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
            'name' => 'Task',
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
            'task' => [
                'label' => 'Task',
                'url' => Backend::url('appcalendar/task/tasks'),
                'icon' => 'icon-tasks',
                'permissions' => ['appcalendar.task.*'],
                'order' => 1003,
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
