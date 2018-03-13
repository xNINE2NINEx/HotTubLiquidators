<?php
if (!defined('ABSPATH')) exit;

if (!current_user_can('manage_options')) {
    die('The account you\'re logged in to doesn\'t have permission to access this page.');
}

function yrw_has_valid_nonce() {
    $nonce_actions = array('yrw_reset', 'yrw_settings', 'yrw_active');
    $nonce_form_prefix = 'yrw-form_nonce_';
    $nonce_action_prefix = 'yrw-wpnonce_';
    foreach ($nonce_actions as $key => $value) {
        if (isset($_POST[$nonce_form_prefix.$value])) {
            check_admin_referer($nonce_action_prefix.$value, $nonce_form_prefix.$value);
            return true;
        }
    }
    return false;
}

function yrw_debug() {
    global $wpdb;
    $businesses = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "yrw_yelp_business");
    $businesses_error = $wpdb->last_error;
    $reviews = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "yrw_yelp_review");
    $reviews_error = $wpdb->last_error; ?>

DB Businesses: <?php echo print_r($businesses); ?>

DB Businesses error: <?php echo $businesses_error; ?>

DB Reviews: <?php echo print_r($reviews); ?>

DB Reviews error: <?php echo $reviews_error;
}

if (!empty($_POST)) {
    $nonce_result_check = yrw_has_valid_nonce();
    if ($nonce_result_check === false) {
        die('Unable to save changes. Make sure you are accessing this page from the Wordpress dashboard.');
    }
}

// Reset
if (isset($_POST['reset'])) {
    foreach (yrw_options() as $opt) {
        delete_option($opt);
    }
    if (isset($_POST['reset_db'])) {
        yrw_reset_db();
    }
    unset($_POST);
    ?>
    <div class="wrap">
        <h3><?php echo yrw_i('Yelp Reviews Widget Reset'); ?></h3>
        <form method="POST" action="?page=yrw">
            <?php wp_nonce_field('yrw-wpnonce_yrw_reset', 'yrw-form_nonce_yrw_reset'); ?>
            <p><?php echo yrw_i('Yelp Reviews Widget has been reset successfully.') ?></p>
            <ul style="list-style: circle;padding-left:20px;">
                <li><?php echo yrw_i('Local settings for the plugin were removed.') ?></li>
            </ul>
            <p>
                <?php echo yrw_i('If you wish to reinstall, you can do that now.') ?>
                <a href="?page=yrw">&nbsp;<?php echo yrw_i('Reinstall') ?></a>
            </p>
        </form>
    </div>
    <?php
    die();
}

// Validate, sanitize, escape

if (isset($_POST['yrw_active']) && isset($_GET['yrw_active'])) {
    update_option('yrw_active', ($_GET['yrw_active'] == '1' ? '1' : '0'));
}

if (isset($_POST['yrw_setting'])) {
    update_option('yrw_api_key', trim(sanitize_text_field($_POST['yrw_api_key'])));
    update_option('yrw_language', trim(sanitize_text_field($_POST['yrw_language'])));
}

if (isset($_POST['yrw_install_db'])) {
    yrw_install_db();
}

wp_enqueue_script('jquery');

wp_register_script('yrw_bootstrap_js', plugins_url('/static/js/bootstrap.min.js', __FILE__));
wp_enqueue_script('yrw_bootstrap_js', plugins_url('/static/js/bootstrap.min.js', __FILE__));
wp_register_style('yrw_bootstrap_css', plugins_url('/static/css/bootstrap.min.css', __FILE__));
wp_enqueue_style('yrw_bootstrap_css', plugins_url('/static/css/bootstrap.min.css', __FILE__));

wp_register_style('yrw_setting_css', plugins_url('/static/css/yrw-setting.css', __FILE__));
wp_enqueue_style('yrw_setting_css', plugins_url('/static/css/yrw-setting.css', __FILE__));

$yrw_enabled = get_option('yrw_active') == '1';
$yrw_api_key = get_option('yrw_api_key');
$yrw_language = get_option('yrw_language');

?>

<span class="version"><?php echo yrw_i('Free Version: %s', esc_html(YRW_VERSION)); ?></span>
<div class="yrw-setting container-fluid">
    <img src="<?php echo YRW_PLUGIN_URL . '/static/img/yelp-logo.png'; ?>" alt="Yelp" style="height:45px">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
            <a href="#about" aria-controls="about" role="tab" data-toggle="tab"><?php echo yrw_i('About'); ?></a>
        </li>
        <li role="presentation">
            <a href="#setting" aria-controls="setting" role="tab" data-toggle="tab"><?php echo yrw_i('Setting'); ?></a>
        </li>
        <li role="presentation">
            <a href="#shortcode" aria-controls="shortcode" role="tab" data-toggle="tab"><?php echo yrw_i('Shortcode'); ?></a>
        </li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="about">
            <div class="row">
                <div class="col-sm-6">
                    <h4><?php echo yrw_i('Yelp Reviews Widget for WordPress'); ?></h4>
                    <p><?php echo yrw_i('Yelp Reviews plugin is an easy and fast way to integrate Yelp business reviews right into your WordPress website. This plugin works instantly and keep all Yelp businesses and reviews in WordPress database thus it has no depend on external services.'); ?></p>
                    <p>To use Yelp Reviews Widget, please do follow:</p>
                    <ol>
                        <li>Go to menu <b>"Appearance"</b> -> <b>"Widgets"</b></li>
                        <li>Move "Yelp Reviews Widget" widget to sidebar</li>
                        <li>Enter 'Search Term' and 'Location' and click 'Search Business'</li>
                        <li>Select your found business in the panel below and click 'Save Business and Reviews'</li>
                        <li>'Business ID' must be filled, if so click 'Save' widget button</li>
                    </ol>
                    <p><?php echo yrw_i('Feel free to contact us by email <a href="mailto:support@richplugins.com">support@richplugins.com</a>.'); ?></p>
                    <p><?php echo yrw_i('<b>Like this plugin? Give it a like on social:</b>'); ?></p>
                    <div class="row">
                        <div class="col-sm-4">
                            <div id="fb-root"></div>
                            <script>(function(d, s, id) {
                              var js, fjs = d.getElementsByTagName(s)[0];
                              if (d.getElementById(id)) return;
                              js = d.createElement(s); js.id = id;
                              js.src = "//connect.facebook.net/en_EN/sdk.js#xfbml=1&version=v2.6&appId=1501100486852897";
                              fjs.parentNode.insertBefore(js, fjs);
                            }(document, 'script', 'facebook-jssdk'));</script>
                            <div class="fb-like" data-href="https://richplugins.com/" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false"></div>
                        </div>
                        <div class="col-sm-4 twitter">
                            <a href="https://twitter.com/richplugins" class="twitter-follow-button" data-show-count="false">Follow @RichPlugins</a>
                            <script>!function (d, s, id) {
                                    var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
                                    if (!d.getElementById(id)) {
                                        js = d.createElement(s);
                                        js.id = id;
                                        js.src = p + '://platform.twitter.com/widgets.js';
                                        fjs.parentNode.insertBefore(js, fjs);
                                    }
                                }(document, 'script', 'twitter-wjs');</script>
                        </div>
                        <div class="col-sm-4 googleplus">
                            <div class="g-plusone" data-size="medium" data-annotation="inline" data-width="200" data-href="https://plus.google.com/101080686931597182099"></div>
                            <script type="text/javascript">
                                window.___gcfg = { lang: 'en-US' };
                                (function () {
                                    var po = document.createElement('script');
                                    po.type = 'text/javascript';
                                    po.async = true;
                                    po.src = 'https://apis.google.com/js/plusone.js';
                                    var s = document.getElementsByTagName('script')[0];
                                    s.parentNode.insertBefore(po, s);
                                })();
                            </script>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <br>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="//www.youtube.com/embed/nVyxAHmYQkU?rel=0" allowfullscreen=""></iframe>
                    </div>
                </div>
            </div>
            <hr>
            <h4>Get More Features with Yelp Reviews Pro!</h4>
            <p><a href="https://richplugins.com/yelp-reviews-pro-wordpress-plugin" target="_blank" style="color:#00bf54;font-size:16px;text-decoration:underline;">Upgrade to Yelp Reviews Pro</a></p>
            <p>* Try to get more then 3 reviews</p>
            <p>* Supports Google Rich Snippets (schema.org)</p>
            <p>* Support shortcode and powerful <b>Shortcode Builder</b></p>
            <p>* Grid theme to show Yelp reviews in testimonials section</p>
            <p>* Trim long reviews and add "read more" link</p>
            <p>* Change business place photo</p>
            <p>* Minimum review rating filter</p>
            <p>* Pagination, Sorting (by default, recent, oldest, highest score, lowest score)</p>
            <p>* Hide/Show business photo, user avatars</p>
            <p>* Priority support</p>
        </div>
        <div role="tabpanel" class="tab-pane" id="setting">
            <h4><?php echo yrw_i('Yelp Reviews Widget Setting'); ?></h4>
            <!-- Configuration form -->
            <form method="POST" enctype="multipart/form-data">
                <?php wp_nonce_field('yrw-wpnonce_yrw_settings', 'yrw-form_nonce_yrw_settings'); ?>
                <div class="form-group">
                    <label class="control-label" for="yrw_api_key"><?php echo yrw_i('API Key'); ?></label>
                    <input class="form-control" type="text" id="yrw_api_key" name="yrw_api_key" value="<?php echo esc_attr($yrw_api_key); ?>">
                    <small><?php echo yrw_i('To fill this field, please go to Yelp developers and '); ?><a href="https://www.yelp.com/developers/v3/manage_app" target="_blank"><?php echo yrw_i('Create New App'); ?></a></small>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo yrw_i('Yelp Reviews API language'); ?></label>
                    <select class="form-control" id="yrw_language" name="yrw_language">
                        <option value="" <?php selected('', $yrw_language); ?>><?php echo yrw_i('Disable'); ?></option>
                        <option value="cs_CZ" <?php selected('cs_CZ', $yrw_language); ?>><?php echo yrw_i('Czech Republic: Czech'); ?></option>
                        <option value="da_DK" <?php selected('da_DK', $yrw_language); ?>><?php echo yrw_i('Denmark: Danish'); ?></option>
                        <option value="de_AT" <?php selected('de_AT', $yrw_language); ?>><?php echo yrw_i('Austria: German'); ?></option>
                        <option value="de_CH" <?php selected('de_CH', $yrw_language); ?>><?php echo yrw_i('Switzerland: German'); ?></option>
                        <option value="de_DE" <?php selected('de_DE', $yrw_language); ?>><?php echo yrw_i('Germany: German'); ?></option>
                        <option value="en_AU" <?php selected('en_AU', $yrw_language); ?>><?php echo yrw_i('Australia: English'); ?></option>
                        <option value="en_BE" <?php selected('en_BE', $yrw_language); ?>><?php echo yrw_i('Belgium: English'); ?></option>
                        <option value="en_CA" <?php selected('en_CA', $yrw_language); ?>><?php echo yrw_i('Canada: English'); ?></option>
                        <option value="en_CH" <?php selected('en_CH', $yrw_language); ?>><?php echo yrw_i('Switzerland: English'); ?></option>
                        <option value="en_GB" <?php selected('en_GB', $yrw_language); ?>><?php echo yrw_i('United Kingdom: English'); ?></option>
                        <option value="en_HK" <?php selected('en_HK', $yrw_language); ?>><?php echo yrw_i('Hong Kong: English'); ?></option>
                        <option value="en_IE" <?php selected('en_IE', $yrw_language); ?>><?php echo yrw_i('Republic of Ireland: English'); ?></option>
                        <option value="en_MY" <?php selected('en_MY', $yrw_language); ?>><?php echo yrw_i('Malaysia: English'); ?></option>
                        <option value="en_NZ" <?php selected('en_NZ', $yrw_language); ?>><?php echo yrw_i('New Zealand: English'); ?></option>
                        <option value="en_PH" <?php selected('en_PH', $yrw_language); ?>><?php echo yrw_i('Philippines: English'); ?></option>
                        <option value="en_SG" <?php selected('en_SG', $yrw_language); ?>><?php echo yrw_i('Singapore: English'); ?></option>
                        <option value="en_US" <?php selected('en_US', $yrw_language); ?>><?php echo yrw_i('United States: English'); ?></option>
                        <option value="es_AR" <?php selected('es_AR', $yrw_language); ?>><?php echo yrw_i('Argentina: Spanish'); ?></option>
                        <option value="es_CL" <?php selected('es_CL', $yrw_language); ?>><?php echo yrw_i('Chile: Spanish'); ?></option>
                        <option value="es_ES" <?php selected('es_ES', $yrw_language); ?>><?php echo yrw_i('Spain: Spanish'); ?></option>
                        <option value="es_MX" <?php selected('es_MX', $yrw_language); ?>><?php echo yrw_i('Mexico: Spanish'); ?></option>
                        <option value="fi_FI" <?php selected('fi_FI', $yrw_language); ?>><?php echo yrw_i('Finland: Finnish'); ?></option>
                        <option value="fil_PH" <?php selected('fil_PH', $yrw_language); ?>><?php echo yrw_i('Philippines: Filipino'); ?></option>
                        <option value="fr_BE" <?php selected('fr_BE', $yrw_language); ?>><?php echo yrw_i('Belgium: French'); ?></option>
                        <option value="fr_CA" <?php selected('fr_CA', $yrw_language); ?>><?php echo yrw_i('Canada: French'); ?></option>
                        <option value="fr_CH" <?php selected('fr_CH', $yrw_language); ?>><?php echo yrw_i('Switzerland: French'); ?></option>
                        <option value="fr_FR" <?php selected('fr_FR', $yrw_language); ?>><?php echo yrw_i('France: French'); ?></option>
                        <option value="it_CH" <?php selected('it_CH', $yrw_language); ?>><?php echo yrw_i('Switzerland: Italian'); ?></option>
                        <option value="it_IT" <?php selected('it_IT', $yrw_language); ?>><?php echo yrw_i('Italy: Italian'); ?></option>
                        <option value="ja_JP" <?php selected('ja_JP', $yrw_language); ?>><?php echo yrw_i('Japan: Japanese'); ?></option>
                        <option value="ms_MY" <?php selected('ms_MY', $yrw_language); ?>><?php echo yrw_i('Malaysia: Malay'); ?></option>
                        <option value="nb_NO" <?php selected('nb_NO', $yrw_language); ?>><?php echo yrw_i('Norway: Norwegian'); ?></option>
                        <option value="nl_BE" <?php selected('nl_BE', $yrw_language); ?>><?php echo yrw_i('Belgium: Dutch'); ?></option>
                        <option value="nl_NL" <?php selected('nl_NL', $yrw_language); ?>><?php echo yrw_i('The Netherlands: Dutch'); ?></option>
                        <option value="pl_PL" <?php selected('pl_PL', $yrw_language); ?>><?php echo yrw_i('Poland: Polish'); ?></option>
                        <option value="pt_BR" <?php selected('pt_BR', $yrw_language); ?>><?php echo yrw_i('Brazil: Portuguese'); ?></option>
                        <option value="pt_PT" <?php selected('pt_PT', $yrw_language); ?>><?php echo yrw_i('Portugal: Portuguese'); ?></option>
                        <option value="sv_FI" <?php selected('sv_FI', $yrw_language); ?>><?php echo yrw_i('Finland: Swedish'); ?></option>
                        <option value="sv_SE" <?php selected('sv_SE', $yrw_language); ?>><?php echo yrw_i('Sweden: Swedish'); ?></option>
                        <option value="tr_TR" <?php selected('tr_TR', $yrw_language); ?>><?php echo yrw_i('Turkey: Turkish'); ?></option>
                        <option value="zh_HK" <?php selected('zh_HK', $yrw_language); ?>><?php echo yrw_i('Hong Kong: Chinese'); ?></option>
                        <option value="zh_TW" <?php selected('zh_TW', $yrw_language); ?>><?php echo yrw_i('Taiwan: Chinese'); ?></option>
                    </select>
                </div>
                <div class="form-group">
                    <input class="form-control" type="checkbox" id="yrw_install_db" name="yrw_install_db" >
                    <label class="control-label" for="yrw_install_db"><?php echo yrw_i('Re-create the DB tables for the plugin (service option)'); ?></label>
                </div>
                <p class="submit" style="text-align: left">
                    <input name="yrw_setting" type="submit" value="Save" class="button-primary button" tabindex="4">
                </p>
            </form>
            <hr>
            <!-- Enable/disable Yelp Reviews Widget toggle -->
            <form method="POST" action="?page=yrw&amp;yrw_active=<?php echo (string)((int)($yrw_enabled != true)); ?>">
                <?php wp_nonce_field('yrw-wpnonce_yrw_active', 'yrw-form_nonce_yrw_active'); ?>
                <span class="status">
                    <?php echo yrw_i('Yelp Reviews Widget are currently <b>'). ($yrw_enabled ? yrw_i('enable') : yrw_i('disable')) . '</b>'; ?>
                </span>
                <input type="submit" name="yrw_active" class="button" value="<?php echo $yrw_enabled ? yrw_i('Disable') : yrw_i('Enable'); ?>" />
            </form>
            <hr>
            <!-- Debug information -->
            <button class="btn btn-primary btn-small" type="button" data-toggle="collapse" data-target="#debug" aria-expanded="false" aria-controls="debug">
                <?php echo yrw_i('Debug Information'); ?>
            </button>
            <div id="debug" class="collapse">
                <textarea style="width:90%; height:200px;" onclick="this.select();return false;" readonly><?php
                    rplg_debug(YRW_VERSION, yrw_options(), 'widget_yrw_widget'); yrw_debug(); ?>
                </textarea>
            </div>
            <div style="max-width:700px"><?php echo yrw_i('Feel free to contact support team by support@richplugins.com for any issues but please don\'t forget to provide debug information that you can get by click on \'Debug Information\' button.'); ?></div>
            <hr>
            <!-- Reset form -->
            <form action="?page=yrw" method="POST">
                <?php wp_nonce_field('yrw-wpnonce_yrw_reset', 'yrw-form_nonce_yrw_reset'); ?>
                <p>
                    <input type="submit" value="Reset" name="reset" onclick="return confirm('<?php echo yrw_i('Are you sure you want to reset the Yelp Reviews Widget plugin?'); ?>')" class="button" />
                    <?php echo yrw_i('This removes all plugin-specific settings.') ?>
                </p>
                <p>
                    <input type="checkbox" id="reset_db" name="reset_db">
                    <label for="reset_db"><?php echo yrw_i('Remove all data including Yelp Reviews'); ?></label>
                </p>
            </form>
        </div>
        <div role="tabpanel" class="tab-pane" id="shortcode">
            <h4><?php echo yrw_i('Shortcode Builder available in Yelp Reviews Pro plugin:'); ?></h4>
            <a href="https://richplugins.com/yelp-reviews-pro-wordpress-plugin" target="_blank" style="color:#00bf54;font-size:16px;text-decoration:underline;"><?php echo yrw_i('Upgrade to Pro'); ?></a>
        </div>
    </div>
</div>
