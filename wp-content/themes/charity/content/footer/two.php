<?php
/**
 * Charity footer two
 * @package charity
 * @version     v.1.0
 * 
 */
?><footer id="footer" class="second-footer">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-3">  
            	<?php if ( is_active_sidebar( 'footer-two-sidebar-one' ) ): ?>       	
            	<?php dynamic_sidebar("footer-two-sidebar-one"); ?>
            	<?php endif; ?>
            </div>
            <div class="col-xs-12 col-sm-2 twitter-update footer-nav">  
            	<?php if ( is_active_sidebar( 'footer-two-sidebar-two' ) ): ?>         	
            	<?php dynamic_sidebar("footer-two-sidebar-two"); ?> 
            	<?php endif; ?>   	
            </div>
            <div class="col-xs-12 col-sm-4 flickr">        	
            	<?php if ( is_active_sidebar( 'footer-two-sidebar-three' ) ): ?>
            	<?php dynamic_sidebar("footer-two-sidebar-three"); ?>
            	<?php endif; ?>
            </div>
            <div class="col-xs-12 col-sm-3 newsletter-social-icon">    
            	<?php if ( is_active_sidebar( 'footer-two-sidebar-four' ) ): ?>      	
            	<?php dynamic_sidebar("footer-two-sidebar-four"); ?>   
            	<?php endif; ?>       	
            </div>
        </div>
    </div>
    <div class="copyright">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <span><?php print(vp_option('vpt_option.copy_right')); ?></span>
                </div>
            </div>
        </div>
    </div>
</footer>
