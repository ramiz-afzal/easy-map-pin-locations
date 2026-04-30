<?php

namespace EASY_MAP_PIN_LOCATIONS\Core;

use EASY_MAP_PIN_LOCATIONS\Base\Functions;
use EASY_MAP_PIN_LOCATIONS\Base\Constant;

class Shortcode
{
    public function register()
    {
        add_shortcode('empl_map', [$this, 'render_shortcode']);
    }

    /**
     * @param array $atts
     * @param null|string $content
     * @param string $shortcode_tag
     */
    public function render_shortcode($atts, $content = null, $shortcode_tag = '')
    {
        try {
            return Functions::get_template("shortcodes/{$shortcode_tag}.php", ['atts' => $atts, 'content' => $content, 'shortcode_tag' => $shortcode_tag]);
        } catch (\Throwable $th) {
            Functions::debug_log(__METHOD__);
            Functions::debug_log($th->getMessage());
            return '';
        }
    }
}
