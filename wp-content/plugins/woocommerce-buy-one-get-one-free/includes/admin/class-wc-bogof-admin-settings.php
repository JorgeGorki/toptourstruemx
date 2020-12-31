<?php
/**
 * WooCommerce Buy One Get One Free admin settings
 *
 * @package WC_BOGOF
 */

defined( 'ABSPATH' ) || exit;

/**
 * WC_BOGOF_Admin_Settings Class
 */
class WC_BOGOF_Admin_Settings {

	/**
	 * Init hooks
	 */
	public static function init() {
		add_filter( 'woocommerce_get_sections_products', array( __CLASS__, 'get_sections' ) );
		add_filter( 'woocommerce_get_settings_products', array( __CLASS__, 'get_settings' ), 10, 2 );
	}

	/**
	 * Add settings page to WooCommerce product settings.
	 *
	 * @param array $sections Product settings sections.
	 * @return array
	 */
	public static function get_sections( $sections ) {
		$sections['buy-one-get-one-free'] = __( 'Buy One Get One Free', 'wc-buy-one-get-one-free' );
		return $sections;
	}

	/**
	 * Return settings array.
	 *
	 * @param array  $settings Product settings.
	 * @param string $current_section Current section.
	 * @return array
	 */
	public static function get_settings( $settings, $current_section ) {
		if ( 'buy-one-get-one-free' === $current_section ) {
			$settings = array(
				array(
					'title' => __( 'Buy One Get One', 'wc-buy-one-get-one-free' ),
					'type'  => 'title',
				),
				array(
					'title'    => __( 'Choose your gift page', 'wc-buy-one-get-one-free' ),
					/* Translators: %s Page contents. */
					'desc_tip' => sprintf( __( 'Page contents: %s', 'wc-buy-one-get-one-free' ), '[wc_choose_your_gift]' ),
					'id'       => 'wc_bogof_cyg_page_id',
					'type'     => 'single_select_page',
					'default'  => '',
					'class'    => 'wc-enhanced-select-nostd',
					'css'      => 'min-width:300px;',
					'desc'     => '<br/>' . __( 'This page needs to be set so that WooCommerce knows where to send users to choose the free products.', 'wc-buy-one-get-one-free' ),
				),
				array(
					'title'       => __( 'Choose your gift notice', 'wc-buy-one-get-one-free' ),
					'desc'        => __( 'Message of the notice to show customer when there are eligible free products. Use [qty] for the number of items.', 'wc-buy-one-get-one-free' ),
					'id'          => 'wc_bogof_cyg_notice',
					'type'        => 'text',
					'css'         => 'min-width:300px;',
					'placeholder' => 'You can now add [qty] product(s) for free to the cart.',
					'desc_tip'    => true,
				),
				array(
					'title'       => __( 'Choose your gift button text', 'wc-buy-one-get-one-free' ),
					'id'          => 'wc_bogof_cyg_notice_button_text',
					'type'        => 'text',
					'css'         => 'min-width:300px;',
					'placeholder' => 'Choose your gift',
				),
				array(
					'title'   => __( 'Disable coupons', 'wc-buy-one-get-one-free' ),
					'desc'    => __( 'Disable coupons usage if there is a free BOGO item in the cart.', 'wc-buy-one-get-one-free' ),
					'id'      => 'wc_bogof_disable_coupons',
					'default' => 'no',
					'type'    => 'checkbox',
				),
				array(
					'type' => 'sectionend',
				),
			);
		}
		return $settings;
	}
}
