<?php
/*
Plugin Name: Yelp Reviews Widget
Plugin URI: https://richplugins.com
Description: Instantly Yelp rating and reviews on your website to increase user confidence and SEO.
Author: RichPlugins <support@richplugins.com>
Version: 1.5
Author URI: https://richplugins.com
*/

if (!defined('ABSPATH')) exit;

require(ABSPATH . 'wp-includes/version.php');

include_once(dirname(__FILE__) . '/api/urlopen.php');
include_once(dirname(__FILE__) . '/helper/debug.php');

define('YRW_VERSION',             '1.5');
define('YRW_API',                 'https://api.yelp.com/v3/businesses');
define('YRW_PLUGIN_URL',          plugins_url(basename(plugin_dir_path(__FILE__ )), basename(__FILE__)));
define('YRW_AVATAR',              YRW_PLUGIN_URL . '/static/img/yelp-avatar.png');

function yrw_options() {
    return array(
        'yrw_version',
        'yrw_active',
        'yrw_api_key',
    );
}

/*-------------------------------- Widget --------------------------------*/
function yrw_init_widget() {
    if (!class_exists('Yelp_Reviews_Widget' ) ) {
        require 'yrw-widget.php';
    }
}

add_action('widgets_init', 'yrw_init_widget');
add_action('widgets_init', create_function('', 'register_widget("Yelp_Reviews_Widget");'));

/*-------------------------------- Menu --------------------------------*/
function yrw_setting_menu() {
     add_submenu_page(
         'options-general.php',
         'Yelp Reviews Widget',
         'Yelp Reviews Widget',
         'moderate_comments',
         'yrw',
         'yrw_setting'
     );
}
add_action('admin_menu', 'yrw_setting_menu', 10);

function yrw_setting() {
    include_once(dirname(__FILE__) . '/yrw-setting.php');
}

/*-------------------------------- Links --------------------------------*/
function yrw_plugin_action_links($links, $file) {
    $plugin_file = basename(__FILE__);
    if (basename($file) == $plugin_file) {
        $settings_link = '<a href="' . admin_url('options-general.php?page=yrw') . '">'.yrw_i('Settings') . '</a>';
        array_unshift($links, $settings_link);
    }
    return $links;
}
add_filter('plugin_action_links', 'yrw_plugin_action_links', 10, 2);

/*-------------------------------- Row Meta --------------------------------*/
function yrw_plugin_row_meta($input, $file) {
    if ($file != plugin_basename( __FILE__ )) {
        return $input;
    }

    $links = array(
        //'<a href="' . esc_url('https://richplugins.com') . '" target="_blank">' . yrw_i('View Documentation') . '</a>',
        '<a href="' . esc_url('https://richplugins.com/yelp-reviews-pro-wordpress-plugin') . '" target="_blank">' . yrw_i('Upgrade to Pro') . ' &raquo;</a>',
    );
    $input = array_merge($input, $links);
    return $input;
}
add_filter('plugin_row_meta', 'yrw_plugin_row_meta', 10, 2);

/*-------------------------------- Database --------------------------------*/
function yrw_activation() {
    if (yrw_does_need_update()) {
        yrw_install();
    }
}
register_activation_hook(__FILE__, 'yrw_activation');

function yrw_install($allow_db_install=true) {

    $version = (string)get_option('yrw_version');
    if (!$version) {
        $version = '0';
    }

    if ($allow_db_install) {
        yrw_install_db($version);
    }

    if (version_compare($version, YRW_VERSION, '=')) {
        return;
    }

    add_option('yrw_active', '1');
    update_option('yrw_version', YRW_VERSION);
}

function yrw_install_db() {
    global $wpdb;

    $wpdb->query("CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "yrw_yelp_business (".
                 "id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,".
                 "business_id VARCHAR(100) NOT NULL,".
                 "name VARCHAR(255) NOT NULL,".
                 "photo VARCHAR(255),".
                 "address VARCHAR(255),".
                 "rating DOUBLE PRECISION,".
                 "url VARCHAR(255),".
                 "website VARCHAR(255),".
                 "review_count INTEGER NOT NULL,".
                 "PRIMARY KEY (`id`),".
                 "UNIQUE INDEX yrw_business_id (`business_id`)".
                 ");");

    $wpdb->query("CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "yrw_yelp_review (".
                 "id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,".
                 "yelp_business_id BIGINT(20) UNSIGNED NOT NULL,".
                 "hash VARCHAR(40) NOT NULL,".
                 "rating INTEGER NOT NULL,".
                 "text VARCHAR(10000),".
                 "url VARCHAR(255),".
                 "time VARCHAR(20) NOT NULL,".
                 "author_name VARCHAR(255),".
                 "author_img VARCHAR(255),".
                 "PRIMARY KEY (`id`),".
                 "UNIQUE INDEX yrw_yelp_review_hash (`hash`),".
                 "INDEX yrw_yelp_business_id (`yelp_business_id`)".
                 ");");
}

function yrw_reset_db() {
    global $wpdb;

    $wpdb->query("DROP TABLE " . $wpdb->prefix . "yrw_yelp_business;");
    $wpdb->query("DROP TABLE " . $wpdb->prefix . "yrw_yelp_review;");
}

/*-------------------------------- Request --------------------------------*/
function yrw_request_handler() {
    global $wpdb;

    if (!empty($_GET['cf_action'])) {

        switch ($_GET['cf_action']) {
            case 'yrw_api_key':
                if (current_user_can('manage_options')) {
                    if (isset($_POST['yrw_wpnonce']) === false) {
                        $error = yrw_i('Unable to call request. Make sure you are accessing this page from the Wordpress dashboard.');
                        $response = compact('error');
                    } else {
                        check_admin_referer('yrw_wpnonce', 'yrw_wpnonce');

                        // Validate, sanitize, escape
                        update_option('yrw_api_key', trim(sanitize_text_field($_POST['app_key'])));
                        $status = 'success';

                        $response = compact('status');
                    }
                    header('Content-type: text/javascript');
                    echo json_encode($response);
                    die();
                }
            break;
            case 'yrw_search':
                if (current_user_can('manage_options')) {
                    if (isset($_GET['yrw_wpnonce']) === false) {
                        $error = yrw_i('Unable to call request. Make sure you are accessing this page from the Wordpress dashboard.');
                        $response = compact('error');
                    } else {
                        check_admin_referer('yrw_wpnonce', 'yrw_wpnonce');
                        $term = trim(sanitize_text_field($_GET['term']));
                        $location = trim(sanitize_text_field($_GET['location']));
                        $api_url = YRW_API . '/search?term=' . $term . '&location=' . $location;
                        $api_key = get_option('yrw_api_key');
                        $response = rplg_json_urlopen($api_url, null, array(
                            'Authorization: Bearer ' . $api_key
                        ));
                    }
                    header('Content-type: text/javascript');
                    echo json_encode($response);
                    die();
                }
            break;
            case 'yrw_reviews':
                if (current_user_can('manage_options')) {
                    if (isset($_GET['yrw_wpnonce']) === false) {
                        $error = yrw_i('Unable to call request. Make sure you are accessing this page from the Wordpress dashboard.');
                        $response = compact('error');
                    } else {
                        check_admin_referer('yrw_wpnonce', 'yrw_wpnonce');
                        $business_id = trim(sanitize_text_field($_GET['business_id']));
                        $api_key = get_option('yrw_api_key');
                        $response = rplg_json_urlopen(yrw_api_url($business_id), null, array(
                            'Authorization: Bearer ' . $api_key
                        ));
                    }
                    header('Content-type: text/javascript');
                    echo json_encode($response);
                    die();
                }
            break;
            case 'yrw_save':
                if (current_user_can('manage_options')) {
                    if (isset($_POST['yrw_wpnonce']) === false) {
                        $error = yrw_i('Unable to call request. Make sure you are accessing this page from the Wordpress dashboard.');
                        $response = compact('error');
                    } else {
                        check_admin_referer('yrw_wpnonce', 'yrw_wpnonce');
                        $api_key = get_option('yrw_api_key');

                        // Validate, sanitize, escape
                        $business_id = trim(sanitize_text_field($_POST['business_id']));

                        $business = rplg_json_urlopen(YRW_API . '/' . $business_id, null, array(
                            'Authorization: Bearer ' . $api_key
                        ));
                        $reviews = rplg_json_urlopen(yrw_api_url($business_id), null, array(
                            'Authorization: Bearer ' . $api_key
                        ));
                        yrw_save_reviews($business, $reviews);
                        $response = 'success';

                    }
                    header('Content-type: text/javascript');
                    echo json_encode($response);
                    die();
                }
            break;
        }
    }
}
add_action('init', 'yrw_request_handler');

function yrw_save_reviews($business, $reviews) {
    global $wpdb;

    $yelp_business_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM " . $wpdb->prefix . "yrw_yelp_business WHERE business_id = %s", $business->id));
    if ($yelp_business_id) {
        $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "yrw_yelp_business SET rating = %s, review_count = %s WHERE ID = %s", $business->rating, $business->review_count, $yelp_business_id));
    } else {
        $address = implode(", ", array($business->location->address1, $business->location->city, $business->location->state, $business->location->zip_code));
        $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "yrw_yelp_business (business_id, name, photo, address, rating, url, review_count) VALUES (%s, %s, %s, %s, %s, %s, %s)", $business->id, $business->name, $business->image_url, $address, $business->rating, $business->url, $business->review_count));
        $yelp_business_id = $wpdb->insert_id;
    }

    if ($reviews && $reviews->reviews) {
        foreach ($reviews->reviews as $review) {
            $hash = sha1($business->id . $review->time_created);
            $yelp_review_hash = $wpdb->get_var($wpdb->prepare("SELECT hash FROM " . $wpdb->prefix . "yrw_yelp_review WHERE hash = %s", $hash));
            if (!$yelp_review_hash) {
                $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "yrw_yelp_review (yelp_business_id, hash, rating, text, url, time, author_name, author_img) VALUES (%d, %s, %s, %s, %s, %s, %s, %s)", $yelp_business_id, $hash, $review->rating, $review->text, $review->url, $review->time_created, $review->user->name, $review->user->image_url));
            }
        }
    }
}

function yrw_lang_init() {
    $plugin_dir = basename(dirname(__FILE__));
    load_plugin_textdomain('yrw', false, basename( dirname( __FILE__ ) ) . '/languages');
}
add_action('plugins_loaded', 'yrw_lang_init');

/*-------------------------------- Helpers --------------------------------*/
function yrw_enabled() {
    global $id, $post;

    $active = get_option('yrw_active');
    if (empty($active) || $active === '0') { return false; }
    return true;
}

function yrw_api_url($business_id, $reviews_lang = '') {
    $url = YRW_API . '/' . $business_id . '/reviews';

    $yrw_language = strlen($reviews_lang) > 0 ? $reviews_lang : get_option('yrw_language');
    if (strlen($yrw_language) > 0) {
        $url = $url . '?locale=' . $yrw_language;
    }
    return $url;
}


function yrw_does_need_update() {
    $version = (string)get_option('yrw_version');
    if (empty($version)) {
        $version = '0';
    }
    if (version_compare($version, '1.0', '<')) {
        return true;
    }
    return false;
}

function yrw_i($text, $params=null) {
    if (!is_array($params)) {
        $params = func_get_args();
        $params = array_slice($params, 1);
    }
    return vsprintf(__($text, 'yrw'), $params);
}

?>