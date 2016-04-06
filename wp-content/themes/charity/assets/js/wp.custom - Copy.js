/**
 * Charity
 * @version charity 1.0
 */
(function($){

    /**
     * Comment form
     */
    if($("#commentform").length> 0){
        
        $("form#commentform" ).wrapInner('<div class="row" />');
        $commentClass=($("form#commentform p" ).hasClass("logged-in-as")) ? "col-xs-12" : "col-xs-12 col-sm-6"; 
        $("#commentform div.charity-comment-fields").addClass($commentClass);
        $("#commentform p.form-submit").wrap('<div class="'+$commentClass+'" />');
        
        if($("#commentform p.logged-in-as").length> 0){
            $("#commentform p.logged-in-as").wrap('<div class="'+$commentClass+'" />');
        }
        
        $("#commentform").submit(function() {
            var $cmtFlag = true;
            $("#email, #comment ,#author").removeClass('charity-required');
            if ($('#email').length > 0) {
                var $emailRgx = /^([a-zA-Z.0-9])+@([a-zA_Z0-9])+\.([a-zA-Z])/;
                var $cmauthor = $("#author").val();
                var $cmemail = $("#email").val();
                if ($cmauthor == "") {
                    $("#author").addClass('charity-required');
                    $cmtFlag = false;
                }
                if ($cmemail == "" || !$emailRgx.test($cmemail)) {
                    $("#email").addClass('charity-required');
                    $cmtFlag = false;
                }
            }
            var $cmmsg = $("#comment").val();
            if ($cmmsg == "") {
                $("#comment").addClass('charity-required');
                $cmtFlag = false;
            }
            return $cmtFlag;
        });        
        
        
    }
    
    /**
     *Search Form
     */
    
    if($("form.search-form").length>0){
        $("form.search-form").submit(function() {
            var $serchFlag = true;
            $("form.search-form input[name=s]").removeClass('charity-required');
            var $serchmsg = $("form.search-form input[name=s]").val();
            if ($serchmsg == "") {
                $("form.search-form input[name=s]").addClass('charity-required');
                $serchFlag = false;
            }
            return $serchFlag;
        });
    }
    
    if($("form.searchform").length>0){
        $("form.searchform").submit(function() {
            var $serchFlag = true;
            $("form.searchform input[name=s]").removeClass('charity-required');
            var $serchmsg = $("form.searchform input[name=s]").val();
            if ($serchmsg == "") {
                $("form.searchform input[name=s]").addClass('charity-required');
                $serchFlag = false;
            }
            return $serchFlag;
        });
    }
    
    /**
     * Causes donation
     */
    
     $(document).on("click", ".charity-donation-button", function(){
	$(".charity-donation-window").html("");
        var $itemID=$(this).data("id");
		
        var $data={
            action: 'donation_window', 
            itemID: $itemID
        };
		$(document).ajaxStart( function(){
            $(".charity-donation-window").addClass("window-ajaxloader");
        });
        
        $(document).ajaxStop( function(){
            $(".charity-donation-window").removeClass("window-ajaxloader");
        });
        
        $.post(charity.ajaxURL, $data, function(msg){
            $(".charity-donation-window").html(msg);
        });
		
    });
	
    /**
	 * Comment List 
	 */
    if($('#comment-nav-below').length > 0 ){
        if($('.nav-previous a').length == 0 ){
            $('.nav-previous').removeClass('btn btn-default btn-sm');
        }
        if($('.nav-next a').length == 0 ){
            $('.nav-next').removeClass('btn btn-default btn-sm pull-right');
        }
    }
	
    /**
	 * Coming Soon
	 */
    //var charityCountdown = new Date();
    var charityCountdown = new Date(charityCustom.countdown);
    $("#charityCountdown").countdown({ 
        until: charityCountdown,
        format: 'DHMS'
    }); 


    /**
	 *Show on shoplanding page 
	 */
    var owl = $(".our-product-section .products");
	  
    if(owl.length > 0){ 
        owl.owlCarousel({    
            itemsCustom : [
            [0, 1],
            [450, 1],
            [600, 2],
            [768, 3],
            [1000, 4]
            ],
            navigation : false 
        });	 
    }

    /**
	 *Footer widgets margin, padding 
	 */
	
    if($("#footer").length>0){
		
        var $footerWidgetHTML=$("#footer .container:first").find(".row .col-xs-12").text().trim();
        if($footerWidgetHTML.length == 0) {
            $("#footer").addClass("footer-without-widgets");
        }
    }
	
	
    
})(jQuery);

/*
jQuery(document).ready(function() {
	
	  var owl = jQuery(".our-product-section .products"); 
	  owl.owlCarousel({    
	      itemsCustom : [
	        [0, 1],
	        [450, 1],
	        [600, 2],
	        [768, 3],
	        [1000, 4]
	      ],
	      navigation : false 
	  });	 
	});
*/