<?php
/**
 * Charity footer one
 * @package charity
 * @version     v.1.0
 * 
 */
?><footer id="footer" class="footer-one">
    
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-4">
            	<?php if ( is_active_sidebar( 'footer-one-sidebar-one' ) ): ?>
				<img src="/wp-content/uploads/2015/06/footer_logo.png" alt="Peduli Anak" class="footerlogo" width="250" height="89" />
                <?php dynamic_sidebar("footer-one-sidebar-one"); ?>
                <?php endif; ?>
               </div>
            <div class="col-xs-12 col-sm-4 twitter-update">
            	<?php if ( is_active_sidebar( 'footer-one-sidebar-two' ) ): ?>
                <?php dynamic_sidebar('footer-one-sidebar-two'); ?> 
                <?php endif; ?>
            </div>
            <div class="col-xs-12 col-sm-4 newsletter-social-icon">
            	<?php if ( is_active_sidebar( 'footer-one-sidebar-three' ) ): ?>
            	<?php dynamic_sidebar('footer-one-sidebar-three'); ?>
            	<?php endif; ?>
                <!-- <h6>Follow us</h6> 
                <ul class="social-icons">
                     
                </ul>-->
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



<?php 

