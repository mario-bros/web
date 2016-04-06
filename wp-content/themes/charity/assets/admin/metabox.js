/**
 * Metabox swicher
 * 
 * @version charity 1.0
 * 
 */   

(function($){
    
    var CharityPostFormatMetabox={
        init: function(){
            this.display();
            this.event();
        },
        
        event: function(){
          var $this=this;
          $("#post-formats-select").on("click", ".post-format:checked", function(){
            $this.display();
          });  
        },
        
        display: function(){
            this.resetBox();
            var $checked=$("#post-formats-select .post-format:checked").val();
            this.setBox($checked);
        },
        
        resetBox: function(){
            var $types=["audio", "video", "gallery" , "quote"];
            
            for(var $key=0; $key<$types.length; $key++){
                $("#post-body #"+$types[$key]+"_meta_metabox").hide();
            }
        },
        
        setBox: function($checked){
            if($checked){
                $("#post-body #"+$checked+"_meta_metabox").show();
            }
        }
        
    };
    
    if($('#post-formats-select .post-format').length> 0){
        CharityPostFormatMetabox.init();
    }
    
})(jQuery);