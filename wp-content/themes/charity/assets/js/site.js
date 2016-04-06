jQuery(function($) {"use strict";
	/*var Site = {
		initialized : false,
		initialize : function() {

			if (this.initialized)
				return;
			this.initialized = true;

			//this.build();
			// this.validation();
			//this.events();
		},
		build : function() {
			var revSlider;
			if ($(".banner-slider").length) {
				if ($('.banner-slider').revolution == undefined)
					revslider_showDoubleJqueryError('.banner-slider');
				else {
					revSlider = $('.banner-slider').revolution({
						delay : 9000,
						startheight : 600,
						startwidth : 1442,
						navigationType : "bullet",
						onHoverStop : "on",
						navigationVOffset : 40
					});
					//$('.rev_slider_wrapper').show();
				}
			}

		},
	};

	Site.initialize(); ***/
})

jQuery(window).ready(function($) {

	isMobile = navigator.userAgent.match(/(iPhone|iPod|Android|BlackBerry|iPad|IEMobile|Opera Mini)/);
        
        
    /**
     * Dropdown Menu
     */
    $('li.menu-item-has-children a[data-toggle="dropdown"]').on('click', function() {
        //if(!isMobile){
            var $elLink=$(this).attr("href");
            location.href = $elLink;
        //}
    });

        
	
	jQuery('img.svg').each(function(){
	        var $img = jQuery(this);
            var imgID = $img.attr('id');
            var imgClass = $img.attr('class');
            var imgURL = $img.attr('src');

            jQuery.get(imgURL, function(data) {
                // Get the SVG tag, ignore the rest
                var $svg = jQuery(data).find('svg');

                // Add replaced image's ID to the new SVG
                if(typeof imgID !== 'undefined') {
                    $svg = $svg.attr('id', imgID);
                }
                // Add replaced image's classes to the new SVG
                if(typeof imgClass !== 'undefined') {
                    $svg = $svg.attr('class', imgClass+' replaced-svg');
                }

                // Remove any invalid XML tags as per http://validator.w3.org
                $svg = $svg.removeAttr('xmlns:a');

                // Replace image with new SVG
                $img.replaceWith($svg);

            }, 'xml');

        });
	// var elemSvg = $('img.svg');
	// for (var i = 0; i < elemSvg.length; i++) {
		// var imgID = jQuery(elemSvg[i]).attr('id');
		// var imgClass = jQuery(elemSvg[i]).attr('class');
		// var imgURL = jQuery(elemSvg[i]).attr('src');
// 
		// jQuery.get(imgURL, function(data) {
			// // Get the SVG tag, ignore the rest
			// var $svg = jQuery(data).find('svg');
// 
			// // Add replaced image's ID to the new SVG
			// if ( typeof imgID !== 'undefined') {
				// $svg = $svg.attr('id', imgID);
			// }
			// // Add replaced image's classes to the new SVG
			// if ( typeof imgClass !== 'undefined') {
				// $svg = $svg.attr('class', imgClass + ' replaced-svg');
			// }
// 
			// // Remove any invalid XML tags as per http://validator.w3.org
			// $svg = $svg.removeAttr('xmlns:a');
// 
			// // Replace image with new SVG
			// jQuery(elemSvg[i]).replaceWith($svg);
// 
		// }, 'xml');
// 
	// }


	// Flexsliderfunction function
	$(window).load(function() {
		if ($('.flexslider').length) {

			$('.our-causes .flexslider').flexslider({
				animation : "slide",
				animationLoop : false,
				itemWidth : 360,
				itemMargin : 30,
				start : function(slider) {
					$('body').removeClass('loading');
				}
			});

			$('.testimonial .flexslider, .donation-holder .flexslider,.flex-slide.flexslider').flexslider({
				animation : "slide",
				animationLoop : false

			});
		}

	});

	// Accordion function

	$('#accordion .panel-title').click(function() {
		if ($(this).find('.fa-plus-circle').hasClass('fa-minus-circle')) {
			$(this).find('.fa-minus-circle').removeClass('fa-minus-circle');
		} else {

			$('#accordion .fa-minus-circle').removeClass('fa-minus-circle');
			$(this).find('.fa-plus-circle').addClass('fa-minus-circle');

		}

	})
	$('#accordion1 .panel-title').click(function() {
		if ($(this).find('.fa-plus-circle').hasClass('fa-minus-circle')) {
			$(this).find('.fa-minus-circle').removeClass('fa-minus-circle');
		} else {

			$('#accordion1 .fa-minus-circle').removeClass('fa-minus-circle');
			$(this).find('.fa-plus-circle').addClass('fa-minus-circle');

		}

	})
	if ($('#accordion .panel-heading').parents('.panel').find('.panel-collapse').hasClass('in')) {
		$('.in').parents('.panel').find('.collape-plus').addClass('fa-minus');

	}
	$('#accordion .panel-heading').click(function() {
		$('#accordion .fa-minus').removeClass('fa-minus');
		if ($(this).parents('.panel').find('.panel-collapse').hasClass('in')) {

		} else {
			$(this).find('.collape-plus').addClass('fa-minus');

		}
	})

	$('#accordion-right .panel-heading').click(function() {
		$('#accordion-right .fa-minus').removeClass('fa-minus');
		if ($(this).parents('.panel').find('.panel-collapse').hasClass('in')) {

		} else {
			$(this).find('.collape-plus').addClass('fa-minus');

		}
	})
	//Header Searh form
	if ($(window).width() >= 768) {

		$('.search-form button,.icon-search').click(function() {

			if ($('.header-second .form-group').css('width') == '0px') {

				$('.header-second .form-group').animate({
					width : '180px'
				});
				$('.header-second .form-group').addClass('bottom-line');
				$('.header-second nav>ul').fadeOut();

			} else {

				$('.header-second .form-group').animate({
					width : '0px'
				});

				$('.bottom-line').removeClass('bottom-line');

				$('.header-second nav>ul').fadeIn();
			}
		})
	}
	//Donate form button
	$('.btn-group *').click(function() {
		$('.btn-group button.active').removeClass('active');
		$(this).addClass('active')

	})
	$('.dropdown-menu a').click(function() {
		var donation_type = $(this).text();
		$('#dropdownMenu1 small').text(donation_type)

	})
	//EqualHeight Function
	
	// var highestBox = 0;
	// var elementObj = $('.equal-block');
// 	
	// for (var i = 0; i < elementObj.length; i++) {
		// if ($(elementObj[i]).height() > highestBox) {
			// highestBox = $(elementObj[i]).height();
		// }
	// }
	// $('.equal-block').height(highestBox);

	//=====
	// var highestBox_1 = 0;
	// var elementObj1 = $('.row .equal-box');
// 
// 	
	// for (var i = 0; i < elementObj1.length; i++) {
// 
		// if ($(elementObj1[i]).height() > highestBox_1) {
			// highestBox_1 = $(elementObj1[i]).height();
		// }
	// }
	// $('.equal-box ').height(highestBox_1);

	// Price Range Slider fucntion
	if ($("#slider-range").length) {
		$("#slider-range").slider({
			range : true,
			min : 0,
			max : 500,
			values : [75, 300],
			slide : function(event, ui) {
				$("#amount").val("$" + ui.values[0] + " - $" + ui.values[1]);
			}
		});
		$("#amount").val("$" + $("#slider-range").slider("values", 0) + " - $" + $("#slider-range").slider("values", 1));
	}
	//video-placeholder function
	$('.embed-responsive-16by9 img').click(function() {
		video = $(this).attr('data-video');
		$(this).after(video);

	});
	$('.play-btn').click(function() {
		video1 = $('.video-section img').attr('data-video');
		$('.video-section img').after(video1);
		return false;

	});

	if (!isMobile) {
		var animSection = function() {
			var elem = $('.anim-section');
			
			for (var i = 0; i < elem.length; i++) {
				if ($(window).scrollTop() > ($(elem[i]).offset().top - $(window).height() / 1.15)) {
					$(elem[i]).addClass('animate')
				}
			}

		}
		if ($('.anim-section').length) {
			animSection()
			$(window).scroll(function() {
				animSection()
			})
		}

		$(window).load(function() {
			if ($('.parallax').length) {
				var elem = $('.parallax');
				for (var i = 0; i < elem.length; i++) {
					parallax($(elem[i]), 0.1);
				}

			}
		});

		$(window).scroll(function() {
			if ($('.parallax').length) {
				var elem = $('.parallax');
				for (var i = 0; i < elem.length; i++) {
					parallax($(elem[i]), 0.1);
				}
			}
		})
		//Progressbar
		if ($('.progressbar').length) {
			$(window).scroll(function() {

				if ($(window).scrollTop() > ($('.progressbar').offset().top - $(window).height() / 1.4)) {

					var elem = $('.progressbar .progress');
					for (var i = 0; i < elem.length; i++) {
						var val = parseInt($(elem[i]).find('.progress-bar').attr('aria-valuenow'));
						$(elem[i]).find('.progress-bar').width(val + "%")
					}

				}
			})
		}

	} else {

		var elem = $('.progressbar .progress');
		for (var i = 0; i < elem.length; i++) {
			var val = parseInt($(elem[i]).find('.progress-bar').attr('aria-valuenow'));
			$(elem[i]).find('.progress-bar').width(val + "%")
		}
	}

	var parallax = function(id, val) {
		if ($(window).scrollTop() > id.offset().top - $(window).height() && $(window).scrollTop() < id.offset().top + id.outerHeight()) {
			var px = parseInt($(window).scrollTop() - (id.offset().top - $(window).height()))
			px *= -val;
			id.css({
				'background-position' : 'center ' + px + 'px'
			})
		}
	}
});

//Stickt Header Yes or No Activet Function

jQuery(window).scroll(function($) {
	//			fixedNav()
})
var initScroll = jQuery(window).scrollTop(), headerHeight = jQuery('#header').height();

function fixedNav() {
	currentScroll = $(window).scrollTop()
	function inteligent() {
		if (currentScroll >= initScroll) {
			$('#header').removeClass('down')
			$('#header').addClass('up')
			if (currentScroll == $(document).height() - $(window).height()) {
				$('#header').removeClass('up')
				$('#header').addClass('down')
			}
			initScroll = currentScroll
		} else {
			$('#header').removeClass('up')
			$('#header').addClass('down')
			initScroll = currentScroll
		}
	}

	if ($('#header').attr('data-sticky') == "yes") {
		if (currentScroll > $('#header').height()) {
			$('#header').addClass('fixed')
			$('#wrapper').css("padding-top", headerHeight)
			inteligent()
		} else {
			$('#header').removeClass('fixed up down')
			$('#wrapper').css("padding-top", "0")
		}
	} else {
		if (currentScroll > $('#header').height()) {
			$('#header').removeClass('fixed up down')
			$('#wrapper').css("padding-top", "0")
		} else {
			$('#header').removeClass('fixed up down')
			$('#wrapper').css("padding-top", "0")
		}
	}
}

// for what image
jQuery(document).ready(function() {

	jQuery('.option-info img').css("cursor", "help");
	jQuery('.option-info img').hover(function() {
		jQuery(this).prev().addClass('animate');
	}, function() {
		jQuery(this).parents('.option-info').hover(function() {/*mouse hover*/
		}, function() {
			jQuery(this).find('.des-text').removeClass('animate');
		});
	});

});

