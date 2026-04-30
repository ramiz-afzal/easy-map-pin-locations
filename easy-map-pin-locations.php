<?php

/**
 * Plugin Name:       Easy Map Pin Locations
 * Plugin URI:        mailto:m.ramiz.afzal@gmail.com
 * Description:       Easy Map Pin Locations
 * Version:           1.0.0
 * Requires at least: 6.1
 * Requires PHP:      7.4
 * Author:            Ramiz Afzal
 * Author URI:        mailto:m.ramiz.afzal@gmail.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

// Direct access protection
defined('ABSPATH') or die();

// composer autoload
if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

// load global variables
if (class_exists('EASY_MAP_PIN_LOCATIONS\\Base\\Variable')) {
    EASY_MAP_PIN_LOCATIONS\Base\Variable::LOAD_VARIABLES(__FILE__);
}

// plugin activation callback
if (class_exists('EASY_MAP_PIN_LOCATIONS\\Base\\Activate')) {
    EASY_MAP_PIN_LOCATIONS\Base\Activate::activate(__FILE__);
}

// plugin deactivation callback
if (class_exists('EASY_MAP_PIN_LOCATIONS\\Base\\Deactivate')) {
    EASY_MAP_PIN_LOCATIONS\Base\Deactivate::deactivate(__FILE__);
}

// load plugin services
if (class_exists('EASY_MAP_PIN_LOCATIONS\\Init')) {
    EASY_MAP_PIN_LOCATIONS\Init::register_services();
}
