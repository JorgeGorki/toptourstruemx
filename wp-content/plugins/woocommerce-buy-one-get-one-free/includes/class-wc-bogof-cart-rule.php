<?php
/**
 * Buy One Get One Free Cart Rule. Handles BOGO rule actions.
 *
 * @package WC_BOGOF
 */

defined( 'ABSPATH' ) || exit;

/**
 * WC_BOGOF_Cart_Rule Class
 */
class WC_BOGOF_Cart_Rule {

	/**
	 * BOGOF rule.
	 *
	 * @var WC_BOGOF_Rule
	 */
	protected $rule;

	/**
	 * Array of cart totals.
	 *
	 * @var array
	 */
	protected $totals;

	/**
	 * Array of notices.
	 *
	 * @var array
	 */
	protected $notices;

	/**
	 * Constructor.
	 *
	 * @param WC_BOGOF_Rule $rule BOGOF rule.
	 */
	public function __construct( $rule ) {
		$this->rule    = $rule;
		$this->totals  = array();
		$this->notices = array();
	}

	/**
	 * Return the cart rule ID.
	 */
	public function get_id() {
		return $this->rule->get_id();
	}

	/**
	 * Return the rule ID.
	 */
	final public function get_rule_id() {
		return $this->rule->get_id();
	}

	/**
	 * Return the rule ID.
	 */
	final public function get_rule() {
		return $this->rule;
	}

	/**
	 * Unset the totals array.
	 */
	protected function clear_totals() {
		$this->totals = array();
	}

	/**
	 * Does the cart item match with the rule?
	 *
	 * @param array $cart_item Cart item.
	 * @return bool
	 */
	protected function cart_item_match( $cart_item ) {
		if ( ! isset( $cart_item['data'] ) || ! is_callable( array( $cart_item['data'], 'get_id' ) ) || wc_bogof_cart_item_match_skip( $this, $cart_item ) ) {
			return false;
		}
		$match      = false;
		$product_id = $cart_item['data']->get_id();

		return $this->rule->is_buy_product( $product_id ) && ! $this->rule->is_exclude_product( $product_id );
	}

	/**
	 * Add the free product to the cart.
	 *
	 * @param int $qty The quantity of the item to add.
	 */
	protected function add_to_cart( $qty = 1 ) {
		$items = WC_BOGOF_Cart::get_free_items( $this->get_id() );

		if ( count( $items ) ) {
			// Set the qty.
			$cart_item_keys = array_keys( $items );
			$cart_item_key  = $cart_item_keys[0];

			$cart_item = WC()->cart->get_cart_item( $cart_item_key );
			if ( ! empty( $cart_item ) && isset( $cart_item['product_id'] ) && isset( $cart_item['quantity'] ) ) {
				$qty_added = $qty - $cart_item['quantity'];

				WC()->cart->set_quantity( $cart_item_key, $qty, false );

				$this->add_free_product_to_cart_message( $cart_item['product_id'], $qty_added );
			}
		} else {
			// Add the free product.
			$cart_item_key = $this->add_free_product_to_cart( $qty );

			if ( $cart_item_key ) {
				$cart_item = WC()->cart->get_cart_item( $cart_item_key );

				if ( ! empty( $cart_item ) && isset( $cart_item['product_id'] ) && isset( $cart_item['quantity'] ) ) {
					$this->add_free_product_to_cart_message( $cart_item['product_id'], $cart_item['quantity'] );
				}
			} else {
				// Log the error.
				$notices = wc_get_notices();

				if ( isset( $notices['error'] ) && count( $notices['error'] ) ) {
					$error      = array_pop( $notices['error'] );
					$error_text = is_array( $error ) && isset( $error['notice'] ) ? $error['notice'] : $error;
					$logger     = wc_get_logger();
					$logger->error( sprintf( 'BOGO id: %s - Imposible to add the free product to the cart: ', $this->rule->get_id() ) . $error_text, array( 'source' => 'woocommerce-buy-one-get-one-free' ) );

					wc_set_notices( $notices );
				}
			}
		}

		$this->clear_totals();
	}

	/**
	 * Add the free product to the cart.
	 *
	 * @param int $qty The quantity of the item to add.
	 * @return string|bool $cart_item_key
	 */
	protected function add_free_product_to_cart( $qty ) {
		$cart_item_key = false;
		$product_id    = $this->rule->get_free_product_id();
		if ( $product_id ) {
			$cart_item_key = WC()->cart->add_to_cart( $product_id, $qty, 0, array(), array( 'wc_bogof_cart_rule' => array( $this->get_id() ) ) );
		}
		return $cart_item_key;
	}

	/**
	 * Add free product to cart message.
	 *
	 * @param int $product_id Product ID.
	 * @param int $qty Quantity.
	 */
	protected function add_free_product_to_cart_message( $product_id, $qty ) {
		global $wp_query;

		if ( is_ajax() && 'add_to_cart' === $wp_query->get( 'wc-ajax' ) && 'yes' !== get_option( 'woocommerce_cart_redirect_after_add' ) ) {
			return;
		}

		/* translators: %s: product name */
		$title = apply_filters( 'woocommerce_add_to_cart_qty_html', absint( $qty ) . ' &times; ', $product_id ) . apply_filters( 'woocommerce_add_to_cart_item_name_in_quotes', sprintf( _x( '&ldquo;%s&rdquo;', 'Item name in quotes', 'wc-buy-one-get-one-free' ), wp_strip_all_tags( get_the_title( $product_id ) ) ), $product_id );
		/* translators: %s: product name */
		$message = sprintf( _n( '%s has been added to your cart for free!', '%s have been added to your cart for free!', $qty, 'wc-buy-one-get-one-free' ), $title );

		// Added the notices to the array.
		$this->notices[] = apply_filters( 'wc_bogof_add_free_product_to_cart_message_html', $message, $product_id, $qty );
		if ( is_ajax() ) {
			$this->add_messages();
		}
	}

	/**
	 * Check if is a shop page.
	 *
	 * @return bool
	 */
	protected function is_shop_page() {
		return did_action( 'parse_request' ) && WC_BOGOF_Choose_Gift::is_choose_your_gift();
	}

	/**
	 * Check the cart coupons.
	 */
	protected function check_cart_coupons() {
		$coupons = $this->rule->get_coupon_codes();
		$valid   = empty( $coupons );
		if ( ! $valid ) {
			$valid = wc_bogof_in_array_intersect( $coupons, WC()->cart->get_applied_coupons() );
		}
		return $valid;
	}

	/**
	 * Checks if a cart data array contains the cart rule ID and unset the element.
	 *
	 * @param array $cart_item_data Cart item data.
	 * @return bool
	 */
	protected function check_cart_item_data( &$cart_item_data ) {
		// phpcs:disable WordPress.Security.NonceVerification
		$cart_rules = false;

		if ( isset( $cart_item_data['wc_bogof_cart_rule'] ) ) {
			$cart_rules = $cart_item_data['wc_bogof_cart_rule'];
		} elseif ( isset( $_REQUEST['wc_bogof_cart_rule'] ) ) {
			$cart_rules = wc_clean( $_REQUEST['wc_bogof_cart_rule'] );
		}
		$cart_rules = is_array( $cart_rules ) ? $cart_rules : array();

		$indexes = array_keys( $cart_rules, $this->get_id() ); // phpcs:ignore WordPress.PHP.StrictInArray
		foreach ( $indexes as $index ) {
			unset( $cart_item_data['wc_bogof_cart_rule'][ $index ] );
			unset( $_REQUEST['wc_bogof_cart_rule'][ $index ] );
		}
		// phpcs:enable
		return count( $indexes ) > 0;
	}

	/**
	 * Count numbers of products that matches the rule.
	 *
	 * @return int
	 */
	public function get_cart_quantity() {
		if ( ! isset( $this->totals['cart_quantity'] ) ) {

			$cart_quantity = 0;
			$cart_contents = WC()->cart->get_cart_contents();
			foreach ( $cart_contents as $key => $cart_item ) {
				if ( ! WC_BOGOF_Cart::is_free_item( $cart_item ) && $this->cart_item_match( $cart_item ) ) {
					$cart_quantity += $cart_item['quantity'];
				}
			}

			$this->totals['cart_quantity'] = $cart_quantity;
		}

		return $this->totals['cart_quantity'];
	}

	/**
	 * Get the quantity of the free items based on rule and on the product quantity in the cart.
	 *
	 * @return int
	 */
	public function get_max_free_quantity() {
		if ( ! isset( $this->totals['free_quantity'] ) ) {

			$cart_qty = $this->get_cart_quantity();
			$free_qty = 0;

			if ( $this->check_cart_coupons() && $cart_qty >= $this->rule->get_min_quantity() && 0 < $this->rule->get_min_quantity() ) {

				$free_qty = absint( ( floor( $cart_qty / $this->rule->get_min_quantity() ) * $this->rule->get_free_quantity() ) );

				if ( $this->rule->get_cart_limit() && $free_qty > $this->rule->get_cart_limit() ) {
					$free_qty = $this->rule->get_cart_limit();
				}
			}

			$this->totals['free_quantity'] = $free_qty;
		}

		return apply_filters( 'wc_bogof_free_item_quantity', $this->totals['free_quantity'], $this->get_cart_quantity(), $this->rule, $this );
	}

	/**
	 * Returns the number of items available for free in the shop.
	 *
	 * @return int
	 */
	public function get_shop_free_quantity() {
		if ( ! isset( $this->totals['shop_free_quantity'] ) ) {
			$this->totals['shop_free_quantity'] = $this->get_max_free_quantity() - WC_BOGOF_Cart::get_free_quantity( $this->get_id() );
		}
		return $this->totals['shop_free_quantity'];
	}

	/**
	 * Is the product avilable for free in the shop.
	 *
	 * @param int $product_id Product ID.
	 * @return bool
	 */
	public function is_shop_avilable_free_product( $product_id ) {
		return $this->get_shop_free_quantity() > 0 && $this->rule->is_free_product( $product_id );
	}

	/**
	 * Update the quantity of free items in the cart.
	 */
	public function update_free_items_qty() {

		$this->clear_totals();

		$max_qty        = $this->get_max_free_quantity();
		$free_items_qty = WC_BOGOF_Cart::get_free_quantity( $this->get_id() );

		if ( $free_items_qty > $max_qty ) {

			$items    = WC_BOGOF_Cart::get_free_items( $this->get_id() );
			$over_qty = $free_items_qty - $max_qty;

			foreach ( $items as $key => $item ) {
				if ( 0 === $over_qty ) {
					break;
				}

				if ( $item['quantity'] > $over_qty ) {
					WC()->cart->set_quantity( $key, $item['quantity'] - $over_qty, false );
					$over_qty = 0;
				} else {
					WC()->cart->set_quantity( $key, 0, false );
					$over_qty -= $item['quantity'];
				}
			}
		} elseif ( $this->rule->is_action( 'add_to_cart' ) && ( $max_qty - $free_items_qty ) > 0 ) {
			$this->add_to_cart( $max_qty );
		}
	}

	/**
	 * Returns SQL string of the free avilable products to be use in a SELECT.
	 *
	 * @see WC_BOGOF_Choose_Gift::posts_where
	 * @return string
	 */
	public function get_free_products_in() {
		global $wpdb;
		$post_in = false;

		if ( $this->get_shop_free_quantity() > 0 ) {
			if ( $this->rule->is_action( 'choose_from_category' ) ) {
				if ( in_array( 'all', $this->rule->get_free_category_ids(), true ) ) {
					$post_in = '1=1';
				} else {
					$term_taxonomy_ids = implode( ',', array_map( 'absint', $this->rule->get_free_category_ids() ) );
					$post_in           = "{$wpdb->posts}.ID IN ( SELECT object_id FROM {$wpdb->term_relationships} WHERE term_taxonomy_id IN ({$term_taxonomy_ids}) )";
				}
			} else {
				$free_products = $this->rule->get_free_product_ids();
				$parents       = array();
				foreach ( $free_products as $product_id ) {
					if ( 'product_variation' === get_post_type( $product_id ) ) {
						$parents[] = wp_get_post_parent_id( $product_id );
					}
				}
				$free_products = array_merge( $free_products, $parents );

				$post_in = $wpdb->posts . '.ID IN (' . implode( ',', array_map( 'absint', $free_products ) ) . ')';
			}
		}

		return $post_in;
	}

	/**
	 * Init filter and actions.
	 */
	public function init_hooks() {
		add_action( 'woocommerce_applied_coupon', array( $this, 'cart_coupons_updated' ) );
		add_action( 'woocommerce_removed_coupon', array( $this, 'cart_coupons_updated' ) );
		add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'before_add_to_cart_button' ) );
		add_filter( 'woocommerce_product_add_to_cart_url', array( $this, 'product_add_to_cart_url' ), 100, 2 );
		add_filter( 'woocommerce_add_cart_item_data', array( $this, 'add_cart_item' ), 100, 3 );
		add_filter( 'woocommerce_product_get_price', array( $this, 'get_free_product_price' ), 9999, 2 );
		add_filter( 'woocommerce_product_variation_get_price', array( $this, 'get_free_product_price' ), 9999, 2 );
		add_filter( 'woocommerce_product_get_sale_price', array( $this, 'get_free_product_price' ), 9999, 2 );
		add_filter( 'woocommerce_product_variation_get_sale_price', array( $this, 'get_free_product_price' ), 9999, 2 );
		add_filter( 'woocommerce_variation_prices_price', array( $this, 'get_free_product_price' ), 9999, 2 );
		add_filter( 'woocommerce_variation_prices_sale_price', array( $this, 'get_free_product_price' ), 9999, 2 );
		add_filter( 'woocommerce_get_variation_prices_hash', array( $this, 'get_variation_prices_hash' ), 100, 2 );
	}

	/**
	 * Add the free product messages to the session.
	 */
	public function add_messages() {
		foreach ( $this->notices as $notice ) {
			wc_add_notice( $notice, apply_filters( 'woocommerce_add_to_cart_notice_type', 'success' ) );
		}
		$this->notices = array();
	}

	/**
	 * Update free quantities on cart coupon updated.
	 *
	 * @param string $coupon Coupon code.
	 */
	public function cart_coupons_updated( $coupon ) {
		if ( in_array( $coupon, $this->rule->get_coupon_codes(), true ) ) {
			$this->update_free_items_qty();
		}
		$this->add_messages();
	}

	/**
	 * Output the bogo cart rule field.
	 */
	public function before_add_to_cart_button() {
		global $product;
		global $post;
		$product_id   = is_callable( array( $product, 'get_id' ) ) ? $product->get_id() : $post->ID;
		$product_type = is_callable( array( $product, 'get_type' ) ) ? $product->get_type() : false;
		$is_free      = $this->is_shop_avilable_free_product( $product_id );

		if ( ! $is_free && 'variable' === $product_type ) {
			foreach ( $product->get_children() as $child_id ) {
				$is_free = $this->is_shop_avilable_free_product( $child_id );
				if ( $is_free ) {
					break;
				}
			}
		}
		if ( $this->is_shop_page() && $is_free ) {
			echo '<input type="hidden" name="wc_bogof_cart_rule[]" value="' . esc_attr( $this->get_id() ) . '" />';
		}
	}

	/**
	 * Appends the bogo cart rule parameter.
	 *
	 * @param string     $url Add to cart URL.
	 * @param WC_Product $product Product instance.
	 * @return string
	 */
	public function product_add_to_cart_url( $url, $product = false ) {
		if ( $this->is_shop_page() && strpos( $url, 'add-to-cart' ) && is_callable( array( $product, 'get_id' ) ) && $this->is_shop_avilable_free_product( $product->get_id() ) ) {
			$url = add_query_arg( 'wc_bogof_cart_rule[]', esc_attr( $this->get_id() ), $url );
		}
		return $url;
	}

	/**
	 * Return the zero price for free products.
	 *
	 * @param mixed      $price Product price.
	 * @param WC_Product $product Product instance.
	 */
	public function get_free_product_price( $price, $product ) {
		if ( $this->is_shop_page() && $this->is_shop_avilable_free_product( $product->get_id() ) ) {
			$price = 0;
		}
		return $price;
	}

	/**
	 * Returns unique cache key to store variation child prices.
	 *
	 * @param array      $price_hash Unique cache key.
	 * @param WC_Product $product Product instance.
	 * @return array
	 */
	public function get_variation_prices_hash( $price_hash, $product ) {
		if ( $this->is_shop_page() ) {
			$price_hash   = is_array( $price_hash ) ? $price_hash : array( $price_hash );
			$price_hash[] = WC_BOGOF_Cart::get_hash() . $this->get_id();
		}
		return $price_hash;
	}

	/**
	 * Update the cart item data.
	 *
	 * @param array $cart_item_data Cart item data.
	 * @param int   $product_id The product ID.
	 * @param int   $variation_id The variation ID.
	 */
	public function add_cart_item( $cart_item_data, $product_id, $variation_id ) {
		if ( WC_BOGOF_Cart::is_free_item( $cart_item_data ) || ! $this->check_cart_item_data( $cart_item_data ) ) { // phpcs:ignore WordPress.PHP.StrictInArray
			return $cart_item_data;
		}

		$product_id = $variation_id ? $variation_id : $product_id;
		if ( $this->is_shop_avilable_free_product( $product_id ) ) {
			// Set as a free item.
			$cart_item_data = WC_BOGOF_Cart::set_cart_item_free( $cart_item_data, $this->get_id() );
		}
		return $cart_item_data;
	}
}
