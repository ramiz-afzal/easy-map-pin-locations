<?php

namespace EASY_MAP_PIN_LOCATIONS;

if (!defined('ABSPATH')) exit;

final class Init
{
    public static function get_services()
    {
        return array(
            Base\Enqueue::class,
            Admin\CustomFields::class,
            Admin\CustomMetaBoxes::class,
            Core\Shortcode::class,
            Core\CustomPostTypes::class,
        );
    }

    public static function register_services()
    {
        foreach (self::get_services() as $class) {

            $service = self::instantiate($class);
            if (method_exists($service, 'register')) {
                $service->register();
            }
        }
    }

    private static function instantiate($class)
    {
        return new $class();
    }
}
