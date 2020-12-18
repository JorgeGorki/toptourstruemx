<?php
    /**
     * Created by PhpStorm.
     * User: Administrator
     * Date: 19-11-2018
     * Time: 8:56 AM
     * Since: 1.0.0
     * Updated: 1.0.0
     */
    $extra_price = get_post_meta( get_the_ID(), 'extra_price', true );
    if ( empty( $extra_price ) ) {
        return;
    }
    $extra = STInput::get( 'extra' );
    if ( empty( $extra ) ) {
        $extra = [];
    }

    if ( !empty( $extra[ 'value' ] ) ) {
        $extra_value = $extra[ 'value' ];
    }
?>
<div class="form-more-extra-solo">
    <div class="button-extra">
        <a href="#dropdown-more-extra" class="dropdown-more-extra"  data-toggle="collapse" >
            <span><?php echo esc_html__( 'Extras - Packages', ST_TEXTDOMAIN ) ?></span>
            <i class="fa fa-angle-down arrow"></i>
        </a>
    </div>
    
    <ul id="dropdown-more-extra" class="dropdown-menu extras collapse"  class="collapse">
        <li>
            <span class="name-extra-title"><?php echo __('Extra',ST_TEXTDOMAIN);?></span>
        </li>
        <?php foreach ( $extra_price as $key => $val ):
            if ( isset( $val[ 'extra_required' ] ) && $val[ 'extra_required' ] == 'on' ) {
                ?>
                <li class="item mt10">
                    <div class="st-flex space-between">
                        <span>
                            <span class="title-extra">
                                <?php echo esc_html($val['title']); ?><span class="c-orange">*</span>
                            </span>
                            <span class="extra-price-item">
                                <?php echo TravelHelper::format_money( $val[ 'extra_price' ] ) ?>
                            </span>
                        </span>
                        <div class="select-wrapper" style="width: 88px;">
                            <?php
                                $max_item = intval( $val[ 'extra_max_number' ] );
                                if ( $max_item <= 0 ) $max_item = 1;
                                $start_i = 0;
                                if ( isset( $val[ 'extra_required' ] ) ) {
                                    if ( $val[ 'extra_required' ] == 'on' ) {
                                        $start_i = 1;
                                    }
                                }
                            ?>
                            <div class="caculator-item">
                                <i class="fa fa-minus"></i>
                                <input type="number" class="form-control app extra-service-select"
                                    name="extra_price[value][<?php echo esc_attr($val[ 'extra_name' ]); ?>]"
                                    id="field-<?php echo esc_attr($val[ 'extra_name' ]); ?>"
                                    data-extra-price="<?php echo esc_attr($val[ 'extra_price' ]); ?>" step= "1" value="<?php echo intval($start_i)?>" min="<?php echo intval($start_i)?>" max="<?php echo intval($max_item)?>">
                                <i class="fa fa-plus"></i>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="extra_price[price][<?php echo esc_attr($val[ 'extra_name' ]); ?>]"
                           value="<?php echo esc_attr($val[ 'extra_price' ]); ?>">
                    <input type="hidden" name="extra_price[title][<?php echo esc_attr($val[ 'extra_name' ]); ?>]"
                           value="<?php echo esc_attr($val[ 'title' ]); ?>">
                </li>
            <?php } else { ?>
                <li class="item mt10">
                    <div class="st-flex space-between">
                        <span>
                            <span class="title-extra">
                                <?php echo esc_html($val['title']); ?>
                            </span>
                            <span class="extra-price-item">
                                <?php echo TravelHelper::format_money( $val[ 'extra_price' ] ) ?>
                            </span>
                        </span>
                        <div class="select-wrapper" style="width: 88px;">
                            <?php
                                $max_item = intval( $val[ 'extra_max_number' ] );
                                if ( $max_item <= 0 ) $max_item = 1;
                                $start_i = 0;
                                if ( isset( $val[ 'extra_required' ] ) ) {
                                    if ( $val[ 'extra_required' ] == 'on' ) {
                                        $start_i = 1;
                                    }
                                }
                            ?>
                            <div class="caculator-item">
                                <i class="fa fa-minus"></i>
                                <input type="number" class="form-control app extra-service-select"
                                    name="extra_price[value][<?php echo esc_attr($val[ 'extra_name' ]); ?>]"
                                    id="field-<?php echo esc_attr($val[ 'extra_name' ]); ?>"
                                    data-extra-price="<?php echo esc_attr($val[ 'extra_price' ]); ?>" step= "1" value="<?php echo intval($start_i)?>" min="<?php echo intval($start_i)?>" max="<?php echo intval($max_item)?>">
                                <i class="fa fa-plus"></i>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="extra_price[price][<?php echo esc_attr($val[ 'extra_name' ]); ?>]"
                           value="<?php echo esc_attr($val[ 'extra_price' ]); ?>">
                    <input type="hidden" name="extra_price[title][<?php echo esc_attr($val[ 'extra_name' ]); ?>]"
                           value="<?php echo esc_attr($val[ 'title' ]); ?>">
                </li>
            <?php } ?>
        <?php endforeach; ?>
    </ul>
</div>
