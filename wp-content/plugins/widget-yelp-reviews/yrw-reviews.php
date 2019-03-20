<?php
if (!defined('ABSPATH')) exit;

wp_register_script('rplg_js', plugins_url('/static/js/rplg.js', __FILE__));
wp_enqueue_script('rplg_js', plugins_url('/static/js/rplg.js', __FILE__));

include_once(dirname(__FILE__) . '/yrw-reviews-helper.php');

$business = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "yrw_yelp_business WHERE business_id = %s", $business_id));
if (!$business) {
    ?>
    <div class="yrw-error" style="padding:10px;color:#B94A48;background-color:#F2DEDE;border-color:#EED3D7;">
        <?php echo yrw_i('Business not found by BusinessID: ') . $business_id; ?>
    </div>
    <?php
    return;
}

$reviews = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "yrw_yelp_review WHERE yelp_business_id = %d ORDER BY time DESC", $business->id));

$rating = number_format((float)$business->rating, 1, '.', '');

if (is_numeric($max_width)) {
    $max_width = $max_width . 'px';
}
if (is_numeric($max_height)) {
    $max_height = $max_height . 'px';
}
?>

<div class="wp-yrw wpac" style="<?php if (isset($max_width) && strlen($max_width) > 0) { ?>width:<?php echo $max_width;?>!important;<?php } ?><?php if (isset($max_height) && strlen($max_height) > 0) { ?>height:<?php echo $max_height;?>!important;overflow-y:auto!important;<?php } ?><?php if ($centered) { ?>margin:0 auto!important;<?php } ?>">
    <div class="wp-yelp-list<?php if ($dark_theme) { ?> wp-dark<?php } ?>">
        <div class="wp-yelp-place">
            <?php yrw_page($business, $rating, $open_link, $nofollow_link); ?>
        </div>
        <div class="wp-yelp-content-inner">
            <?php yrw_page_reviews($reviews, $text_size, $pagination, $read_on_yelp, $open_link, $nofollow_link); ?>
        </div>
    </div>
</div>