<?php
/**
 * Choose your gift shortcode
 *
 * @package  WC_BOGOF
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Choose your gift shortcode class.
 */
class WC_BOGOF_Choose_Gift_Shortcode extends WC_Shortcode_Products {

	/**
	 * BOGO hash.
	 *
	 * @var   string
	 */
	protected $bogo_hash = '';

	/**
	 * Initialize shortcode.
	 *
	 * @param array $attributes Shortcode attributes.
	 */
	public function __construct( $attributes = array() ) {
		parent::__construct( $attributes, 'choose_your_gift' );

		add_action( 'woocommerce_shortcode_before_choose_your_gift_loop', array( $this, 'before_choose_your_gift_loop' ) );
		add_action( 'woocommerce_shortcode_after_choose_your_gift_loop', array( $this, 'after_choose_your_gift_loop' ), 5 );
		add_action( 'woocommerce_shortcode_after_choose_your_gift_loop', 'woocommerce_pagination', 10 );
		add_action( 'woocommerce_shortcode_choose_your_gift_loop_no_results', array( $this, 'choose_your_gift_no_results' ) );
	}

	/**
	 * Parse attributes.
	 *
	 * @param  array $attributes Shortcode attributes.
	 * @return array
	 */
	protected function parse_attributes( $attributes ) {
		$attributes = parent::parse_attributes( $attributes );
		if ( ! is_array( $attributes ) ) {
			$attributes = array();
		}

		$attributes['ids']            = '';
		$attributes['skus']           = '';
		$attributes['category']       = '';
		$attributes['cat_operator']   = 'IN';
		$attributes['attribute']      = '';
		$attributes['terms']          = '';
		$attributes['terms_operator'] = 'IN';
		$attributes['tag']            = '';
		$attributes['tag_operator']   = 'IN';
		$attributes['paginate']       = true;
		$attributes['cache']          = ! ( defined( 'WP_DEBUG' ) && WP_DEBUG );
		$attributes['visibility']     = 'choose_your_gift';
		$attributes['post_where']     = $this->get_post_where();

		return $attributes;
	}

	/**
	 * Get post where.
	 *
	 * @return string
	 */
	protected function get_post_where() {
		global $wpdb;
		$filters = array();

		foreach ( WC_BOGOF_Cart::get_cart_rules() as $cart_rule ) {
			$post_id_in = $cart_rule->get_free_products_in();
			if ( $post_id_in ) {
				$filters[] = '(' . $post_id_in . ')';
			}
		}

		if ( ! empty( $filters ) ) {
			$where = ' AND (' . implode( ' OR ', $filters ) . ')';
		} else {
			$where = ' AND 1=0';
		}

		return $where;
	}

	/**
	 * Set visibility as "choose_your_gift" (all products).
	 *
	 * @param array $query_args Query args.
	 */
	protected function set_visibility_choose_your_gift_query_args( &$query_args ) {
		$this->custom_visibility   = true;
		$query_args['tax_query'][] = array(
			'taxonomy'         => 'product_type',
			'terms'            => array( 'external' ),
			'field'            => 'name',
			'operator'         => 'NOT IN',
			'include_children' => false,
		);

	}

	/**
	 * Generate and return the transient name for this shortcode based on the query args.
	 *
	 * @since 3.3.0
	 * @return string
	 */
	protected function get_transient_name() {
		return 'wc_product_loop_' . md5( wp_json_encode( $this->query_args ) . $this->type . $this->attributes['post_where'] );
	}

	/**
	 * Run the query and return an array of data, including queried ids and pagination information.
	 *
	 * @return object Object with the following props; ids, per_page, found_posts, max_num_pages, current_page
	 */
	protected function get_query_results() {
		add_filter( 'posts_where', array( $this, 'posts_where' ), 10, 2 );

		$results = parent::get_query_results();

		remove_filter( 'posts_where', array( $this, 'posts_where' ), 10, 2 );

		return $results;
	}

	/**
	 * Add the choose your gift product filter.
	 *
	 * @param string   $where The WHERE clause of the query.
	 * @param WP_Query $q The WP_Query instance (passed by reference).
	 */
	public function posts_where( $where, $q ) {
		$where .= $this->attributes['post_where'];
		return $where;
	}

	/**
	 * Actions before "choose your gift" loop.
	 */
	public function before_choose_your_gift_loop() {
		$this->attributes['paginate'] = false;

		add_filter( 'post_type_link', array( $this, 'product_link' ) );
		add_filter( 'woocommerce_loop_product_link', array( $this, 'product_link' ) );
		add_filter( 'woocommerce_product_add_to_cart_url', array( $this, 'product_link' ), 100 );
		add_filter( 'woocommerce_product_supports', array( $this, 'product_supports' ), 100, 2 );
	}

	/**
	 * Actions after "choose your gift" loop.
	 */
	public function after_choose_your_gift_loop() {
		$this->attributes['paginate'] = true;

		remove_filter( 'post_type_link', array( $this, 'product_link' ) );
		remove_filter( 'woocommerce_loop_product_link', array( $this, 'product_link' ) );
		remove_filter( 'woocommerce_product_add_to_cart_url', array( $this, 'product_link' ), 100 );
		remove_filter( 'woocommerce_product_supports', array( $this, 'product_supports' ), 100, 2 );
	}

	/**
	 * Add the bogof parameter to the URL.
	 *
	 * @param string $product_link Product link.
	 */
	public function product_link( $product_link ) {
		if ( false === strpos( $product_link, 'wc_bogo_refer' ) ) {
			$product_link = add_query_arg( 'wc_bogo_refer', WC_BOGOF_Cart::get_hash(), $product_link );
		}
		return $product_link;
	}

	/**
	 * Disable AJAX add to cart.
	 *
	 * @param bool   $support Support the feature?.
	 * @param string $feature string The name of a feature to test support for.
	 */
	public function product_supports( $support, $feature ) {
		if ( 'ajax_add_to_cart' === $feature ) {
			$support = false;
		}
		return $support;
	}

	/**
	 * No eligible gifts.
	 */
	public static function choose_your_gift_no_results() {
		echo '<p>' . esc_html__( 'There are no gifts for you yet.', 'wc-buy-one-get-one-free' ) . '<p>';
		if ( wc_get_page_id( 'shop' ) > 0 ) {
			echo '<a class="button wc-backward" href="' . esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ) . '">';
			esc_html_e( 'Return to shop', 'wc-buy-one-get-one-free' );
			echo '</a>';
		}
	}
}
