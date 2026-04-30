<?php

namespace EASY_MAP_PIN_LOCATIONS\Admin;

use EASY_MAP_PIN_LOCATIONS\Base\Constant;
use \Carbon_Fields\Carbon_Fields;
use \Carbon_Fields\Container;
use \Carbon_Fields\Field;
use EASY_MAP_PIN_LOCATIONS\Base\Functions;

if (!defined('ABSPATH')) exit;

class CustomFields
{

    public function register()
    {

        // init carbon fields
        add_action('after_setup_theme', [$this, 'load_carbon_fields']);

        // register fields and containers
        add_action('carbon_fields_register_fields', [$this, 'register_carbon_fields']);
    }

    /**
     * init carbon fields
     */
    public function load_carbon_fields()
    {
        Carbon_Fields::boot();
    }

    /**
     * register fields and containers
     */
    public function register_carbon_fields()
    {
        self::register_sub_city_custom_fields();
    }


    public static function register_sub_city_custom_fields()
    {
        $container = Container::make('post_meta', __('Map Settings'));
        $container->where('post_type', '=', Constant::CPT_MAP);
        $container->add_fields(array(
            Field::make('file', Functions::prefix('locations_data'), __('Locations'))->set_type(['csv']),

        ));
    }
}
