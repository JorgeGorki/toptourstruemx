<?php
/**
 * Buy One Get One Free Cart Rule Buy A Get A. Handles BOGO rule actions.
 *
 * @package WC_BOGOF
 */

defined( 'ABSPATH' ) || exit;

/**
 * WC_BOGOF_Cart_Rule_Buy_A_Get_A Class
 */
class WC_BOGOF_Cart_Rule_Buy_A_Get_A extends WC_BOGOF_Cart_Rule_Individual {

	/**
	 * Unique ID to handle the add to cart action.
	 *
	 * @var string
	 */
	protected $uniqid;
	/**
	 * Returns the number of items available for free in the shop.
	 *
	 * @return int
	 */
	public function get_shop_free_quantity() {
		return 0;
	}

	/**
	 * Add the free product to the cart.
	 *
	 * @param int $qty The quantity of the item to add.
	 */
	protected function add_free_product_to_cart( $qty ) {
		$cart_item_data = false;
		$cart_item_key  = false;

		foreach ( WC()->cart->get_cart_contents() as $cart_item_key => $cart_item ) {
			if ( $this->cart_item_match( $cart_item ) ) {
				$cart_item_data = $cart_item;
				break;
			}
		}

		if ( false !== $cart_item_data ) {

			$product_id   = isset( $cart_item_data['product_id'] ) ? $cart_item_data['product_id'] : $this->product_id;
			$variation_id = isset( $cart_item_data['variation_id'] ) ? $cart_item_data['variation_id'] : 0;
			$variation    = isset( $cart_item_data['variation'] ) ? $cart_item_data['variation'] : array();

			$this->uniqid                         = uniqid( $this->get_id() );
			$cart_item_data['wc_bogof_cart_rule'] = $this->uniqid;

			unset( $cart_item_data['key'] );
			unset( $cart_item_data['product_id'] );
			unset( $cart_item_data['variation_id'] );
			unset( $cart_item_data['variation'] );
			unset( $cart_item_data['quantity'] );
			unset( $cart_item_data['data'] );
			unset( $cart_item_data['data_hash'] );

			$cart_item_key = WC()->cart->add_to_cart( $product_id, $qty, $variation_id, $variation, $cart_item_data );
		}
		return $cart_item_key;
	}

	/**
	 * Update the cart item data.
	 *
	 * @param array $cart_item_data Cart item data.
	 * @param int   $product_id The product ID.
	 * @param int   $variation_id The variation ID.
	 */
	public function add_cart_item( $cart_item_data, $product_id, $variation_id ) {
		if ( WC_BOGOF_Cart::is_free_item( $cart_item_data ) || empty( $cart_item_data['wc_bogof_cart_rule'] ) || $this->uniqid !== $cart_item_data['wc_bogof_cart_rule'] ) {
			return $cart_item_data;
		}

		$product_id = $variation_id ? $variation_id : $product_id;

		if ( $product_id === $this->product_id ) {
			// Set as a free item.
			$cart_item_data = WC_BOGOF_Cart::set_cart_item_free( $cart_item_data, $this->get_id() );
		}
		return $cart_item_data;
	}


}
