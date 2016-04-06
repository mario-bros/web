<?php
$daye = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15);
$monthe = array(1,2,3,4,5,6,7,8,9,10,11,12);
$yeare = array(1,2,3,4,5);
 global $wpdb;
 $choicedata = $wpdb->get_row("select * from {$wpdb->prefix}easypay_recurring ");
 //print_r($choicedata->choice3);
// Update
if (isset($_POST['rec'])): 
      @extract($_POST);
       global $wpdb;
       if(count($choicedata)>0){
        $query = $wpdb->query("update {$wpdb->prefix}easypay_recurring SET choice1 = '".$choice1."',choice2 = '".$choice2."',choice3 = '".$choice3."' ");
         //header( "refresh:5;" );
       }else{
        $query = $wpdb->query("insert into {$wpdb->prefix}easypay_recurring SET choice1 = '".$choice1."',choice2 = '".$choice2."',choice3 = '".$choice3."' ");
         //header( "refresh:5;" );
   }
        ?>
    <div id="message" class="updated below-h2"><p><?php _e('Settings updated successfully.', 'easypay') ?></p></div>      
<?php elseif (isset($_POST['rec'])): ?>
    <div id="message" class="error below-h2"><p><?php _e("There's nothing to update.", 'easypay') ?></p></div>
<?php endif; ?>
<div class="postbox">
    <?php
    // Settings
    $wppd_rec_options = get_option('easypay_rec');
   // $choice1 = isset($wppd_options['choice1']) ? $wppd_options['choice1'] : '';
   // $choice2 = isset($wppd_options['choice2']) ? $wppd_options['choice2'] : '';
   // $choice3 = isset($wppd_options['choice3']) ? $wppd_options['choice3'] : '';    
    ?>
    <div class="inside">
        <h4><?php _e('Recurring Settings', 'easypay'); ?></h4>
        <form action="" method="post">
            <table class="form-table">
                <tbody>
                   

                    <tr>
                        <td><?php _e('Payment duration choice 1:', 'easypay'); ?></td>
                        <td>
                            <select name="choice1">
                                <?php foreach($daye as $d){?>
                                <option value="<?php echo $d;?>,D" <?php if($choicedata->choice1 == $d.',D'){?> selected <?php }?>><?php echo $d;?> Day</option>
                                <?php }?>
                                <?php //echo $this->wppd_get_pages_option($easypay_success_url); ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="1"></td>
                        <td><span class="wpscsmall description"><?php _e('Select recurring payments first duration choice.', 'easypay'); ?>  </span>
                        </td>
                    </tr>

                    <tr>
                        <td><?php _e('Payment duration choice 2:', 'easypay'); ?></td>
                        <td>

                            <select name="choice2">
                                <?php foreach($monthe as $m){?>
                                <option value="<?php echo $m;?>,M" <?php if($choicedata->choice2 == $m.',M'){?> selected <?php }?>><?php echo $m;?> Month</option>
                                <?php }?>
                                <?php //echo $this->wppd_get_pages_option($easypay_fail_url); ?>
                            </select>

                        </td>
                    </tr>
                    <tr>
                        <td colspan="1"></td>
                        <td><span class="wpscsmall description"><?php _e('Select recurring payments second duration choice.', 'easypay'); ?>  </span>
                        </td>
                    </tr>
                    <tr>
                        <td><?php _e('Payment duration choice 3:', 'easypay'); ?></td>
                        <td>

                            <select name="choice3">
                                <option value="1,M" <?php if($choicedata->choice3 == '1,M'){?> selected <?php }?>>1 Month</option>
                                <option value="2,M" <?php if($choicedata->choice3 == '2,M'){?> selected <?php }?>>2 Month</option>
                                <option value="3,M" <?php if($choicedata->choice3 == '3,M'){?> selected <?php }?>>3 Month</option>
                                <option value="4,M" <?php if($choicedata->choice3 == '4,M'){?> selected <?php }?>>4 Month</option>
                                <option value="5,M" <?php if($choicedata->choice3 == '5,M'){?> selected <?php }?>>5 Month</option>
                                <option value="6,M" <?php if($choicedata->choice3 == '6,M'){?> selected <?php }?>>6 Month</option>
                                <option value="7,M" <?php if($choicedata->choice3 == '7,M'){?> selected <?php }?>>7 Month</option>
                                <option value="8,M" <?php if($choicedata->choice3 == '8,M'){?> selected <?php }?>>8 Month</option>
                                <option value="9,M" <?php if($choicedata->choice3 == '9,M'){?> selected <?php }?>>9 Month</option>

                                <?php foreach($yeare as $y){?>
                                <option value="<?php echo $y;?>,Y" <?php if($choicedata->choice3 == $y.',Y'){?> selected <?php }?>><?php echo $y;?> Year</option>
                                <?php }?>
                                <?php //echo $this->wppd_get_pages_option($easypay_retry_url); ?>
                            </select>

                        </td>
                    </tr>
                    <tr>
                        <td colspan="1"></td>
                        <td><span class="wpscsmall description"><?php _e('Select recurring payments third duration choice.', 'easypay'); ?>  </span>
                        </td>
                    </tr>
                   

                    <tr class="update_gateway">
                        <td colspan="2">
                            <div class="submit">
                                <input type="submit" class="button button-primary button-hero" value="<?php _e('Update Settings', 'easypay'); ?>" name="rec">
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </form>
    </div>
</div>
