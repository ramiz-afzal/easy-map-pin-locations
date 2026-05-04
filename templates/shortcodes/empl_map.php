<?php

use EASY_MAP_PIN_LOCATIONS\Base\Functions;
use function Symfony\Component\String\u; ?>

<?php $post_id = isset($atts) && isset($atts['id']) ? $atts['id'] : null; ?>

<?php if (empty($post_id)): ?>
    <p>Post not found</p>
    <?php die(); ?>
<?php endif; ?>

<?php $attachment_id = carbon_get_post_meta($post_id, Functions::prefix('locations_data')) ?>

<?php if (empty($attachment_id)): ?>
    <p>Location data not found</p>
    <?php die(); ?>
<?php endif; ?>

<?php

// enqueue scripts
wp_enqueue_style(Functions::with_uuid('leaflet-styles'));
wp_enqueue_script(Functions::with_uuid('leaflet-scripts'));

$file_path      = get_attached_file($attachment_id);
$rows           = file_exists($file_path) ? array_map('str_getcsv', file($file_path)) : [];
$header         = array_shift($rows);
$locations_data = array();

foreach ($header as $key => $label) {
    $key = u($label)->snake()->toString();
    $header[$key] = array(
        'key'   => $key,
        'label' => $label,
    );
}

foreach ($rows as $row) {
    $item       = array_combine(array_column($header, 'key'), $row);
    $title      = isset($item['title']) ? $item['title'] : '';
    $image      = isset($item['image']) ? $item['image'] : '';
    $url        = isset($item['url']) ? $item['url'] : '';
    $latitude   = isset($item['latitude']) ? $item['latitude'] : null;
    $longitude  = isset($item['longitude']) ? $item['longitude'] : null;

    if (empty($latitude) || empty($longitude)) {
        continue;
    }

    unset($item['title'], $item['image'], $item['url'], $item['latitude'], $item['longitude']);

    $props = array();
    foreach ($item as $key => $value) {
        $props[] = array(
            'key'   => $key,
            'label' =>  $header[$key]['label'],
            'value' => $value,
        );
    }

    $locations_data[] = array(
        'title'         => $title,
        'image'         => $image,
        'url'           => $image,
        'latitude'      => $latitude,
        'longitude'     => $longitude,
        'props'         => $props
    );
}
?>

<div class="empl-map-wrapper">
    <div class="empl-map" id="empl-map-<?= uniqid(true); ?>" style="height: 500px;" data-locations-data='<?= json_encode($locations_data); ?>'></div>
</div>