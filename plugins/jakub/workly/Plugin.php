<?php namespace Jakub\Workly;

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
            'name' => 'Workly',
            'description' => 'Plugin for Workly API',
            'author' => 'Jakub',
            'icon' => 'icon-leaf'
        ];
    }
}