<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 6/21/2016
 * Time: 9:38 AM
 */
if( is_admin() ){
    $post_id = get_the_ID();
}else{
    $post_id = STInput::get('id','');
}
wp_enqueue_script('bulk-calendar' );
?>
<div id="form-bulk-edit">
    <div class="form-container">
        <?php if( is_admin() ): ?>
        <div class="overlay">
            <span class="spinner is-active"></span>
        </div>
    <?php else: ?>
        <div class="overlay-form" style="display: none;"><i class="fa fa-refresh text-color"></i></div>
    <?php endif; ?>
        <div class="form-title">
            <h3 class="clearfix"><?php echo __('Bulk Price Edit', ST_TEXTDOMAIN); ?>
                <button style="float: right;" type="button" id="calendar-bulk-close" class="button button-small btn btn-default btn-xs"><?php echo __('Close',ST_TEXTDOMAIN); ?></button>
            </h3>
        </div>
        <div class="form-content clearfix">
            <h4 style="margin-bottom: 20px;"><?php echo __('Choose Date:', ST_TEXTDOMAIN); ?></h4>
            <div class="form-group">
                <div class="form-title">
                    <h4 class=""><input type="checkbox" class="check-all" data-name="day-of-week"> <?php echo __('Days Of Week', ST_TEXTDOMAIN); ?></h4>
                </div>
                <div class="form-content">
                    <label class="block"><input type="checkbox" name="day-of-week[]" value="Sunday" style="margin-right: 5px;"><?php echo __('Sunday', ST_TEXTDOMAIN); ?></label>
                    <label class="block"><input type="checkbox" name="day-of-week[]" value="Monday" style="margin-right: 5px;"><?php echo __('Monday', ST_TEXTDOMAIN); ?></label>
                    <label class="block"><input type="checkbox" name="day-of-week[]" value="Tuesday" style="margin-right: 5px;"><?php echo __('Tuesday', ST_TEXTDOMAIN); ?></label>
                    <label class="block"><input type="checkbox" name="day-of-week[]" value="Wednesday" style="margin-right: 5px;"><?php echo __('Wednesday', ST_TEXTDOMAIN); ?></label>
                    <label class="block"><input type="checkbox" name="day-of-week[]" value="Thursday" style="margin-right: 5px;"><?php echo __('Thursday', ST_TEXTDOMAIN); ?></label>
                    <label class="block"><input type="checkbox" name="day-of-week[]" value="Friday" style="margin-right: 5px;"><?php echo __('Friday', ST_TEXTDOMAIN); ?></label>
                    <label class="block"><input type="checkbox" name="day-of-week[]" value="Saturday" style="margin-right: 5px;"><?php echo __('Saturday', ST_TEXTDOMAIN); ?></label>
                </div>
            </div>
            <div class="form-group">
                <div class="form-title">
                    <h4 class=""><input type="checkbox" class="check-all" data-name="day-of-month"> <?php echo __('Days Of Month', ST_TEXTDOMAIN); ?></h4>
                </div>
                <div class="form-content">
                    <?php for( $i = 1; $i <= 31; $i ++):
                        if( $i == 1){
                            echo '<div>';
                        }
                        ?>
                        <label style="width: 40px;"><input type="checkbox" name="day-of-month[]" value="<?php echo esc_html($i); ?>" style="margin-right: 5px;"><?php echo esc_html($i); ?></label>

                        <?php
                        if( $i != 1 && $i % 5 == 0 ) echo '</div><div>';
                        if( $i == 31 ) echo '</div>';
                        ?>

                    <?php endfor; ?>
                </div>
            </div>
            <div class="form-group">
                <div class="form-title">
                    <h4 class=""><input type="checkbox" class="check-all" data-name="months"> <?php echo __('Months', ST_TEXTDOMAIN); ?>(*)</h4>
                </div>
                <div class="form-content">
                    <?php
                    $months = array(
                        'January' => __('January', ST_TEXTDOMAIN),
                        'February' => __('February', ST_TEXTDOMAIN),
                        'March' => __('March', ST_TEXTDOMAIN),
                        'April' => __('April', ST_TEXTDOMAIN),
                        'May' => __('May', ST_TEXTDOMAIN),
                        'June' => __('June', ST_TEXTDOMAIN),
                        'July' => __('July', ST_TEXTDOMAIN),
                        'August' => __('August', ST_TEXTDOMAIN),
                        'September' => __('September', ST_TEXTDOMAIN),
                        'October' => __('October', ST_TEXTDOMAIN),
                        'November' => __('November', ST_TEXTDOMAIN),
                        'December' => __('December', ST_TEXTDOMAIN),
                    );
                    $i = 0;
                    foreach( $months as $key => $month ):
                        if( $i == 0 ){
                            echo '<div>';
                        }
                        ?>
                        <label style="width: 100px;"><input type="checkbox" name="months[]" value="<?php echo esc_html($key); ?>" style="margin-right: 5px;"><?php echo esc_html($month); ?></label>

                        <?php
                        if( $i != 0 && ($i + 1) % 2 == 0 ) echo '</div><div>';
                        if( $i + 1 == count( $months ) ) echo '</div>';
                        $i++;
                        ?>

                    <?php endforeach; ?>
                </div>
            </div>
            <div class="form-group">
                <div class="form-title">
                    <h4 class=""><input type="checkbox" class="check-all" data-name="years"> <?php echo __('Years', ST_TEXTDOMAIN); ?>(*)</h4>
                </div>
                <div class="form-content">
                    <?php
                    $year = date('Y');
                    $j = $year -1 ;
                    for( $i = $year; $i <= $year + 2; $i ++ ):
                        if( $i == $year ){
                            echo '<div>';
                        }
                        ?>
                        <label style="width: 100px;"><input type="checkbox" name="years[]" value="<?php echo esc_html($i); ?>" style="margin-right: 5px;"><?php echo esc_html($i); ?></label>

                        <?php
                        if( $i != $year && ($i == $j + 2 ) ) { echo '</div><div>'; $j = $i; }
                        if( $i == $year + 2 ) echo '</div>';
                        ?>

                    <?php endfor; ?>
                </div>
            </div>
        </div>
        <div class="form-content clearfix">
            <div style="margin-bottom: 15px">
                <label class="block"><span><strong><?php echo __('Price', ST_TEXTDOMAIN); ?>: </strong></span>
                <input type="text" value="0" class="form-control" name="price-bulk" id="price-bulk" placeholder="<?php echo __('Price', ST_TEXTDOMAIN); ?>"></label>
            </div>
            <div style="margin-bottom: 15px">
                <label class="block"><span><strong><?php echo __('Status', ST_TEXTDOMAIN); ?>: </strong></span></label>
                <select name="status" id="" class="form-control">
                    <option value="available"><?php echo __('Available', ST_TEXTDOMAIN); ?></option>
                    <option value="unavailable"><?php echo __('Unavailable', ST_TEXTDOMAIN); ?></option>
                </select>
            </div>
            <input type="hidden" name="post-id" value="<?php echo esc_html($post_id); ?>">
            <div class="form-message" style="margin-top: 20px;"></div>
        </div>
        <div class="form-footer">
            <button type="button" id="calendar-bulk-save" class="button button-primary button-large btn btn-primary btn-sm"><?php echo __('Save',ST_TEXTDOMAIN); ?></button><!--
								<button type="button" id="calendar-bulk-cancel" class="button button-large"><?php echo __('Cancel',ST_TEXTDOMAIN); ?></button> -->
        </div>
    </div>
</div>
