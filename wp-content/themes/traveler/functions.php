<?php

/**
 * @package    WordPress
 * @subpackage Traveler
 * @since      1.0
 *
 * function
 *
 * Created by ShineTheme
 *
 */
if (!defined('ST_TEXTDOMAIN'))
    define('ST_TEXTDOMAIN', 'traveler');
if (!defined('ST_TRAVELER_VERSION')) {
    $theme = wp_get_theme();
    if ($theme->parent()) {
        $theme = $theme->parent();
    }
    define('ST_TRAVELER_VERSION', $theme->get('Version'));
}
define("ST_TRAVELER_DIR", get_template_directory());
define("ST_TRAVELER_URI", get_template_directory_uri());

global $st_check_session;

if (session_status() == PHP_SESSION_NONE) {
    $st_check_session = true;
    session_start();
}

$status = load_theme_textdomain(ST_TEXTDOMAIN, get_stylesheet_directory() . '/language');

get_template_part('inc/class.traveler');
get_template_part('inc/extensions/st-vina-install-extension');

if (!class_exists("Abraham\TwitterOAuth\TwitterOAuth")) {
    include_once "vendor/autoload.php";
}
add_filter('http_request_args', 'st_check_request_api', 10, 2);

function st_check_request_api($parse, $url) {
    global $st_check_session;
    if ($st_check_session) {
        session_write_close();
    }

    return $parse;
}

add_filter('upload_mimes', 'traveler_upload_types', 1, 1);

function traveler_upload_types($mime_types) {
    $mime_types['svg'] = 'image/svg+xml';

    return $mime_types;
}

add_theme_support(
    'html5', array(
    'search-form',
    'comment-form',
    'comment-list',
    'gallery',
    'caption',
        )
);
//get_template_part('demo/landing_function');
//get_template_part('demo/demo_functions');
//get_template_part('quickview_demo/functions');
//get_template_part('user_demo/functions');

/**
 * @snippet       Buy 1 Get 1 - WooCommerce
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    Woo 3.8
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */
 
// add_action( 'template_redirect', 'bbloomer_add_gift_if_id_in_cart' );
 
function bbloomer_add_gift_if_id_in_cart() {
 
   if ( is_admin() ) return;
   if ( WC()->cart->is_empty() ) return;
 
   $product_bought_id = 9142;
   $product_gifted_id = 9142;
 
   // see if product id in cart
   $product_bought_cart_id = WC()->cart->generate_cart_id( $product_bought_id );
   $product_bought_in_cart = WC()->cart->find_product_in_cart( $product_bought_cart_id );
 
   // see if gift id in cart
   $product_gifted_cart_id = WC()->cart->generate_cart_id( $product_gifted_id );
   $product_gifted_in_cart = WC()->cart->find_product_in_cart( $product_gifted_cart_id );
 
   // if not in cart remove gift, else add gift
   if ( ! $product_bought_in_cart ) {
      if ( $product_gifted_in_cart ) WC()->cart->remove_cart_item( $product_gifted_in_cart );
   } else {
      if ( ! $product_gifted_in_cart ) WC()->cart->add_to_cart( $product_gifted_id );
   }
}