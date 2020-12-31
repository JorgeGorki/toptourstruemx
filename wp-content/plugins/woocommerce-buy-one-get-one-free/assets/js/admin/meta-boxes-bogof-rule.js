jQuery(function( $ ) {

	/**
	 * Coupon actions
	 */
	var wc_meta_boxes_bogof_actions = {

		init: function() {
			$('#_type').on( 'change', this.on_type_change);
			$('#_type').on( 'change', function() {
				$('#_buy_product_ids').val(null).trigger('change');
			} );
			$('#_applies_to').on('change', this.on_applies_to_change);
			$('#_action').on('change', this.on_action_change);

			this.on_type_change();
			this.on_applies_to_change();
			this.on_action_change();
		},

		on_type_change: function() {
			$('#_action').closest('div.options_group').toggle( $('#_type').val() === 'buy_a_get_b' );
			$('#_individual').closest('div.options_group').toggle( $('#_type').val() === 'buy_a_get_b' && $('#_applies_to').val() === 'category' );
			if ( $('#_type').val() !== 'buy_a_get_b' ) {
				$('#_buy_product_ids').data( 'action', 'wc_bogof_json_search_free_products' );
				$('#_buy_product_ids').data( 'exclude', wc_admin_bogof_meta_boxes_params.incompatible_types );
			} else {
				$('#_buy_product_ids').data( 'action', '' );
				$('#_buy_product_ids').data( 'exclude', '' );
			}
		},

		on_applies_to_change: function() {
			$('p._buy_product_ids_field').toggle( $('#_applies_to').val() === 'product' );
			$('p._buy_category_ids_field').toggle( $('#_applies_to').val() === 'category' );
			$('#_individual').closest('div.options_group').toggle( $('#_type').val() === 'buy_a_get_b' && $('#_applies_to').val() === 'category' );
		},

		on_action_change: function() {
			var show_class = 'show_if_' + $('#_action').val();

			$('p.action_objects_fields').hide();
			$('p.action_objects_fields.' + show_class).show();
		}
	};
	wc_meta_boxes_bogof_actions.init();

	// Toggle rule on/off.
	if ( 'undefined' === typeof wc_admin_bogof_meta_boxes_params ) {
		return;
	}

	$( 'tr.type-shop_bogof_rule' ).on( 'click', '.wc-bogof-rule-toggle-enabled', function( e ) {
		e.preventDefault();
		var $link   = $( this ),
			$toggle = $link.find( '.woocommerce-input-toggle' );

		var data = {
			action:  'wc_bogof_toggle_rule_enabled',
			security: wc_admin_bogof_meta_boxes_params.nonces.rule_toggle,
			rule_id:  $link.data( 'rule_id' )
		};

		$toggle.addClass( 'woocommerce-input-toggle--loading' );

		$.ajax( {
			url:      woocommerce_admin.ajax_url,
			data:     data,
			dataType : 'json',
			type     : 'POST',
			success:  function( response ) {
				if ( true === response.data ) {
					$toggle.removeClass( 'woocommerce-input-toggle--enabled, woocommerce-input-toggle--disabled' );
					$toggle.addClass( 'woocommerce-input-toggle--enabled' );
					$toggle.removeClass( 'woocommerce-input-toggle--loading' );
				} else if ( false === response.data ) {
					$toggle.removeClass( 'woocommerce-input-toggle--enabled, woocommerce-input-toggle--disabled' );
					$toggle.addClass( 'woocommerce-input-toggle--disabled' );
					$toggle.removeClass( 'woocommerce-input-toggle--loading' );
				}
			}
		} );

		return false;
	});
});