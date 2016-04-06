<?php
/**
 * Single Product Image
 *
 * @author 		Varun Sridharan
 * @package 	WC Quick Donation/Templates
 * @version     0.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $donate;
?>

<script type="text/javascript">
function showrecurring() {
jQuery('.recurring').show();	
}
function hiderecurring() {
jQuery('.recurring').hide();	
}
</script>

<form method="post" action="/checkout/" class="wppd-form">
<input type="hidden" name="give-form-id" value="<?php echo $donate['formid']; ?>" />
<input type="hidden" name="give-form-title" value="<?php echo $donate['formtitel']; ?>" />	
<fieldset><div class="form-group component">
	
	
					
					
           <label class="col-lg-4 control-label">Payment Type</label>
					<div class="input-block col-md-5">
					<label class="radio-inline" for="recurring"><input name="paymenttype" id="paymenttype-recurring" value="recurring" maxlength="255" onclick="showrecurring();" type="radio">Recurring</label>
					<label class="radio-inline" for="normal"><input name="paymenttype" id="paymenttype-onetime" value="onetime" maxlength="255" onclick="hiderecurring();" checked="checked"  type="radio">One Time Donation</label>					
            		</div>
					<input name="itemname" id="itemname" value="301" type="hidden">
        </div>
		<div class="form-group component recurring" style="display:none;">
		<p class="note">Note: Recurring payments are only available for credit card payments</p>
            <label class="col-lg-4 control-label">Recurring period *</label>
			
			<div class="input-block col-md-5">			
			
			<label class="radio-inline" for="monthly"><input name="recur_period" id="paymenttype-monthly" value="monthly" maxlength="255" type="radio">Monthly</label>
			<label class="radio-inline" for="daily"><input name="recur_period" id="paymenttype-yearly" value="yearly" maxlength="255" type="radio">Yearly</label>
			</div>
		</div>
		<div class="form-group component">
            <label class="col-lg-4 control-label">Amount (donation) &nbsp;EUR *</label><div class="input-block col-md-5"><input class="form-control" name="give-amount" id="give-amount" placeholder="0.00" value="" maxlength="10" required="" type="text">
                    
                </div>
        </div>
<!--		<div class="form-group component">
            <label class="col-lg-4 control-label">Email *</label>
                <div class="input-block col-md-5">
                    <input name="amount" id="amount" value="" type="hidden">
                    <input id="email" maxlength="255" class="form-control" name="email" placeholder="Email" value="" required="" type="email"></div>
        </div></fieldset><fieldset id="builder-fields">

<div class="form-group">
  <label class="col-md-4 control-label" for="name">Name</label>  
  <div class="col-md-5">
  <input id="name" name="name" placeholder="" class="form-control input-md" required="" type="text">
    
  </div>
</div>


<div class="form-group">
  <label class="col-md-4 control-label" for="lastname">Last Name</label>  
  <div class="col-md-5">
  <input id="lastname" name="lastname" placeholder="" class="form-control input-md" required="" type="text">
    
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="phone">Phone</label>  
  <div class="col-md-5">
  <input id="phone" name="phone" placeholder="" class="form-control input-md" required="" type="text">
    
  </div>
</div>


<div class="form-group">
  <label class="col-md-4 control-label" for="address">Address</label>
  <div class="col-md-4">                     
    <textarea class="form-control" id="address" name="address"></textarea>
  </div>
</div>


<div class="form-group">
  <label class="col-md-4 control-label" for="additional">Additional Note</label>
  <div class="col-md-4">                     
    <textarea class="form-control" id="additional" name="additional"></textarea>
  </div>
</div>-->

</fieldset><fieldset><legend>&nbsp;</legend><div class="form-group component">
		  		<label class="control-label col-lg-4">&nbsp;</label>
				<div class="input-block col-lg-8">
				<!--image id="loadingTxt" style="visibility: hidden;" src="http://cms.thesparxitsolutions.com/cms_charitytheme/wp-content/plugins/easypay/public/images/icons/loading.GIF"/--->
				<input type="submit" class="pull-right btn btn-donatie" name="donation_add" value="Donate Now"/>
				</div></div></fieldset>	
					
	
					
						
						
					
						
					</div>

				</div>

</form> 

