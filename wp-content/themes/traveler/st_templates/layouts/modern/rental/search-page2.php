<?php
get_header();
wp_enqueue_script('filter-rental-js');
?>
    <div id="st-content-wrapper" class="search-result-page layout2 st-rental ">
        <?php
        echo st()->load_template('layouts/modern/rental/elements/banner');
        ?>
        <div class="full-map hidden-xs hidden-sm">
            <?php echo st()->load_template('layouts/modern/rental/elements/search-form'); ?>
        </div>
        <div class="st-hotel-result" id="sticky-halfmap">
            <div class="container">
                <?php
                echo st()->load_template('layouts/modern/rental/elements/top-filter/top-filter');
                $query           = array(
                    'post_type'      => 'st_rental' ,
                    'post_status'    => 'publish' ,
                    's'              => '' ,
                    'orderby' => 'post_modified',
                    'order'   => 'DESC',
                    'posts_per_page' => get_option('posts_per_page', 10)
                );
                global $wp_query , $st_search_query;
                $rental = STRental::inst();
                $rental->alter_search_query();
                query_posts( $query );
                $st_search_query = $wp_query;
                $rental->remove_alter_search_query();
                wp_reset_query();
                echo st()->load_template('layouts/modern/rental/elements/content2'); ?>
            </div>
        </div>
        <div class="as">
		    <?php echo st()->load_template('layouts/modern/common/footer1'); ?>
        </div>
    </div>
    <div class="modal fade" id="st-login-form" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document" style="max-width: 450px;">
            <div class="modal-content relative">
                <?php echo st()->load_template('layouts/modern/common/loader'); ?>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <?php echo TravelHelper::getNewIcon('Ico_close') ?>
                    </button>
                    <h4 class="modal-title"><?php echo esc_html__('Log In', ST_TEXTDOMAIN) ?></h4>
                </div>
                <div class="modal-body relative">
                    <form action="" class="form" method="post">
                        <input type="hidden" name="st_theme_style" value="modern"/>
                        <input type="hidden" name="action" value="st_login_popup">
                        <div class="form-group">
                            <input type="text" class="form-control" name="username" autocomplete="off"
                                   placeholder="<?php echo esc_html__('Email or Username', ST_TEXTDOMAIN) ?>">
                            <?php echo TravelHelper::getNewIcon('ico_email_login_form', '', '18px', ''); ?>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="password" autocomplete="off"
                                   placeholder="<?php echo esc_html__('Password', ST_TEXTDOMAIN) ?>">
                            <?php echo TravelHelper::getNewIcon('ico_pass_login_form', '', '16px', ''); ?>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="submit" class="form-submit"
                                   value="<?php echo esc_html__('Log In', ST_TEXTDOMAIN) ?>">
                        </div>
                        <div class="message-wrapper mt20"></div>
                        <div class="mt20 st-flex space-between st-icheck">
                            <div class="st-icheck-item">
                                <label for="remember-me" class="c-grey">
                                    <input type="checkbox" name="remember" id="remember-me"
                                           value="1"> <?php echo esc_html__('Remember me', ST_TEXTDOMAIN) ?>
                                    <span class="checkmark fcheckbox"></span>
                                </label>
                            </div>
                            <a href="" class="st-link open-loss-password"
                               data-toggle="modal"><?php echo esc_html__('Forgot Password?', ST_TEXTDOMAIN) ?></a>
                        </div>
                        <div class="advanced">
                            <p class="text-center f14 c-grey"><?php echo esc_html__('or continue with', ST_TEXTDOMAIN) ?></p>
                            <div class="row">
                                <div class="col-xs-12 col-sm-4">
                                    <?php if (st_social_channel_status('facebook')): ?>
                                        <a onclick="return false" href="#"
                                           class="btn_login_fb_link st_login_social_link" data-channel="facebook">
                                            <?php echo TravelHelper::getNewIcon('fb', '', '100%') ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <div class="col-xs-12 col-sm-4">
                                    <?php if (st_social_channel_status('google')): ?>
                                        <a href="javascript: void(0)" id="st-google-signin2"
                                           class="btn_login_gg_link st_login_social_link" data-channel="google">
                                            <?php echo TravelHelper::getNewIcon('g+', '', '100%') ?>
                                        </a>
                                        <!--<div id="st-google-signin2" class="btn_login_gg_link st_login_social_link"></div>-->
                                    <?php endif; ?>
                                </div>
                                <div class="col-xs-12 col-sm-4">
                                    <?php if (st_social_channel_status('twitter')): ?>
                                        <a href="<?php echo site_url() ?>/social-login/twitter"
                                           onclick="return false"
                                           class="btn_login_tw_link st_login_social_link" data-channel="twitter">
                                            <?php echo TravelHelper::getNewIcon('tt', '', '100%') ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="mt20 c-grey font-medium f14 text-center">
                            <?php echo esc_html__('Do not have an account? ', ST_TEXTDOMAIN) ?>
                            <a href=""
                               class="st-link open-signup"
                               data-toggle="modal"><?php echo esc_html__('Sign Up', ST_TEXTDOMAIN) ?></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="st-register-form" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document" style="max-width: 520px;">
            <div class="modal-content relative">
                <?php echo st()->load_template('layouts/modern/common/loader'); ?>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <?php echo TravelHelper::getNewIcon('Ico_close') ?>
                    </button>
                    <h4 class="modal-title"><?php echo esc_html__('Sign Up', ST_TEXTDOMAIN) ?></h4>
                </div>
                <div class="modal-body">
                    <form action="" class="form" method="post">
                        <input type="hidden" name="st_theme_style" value="modern"/>
                        <input type="hidden" name="action" value="st_registration_popup">
                        <div class="form-group">
                            <input type="text" class="form-control" name="username" autocomplete="off"
                                   placeholder="<?php echo esc_html__('Username *', ST_TEXTDOMAIN) ?>">
                            <?php echo TravelHelper::getNewIcon('ico_username_form_signup', '', '20px', ''); ?>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="fullname" autocomplete="off"
                                   placeholder="<?php echo esc_html__('Full Name', ST_TEXTDOMAIN) ?>">
                            <?php echo TravelHelper::getNewIcon('ico_fullname_signup', '', '20px', ''); ?>
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" name="email" autocomplete="off"
                                   placeholder="<?php echo esc_html__('Email *', ST_TEXTDOMAIN) ?>">
                            <?php echo TravelHelper::getNewIcon('ico_email_login_form', '', '18px', ''); ?>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="password" autocomplete="off"
                                   placeholder="<?php echo esc_html__('Password *', ST_TEXTDOMAIN) ?>">
                            <?php echo TravelHelper::getNewIcon('ico_pass_login_form', '', '16px', ''); ?>
                        </div>
                        <div class="form-group">
                            <p class="f14 c-grey"><?php echo esc_html__('Select User Type', ST_TEXTDOMAIN) ?></p>
                            <label class="block" for="normal-user">
                                <input checked id="normal-user" type="radio" class="mr5" name="register_as"
                                       value="normal"> <span class="c-main" data-toggle="tooltip" data-placement="right"
                                                             title="<?php echo esc_html__('Used for booking services', ST_TEXTDOMAIN) ?>"><?php echo esc_html__('Normal User', ST_TEXTDOMAIN) ?></span>
                            </label>
                            <label class="block" for="partner-user">
                                <input id="partner-user" type="radio" class="mr5" name="register_as"
                                       value="partner">
                                <span class="c-main" data-toggle="tooltip" data-placement="right"
                                      title="<?php echo esc_html__('Used for upload and booking services', ST_TEXTDOMAIN) ?>"><?php echo esc_html__('Partner User', ST_TEXTDOMAIN) ?></span>
                            </label>
                        </div>
                        <div class="form-group st-icheck-item">
                            <label for="term">
                                <?php
                                $term_id = get_option('wp_page_for_privacy_policy');
                                ?>
                                <input id="term" type="checkbox" name="term"
                                       class="mr5"> <?php echo wp_kses(sprintf(__('I have read and accept the <a class="st-link" href="%s">Terms and Privacy Policy</a>', ST_TEXTDOMAIN), get_the_permalink($term_id)), ['a' => ['href' => [], 'class' => []]]); ?>
                                <span class="checkmark fcheckbox"></span>
                            </label>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="submit" class="form-submit"
                                   value="<?php echo esc_html__('Sign Up', ST_TEXTDOMAIN) ?>">
                        </div>
                        <div class="message-wrapper mt20"></div>
                        <div class="advanced">
                            <p class="text-center f14 c-grey"><?php echo esc_html__('or continue with', ST_TEXTDOMAIN) ?></p>
                            <div class="row">
                                <div class="col-xs-12 col-sm-4">
                                    <?php if (st_social_channel_status('facebook')): ?>
                                        <a onclick="return false" href="#"
                                           class="btn_login_fb_link st_login_social_link" data-channel="facebook">
                                            <?php echo TravelHelper::getNewIcon('fb', '', '100%') ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <div class="col-xs-12 col-sm-4">
                                    <?php if (st_social_channel_status('google')): ?>
                                        <a href="javascript: void(0)" id="st-google-signin3"
                                           class="btn_login_gg_link st_login_social_link" data-channel="google">
                                            <?php echo TravelHelper::getNewIcon('g+', '', '100%') ?>
                                        </a>
                                        <!--<div id="st-google-signin3" class="btn_login_gg_link st_login_social_link"></div>-->

                                    <?php endif; ?>
                                </div>
                                <div class="col-xs-12 col-sm-4">
                                    <?php if (st_social_channel_status('twitter')): ?>
                                        <a href="<?php echo site_url() ?>/social-login/twitter"
                                           onclick="return false"
                                           class="btn_login_tw_link st_login_social_link" data-channel="twitter">
                                            <?php echo TravelHelper::getNewIcon('tt', '', '100%') ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="mt20 c-grey f14 text-center">
                            <?php echo esc_html__('Already have an account? ', ST_TEXTDOMAIN) ?>
                            <a href="" class="st-link open-login"
                               data-toggle="modal"><?php echo esc_html__('Log In', ST_TEXTDOMAIN) ?></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php
echo st()->load_template('layouts/modern/rental/elements/popup/date');
echo st()->load_template('layouts/modern/rental/elements/popup/guest');
wp_footer(); ?>
</body>
</html>