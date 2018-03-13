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

$reviews = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "yrw_yelp_review WHERE yelp_business_id = %d", $business->id));

$rating = number_format((float)$business->rating, 1, '.', '');
?>

<div class="wp-yrw wpac">
    <div class="wp-yelp-list<?php if ($dark_theme) { ?> wp-dark<?php } ?>">
        <div class="wp-yelp-place">
            <?php yrw_page($business, $rating, $open_link, $nofollow_link); ?>
        </div>
        <div class="wp-yelp-content-inner">
            <?php yrw_page_reviews($reviews, $open_link, $nofollow_link); ?>
        </div>
    </div>
</div>