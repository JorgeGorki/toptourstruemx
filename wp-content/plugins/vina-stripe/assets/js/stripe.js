jQuery(document).ready(function ($) {
	var old_order_id = !1;
	var new_nonce = !1;
	$.fn.STSendAjaxPackage = function () {
	    this.each(function () {
	        var me = $(this);
	        var button = $('#st_submit_member_package', this);
	        var data = me.serializeArray();
	        data.push({name: 'action', value: 'booking_form_package_direct_submit'});
	        me.find('.form-control').removeClass('error');
	        me.find('.form_alert').addClass('hidden');
	        var dataobj = {};
	        for (var i = 0; i < data.length; ++i) {
	            dataobj[data[i].name] = data[i].value
	        }
	        dataobj.order_id = old_order_id;
	        button.addClass('loading');

	        $.ajax({
	            type: 'post',
	            url: st_params.ajax_url,
	            data: dataobj,
	            dataType: 'json',
	            success: function (data) {
	            	console.log(data);
	                var stripePublishKey = st_vina_stripe_params.vina_stripe.publishKey;
	                if(st_vina_stripe_params.vina_stripe.sanbox == 'sandbox'){
	                    stripePublishKey = st_vina_stripe_params.vina_stripe.testPublishKey
	                }
	                var stripe = Stripe(stripePublishKey);
	                if (typeof(data.payment_intent_client_secret) != 'undefined' && data.payment_intent_client_secret) {
	                    stripe.handleCardAction(
	                      data.payment_intent_client_secret
	                    ).then(function(result) {
	                    	console.log(result);
	                      if (result.error) {
	                            var html_error = "<div class='alert alert-infor'><button type=button class=close data-dismiss=alert><span aria-hidden=true>&times;</span></button>"+payment_intent_id.message+"</div>";
	                            me.find('.mt20').html(html_error);
	                            console.log(payment_intent_id);
	                      } else {

	                        $.ajax({
	                            url: st_params.ajax_url,
	                            dataType: 'json',
	                            type: 'POST',
	                            data: {
	                                'action' : 'vina_stripe_package_confirm_server',
	                                'st_order_id' : data.order_id,
	                                'payment_intent_id' : result.paymentIntent.id,
	                                'data_step2' : data,
	                            },
	                            beforeSend: function () {
	                               //handleServerResponse();
	                            },
	                            success: function (response_server) {
	                                console.log(response_server);
	                                console.log("stripe server confirm");
	                            },
	                            complete: function (jqXHR) {
	                                if (typeof(data.order_id) != 'undefined' && data.order_id) {
				                        old_order_id = data.order_id
				                    }
				                    if (data.message) {
				                        me.find('.form_alert').addClass('alert-danger').removeClass('hidden');
				                        me.find('.form_alert').html(data.message)
				                    }
				                    if (data.redirect) {
				                        window.location.href = data.redirect
				                    }
				                    if (data.redirect_form) {
				                        $('body').append(data.redirect_form)
				                    }
				                    if (data.new_nonce) {

				                    }
				                    var widget_id = 'st_recaptchar_' + dataobj.item_id;
				                    get_new_captcha(me);
				                    button.removeClass('loading')
	                                
	                            },
	                        });
	                      }
	                    });
	                }  else {
	                    if (typeof(data.order_id) != 'undefined' && data.order_id) {
	                        old_order_id = data.order_id
	                    }
	                    if (data.message) {
	                        me.find('.form_alert').addClass('alert-danger').removeClass('hidden');
	                        me.find('.form_alert').html(data.message)
	                    }
	                    if (data.redirect) {
	                        window.location.href = data.redirect
	                    }
	                    if (data.redirect_form) {
	                        $('body').append(data.redirect_form)
	                    }
	                    if (data.new_nonce) {

	                    }
	                    var widget_id = 'st_recaptchar_' + dataobj.item_id;
	                    get_new_captcha(me);
	                    button.removeClass('loading')
	                }
	                
	            },
	            error: function (e) {
	                button.removeClass('loading');
	                alert('Lost connect to server');
	                //get_new_captcha(me)
	            }
	        });
	    });
	};
	jQuery(document).ready(function ($) {
	    jQuery('#st_submit_member_package').submit(function ($) {
	        var form = $(this).closest('form');
	        form.trigger('st_before_checkout');

	        var payment = $('input[name="st_payment_gateway"]:checked', form).val();
	        var wait_validate = $('input[name="wait_validate_' + payment + '"]', form).val();
	        if (wait_validate === 'wait') {
	            form.trigger('st_wait_checkout');
	            return false;
	        }
	        form.STSendAjaxPackage();
	    });
	});
	function get_new_captcha(me) {
        var captcha_box = me.find('.captcha_box');
        url = captcha_box.find('.captcha_img').attr('src');
        captcha_box.find('.captcha_img').attr('src', url)
    }
});
