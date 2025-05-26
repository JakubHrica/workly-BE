<?php namespace AppUser\Profile;

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
            'name' => 'Profile',
            'description' => 'Plugin for managing workly profiles',
            'author' => 'AppUser',
            'icon' => 'icon-profile'
        ];
    }

    /**
     * registerNavigation used by the backend.
     */
    public function registerNavigation()
    {
        return [
            'user' => [
                'label' => 'User',
                'url' => Backend::url('appuser/profile/users'),
                'icon' => 'icon-user-circle',
                'permissions' => ['appuser.user.*'],
                'order' => 1001,
            ],
        ];
    }
}
