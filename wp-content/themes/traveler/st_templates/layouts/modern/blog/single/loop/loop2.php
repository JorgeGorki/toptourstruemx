<?php
while (have_posts()):
    the_post();
    ?>
    <div id="st-content-wrapper" class="st-single-blog--solo">
        <?php
        $blog_image = get_the_post_thumbnail_url(get_the_ID());
        if (empty($blog_image)) {
            $blog_image = st()->get_option('header_blog_image');
        }
        ?>
        <div class="single-blog--heading" style="background-image: url(<?php echo esc_attr($blog_image); ?>)">
            <div class="st-title--bg">
                <div class="container">
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-xs-12 col-sm-12 col-md-8 blog-tablet">
                            <div class="post-info">
                                <?php
                                $category_detail = get_the_category(get_the_ID());
                                $v = $category_detail[0];
                                $color = get_term_meta($v->term_id, '_category_color', true);
                                $inline_css = '';
                                if (!empty($color)) {
                                    $inline_css = 'style="color: #' . esc_attr($color) . '"';
                                }
                                echo '<a ' . $inline_css . ' href="' . get_category_link($v->term_id) . '">' . esc_html($v->name) . '</a>';
                                ?>
                                <?php echo get_the_date(); ?>
                                <h2 class="title"><?php the_title() ?></h2>
                            </div>
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="st-blog">
            <div class="blog-content content">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 blog-content--center">
                            <div class="article article--detail-solo">
                                <div class="st-title--bg">
                                    <div class="post-info">
                                        <?php
                                        $category_detail = get_the_category(get_the_ID());
                                        $v = $category_detail[0];
                                        $color = get_term_meta($v->term_id, '_category_color', true);
                                        $inline_css = '';
                                        if (!empty($color)) {
                                            $inline_css = 'style="color: #' . esc_attr($color) . '"';
                                        }
                                        echo '<a ' . $inline_css . ' href="' . get_category_link($v->term_id) . '">' . esc_html($v->name) . '</a>';
                                        ?>
                                        <?php echo get_the_date(); ?>
                                        <h2 class="title"><?php the_title() ?></h2>
                                    </div>
                                </div>
                                <div class="post-content"><?php the_content() ?></div>
                                <div class="st-flex space-between">
                                    <div class="share">
                                        <?php echo __('Share', ST_TEXTDOMAIN); ?>
                                        <div class="share-icon">
                                            <a class="facebook share-item"
                                               href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink() ?>&amp;title=<?php the_title() ?>"
                                               target="_blank" rel="noopener" original-title="Facebook"><i
                                                    class="fa fa-facebook fa-lg"></i></a>
                                            <a class="twitter share-item"
                                               href="https://twitter.com/share?url=<?php the_permalink() ?>&amp;title=<?php the_title() ?>"
                                               target="_blank" rel="noopener" original-title="Twitter"><i
                                                    class="fa fa-twitter fa-lg"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="st-blog-solo--wrapper">
                <div class="st-blog--search">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <div class="single-blog--title">
                                    <p><?php esc_html_e('Lastest News', ST_TEXTDOMAIN) ?></p>
                                    <h3><?php esc_html_e('Learn More About Tours', ST_TEXTDOMAIN) ?></h3>
                                </div>
                                <div class="content">
                                    <?php
                                    $args = array(
                                        'post_type' => 'post',
                                        'posts_per_page' => 3,
                                        'post__not_in' => [get_the_ID()],
                                        'order' => 'desc',
                                        'post_status' => 'publish'
                                    );
                                    $query = new WP_Query($args);
                                    if ($query->have_posts()) {
                                        echo '<div class="blog-wrapper col-sm-12 col-xs-12">';
                                        while ($query->have_posts()) {
                                            $query->the_post();
                                            echo st()->load_template('layouts/modern/blog/content', 2);
                                        }
                                        echo '</div>';
                                    }
                                    wp_reset_postdata();
                                    ?>
                                </div>
                                <div class="st-blog-btn">
                                    <a href="<?php echo get_permalink(get_option('page_for_posts')); ?>"><?php esc_html_e('READ MORE ARTICLES', ST_TEXTDOMAIN) ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endwhile; ?>
