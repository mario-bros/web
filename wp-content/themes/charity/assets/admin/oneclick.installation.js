(function($) {
    //"use strict";
    
    if($("#dialogInstallationStart").length>0){

        var CharityOneClickInstall={
            threadInterval: null,
            init: function(){
                this.initInstallDialogAlert();
                this.initInstallDialog();
                this.showLogDialog();
                this.events();
            },
            initInstallDialogAlert: function(){
                
                $("#dialogInstallationAlert").dialog({
                    resizable: true,
                    autoOpen:false,
                    modal: false,
                    open: function() {
                        $(this).dialog("widget").find(".ui-dialog-titlebar").css("visibility", "hidden");
                        $(this).dialog("widget").find(".ui-dialog-buttonpane").css("border", "0px");
                    },
                    buttons: {
                        OK: function() {
                            $( this ).dialog( "close" );
                        }
                    }
                });
                
            },
            
            initInstallDialog: function(){
                var $this=this;
                $("#dialogInstallationStart").dialog({
                    resizable: true,
                    autoOpen:false,
                    modal: false,
                    open: function() {
                        $(this).dialog("widget").find(".ui-dialog-titlebar").css("visibility", "hidden");
                        $(this).dialog("widget").find(".ui-dialog-buttonpane").css("border", "0px");
                    },
                    buttons: {
                        No: function() {
                            $( this ).dialog( "close" );
                        },
                        Yes: function() {
                            $( this ).dialog( "close" );
                            $this.isYes();
                        }
                    }
                });
                
            },
            isYes: function() {
                this.ajax();
            },
            ajax: function() {
                var $this = this;
                //var $flag=1;
                this.ajaxInit();
                //URL: http://www.dave-bond.com/blog/2010/01/JQuery-ajax-progress-HMTL5/
                //URL: http://www.bennolan.com/2011/07/21/ajax-download-progress.html
                
                $.ajax({
                    type: 'post',
                    data: {
                        action: 'charity_oneclick'
                    },
                    url: charityAdmin.ajaxURL,
                    cache: false, 
                    dataType: "text",
                    xhr: function() {
                        return $this.xhrInit();
                    },
                    complete: function() {
                        return clearInterval($this.threadInterval);
                    },
                    success: function($html) {
                        $html = $html.replace(/<<(\d+)\>>/gi, "");
                        
                        $html2='<p>All done. Have fun!</p><p>Remember to update the passwords and roles of imported users.</p>';
                        $("#dialogInstallationLog").html($html2);
                       
                        $("#installationLog").removeClass("tm-btn-hide");
                        $this.showProgress("100");
                        window.location.href=charityAdmin.importSucess;
                    }
                });
            },
            xhrInit: function() {
                var $this = this;
                var xhr;
                xhr = jQuery.ajaxSettings.xhr();
                $this.threadInterval = setInterval(function() {
                    var response, status;
                    if (xhr.readyState > 2) {
                        response = xhr.responseText;
                        status = $this.getStatus(response);
                        $this.showProgress(status);
                        
                        
                    }
                }, 100);
                return xhr;
            },
            
            showLogDialog: function(){
                $("#dialogInstallationLog").dialog({
                    resizable: true,
                    autoOpen:false,
                    modal: false,
                    width: 700,
                    open: function() {
                        $(this).dialog("widget").find(".ui-dialog-titlebar").css("visibility", "hidden");
                        $(this).dialog("widget").find(".ui-dialog-buttonpane").css("border", "0px");
                    },
                    buttons: {
                        Close: function() {
                            $( this ).dialog( "close" );
                        }
                    }
                });
               
            },
            showProgress: function($point) {
                // console.log($point);
                $(".charity-progressbar-value").css("width", $point+"%");
                $(".charity-progress-label").html($point+"%");
                
                
                $(".tm-s-"+$point).removeClass("tm-hide");
                if($(".tm-e-"+$point).length > 0){
                    $(".tm-e-"+$point).find("i.fa").addClass('fa-check').removeClass('fa-spinner');
                    
                    if($point == "100"){
                        $(".tm-finish").find("i.fa").addClass('fa-check').removeClass('fa-spinner');
                    }
                }
                
            },
            getStatus: function($html) {
                var $matches, $status;
                $matches = $html.match(/<<(\d+)\>>/gi);
                $status = $matches[$matches.length - 1];
                $status = $status.replace('<<', '').replace('>>', '');
                return $status;
            },
            //<i class="fa fa-check text-success"></i>
            
            ajaxInit: function() {
                $(document).ajaxStart(function() {
                    $("#installationStart").addClass("tm-btn-hide");
                    $("#charity-progressbar").removeClass("tm-btn-hide");
                });
            },

            events: function() {
                var $this = this;
                $("#installationStart").on("click", function() {
                    if($("#setting-error-tgmpa").length> 0){
                        $("#dialogInstallationAlert").dialog("open");
                    }
                    else{
                        $("#dialogInstallationStart").dialog("open");
                    }
                });
                $("#installationLog").on("click", function() {
                    $("#dialogInstallationLog").dialog("open");
                });
                
                
            }        
        };
        
        
        CharityOneClickInstall.init();

    }

 
    
    
/*
    if ($("#btn-oneclick-install").length > 0) {




        var BBOneclickInstall = {
            threadInterval: null,
            init: function() {
                this.events();
            },
            isYes: function($_this) {
                $("#bb-modal-window").modal("hide");
                this.ajax();
                return false;
            },
            ajax: function() {
                var $this = this;
                //var $flag=1;
                this.ajaxInit();
                //URL: http://www.dave-bond.com/blog/2010/01/JQuery-ajax-progress-HMTL5/
                //URL: http://www.bennolan.com/2011/07/21/ajax-download-progress.html
                
                $.ajax({
                    type: 'post',
                    data: {
                        action: 'ad_oneclick_install'
                    },
                    url: adOneclick.url,
                    cache: false, 
                    dataType: "text",
                    xhr: function() {
                        return $this.xhrInit();
                    },
                    complete: function() {
                        return clearInterval($this.threadInterval);
                    },
                    success: function($html) {
                        $html = $html.replace(/<<(\d+)\>>/gi, "");
                        $(".bb-one-click-error-log").html($html);
                        $(".bb-import-log-btn").removeClass("bb-oneclick-visibility");
                        $this.showProgress("100");
                    }
                });
            },
            xhrInit: function() {
                var $this = this;
                var xhr;
                xhr = jQuery.ajaxSettings.xhr();
                $this.threadInterval = setInterval(function() {
                    var response, status;
                    if (xhr.readyState > 2) {
                        response = xhr.responseText;
                        status = $this.getStatus(response);
                        $this.showProgress(status);
                        
                    }
                }, 100);
                return xhr;
            },
            showProgress: function($point) {
                $(".bb-progress .progress-bar").css({
                    width: $point + "%"
                });
                $(".bb-progress .progress-bar").attr("aria-valuenow", $point);
                this.showProcess($point);
                $(".bb-progress .progress-bar").html($point + "%");
                
            },
            getStatus: function($html) {
                var $matches, $status;
                $matches = $html.match(/<<(\d+)\>>/gi);
                $status = $matches[$matches.length - 1];
                $status = $status.replace('<<', '').replace('>>', '');
                return $status;
            },
            //<i class="fa fa-check text-success"></i>
            showProcess: function($point){
                $(".bb-import-process").removeClass("bb-import-process-hide");
                $(".bb-start-import-"+$point).removeClass("bb-import-process-hide");
                
                if($(".bb-end-import-"+$point).length > 0){
                    $(".bb-end-import-"+$point).find("td.bb-process-icon").html('<i class="fa fa-check text-success"></i>');
                    
                    if($point == "100"){
                        $(".bb-finish-import").find("td.bb-process-icon").html('<i class="fa fa-check text-success"></i>');
                    }
                }
            },
            
            ajaxInit: function() {
                $(document).ajaxStart(function() {
                    $(".bb-progress").removeClass("bb-oneclick-visibility");
                    $(".bb-import-button").addClass("bb-oneclick-visibility");
                });
            },
            events: function() {
                var $this = this;
                $("#btn-oneclick-install").on("click", function() {
                    return $this.isYes($(this));
                });
            }
        };
        BBOneclickInstall.init();
    }*/
})(jQuery);