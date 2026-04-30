<?php

namespace PLUGIN_NAMESPACE\Admin;

use PLUGIN_NAMESPACE\Base\Variable;
use PLUGIN_NAMESPACE\Base\Functions;
use PLUGIN_NAMESPACE\Base\Constant;
use \Carbon_Fields\Carbon_Fields;
use \Carbon_Fields\Container;
use \Carbon_Fields\Field;
use PLUGIN_NAMESPACE\Core\WordPressHooks;

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
        self::register_settings_page_custom_fields();
    }


    public static function register_settings_page_custom_fields()
    {
        Container::make('theme_options', __('Theme Options'))
            ->set_page_menu_title('Theme Options')
            ->set_page_file(Constant::SLUG_ADMIN_MENU)
            ->set_icon('data:image/svg+xml;base64,' . base64_encode(file_get_contents(Variable::GET('PATH') . 'assets/img/menu-icon.svg')))
            ->set_page_menu_position(80)
            ->add_fields(array(
                //
                Field::make('separator', 'separator_1', __('Settings Fields Here')),
            ));
    }
}
