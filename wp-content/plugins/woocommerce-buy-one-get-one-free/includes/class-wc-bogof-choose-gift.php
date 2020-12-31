<?php
/**
 * Buy One Get One Free Choose your gift. Handles choose your gift actions.
 *
 * @package WC_BOGOF
 */

defined( 'ABSPATH' ) || exit;

/**
 * WC_BOGOF_Choose_Gift Class
 */
class WC_BOGOF_Choose_Gift {

	/**
	 * Flag to handle is "choose your gift page".
	 *
	 * @var string
	 */
	private static $is_choose_your_gift = false;

	/**
	 * Cart hash.
	 *
	 * @var string
	 */
	private static $bogof_cart_hash = false;

	/**
	 * Init hooks
	 */
	public static function init() {
		add_action( 'wc_bogof_cart_rules_loaded', array( __CLASS__, 'choose_your_gift_page' ) );
		add_action( 'woocommerce_shortcode_before_product_cat_loop', array( __CLASS__, 'choose_your_gift_notice' ), 10 );
		add_action( 'woocommerce_before_shop_loop', array( __CLASS__, 'choose_your_gift_notice' ), 10 );
		add_action( 'woocommerce_before_single_product', array( __CLASS__, 'choose_your_gift_notice' ), 15 );
		add_action( 'woocommerce_before_cart', array( __CLASS__, 'choose_your_gift_notice' ), 15 );
		add_action( 'woocommerce_before_checkout_form', array( __CLASS__, 'choose_your_gift_notice' ), 15 );
		add_filter( 'pre_option_woocommerce_cart_redirect_after_add', array( __CLASS__, 'cart_redirect_after_add' ) );
		add_shortcode( 'wc_choose_your_gift', array( __CLASS__, 'choose_your_gift' ) );
	}

	/**
	 * Init the "choose your gift" page
	 */
	public static function choose_your_gift_page() {
		self::$bogof_cart_hash = false;
		$cart_hash             = self::get_hash_from_request();
		if ( $cart_hash && WC_BOGOF_Cart::get_hash() === $cart_hash ) {
			self::$bogof_cart_hash = $cart_hash;
			self::init_hooks();
		}
	}

	/**
	 * Get the cart hash from query string.
	 *
	 * @since 2.0.5
	 * @return string
	 */
	private static function get_hash_from_request() {
		$cart_hash = isset( $_REQUEST['wc_bogo_refer'] ) ? wc_clean( $_REQUEST['wc_bogo_refer'] ) : false; // phpcs:ignore WordPress.Security.NonceVerification
		if ( ! $cart_hash && defined( 'WC_DOING_AJAX' ) && WC_DOING_AJAX ) {
			$query = wp_parse_url( wp_get_referer(), PHP_URL_QUERY );
			wp_parse_str( $query, $params );

			$cart_hash = isset( $params['wc_bogo_refer'] ) ? $params['wc_bogo_refer'] : false;
		}
		return $cart_hash;
	}

	/**
	 * Adds the choose your gift page hooks.
	 */
	private static function init_hooks() {
		add_filter( 'woocommerce_add_to_cart_form_action', array( __CLASS__, 'add_to_cart_form_action' ) );
		add_filter( 'woocommerce_quantity_input_max', array( __CLASS__, 'quantity_input_max' ), 10, 2 );
	}

	/**
	 * Is choose your gift page?
	 */
	public static function is_choose_your_gift() {
		return ! empty( self::$bogof_cart_hash ) || self::$is_choose_your_gift;
	}

	/**
	 * Add the bogof parameter to the URL
	 *
	 * @param string $form_action Form action link.
	 */
	public static function add_to_cart_form_action( $form_action ) {
		global $product;
		$product_id = is_callable( array( $product, 'get_id' ) ) ? $product->get_id() : false;
		if ( $product_id && WC_BOGOF_Cart::get_product_shop_free_quantity( $product_id ) > 0 ) {
			$form_action = add_query_arg( 'wc_bogo_refer', WC_BOGOF_Cart::get_hash(), $form_action );
		}
		return $form_action;
	}

	/**
	 * Set the max purchase qty.
	 *
	 * @param int        $max_quantity Max purchase qty.
	 * @param WC_Product $product Product object.
	 * @return int
	 */
	public static function quantity_input_max( $max_quantity, $product ) {
		$max_free_qty = WC_BOGOF_Cart::get_product_shop_free_quantity( $product->get_id() );
		if ( $max_free_qty > 0 && $max_free_qty > $max_quantity ) {
			$max_quantity = $max_free_qty;
		}
		return $max_free_qty;
	}

	/**
	 * Redirects to the cart when there are no more free items.
	 *
	 * @param string $value Option value.
	 */
	public static function cart_redirect_after_add( $value ) {
		if ( isset( $_REQUEST['wc_bogof_cart_rule'] ) && WC_BOGOF_Cart::get_shop_free_quantity() <= 0 ) { // phpcs:ignore WordPress.Security.NonceVerification
			$value = 'yes';
			add_filter( 'woocommerce_continue_shopping_redirect', array( __CLASS__, 'continue_shopping_redirect' ) );
		}
		return $value;
	}

	/**
	 * Return the shop page after add to cart from the choose your gift page.
	 *
	 * @param string $return_to Return URL.
	 * @return string
	 */
	public static function continue_shopping_redirect( $return_to ) {
		return wc_get_page_permalink( 'shop' );
	}

	/**
	 * Sortcode callback. Lists free available products.
	 *
	 * @param array $atts Attributes.
	 * @return string
	 */
	public static function choose_your_gift( $atts ) {
		if ( is_admin() ) {
			return;
		}

		self::$is_choose_your_gift = true;

		$shortcode = new WC_BOGOF_Choose_Gift_Shortcode( $atts );
		$content   = $shortcode->get_content();

		self::$is_choose_your_gift = false;

		return $content;
	}

	/**
	 * Add a WooCommerce notice if there are avilable gifts.
	 */
	public static function choose_your_gift_notice() {
		$qty = WC_BOGOF_Cart::get_shop_free_quantity();
		if ( $qty <= 0 ) {
			return;
		}

		$page_id     = get_option( 'wc_bogof_cyg_page_id', 0 );
		$page_link   = get_permalink( $page_id );
		$text        = get_option( 'wc_bogof_cyg_notice', false );
		$button_text = get_option( 'wc_bogof_cyg_notice_button_text', false );

		if ( ! $page_link ) {
			$logger = wc_get_logger();
			$logger->error( 'The "choose your gift" page does not exist.', array( 'source' => 'woocommerce-buy-one-get-one-free' ) );
		}
		// translators: 1 free products qty, 2,3: html tags.
		$text        = empty( $text ) ? sprintf( _n( 'You can now add %1$s product for free to the cart.', 'You can now add %1$s products for free to the cart.', $qty, 'wc-buy-one-get-one-free' ), $qty ) : str_replace( '[qty]', $qty, $text );
		$button_text = empty( $button_text ) ? esc_html__( 'Choose your gift', 'wc-buy-one-get-one-free' ) : $button_text;

		$url     = add_query_arg( 'wc_bogo_refer', WC_BOGOF_Cart::get_hash(), $page_link );
		$message = sprintf( ' %s <a href="%s" tabindex="1" class="button">%s</a>', esc_html( $text ), esc_url( $url ), $button_text );

		echo '<div class="woocommerce-notices-wrapper woocommerce-choose-your-gift-notice-wrapper">';
		wc_print_notice( $message, 'success' );

		if ( current_user_can( 'manage_woocommerce' ) && ! wc_bogof_has_choose_your_gift_shortcode( $page_id ) ) {
			// translators: HTML tags.
			wc_print_notice( sprintf( __( 'The "choose your gift" page has not set! Customers will not be able to add to the cart the free product. Go to the %1$ssettings page%2$s and set a page that contains the [wc_choose_your_gift] shortcode. ', 'wc-buy-one-get-one-free' ), '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=products&section=buy-one-get-one-free' ) . '">', '</a>' ), 'error' );
		}
		echo '</div>';
	}
}
