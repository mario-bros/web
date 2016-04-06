/**
 * All of the code for your public-facing JavaScript source
 * should reside in this file.
 */

(function($) {
    "use strict";
    $(function() {

        /*------------------------------------------------*
         *  Calculating paypal charges on keyup event
         * -----------------------------------------------*/

        // Timer for the timeout
        var amount = '';
        var initial;
        var gateway_fee = wppd_display.paypal_fee;
        $(document).on('keyup blur change', '#actualamount, #amount', function() {
            if (amount !== jQuery('#actualamount').val()) {
                jQuery('#payFees').html('<img class="wppd_form_loader" src="' + wppd_display.loader_url + '" alt="loading..."  />');
                clearTimeout(initial);
                initial = window.setTimeout(function() {

                    var response = calculateAmount(amount, gateway_fee);

                    amount = response.new_amount;
                    gateway_fee = response.new_fee;


                }, 1500);
                return false;
            }
        });
        
        //====Instanse Payment =========
        
        var CharityCreditCard={
            init: function(button){
                //	alert("ccc");                    
                //alert($(button).parents("form").serialize()+"&action=aaaaaaaaaaaaaaaa");
                // this.loader();
                this.sendForm(button);
                
            },
        		
            sendForm: function(button){
                var self=this;
                $.ajax({
                    type: "POST",
                    url: wppd_display.ajaxurl,
                    data: $(button).parents("form").serialize()+"&action=easyCreditSystem",
                    success: function(msg) {
                                         
                        try{
                            var obj=JSON.parse(msg);
                            if(obj.msg == "success"){
                                self.redirect(obj.redirect_url);
                            }
                        }   
                        catch(e){
                            $("#creditCardError").html(msg);
                        //$("#submit-ccard").after("<div id='creditCardError'>"+msg+"</div>");
                        }
                    }
                });
	
            },
            loader: function(){
                $("#creditCardError").remove();
                $("#submit-ccard").after("<div id='creditCardError'></div>");
                $(document).ajaxStart( function(){
                    $("#creditCardError").html("Loading ....");
                });
                $(document).ajaxStop( function(){
                    
                    });
            },
                        
            redirect: function($location){
                window.location.href=$location;                           
            }                               
        };
              
        //=============================
             
        $(document).on("click", ".wppd-form #payDirectSubmit", function(){       	
          
            var self=this;	
            //var validationSuccess=false;
        	
            var isCreditCard=$(".wppd-form input[name='gateway']:checked").val();
            //console.log(isCreditCard);
            // =============================
            if(isCreditCard == "ccard"){
                $("#submit-stripe").find("input").removeAttr("required");
                $("#submit-stripe").find("select").removeAttr("required");
                $("#submit-ccard").find("input").attr("required", "");
                $("#submit-ccard").find("select").attr("required", "");
            }
            else if(isCreditCard == "stripe"){
                $("#submit-stripe").find("input").attr("required", "");
                $("#submit-stripe").find("select").attr("required", "");
                $("#submit-ccard").find("input").removeAttr("required");
                $("#submit-ccard").find("select").removeAttr("required");
            }
            else{
                $("#submit-stripe").find("input").removeAttr("required");
                $("#submit-stripe").find("select").removeAttr("required");
                $("#submit-ccard").find("input").removeAttr("required");
                $("#submit-ccard").find("select").removeAttr("required");
            }
            //$("#card_holder_name, #card_number, #expiry_month, ").removeAttr("required");
            	
            //	=======================
            
            
            
            
            
            $('.donate-form .wppd-form').validate({
                highlight: function(element) {
                    //validationSuccess=true;
                    $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
                },
                unhighlight: function(element) {
                    $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
                },
                errorElement: 'span',
                errorClass: 'help-block',
                errorPlacement: function(error, element) {
                    if (element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } else {
                        error.insertAfter(element);
                    }
                },
                rules: {
                    actualamount: {
                        required: true,
                        number: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                },
                submitHandler: function(form) {
                    //alert("aaaaaaaaaaaa");
                    //return false;
                    ///==================
                    if(isCreditCard == "ccard"){
                        CharityCreditCard.init(self); //CreditCard
                        return false;
                    }
                    else if(isCreditCard == "stripe"){
                        jQuery("#stripe-error").remove();
                        jQuery("#submit-stripe").append("<div id='stripe-error'>Loading ... </div>");

                        // createToken returns immediately - the supplied callback submits the form if there are no errors
                        Stripe.card.createToken({
                            number: $('#cardno_stripe').val(),
                            cvc: $('#cardcvv_stripe').val(),
                            name: $('#name').val(),
                            address_line1: $('#address').val(),
                            exp_month : $('#expmonth_stripe').val(),
                            exp_year : $('#expyear_stripe').val(),
                        }, stripeResponseHandler);
                        // alert("aaaaaaa");
                        return false; // submit from callback
                    }
                    else{
                        form.submit();
                    }
                    return false;
                }
                
            });
            
        //alert(validationSuccess);
        // return false;
            
        /*** if(isCreditCard == "ccard"){
                //alert("bb");
                CharityCreditCard.init(self); //CreditCard
                return false;
            }
            else if(isCreditCard == "stripe"){
            	
            	// createToken returns immediately - the supplied callback submits the form if there are no errors
                Stripe.card.createToken({
                    number: $('#cardno_stripe').val(),
                    cvc: $('#cardcvv_stripe').val(),
		            name: $('#name').val(),
		            address_line1: $('#address').val(),
		            exp_month : $('#expmonth_stripe').val(),
		            exp_year : $('#expyear_stripe').val(),
                }, stripeResponseHandler);
               // alert("aaaaaaa");
                return false; // submit from callback
            }***/
        });
                                

        $('#builder-fields input').each(function() {
            $(this).attr('maxlength', '255');
        });

        $('#builder-fields textarea').each(function() {
            $(this).attr('maxlength', '2000');
        })

        $('form.wppd-form input, #submit-ccard input[name="card_holder_name"]').not('form.wppd-form input[type="radio"], form.wppd-form input[type="checkbox"]').on('keyup change blur', function() {
            $(this).val(remove_special_chars($(this).val()));
        });

        // onload
        var amountentered = 0;
        amountentered = $.trim($('#actualamount').val());
        if (amountentered.length > 0) {
            calculateAmount(amount, gateway_fee);
        }

        /**
     * Toggle Payment Gateways
     */

        $(document).on('change', 'input[name="gateway"]', function() {      	
            var $type = $(this).val();
            $('.gateway-form').hide();
            $('#submit-' + $type).show('slow');

            var response = calculateAmount(amount, $(this).data('fee'));

            amount = response.new_amount;
            gateway_fee = response.new_fee;

        });
        
        //if($("input[name='paymenttype']").length>0){
        //$(".time-period").hide();
        $(document).on('change', "input[name='paymenttype']", function() {
            //$("#paymenttype-recurring").click( function(){
            var $payType=$(this).val();
            if($payType == "recurring"){
                $(".time-period").show();
            }
            else{
                $(".time-period").hide();
            }
            
        });
                
        /*if($("input[name='paymenttype']").is(':checked')){
           
                //$(".time-period").show();
                var $payType=$(this).val();
                if($payType == "recurring"){
                    $(".time-period").show();
                }
                else{
                    $(".time-period").hide();
                }           
            }
            else{
                $(".time-period").hide();
           
            }*/

        //}
        /*$(document).on('ready', function() {
        	//$(".gateway-form:not(:eq(0))").hide();
        	//$(".gateway-form").hide();
        });*/

        /**
     * Toggle amount based on choices
     */

        $('input[name="actualamount_option"]').on('change', function() {
            $('#actualamount').val($(this).val());
            $('#actualamount').trigger('change');
        });

        /**
     * Avoid cut, copy, paste on credit card values
     */
        $(document).on("cut copy paste", '#submit-ccard input#card_number', function(e){
            //$('#submit-ccard input').bind("cut copy paste", function(e) {
            e.preventDefault();
        });
        
    /*  $(document).on("click", "#payDirectSubmit", function(e){
        	var isCreditCard=$(".wppd-form input[name='paymenttype']:checked").val();
        	alert("aaaa"+isCreditCard);
        	if(isCreditCard == "ccard"){
        		alert("aaaaaaa");
        		
        		 e.preventDefault();
        		 return false;
        	}
        });**/
    });
      
    // this identifies your website in the createToken call below
    Stripe.setPublishableKey(wppd_display.stripe_pk_key);
           
    // Disable stripe getway      
    $(document).on("click", "#paymenttype-recurring, #paymenttype-onetime", function(){               	
        var paymentType=$(".wppd-form input[name='paymenttype']:checked").val();    
                
        if(paymentType == 'recurring'){
            $('#stripe').parents(".radio").hide();
            $("#submit-stripe").hide();
        }
        else{
            $('#stripe').parents(".radio").show();
            $("#submit-stripe").show();
        }                
    });
            
})(jQuery);

/**
* 
* @param {string} text
* @returns {unresolved}
*/

function stripeResponseHandler(status, response) {
    if (response.error) {
        // re-enable the submit button
        jQuery('#payDirectSubmit').removeAttr("disabled");
        // show hidden div
        //document.getElementById('a_x200').style.display = 'block';
        // show the errors on the form
        jQuery("#stripe-error").remove();
        jQuery("#submit-stripe").append("<div id='stripe-error'><div class='alert alert-danger' role='alert'>"+response.error.message+"</div></div>");
    // return false;
    //alert(response.error.message);
    //jQuery(".payment-errors").html(response.error.message);
    } else {
    	
        jQuery('#loadingTxt').css('visibility', 'visible');
        jQuery.ajax({
            type: "POST",
            url: wppd_display.ajaxurl,
            data:jQuery("form.wppd-form").serialize()+"&action=easyStripPayment&stripeToken="+response['id'],
            success: function(msg) {
                alert(msg);
                try{
                    var obj=JSON.parse(msg);
                    if(obj.msg == "success"){
                        window.location.href=obj.redirect_url;
                    }
                }   
                catch(e){
                    $("#creditCardError").html(msg);
                } 

            }
        });

    }
}


function remove_special_chars(text) {
    // Don't validate the input if below arrow, delete and backspace keys were pressed 
    // @TODO Remove undefined key error for radio & checkboxes
    if (key == 37 || key == 38 || key == 39 || key == 40 || key == 8 || key == 46) { // Left / Up / Right / Down Arrow, Backspace, Delete keys
        return text;
    } else {
        return text.replace(/[^\w\s.@-_]/gi, '');
    }
}

/*-------------------------------------*
* Function to calculate paypal charges
*------------------------------------*/

function calculatePaypalTax(amount, fee) {

    var tax = 0;
    tax = (amount * fee) / 100;
    //tax = tax + 0.30;
    return tax;
}

/*-------------------------------------------*
*	Function to round off the float values
*-------------------------------------------*/


function format_number(amount) {
	
    var i = parseFloat(amount);
    if (isNaN(i)) {
        i = 0.00;
    }
    var minus = '';
    if (i < 0) {
        minus = '-';
    }
    i = Math.abs(i);
    i = parseInt((i + 0.005) * 100);
    i = i / 100;
    var s = String(i);
    if (s.indexOf('.') < 0) {
        s += '.00';
    }
    if (s.indexOf('.') == (s.length - 2)) {
        s += '0';
    }
    s = minus + s;
    return s;
}

/*----------------------------------------------------*
* 	Function to display total payable amount
*----------------------------------------------------*/

function calculateAmount(amount, fee) {

    var totalamout = jQuery('#actualamount').val();
    jQuery("#actualamountInfo").text('');
    var tax = 0;
    totalamout = Number(totalamout);
    tax = calculatePaypalTax(totalamout, fee);
    totalamout = format_number(totalamout + tax);
    tax = format_number(tax);
    jQuery('#payFees').html(tax);
    jQuery('#feesTotal').html(totalamout);
    jQuery('#amount').val(totalamout);
    return {
        'new_amount': jQuery('#actualamount').val(), 
        'new_fee': fee
    };

}