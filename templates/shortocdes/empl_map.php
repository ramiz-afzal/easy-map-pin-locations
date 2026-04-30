<?php

use EASY_MAP_PIN_LOCATIONS\Base\Functions; ?>

<?php defined('ABSPATH') or die(); ?>
<?php $post_id = isset($atts) && isset($atts['id']) ? $atts['id'] : null; ?>

<?php if (empty($post_id)): ?>
    <p>Post not found</p>
    <?php die(); ?>
<?php endif; ?>

<?php $locations_data = carbon_get_post_meta($post_id, Functions::prefix('locations_data')) ?>

<?php if (empty($locations_data) || !is_array($locations_data)): ?>
    <p>Location data not found</p>
    <?php die(); ?>
<?php endif; ?>

<div class="empl-map-wrapper">
    <div id="empl-map-<?= uniqid(true); ?>" data-locations-data='<?= json_encode($locations_data); ?>'></div>
</div>