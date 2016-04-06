<?php
/**
 * Charity footer three
 * @package charity
 * @version     v.1.0
 * 
 */
?><footer id="footer" class="footer-third">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-5">
            	<?php if ( is_active_sidebar( 'footer-three-sidebar-one' ) ): ?>
                <?php dynamic_sidebar("footer-three-sidebar-one"); ?>  
                <?php endif; ?>          
            </div>
            <div class="col-xs-12 col-sm-3 twitter-update">
            	<?php if ( is_active_sidebar( 'footer-three-sidebar-two' ) ): ?>
                <?php dynamic_sidebar("footer-three-sidebar-two"); ?>   
                <?php endif; ?>           
            </div>
            <div class="col-xs-12 col-sm-4 newsletter-social-icon">    
            	<?php if ( is_active_sidebar( 'footer-three-sidebar-three' ) ): ?>            
                <?php dynamic_sidebar("footer-three-sidebar-three"); ?> 
                <?php endif; ?>               
            </div>
        </div>
    </div>
    <div class="copyright copyright-alternate">
        <div class="container">
            <div class="row">
                <div class="col-xs-12"><span><?php print(vp_option('vpt_option.copy_right')); ?></span>
                </div>
            </div>
        </div>
    </div>
</footer>
<?php 