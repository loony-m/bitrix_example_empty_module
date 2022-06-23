<?php

namespace Firstbit\Settings;

use Bitrix\Main\Config\Option;

class Control
{
    public static function GetOption()
    {
        $settings = [];

        $module_id = pathinfo(dirname(__DIR__))["basename"];

        $settings['hide_news'] = Option::get($module_id, "hide_news");
        $settings['phone'] = Option::get($module_id, "phone");
        $settings['email'] = Option::get($module_id, "email");
        $settings['SOCIAL_VK'] = Option::get($module_id, "SOCIAL_VK");
        $settings['SOCIAL_OK'] = Option::get($module_id, "SOCIAL_OK");

        return $settings;
    }
}