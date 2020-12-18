<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 20-12-2018
 * Time: 1:55 PM
 * Since: 1.0.0
 * Updated: 1.0.0
 */
while (have_posts()): the_post();
    $post_id = get_the_ID();
    $address = get_post_meta($post_id, 'address', true);
    $review_rate = STReview::get_avg_rate();
    $count_review = STReview::count_review($post_id);
    $lat = get_post_meta($post_id, 'map_lat', true);
    $lng = get_post_meta($post_id, 'map_lng', true);
    $zoom = get_post_meta($post_id, 'map_zoom', true);
    $gallery = get_post_meta($post_id, 'gallery', true);
    $gallery_array = explode(',', $gallery);
    $marker_icon = st()->get_option('st_tours_icon_map_marker', '');
    $tour_external = get_post_meta(get_the_ID(), 'st_tour_external_booking', true);
    $tour_external_link = get_post_meta(get_the_ID(), 'st_tour_external_booking_link', true);
    $booking_type = st_get_booking_option_type();
    $map_iframe = get_post_meta($post_id, 'map_iframe', true);
    $is_iframe = get_post_meta(get_the_ID(), 'is_iframe', true);
    ?>
    <div id="st-content-wrapper" class="st-single-tour st-single-tour-new st-single--popup st-content-wrapper--solo">
        <div class="hotel-target-book-mobile st-single--popup__item">
            <div class="price-wrapper">
                <?php echo wp_kses(sprintf(__('<span class="price">%s</span>', ST_TEXTDOMAIN), STTour::get_price_html(get_the_ID())), ['span' => ['class' => []]]) ?>
            </div>
            <?php
            if ($tour_external == 'off' || empty($tour_external)) {
                ?>
                <div class="st-btn">
                    <a href=""
                       class="btn btn-mpopup btn-green btn--scroll"><?php echo esc_html__('Book Now', ST_TEXTDOMAIN) ?></a>
                    <span class="st-btn--bg"></span>
                </div>

                <?php
            } else {
                ?>
                <div class="st-btn">
                    <a href="<?php echo esc_url($tour_external_link); ?>"
                       class="btn btn-green"><?php echo esc_html__('Book Now', ST_TEXTDOMAIN) ?></a>
                    <span class="st-btn--bg"></span>
                </div>

                <?php
            }
            ?>
        </div>
        <div class="st-tour-content style7">
            <div class="container">
                <!--Tour header-->
                <div class="st-hotel-header st-header--info">
                    <div class="info__content">
                        <div class="left">
                            <?php
                            $country = explode("_", get_post_meta(get_the_ID(), 'multi_location', true));
                            //$url = st_get_link_with_search(get_the_permalink(st()->get_option('tours_search_result_page')), array('location_name'), ['location_name' => get_the_title($country[1])]);
                            ?>
                            <a class="st-header--info__link"><?php echo esc_html(!empty($country[1]) ? get_the_title($country[1]) : '' ); ?></a>
                            <h2 class="st-title"><?php the_title(); ?></h2>

                        </div>
                        <div class="right">
                            <div class="shares dropdown">

                                <div class="shares__wishlist">
                                    <?php echo st()->load_template('layouts/modern/hotel/loop/wishlist'); ?>
                                </div>
                                <ul class="share-wrapper">
                                    <li class="share-bg--facebook"><a class="facebook"
                                                                      href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink() ?>&amp;title=<?php the_title() ?>"
                                                                      target="_blank" rel="noopener" original-title="Facebook"><i
                                                class="fa fa-facebook fa-lg"></i></a></li>
                                    <li class="share-bg--twitter"><a class="twitter"
                                                                     href="https://twitter.com/share?url=<?php the_permalink() ?>&amp;title=<?php the_title() ?>"
                                                                     target="_blank" rel="noopener" original-title="Twitter"><i
                                                class="fa fa-twitter fa-lg"></i></a></li>
                                    <li class="share-bg--google"><a class="google"
                                                                    href="https://plus.google.com/share?url=<?php the_permalink() ?>&amp;title=<?php the_title() ?>"
                                                                    target="_blank" rel="noopener" original-title="Google+"><i
                                                class="fa fa-google-plus fa-lg"></i></a></li>
                                    <li class="share-bg--pinterest"><a class="no-open pinterest"
                                                                       href="javascript:void((function()%7Bvar%20e=document.createElement('script');e.setAttribute('type','text/javascript');e.setAttribute('charset','UTF-8');e.setAttribute('src','https://assets.pinterest.com/js/pinmarklet.js?r='+Math.random()*99999999);document.body.appendChild(e)%7D)());"
                                                                       target="_blank" rel="noopener" original-title="Pinterest"><i
                                                class="fa fa-pinterest fa-lg"></i></a></li>
                                    <li class="share-bg--linkedin"><a class="linkedin"
                                                                      href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink() ?>&amp;title=<?php the_title() ?>"
                                                                      target="_blank" rel="noopener" original-title="LinkedIn"><i
                                                class="fa fa-linkedin fa-lg"></i></a></li>
                                </ul>
                                <div class="shares__social">
                                    <a href="#" class="share-item social-share">
                                        <?php echo TravelHelper::getNewIcon('icon-share-solo', '#FFFFFF', '24px', '24px') ?>
                                    </a>
                                </div>


                            </div>
                        </div>
                    </div>


                    <div class="st-permalink">
                        <ul class="st-permalink--item">
                            <li class="item__color">
                                <a href="<?php echo esc_attr(get_permalink(home_url())) ?>">
                                    <?php echo esc_html__('Home', ST_TEXTDOMAIN); ?>
                                </a>
                            </li>
                            <li><?php
                                echo esc_html(get_the_title(get_the_ID()));
                                $tour_type = get_post_meta(get_the_ID(), 'type_tour', true);
                                ?>

                            </li>
                        </ul>
                    </div>
                </div>


            </div>
            <!-- Book tour -->
            <div class="st-tour-booking" data-screen="992px">
                <div class="container">
                    <?php
                    $info_price = STTour::get_info_price();
                    ?>
                    <div class="widgets widgets--margin">
                        <div id="booking-request" data-screen="992px">
                            <div class="close-icon hide">
                                <?php echo TravelHelper::getNewIcon('Ico_close'); ?>
                            </div>

                            <?php
                            if ($booking_type == 'instant_enquire') {
                                ?>
                                <div class="form-book-wrapper st-tour-booking__bg relative">
                                    <?php echo st()->load_template('layouts/modern/common/loader'); ?>

                                    <?php if (empty($tour_external) || $tour_external == 'off') { ?>
                                        <nav>
                                            <ul class="nav nav-tabs nav-fill-st" id="nav-tab" role="tablist">
                                                <li class="active"><a id="nav-book-tab" data-toggle="tab" href="#nav-book" role="tab" aria-controls="nav-home" aria-selected="true"><?php echo esc_html__('Book', ST_TEXTDOMAIN) ?></a></li>
                                                <li><a id="nav-inquirement-tab" data-toggle="tab" href="#nav-inquirement" role="tab" aria-controls="nav-profile" aria-selected="false"><?php echo esc_html__('Inquiry', ST_TEXTDOMAIN) ?></a></li>
                                            </ul>
                                        </nav>
                                        <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
                                            <div class="tab-pane fade in active" id="nav-book" role="tabpanel" aria-labelledby="nav-book-tab">
                                                <form id="form-booking-inpage" method="post" action="#booking-request" class="tour-booking-form form-has-guest-name st-tour-booking__info">
                                                    <input type="hidden" name="action" value="tours_add_to_cart">
                                                    <input type="hidden" name="item_id" value="<?php echo get_the_ID(); ?>">
                                                    <input type="hidden" name="type_tour" value="<?php echo get_post_meta(get_the_ID(), 'type_tour', true) ?>">
                                                    <?php
                                                    $current_calendar = TravelHelper::get_current_available_calendar(get_the_ID());
                                                    $current_calendar_reverb = date('m/d/Y', strtotime($current_calendar));

                                                    $start = STInput::request('start', date(TravelHelper::getDateFormat(), strtotime($current_calendar)));
                                                    $end = STInput::request('end', date(TravelHelper::getDateFormat(), strtotime($current_calendar)));
                                                    $date = STInput::request('date', date('d/m/Y h:i a', strtotime($current_calendar)) . '-' . date('d/m/Y h:i a', strtotime($current_calendar)));
                                                    $has_icon = (isset($has_icon)) ? $has_icon : false;
                                                    ?>
                                                    <div class="form-group st-tour-booking__border form-date-field form-date-search clearfix <?php if ($has_icon) echo ' has-icon '; ?>" data-format="<?php echo TravelHelper::getDateFormatMoment() ?>" data-availability-date="<?php echo esc_attr($current_calendar_reverb); ?>">
                                                        <?php
                                                        if ($has_icon) {
                                                            echo TravelHelper::getNewIcon('ico_calendar_search_box');
                                                        }
                                                        ?>
                                                        <div data-custom-class="solo-datepicker" class="date-wrapper st-tour-booking__date--wrapper clearfix">
                                                            <?php echo TravelHelper::getNewIcon('icon-calendar-solo', '#123a32', '15px', '16px'); ?>
                                                            <div class="check-in-wrapper st-tour-booking__check-in">

                                                                <div class="render check-in-render"><?php echo esc_html($start); ?></div>
                                                                <?php
                                                                $class_hidden_enddate = 'hidden';
                                                                if ($tour_type != 'daily_tour' && (strtotime($end) - strtotime($start)) > 0) {
                                                                    $class_hidden_enddate = '';
                                                                }
                                                                ?>
                                                                <span class="sts-tour-checkout-label <?php echo esc_attr($class_hidden_enddate); ?>"><span> - </span><div class="render check-out-render"><?php echo esc_html($end); ?></div></span>
                                                            </div>
                                                            <i class="fa fa-angle-down arrow"></i>
                                                        </div>
                                                        <input type="text" class="check-in-input" value="<?php echo esc_attr($start) ?>" name="check_in">
                                                        <input type="hidden" class="check-out-input" value="<?php echo esc_attr($end) ?>" name="check_out" >
                                                        <input type="text" class="check-in-out-input" value="<?php echo esc_attr($date) ?>" name="check_in_out" data-action="st_get_availability_tour_frontend" data-tour-id="<?php the_ID(); ?>" data-posttype="st_tours">
                                                    </div>

                                                    <?php
                                                    /* Starttime */
                                                    $starttime_value = STInput::request('starttime_tour', '');
                                                    ?>

                                                    <div class="form-group st-tour-booking__border form-more-extra st-form-starttime" <?php echo ($starttime_value != '') ? '' : 'style="display: none"' ?>>
                                                        <input type="hidden" data-starttime="<?php echo esc_attr($starttime_value); ?>"
                                                               data-checkin="<?php echo esc_attr($start); ?>" data-checkout="<?php echo esc_attr($end); ?>"
                                                               data-tourid="<?php echo get_the_ID(); ?>" id="starttime_hidden_load_form" data-posttype="st_tours"/>
                                                        <div class="" id="starttime_box">
                                                            <label><?php echo __('Start time', ST_TEXTDOMAIN); ?></label>
                                                            <select class="form-control st_tour_starttime" name="starttime_tour"
                                                                    id="starttime_tour"></select>
                                                        </div>
                                                    </div>
                                                    <!--End starttime-->

                                                    <?php echo st()->load_template('layouts/modern/tour/elements/search/single/guest-solo', ''); ?>
                                                    <?php echo st()->load_template('layouts/modern/tour/elements/search/single/package', ''); ?>
                                                    <?php echo st()->load_template('layouts/modern/tour/elements/search/single/extra-solo', ''); ?>
                                                    <div class="st-tour-booking__price">
                                                        <div class="st-tour-booking__price--item price">
                                                            <span class="value">
                                                                <?php
                                                                echo STTour::get_price_html(get_the_ID());
                                                                ?>
                                                            </span>

                                                        </div>
                                                    </div>
                                                    <div class="submit-group st-tour-booking__submit">
                                                        <button class="btn btn-green btn-large btn-full upper btn-book-ajax"
                                                                type="submit"
                                                                name="submit">
                                                                    <?php echo esc_html__('Book Now', ST_TEXTDOMAIN) ?>
                                                            <i class="fa fa-spinner fa-spin hide"></i>
                                                        </button>
                                                        <input style="display:none;" type="submit" class="btn btn-default btn-send-message" data-id="<?php echo get_the_ID(); ?>" name="st_send_message" value="<?php echo __('Send message', ST_TEXTDOMAIN); ?>">
                                                    </div>
                                                    <div class="message-wrapper mt30">
                                                        <?php echo STTemplate::message() ?>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="tab-pane fade " id="nav-inquirement" role="tabpanel" aria-labelledby="nav-inquirement-tab">
                                                <?php echo st()->load_template('email/email_single_service'); ?>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="submit-group st-tour-booking__submit mb30">
                                            <a href="<?php echo esc_url($tour_external_link); ?>" class="btn btn-green btn-large btn-full upper"><?php echo esc_html__('Book Now', ST_TEXTDOMAIN); ?></a>
                                            <input style="display:none;" type="submit" class="btn btn-default btn-send-message" data-id="<?php echo get_the_ID(); ?>" name="st_send_message" value="<?php echo __('Send message', ST_TEXTDOMAIN); ?>">
                                        </div>
                                    <?php } ?>
                                </div>
                                <?php
                            } else {
                                if ($booking_type == 'enquire') {
                                    ?>
                                    <div class="form-book-wrapper st-tour-booking__bg relative">

                                        <?php echo st()->load_template('layouts/modern/common/loader'); ?>

                                        <h4 class="title-enquiry-form"><?php echo esc_html__('Inquiry', ST_TEXTDOMAIN); ?></h4>
                                        <?php echo st()->load_template('email/email_single_service'); ?>
                                        <input style="display:none;" type="submit" class="btn btn-default btn-send-message" data-id="<?php echo get_the_ID(); ?>" name="st_send_message" value="<?php echo __('Send message', ST_TEXTDOMAIN); ?>">
                                    </div>
                                    <?php
                                } else {
                                    ?>
                                    <div class="form-book-wrapper st-tour-booking__bg relative">

                                        <?php echo st()->load_template('layouts/modern/common/loader'); ?>
                                        <?php if (empty($tour_external) || $tour_external == 'off') { ?>
                                            <form id="form-booking-inpage" method="post" action="#booking-request" class="tour-booking-form form-has-guest-name st-tour-booking__info">
                                                <input type="hidden" name="action" value="tours_add_to_cart">
                                                <input type="hidden" name="item_id" value="<?php echo get_the_ID(); ?>">
                                                <input type="hidden" name="type_tour" value="<?php echo get_post_meta(get_the_ID(), 'type_tour', true) ?>">
                                                <?php
                                                $current_calendar = TravelHelper::get_current_available_calendar(get_the_ID());
                                                $current_calendar_reverb = date('m/d/Y', strtotime($current_calendar));

                                                $start = STInput::request('start', date(TravelHelper::getDateFormat(), strtotime($current_calendar)));
                                                $end = STInput::request('end', date(TravelHelper::getDateFormat(), strtotime($current_calendar)));
                                                $date = STInput::request('date', date('d/m/Y h:i a', strtotime($current_calendar)) . '-' . date('d/m/Y h:i a', strtotime($current_calendar)));
                                                $has_icon = (isset($has_icon)) ? $has_icon : false;
                                                ?>
                                                <div class="form-group st-tour-booking__border form-date-field form-date-search clearfix <?php if ($has_icon) echo ' has-icon '; ?>" data-format="<?php echo TravelHelper::getDateFormatMoment() ?>" data-availability-date="<?php echo esc_attr($current_calendar_reverb); ?>">
                                                    <?php
                                                    if ($has_icon) {
                                                        echo TravelHelper::getNewIcon('ico_calendar_search_box');
                                                    }
                                                    ?>
                                                    <div class="date-wrapper st-tour-booking__date--wrapper clearfix" data-custom-class="solo-datepicker">
                                                        <?php echo TravelHelper::getNewIcon('icon-calendar-solo', '#123a32', '15px', '16px'); ?>
                                                        <div class="check-in-wrapper st-tour-booking__check-in">

                                                            <div class="render check-in-render"><?php echo esc_html($start); ?>

                                                            </div>
                                                            <?php
                                                            $class_hidden_enddate = 'hidden';
                                                            if ($tour_type != 'daily_tour' && (strtotime($end) - strtotime($start)) > 0) {
                                                                $class_hidden_enddate = '';
                                                            }
                                                            ?>
                                                            <span class="sts-tour-checkout-label <?php echo esc_attr($class_hidden_enddate); ?>"><span> - </span><div class="render check-out-render"><?php echo esc_html($end); ?></div></span>
                                                        </div>
                                                        <i class="fa fa-angle-down arrow"></i>
                                                    </div>
                                                    <input type="text" class="check-in-input" value="<?php echo esc_attr($start) ?>" name="check_in">
                                                    <input type="hidden" class="check-out-input" value="<?php echo esc_attr($end) ?>" name="check_out" >
                                                    <input type="text" class="check-in-out-input" value="<?php echo esc_attr($date) ?>" name="check_in_out" data-action="st_get_availability_tour_frontend" data-tour-id="<?php the_ID(); ?>" data-posttype="st_tours">
                                                </div>

                                                <?php
                                                /* Starttime */
                                                $starttime_value = STInput::request('starttime_tour', '');
                                                ?>

                                                <div class="form-group st-tour-booking__border form-more-extra st-form-starttime" <?php echo ($starttime_value != '') ? '' : 'style="display: none"' ?>>
                                                    <input type="hidden" data-starttime="<?php echo esc_attr($starttime_value); ?>"
                                                           data-checkin="<?php echo esc_attr($start); ?>" data-checkout="<?php echo esc_attr($end); ?>"
                                                           data-tourid="<?php echo get_the_ID(); ?>" id="starttime_hidden_load_form" data-posttype="st_tours"/>
                                                    <div class="" id="starttime_box">
                                                        <label><?php echo __('Start time', ST_TEXTDOMAIN); ?></label>
                                                        <select class="form-control st_tour_starttime" name="starttime_tour"
                                                                id="starttime_tour"></select>
                                                    </div>
                                                </div>
                                                <!--End starttime-->

                                                <?php echo st()->load_template('layouts/modern/tour/elements/search/single/guest-solo', ''); ?>
                                                <?php echo st()->load_template('layouts/modern/tour/elements/search/single/package', ''); ?>
                                                <?php echo st()->load_template('layouts/modern/tour/elements/search/single/extra-solo', ''); ?>
                                                <div class="st-tour-booking__price">
                                                    <div class="st-tour-booking__price--item price">

                                                        <span class="value">
                                                            <?php
                                                            echo STTour::get_price_html(get_the_ID());
                                                            ?>
                                                        </span>

                                                    </div>
                                                </div>
                                                <div class="submit-group st-tour-booking__submit">
                                                    <button class="btn btn-green btn-large btn-full upper btn-book-ajax"
                                                            type="submit"
                                                            name="submit">
                                                                <?php echo esc_html__('Book Now', ST_TEXTDOMAIN) ?>
                                                        <i class="fa fa-spinner fa-spin hide"></i>
                                                    </button>
                                                    <input style="display:none;" type="submit" class="btn btn-default btn-send-message" data-id="<?php echo get_the_ID(); ?>" name="st_send_message" value="<?php echo __('Send message', ST_TEXTDOMAIN); ?>">
                                                </div>
                                                <div class="message-wrapper mt30">
                                                    <?php echo STTemplate::message() ?>
                                                </div>
                                            </form>
                                        <?php } else { ?>
                                            <div class="submit-group st-tour-booking__submit mb30">
                                                <a href="<?php echo esc_url($tour_external_link); ?>" class="btn btn-green btn-large btn-full upper"><?php echo esc_html__('Book Now', ST_TEXTDOMAIN); ?></a>
                                                <input style="display:none;" type="submit" class="btn btn-default btn-send-message" data-id="<?php echo get_the_ID(); ?>" name="st_send_message" value="<?php echo __('Send message', ST_TEXTDOMAIN); ?>">
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <?php
                                }
                            }
                            ?>

                        </div>
                    </div>
                </div>

            </div>
            <!-- End Book -->
            <?php $bg_image_overview = get_post_meta($post_id, 'tour_over_view_bg_img', true); ?>
            <div class="st-content--info" style="background-image: url('<?php echo esc_attr($bg_image_overview); ?>');">

                <div class="container">
                    <div class="row">
                        <div class="col-sm-6 col-md-5 info__maxwidth">
                            <!--Tour Overview-->
                            <?php
                            global $post;
                            $content = $post->post_content;
                            $count = str_word_count($content);
                            if (!empty($content)) {
                                ?>
                                <div class="info__overview st-overview">
                                    <h3 class="overview__title"><?php echo esc_html__('Overview', ST_TEXTDOMAIN); ?></h3>
                                    <div class="overview__description st-description">
                                        <div class="st-description-more">
                                            <?php
                                            echo wp_trim_words(get_the_content(), 50, '...');
                                            ?>
                                            <span class="stt-more"><?php echo esc_html__('More', ST_TEXTDOMAIN); ?></span>
                                        </div>
                                        <div class="st-description-less">
                                            <?php echo the_content(); ?>
                                            <span class="stt-less"><?php echo esc_html__('Less', ST_TEXTDOMAIN); ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <!--End Tour Overview-->
                            <!--Tour Info-->
                            <div class="st-tour--feature style7">

                                <div class="feature__item">
                                    <div class="item__icon">
                                        <?php echo TravelHelper::getNewIcon('icon-tour-solo-length', '#ec927e', '21px', '21px'); ?>
                                    </div>
                                    <div class="feature__info">
                                        <h4 class="info__name"><?php echo esc_html__('Duration', ST_TEXTDOMAIN); ?></h4>
                                        <p class="info__value">
                                            <?php
                                            $duration = get_post_meta(get_the_ID(), 'duration_day', true);
                                            echo esc_html($duration);
                                            ?>
                                        </p>
                                    </div>
                                </div>


                                <div class="feature__item">
                                    <div class="item__icon">
                                        <?php echo TravelHelper::getNewIcon('icon-tour-solo-type', '#ec927e', '21px', '21px'); ?>
                                    </div>
                                    <div class="feature__info">
                                        <h4 class="info__name"><?php echo esc_html__('Tour Type', ST_TEXTDOMAIN); ?></h4>
                                        <p class="info__value">
                                            <?php
                                            $tour_type = get_post_meta(get_the_ID(), 'type_tour', true);
                                            if ($tour_type == 'daily_tour') {
                                                echo esc_html__('Daily Tour', ST_TEXTDOMAIN);
                                            } else {
                                                echo esc_html__('Specific Tour', ST_TEXTDOMAIN);
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="feature__item">
                                    <div class="item__icon">
                                        <?php echo TravelHelper::getNewIcon('icon-tour-solo-language', '#ec927e', '21px', '21px'); ?>
                                    </div>
                                    <div class="feature__info">
                                        <h4 class="info__name"><?php echo esc_html__('Languages', ST_TEXTDOMAIN); ?></h4>
                                        <p class="info__value">
                                            <?php
                                            $term_list = wp_get_post_terms(get_the_ID(), 'languages');
                                            $str_term_arr = [];
                                            if (!is_wp_error($term_list) && !empty($term_list)) {
                                                foreach ($term_list as $k => $v) {
                                                    array_push($str_term_arr, $v->name);
                                                }

                                                echo implode(', ', $str_term_arr);
                                            } else {
                                                echo '___';
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="feature__item">
                                    <div class="item__icon">
                                        <?php echo TravelHelper::getNewIcon('icon-tour-solo-size', '#ec927e', '21px', '21px'); ?>
                                    </div>
                                    <div class="feature__info">
                                        <h4 class="info__name"><?php echo esc_html__('Group Size', ST_TEXTDOMAIN); ?></h4>
                                        <p class="info__value">
                                            <?php
                                            $max_people = get_post_meta(get_the_ID(), 'max_people', true);
                                            if (empty($max_people) or $max_people == 0 or $max_people < 0) {
                                                echo esc_html__('Unlimited', ST_TEXTDOMAIN);
                                            } else {
                                                if ($max_people == 1)
                                                    echo sprintf(esc_html__('%s person', ST_TEXTDOMAIN), $max_people);
                                                else
                                                    echo sprintf(esc_html__('%s people', ST_TEXTDOMAIN), $max_people);
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>   

                            </div>
                            <!--End Tour info-->
                            <!--Tour Include/Exclude-->
                            <?php
                            $include = get_post_meta(get_the_ID(), 'tours_include', true);
                            $exclude = get_post_meta(get_the_ID(), 'tours_exclude', true);
                            if (!empty($include) or ! empty($exclude)) {
                                ?>
                                <div class="st-include--info">
                                    <h3 class="info__title">
                                        <?php echo esc_html__('Included/Excluded', ST_TEXTDOMAIN); ?>
                                    </h3>
                                    <div class="info__description">
                                        <?php if (!empty($include)) { ?>
                                            <ul class="info__content info__margin">
                                                <?php
                                                $in_arr = explode("\n", $include);
                                                if (!empty($in_arr)) {
                                                    foreach ($in_arr as $k => $v) {
                                                        if (!empty($v)) {
                                                            echo '<li>' . TravelHelper::getNewIcon('check-1', '#36bca1', '15px', '15px', false) . esc_attr($v) . '</li>';
                                                        }
                                                    }
                                                }
                                                ?>
                                            </ul>
                                        <?php } ?>
                                        <?php if (!empty($exclude)) { ?>
                                            <ul class="info__content">
                                                <?php
                                                $ex_arr = explode("\n", $exclude);
                                                if (!empty($ex_arr)) {
                                                    foreach ($ex_arr as $k => $v) {
                                                        if (!empty($v)) {
                                                            echo '<li>' . TravelHelper::getNewIcon('remove', '#ec927e', '15px', '15px', false) . esc_attr($v) . '</li>';
                                                        }
                                                    }
                                                }
                                                ?>
                                            </ul>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <!--End Tour Include/Exclude-->
                        </div>
                        <div class="col-sm-6 col-md-7 info__margin">
                            <!--Tour Banner-->
                            <div class="info__bg">
                                <?php
                                 $url = get_the_post_thumbnail_url($post_id, 'full');
                                if (has_post_thumbnail()) {
                                   
                                    the_post_thumbnail(array( 649 , 396), array('alt' => TravelHelper::get_alt_image(), 'class' => 'img-responsive'));

                                    ?>
                                <?php }else{
                                    echo '<img src="'. get_template_directory_uri() . '/img/no-image.png' .'" alt="Default Thumbnail" class="img-responsive" />';
                                }
                                ?>
                            </div>

                            <div class="info__btn--group">
                                <div class="group__left">
                                    <a href="#st-gallery-popup"
                                       class="group__gallery--popup  has-icon  st-gallery-popup"><?php echo TravelHelper::getNewIcon('icon-gallery-solo', '#FFFFFF', '40px', '35px') ?></a>
                                    <?php if(!empty($gallery)){?>
                                        <a href="#st-gallery-popup" class="has-icon  st-gallery-popup group__link--gallery"><?php echo __('More Photos', ST_TEXTDOMAIN) ?></a>
                                    <?php }?>
                                    <div id="st-gallery-popup" class="hidden">
                                        <?php
                                        if (!empty($gallery_array)) {
                                            foreach ($gallery_array as $k => $v) {
                                                echo '<a href="' . wp_get_attachment_image_url($v, 'full') . '">' . __('Image', ST_TEXTDOMAIN) . '</a>';
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="group__right">
                                    <?php
                                    $video_url = get_post_meta(get_the_ID(), 'video', true);
                                    if (!empty($video_url)) {
                                        ?>
                                        <a href="<?php echo esc_url($video_url); ?>"
                                           class="group__video--popup   has-icon  st-video-popup"><?php echo TravelHelper::getNewIcon('icon-video-solo', '#FFFFFF', '40px', '35px') ?></a>
                                        <a href="<?php echo esc_url($video_url); ?>" class="has-icon  st-video-popup group__link--video"><?php echo __('Tour Video', ST_TEXTDOMAIN) ?></a>
                                    <?php } ?>
                                </div>


                            </div>
                            <!--Tour Include/Exclude-->
                            <?php
                            $include = get_post_meta(get_the_ID(), 'tours_include', true);
                            $exclude = get_post_meta(get_the_ID(), 'tours_exclude', true);
                            if (!empty($include) or ! empty($exclude)) {
                                ?>
                                <div class="st-include--info">
                                    <h3 class="info__title">
                                        <?php echo esc_html__('Included/Excluded', ST_TEXTDOMAIN); ?>
                                    </h3>
                                    <div class="info__description">
                                        <?php if (!empty($include)) { ?>
                                            <ul class="info__content">
                                                <?php
                                                $in_arr = explode("\n", $include);
                                                if (!empty($in_arr)) {
                                                    foreach ($in_arr as $k => $v) {
                                                        echo '<li>' . TravelHelper::getNewIcon('check-1', '#36bca1', '15px', '15px', false) . esc_attr($v) . '</li>';
                                                    }
                                                }
                                                ?>
                                            </ul>
                                        <?php } ?>
                                        <?php if (!empty($exclude)) { ?>
                                            <ul class="info__content">
                                                <?php
                                                $ex_arr = explode("\n", $exclude);
                                                if (!empty($ex_arr)) {
                                                    foreach ($ex_arr as $k => $v) {
                                                        echo '<li>' . TravelHelper::getNewIcon('remove', '#ec927e', '15px', '15px', false) . esc_attr($v) . '</li>';
                                                    }
                                                }
                                                ?>
                                            </ul>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <!--End Tour Include/Exclude-->

                        </div>

                    </div>
                </div>

            </div>
            <div class="st-program--wrapper st-program-parent">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <!--Tour program-->
                            <?php
                            $tour_program_style = get_post_meta(get_the_ID(), 'tours_program_style', true);
                            if (empty($tour_program_style))
                                $tour_program_style = 'style1';
                            if ($tour_program_style == 'style1' or $tour_program_style == 'style3')
                                $tour_programs = get_post_meta(get_the_ID(), 'tours_program', true);
                            else if ($tour_program_style == 'style2')
                                $tour_programs = get_post_meta(get_the_ID(), 'tours_program_bgr', true);
                            else
                                $tour_programs = get_post_meta(get_the_ID(), 'tours_program_style4', true);
                            if (!empty($tour_programs)) {
                                ?>
                                <div class="st-program st-program--padding st-maxheight">
                                    <div class="st-title-wrapper st-program--title">
                                        <h3 class="st-section-title st-title__item"><?php echo esc_html__('Tour Itinerary', ST_TEXTDOMAIN); ?></h3>
                                    </div>
                                    <div class="st-program-list <?php echo esc_attr($tour_program_style); ?>">
                                        <?php
                                        echo st()->load_template('layouts/modern/tour/single/items/itenirary/' . esc_attr($tour_program_style), '', array('style' => $tour_program_style));
                                        ?>
                                    </div>
                                </div>
                            <?php } ?>

                            <!--End Tour program-->
                        </div>
                    </div>
                </div>
            </div>
            <?php $bg_image_hightlight = get_post_meta($post_id, 'tours_highlight_bg_img', true); ?>
            <div class="st-content--hightlight"style="background-image: url('<?php echo esc_attr($bg_image_hightlight); ?>');">
                <div class="container">
                    <!--Tour highlight-->

                    <?php
                    $tours_highlight = get_post_meta(get_the_ID(), 'tours_highlight', true);
                    if (!empty($tours_highlight)) {
                        $arr_highlight = explode("\n", trim($tours_highlight));
                        ?>
                        <div class="st-highlight">
                            <h3 class="hightlight__title"><?php echo esc_html__('Tour Highlights', ST_TEXTDOMAIN); ?></h3>
                            <ul class="row">
                                <?php
                                if (!empty($arr_highlight)) {
                                    foreach ($arr_highlight as $k => $v) {
                                        if (!empty($v)) {
                                            echo '<li class="col-xs-12 col-sm-3 col-sm-offset-1 col-md-3 col-md-offset-1 col-lg-3 col-lg-offset-1">' . TravelHelper::getNewIcon('check-1', '#36bca1', '15px', '15px') . esc_html($v) . '</li>';
                                        }
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                    <?php } ?>
                    <!--End Tour highlight-->
                </div>
            </div>
            <div class="container">
                <!--Tour FAQ-->
                <?php
                $tour_faq = get_post_meta(get_the_ID(), 'tours_faq', true);
                if (!empty($tour_faq)) {
                        $number1 = $number2 = 0;
                        $columns = count($tour_faq);
                        $number1 = round((int)$columns/2);
                        $number2 = (int)($columns) - $number1;
                        
                    ?>
                    <div class="st-faq">
                        <h3 class="st-section-title">
                            <?php echo esc_html__('Frequently Asked Questions', ST_TEXTDOMAIN); ?>
                        </h3>
                        <div class="st-flex--faq row">
                            <div class="col-md-6 st-faq--content st-left">
                            <?php 
                                 for($i=0; $i < $number1 ; $i++){
                                    ?>
                                     <div class="item <?php echo ($i == 0) ? 'active' : ''; ?>">
                                        <div class="header">
                                            <h5><?php echo esc_html($tour_faq[$i]['title']); ?></h5>
                                            <span class="arrow">
                                                <i class="fa fa-angle-down"></i>
                                            </span>
                                        </div>
                                        <div class="body">
                                            <?php echo balanceTags(nl2br($tour_faq[$i]['desc'])); ?>
                                        </div>
                                     </div>
                                     <?php
                                 }
                                 ?>
                            </div>
                            <div class="col-md-6  st-right">
                                 <?php 
                                    if($columns > 1){
                                        for($i = $number1; $i < $columns; $i++){
                                            ?>
                                            <div class="item">
                                                <div class="header">
                                                    <h5><?php echo esc_html($tour_faq[$i]['title']); ?></h5>
                                                    <span class="arrow">
                                                        <i class="fa fa-angle-down"></i>
                                                    </span>
                                                </div>
                                                <div class="body">
                                                    <?php echo balanceTags(nl2br($tour_faq[$i]['desc'])); ?>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                 ?>
                            </div>
                           
                        </div>
                    </div>
                    <?php
                }
                ?>
                <!--End Tour FAQ-->
            </div>
            <div class="container">
                <?php
                    $all_attribute = TravelHelper::st_get_attribute_advance( 'st_tours');
                    foreach ($all_attribute as $key_attr => $attr) {
                        $facilities = get_the_terms( get_the_ID(), $attr["value"]);
                        if(!empty($attr["value"]) && (!empty( $facilities))){
                            $get_label_tax = get_taxonomy($attr["value"]);
                            if($attr["value"] !== 'st_tour_type'){
                            ?>
                                <div class="st-hr large"></div>
                                <?php
                                    if(!empty($get_label_tax)){
                                        echo '<h2 class="st-heading-section">'.esc_html($get_label_tax->label).'</h2>';
                                    }
                                ?>
                                <?php
                                    if ( $facilities ) {
                                        $count = count( $facilities );
                                        ?>
                                        <div class="facilities" data-toggle-section="st-<?php echo esc_attr($attr["value"]);?>"
                                            <?php if ( $count > 6 ) echo 'data-show-all="st-'. esc_attr($attr["value"]) .'"
                                            data-height="150"'; ?>
                                            >
                                            <div class="row">
                                                <?php

                                                    foreach ( $facilities as $term ) {
                                                        $icon     = TravelHelper::handle_icon( get_tax_meta( $term->term_id, 'st_icon', true ) );
                                                        $icon_new = TravelHelper::handle_icon( get_tax_meta( $term->term_id, 'st_icon_new', true ) );
                                                        if ( !$icon ) $icon = "fa fa-cogs";
                                                        ?>
                                                        <div class="col-xs-6 col-sm-4">
                                                            <div class="item has-matchHeight">
                                                                <?php
                                                                    if ( !$icon_new ) {
                                                                        echo '<i class="' . esc_attr($icon) . '"></i>' . esc_html($term->name);
                                                                    } else {
                                                                        echo TravelHelper::getNewIcon( $icon_new, '#5E6D77', '24px', '24px' ) . esc_html($term->name);
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    <?php }
                                                ?>
                                            </div>
                                        </div>
                                        <?php if ( $count > 6 ) { ?>
                                            <a href="#" class="st-link block" data-show-target="st-<?php echo esc_attr($attr["value"]);?>"
                                                data-text-less="<?php echo esc_html__( 'Show Less', ST_TEXTDOMAIN ) ?>"
                                                data-text-more="<?php echo esc_html__( 'Show All', ST_TEXTDOMAIN ) ?>"><span
                                                        class="text"><?php echo esc_html__( 'Show All', ST_TEXTDOMAIN ) ?></span>
                                                <i  class="fa fa-caret-down ml3"></i></a>
                                        <?php
                                        }
                                    } ?>
                            <?php }
                        }
                    }
                ?>
            </div>
            

            <div class="stoped-scroll-section"></div>
            <?php $bg_image_service = get_post_meta($post_id, 'tours_related_bg_img', true); ?>
            <div class="st-list-service--bg st-list-service--slider" style="background-image: url('<?php echo esc_attr($bg_image_service); ?>');">
                <div class="container bg__pd">
                    <?php
                    $search_tax_advance = st()->get_option( 'attribute_search_form_tour', 'st_tour_type' );
                    $terms_posts = wp_get_post_terms(get_the_ID(),$search_tax_advance);
                    $arr_id_term_post = array();
                    
                    if(!isset($terms_posts->errors)){
                        foreach($terms_posts as $term_post){
                            $arr_id_term_post[] = $term_post->term_id;
                        }
                        $args = [
                            'posts_per_page' => 4,
                            'post_type' => 'st_tours',
                            'post_author' => get_post_field('post_author', get_the_ID()),
                            'post__not_in' => [$post_id],
                            'orderby' => 'rand',
                            'tax_query' => array(
                                'taxonomy' => $search_tax_advance,
                                'terms' => $arr_id_term_post,
                                'field' => 'term_id',
                                'operator' => 'IN'
                            ),
                        ];
                    } else {
                        $args = [
                            'posts_per_page' => 4,
                            'post_type' => 'st_tours',
                            'post_author' => get_post_field('post_author', get_the_ID()),
                            'post__not_in' => [$post_id],
                            'orderby' => 'rand',
                        ];
                    }
                    global $post;
                    $old_post = $post;
                    $query = new WP_Query($args);
                    if ($query->have_posts()):
                        ?>
                        <div class="st-hr large"></div>
                        <h2 class="heading text-center  mt50"><?php echo esc_html__('You might also like', ST_TEXTDOMAIN) ?></h2>
                        <div class="st-list-tour-related st-list-tour--slide owl-carousel mt50">
                            <?php
                            while ($query->have_posts()): $query->the_post();
                                ?>
                                <div class=" margin">
                                    <div class="item related__item has-matchHeight" >
                                     
                                        <div class="featured">
                                            <div class="thumb">
                                                <a href="<?php the_permalink() ?>">
                                                    <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), [800, 600]) ?>"
                                                         alt="<?php echo TravelHelper::get_alt_image() ?>"
                                                         class="img-responsive">
                                                </a>
                                            </div>
                                            <?php
                                            $country = explode("_", get_post_meta(get_the_ID(), 'multi_location', true));
                                            if (!empty($country[1])) {
                                                ;
                                                $color_location = get_post_meta($country[1], 'color', true);
                                            }
                                            if ($country && isset($country[1])) {
                                                ?>
                                                <span class="ml5 f14 address st-location--style4"  style="background:<?php echo esc_attr($color_location) ?>"><?php echo esc_html(get_the_title($country[1])); ?></span>
                                                <?php
                                            }
                                            ?>
                                            <?php echo st_get_avatar_in_list_service(get_the_ID(), 50); ?>
                                        </div>
                                        <h4 class="title title--color"><a href="<?php the_permalink() ?>"
                                                                          class="st-link c-main"><?php the_title(); ?></a></h4>
                                        <?php
                                        $description_tour = get_post(get_the_ID());
                                        if (!empty($description_tour)) {
                                            ?>
                                            <p class="st-tour--description"><?php echo wp_trim_words(get_the_content(), 14, '...'); ?></p>
                                            <?php
                                        }
                                        ?>
                                        <div class="section-footer">
                                            <div class="st-flex space-between st-price__wrapper">

                                                <div class="right">

                                                    <span class=" price--tour">
                                                        <?php echo sprintf(esc_html__('%s', ST_TEXTDOMAIN), STTour::get_price_html(get_the_ID())); ?>
                                                    </span>
                                                </div>
                                                <div class="st-btn--book">
                                                    <a href="<?php echo esc_attr(get_permalink(get_the_ID())); ?>"><?php echo esc_html__('BOOK NOW', ST_TEXTDOMAIN); ?></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="fixed-bottom">
                                            <div class="st-tour--feature">
                                                <div class="st-tour__item">
                                                    <div class="item__icon">
                                                        <?php echo TravelHelper::getNewIcon('icon-calendar-tour-solo', '#ec927e', '24px', '24px'); ?>
                                                    </div>
                                                    <div class="item__info">
                                                        <h4 class="info__name"><?php echo esc_html__('Duration', ST_TEXTDOMAIN); ?></h4>
                                                        <p class="info__value">
                                                            <?php
                                                            $duration = get_post_meta(get_the_ID(), 'duration_day', true);
                                                            echo esc_html($duration);
                                                            ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                
                                                    <div class="st-tour__item">
                                                        <div class="item__icon">
                                                            <?php echo TravelHelper::getNewIcon('icon-service-tour-solo', '#ec927e', '24px', '24px'); ?>
                                                        </div>
                                                        <div class="item__info">
                                                            <h4 class="info__name"><?php echo esc_html__('Group Size', ST_TEXTDOMAIN); ?></h4>
                                                            <p class="info__value">
                                                                <?php
                                                                $max_people = get_post_meta(get_the_ID(), 'max_people', true);
                                                                if (empty($max_people) or $max_people == 0 or $max_people < 0) {
                                                                    echo esc_html__('Unlimited', ST_TEXTDOMAIN);
                                                                } else {
                                                                    if ($max_people == 1)
                                                                        echo sprintf(esc_html__('%s person', ST_TEXTDOMAIN), $max_people);
                                                                    else
                                                                        echo sprintf(esc_html__('%s people', ST_TEXTDOMAIN), $max_people);
                                                                }
                                                                ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                
                                            </div>
                                        </div>
                                      



                                    </div>
                                </div>
                                <?php
                            endwhile;
                            ?>
                        </div>
                        <?php
                    endif;
                    wp_reset_postdata();
                    $post = $old_post;
                    ?>
                </div>
            </div>

        </div>
    </div>
    <?php
endwhile;
?>

<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery(document).on('click', 'ul#popup-menu', function (e) {
            e.stopPropagation();
        });
        jQuery(document).on('click', '.single-st_tours', function () {
            jQuery('ul#popup-menu').removeAttr("style").hide();
        });
    });
</script>
