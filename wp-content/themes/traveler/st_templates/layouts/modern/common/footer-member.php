<?php
    wp_reset_postdata();
    wp_reset_query();
    $footer_template = TravelHelper::st_get_template_footer( get_the_ID(), true );
    if ( $footer_template ) {
        $vc_content = STTemplate::get_vc_pagecontent( $footer_template );
        if ( $vc_content ) {
            echo '<footer id="main-footer" class="clearfix">';
            echo balanceTags($vc_content);
            echo ' </footer>';
        }
    } else {
        ?>
        <footer id="main-footer" class="container-fluid">
            <div class="container text-center">
                <p><?php _e( 'Copy &copy; 2014 Shinetheme. All Rights Reserved', ST_TEXTDOMAIN ) ?></p>
            </div>

        </footer>
    <?php } ?>
<div class="container main-footer-sub">
    <div class="st-flex space-between">
        <div class="left mt20">
            <div class="f14"><?php echo sprintf( esc_html__( 'Copyright © %s by', ST_TEXTDOMAIN ), date( 'Y' ) ); ?> <a
                        href="<?php echo esc_url( home_url( '/' ) ) ?>"
                        class="st-link"><?php bloginfo( 'name' ) ?></a></div>
        </div>
        <div class="right mt20">
            <img src="<?php echo get_template_directory_uri() ?>/v2/images/svg/ico_paymethod.svg" alt=""
                 class="img-responsive">
        </div>
    </div>
</div>
<?php wp_footer(); ?>
</body>
</html>
