/**
 * Charity options
 *
 * @version charity 1.1.0
 * 
 */﻿

(function($){
    
    var CharityOptions={
        init: function(){
            var optionURL= charity.template_url + "/assets/charity-options/assets";
            this.loadSkin(optionURL);
            this.loadLess(optionURL);
            this.stickyHeader();
            
        },
        
        loadSkin: function(optionURL){
            $("head").append($('<link rel="stylesheet">').attr("href", optionURL + "/css/jquery-ui.css"));
            $("head").append($('<link rel="stylesheet/less">').attr("href", optionURL + "/css/skin.less"));
        },
        loadLess: function(optionURL){
            var self=this;
            $.getScript(optionURL + "/js/less.js", function(data, textStatus, jqxhr) {
                self.setLessValue();
            });
        },
        
        setLessValue: function(){
            less.modifyVars({
                skinColor : charity.color,
                fontFamily : charity.global_font
            }); 
        },
        
        stickyHeader: function(){
            var self=this;
            var sticky=charity.sticky_header;
            if (sticky == 'sticky-yes'){
                var headerHeight = $('#header').height();
                $('#header').addClass('fixed down');
                $('#wrapper').css("padding-top", headerHeight);
                
                self.isAdminBar(); 
                self.fixedNav();
            }
            
        },
        fixedNav: function() {

            var headerHeight = $('#header').height();
            var currentScroll = $(window).scrollTop();
            
            var prevScroll=0;
            var curScroll=0;
            var self=this;

            $(window).scroll(function() {

                currentScroll = $(window).scrollTop();
                curScroll=$(window).scrollTop();

                if(curScroll>prevScroll)
                {
                    if(currentScroll > headerHeight+50)
                    {

                        self.resetHeader('down','up' );
                    //$('#header').removeClass('down');
                    //$('#header').addClass('up');
                    }
                    if($(window).scrollTop() >=$(document).height()-$(window).height() ){

                        self.resetHeader('up', 'down' );
                    //$('#header').removeClass('up')
                    //$('#header').addClass('down')
                    }

                }
                else if(curScroll<prevScroll)
                {
                    self.resetHeader('up', 'down' );
                // $('#header').removeClass('up')
                // $('#header').addClass('down')
                }
                prevScroll=$(window).scrollTop();

            });
        },
        
        resetHeader: function($remove, $add){
            $('#header').removeClass($remove);
            $('#header').addClass($add);

        },
        
        isAdminBar: function(){
            //console.log("aaa");
            
            if($("body").hasClass("admin-bar")){
            //if($("body #wpadminbar").length> 0){
            //console.log("bbb");
                //var $adminbarheight=($("body #wpadminbar").height())? 32 : 0;
                 //console.log("bbb"+$adminbarheight);
                
                $("body #header.down").css({top: 32});
                
            }
        }
        
        
    };
    
    CharityOptions.init();
    
})(jQuery); 
