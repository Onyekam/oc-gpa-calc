<?php namespace Expressuals\GpaCalc;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            'Expressuals\GpaCalc\Components\Courses' => 'Courses',
        ];
        
    }

    public function registerSettings()
    {
    }
}
