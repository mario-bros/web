/**
 * Media Upload Button
 */   

//jQuery(document).ready(function($){
(function($){
var AirDevShortcodeMedia = {
	    uiWrapper: null,
	    argsMedia: [],
	    init: function() {
	        this.argsMedia = {
	            title: 'AirDev - Insert a media',
	            library: {type: 'image'},
	            multiple: false,
	            button: {text: 'Insert'}
	        };
	
	        this.event();
	    },
	    event: function() {
	        var $_this = this;
	        $(document).on("click", ".ad-iu-btn", function() {
	        	//console.log("media start...");
	            $_this.uiWrapper = $(this).parents(".ad-iu-wrapper");
	            $_this.frameOpen();
                    return false;
	        });
	        
	        $(document).on("click", ".ad-iu-remove", function() {
	            $_this.removeThumb($(this));
	        });
	        
	    },
	    frameOpen: function() {
	        var $_this = this;
	        if (adMediaFrame) {
	            adMediaFrame.open();
	            return;
	        }
	        //console.log($_this.argsMedia);
	        adMediaFrame = wp.media($_this.argsMedia);
	        adMediaFrame.open();
	        this.insert();
	    },
	    insert: function() {
	        var $_this = this;
	        var imageURL, attachment;
	        adMediaFrame.on('select', function() {
	            attachment = adMediaFrame.state().get('selection').first().toJSON();
	            imageURL = attachment.url;
	            if (imageURL != "") {
	                $_this.addThumb(imageURL);
	            }
	        });
	    },
	    addThumb: function($url) {
	        this.uiWrapper.find(".ad-iu-image-wrapper").css({display: "block"});
	        this.uiWrapper.find(".ad-iu-image").attr("src", $url);
	        this.uiWrapper.find(".ad-iu-url").val($url);
	    }, 
	    
	    removeThumb: function($this){
	        var parent=$this.parents(".ad-iu-wrapper");
	        parent.find(".ad-iu-image-wrapper").css({display: "none"});
	        parent.find(".ad-iu-image").attr("src", "");
	        parent.find(".ad-iu-url").val("");
	    }
	};
	var adMediaFrame;
	AirDevShortcodeMedia.init();
//});
})(jQuery);