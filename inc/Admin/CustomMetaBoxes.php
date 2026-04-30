<?php

namespace EASY_MAP_PIN_LOCATIONS\Admin;

use EASY_MAP_PIN_LOCATIONS\Base\Constant;
use EASY_MAP_PIN_LOCATIONS\Base\Functions;

if (!defined('ABSPATH')) exit;

class CustomMetaBoxes
{

    public function register()
    {
        // add custom metabox
        add_action('add_meta_boxes', [$this, 'add_meta_box'], 10, 1);
    }

    /**
     * 
     */
    public static function get_meta_boxes()
    {
        return array(
            Constant::CPT_MAP => array(
                array(
                    'id'            => 'map-shortcode',
                    'title'         => 'Shortcode',
                    'callback'      => [get_called_class(), 'render_metabox'],
                    'screen'        => Constant::CPT_MAP,
                    'context'       => 'side',
                    'priority'      => 'default',
                    'callback_args' => null,
                ),
            ),
        );
    }

    /**
     * @param String $current_post_type
     */
    public function add_meta_box($current_post_type)
    {
        $cpt_meta_boxes = self::get_meta_boxes();
        foreach ($cpt_meta_boxes as $post_type => $meta_boxes) {
            if ($current_post_type == $post_type) {

                foreach ($meta_boxes as $args) {

                    $id             = $args['id'] ?? uniqid('empl_', true);
                    $title          = $args['title'] ?? NULL;
                    $callback       = $args['callback'] ?? NULL;
                    $screen         = $args['screen'] ?? $post_type;
                    $context        = $args['context'] ?? NULL;
                    $priority       = $args['priority'] ?? NULL;
                    $callback_args  = $args['callback_args'] ?? NULL;

                    add_meta_box($id, $title, $callback, $screen, $context, $priority, $callback_args);
                }
            }
        }
    }


    public static function render_metabox($post, $metabox = [])
    {
        if (!isset($metabox['id']) || strpos($post->post_status, 'draft') !== false) {
            echo Functions::get_template("admin/metaboxes/default.php");
            return;
        }

        $metabox_id = $metabox['id'];
        echo Functions::get_template("admin/metaboxes/{$metabox_id}.php", ['post' => $post]);
    }
}
