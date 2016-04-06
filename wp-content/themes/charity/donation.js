jQuery(document).ready(function () { 
  countDonation();
  rulesField();
  
  jQuery(".radio-submit").each(function(){
    jQuery(this).click(function(){
      jQuery(".radio-submit").removeAttr('checked');
      jQuery(this).attr('checked', true);
      checkPaymentMethod();
    });
  });
});

function checkPaymentMethod() {
  if (jQuery(".ideal-radio").is(':checked')) {
    jQuery(".step-next").hide();
    jQuery("#ideal").fadeIn();
  }

  if (jQuery(".creditcard-radio").is(':checked')) {
    jQuery(".step-next").hide();
    jQuery("#creditcard").fadeIn();
  }

  if (jQuery(".paypall-radio").is(':checked')) {
    jQuery(".step-next").hide();
    jQuery("#paypall").fadeIn();
  }

  if (jQuery(".autodebit-radio").is(':checked')) {
    jQuery(".step-next").hide();
    jQuery("#autodebit").fadeIn();
  }
}

function countDonation() {
  jQuery(".submit-general-donation").click(function(){
    var amount  = jQuery("#donationamount").val();
    var period  = jQuery("#period").val();
    var amounts = jQuery("#amount-single").val();

    if (amount && period) {
      jQuery(".sub-total").html("€" + amount + ".00");
      jQuery(".form_donation_complete").fadeIn();
    } else if (amounts) {
      jQuery(".sub-total").html("€" + amounts);
      jQuery(".form_donation_complete").fadeIn();
    }
  });
}

function rulesField() {
  jQuery('.form-group').on('keydown', '#donationamount', function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});
}