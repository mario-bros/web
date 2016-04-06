<?php
global $current_user, $wp_roles;
//get_currentuserinfo(); //deprecated since 3.1

/* Load the registration file. */
//require_once( ABSPATH . WPINC . '/registration.php' ); //deprecated since 3.1
$error = array();    
/* If profile was saved, update profile. */
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'update-user' ) {

    /* Update user password. */
    if ( !empty($_POST['pass1'] ) && !empty( $_POST['pass2'] ) ) {
        if ( $_POST['pass1'] == $_POST['pass2'] )
            wp_update_user( array( 'ID' => $current_user->ID, 'user_pass' => esc_attr( $_POST['pass1'] ) ) );
        else
            $error[] = __('The passwords you entered do not match.  Your password was not updated.', 'profile');
    }

    /* Update user information. */
    if ( !empty( $_POST['url'] ) )
        wp_update_user( array( 'ID' => $current_user->ID, 'user_url' => esc_url( $_POST['url'] ) ) );
    if ( !empty( $_POST['email'] ) ){
        if (!is_email(esc_attr( $_POST['email'] )))
            $error[] = __('The Email you entered is not valid.  please try again.', 'profile');
        elseif(email_exists(esc_attr( $_POST['email'] )) != $current_user->id )
            $error[] = __('This email is already used by another user.  try a different one.', 'profile');
        else{
            wp_update_user( array ('ID' => $current_user->ID, 'user_email' => esc_attr( $_POST['email'] )));
        }
    }

    if ( !empty( $_POST['first-name'] ) )
        update_user_meta( $current_user->ID, 'first_name', esc_attr( $_POST['first-name'] ) );
    if ( !empty( $_POST['last-name'] ) )
        update_user_meta($current_user->ID, 'last_name', esc_attr( $_POST['last-name'] ) );
    if ( !empty( $_POST['description'] ) )
        update_user_meta( $current_user->ID, 'description', esc_attr( $_POST['description'] ) );
		
    if ( !empty( $_POST['billing_city'] ) )
        update_user_meta( $current_user->ID, 'billing_city', esc_attr( $_POST['billing_city'] ) );
    if ( !empty( $_POST['billing_address_1'] ) )
        update_user_meta( $current_user->ID, 'billing_address_1', esc_attr( $_POST['billing_address_1'] ) );
    if ( !empty( $_POST['billing_address_2'] ) )
        update_user_meta( $current_user->ID, 'billing_address_2', esc_attr( $_POST['billing_address_2'] ) );						
   if ( !empty( $_POST['billing_postcode'] ) )
        update_user_meta( $current_user->ID, 'billing_postcode', esc_attr( $_POST['billing_postcode'] ) );
    if ( !empty( $_POST['billing_city'] ) )
        update_user_meta( $current_user->ID, 'billing_city', esc_attr( $_POST['billing_city'] ) );		
    if ( !empty( $_POST['billing_phone'] ) )
        update_user_meta( $current_user->ID, 'billing_phone', esc_attr( $_POST['billing_phone'] ) );		
    if ( !empty( $_POST['billing_country'] ) )
        update_user_meta( $current_user->ID, 'billing_country', esc_attr( $_POST['billing_country'] ) );
    /* Redirect so the page will show updated info.*/
  /*I am not Author of this Code- i dont know why but it worked for me after changing below line to if ( count($error) == 0 ){ */
    if ( count($error) == 0 ) {
        //action hook for plugins and extra fields saving
        do_action('edit_user_profile_update', $current_user->ID);
        //wp_redirect( the_permalink() );
        //exit;
    }
}



?><form method="post" id="adduser" action="<?php the_permalink(); ?>">
		<div class="um-form">
			<div class="containeraccount">			
			<p class="editaccount">Edit Account</p>
			<div class="leftaccount">
			<p class="accountheader"><strong>Account Details</strong></p>
                    <p class="form-email">
                        <label for="email"><?php _e('E-mail *', 'profile'); ?></label>
                        <input class="text-input" name="email" type="text" id="email" value="<?php the_author_meta( 'user_email', $current_user->ID ); ?>" />
                    </p><!-- .form-email -->				
                

                    <p class="form-password">
                        <label for="pass1"><?php _e('Password *', 'profile'); ?> </label>
                        <input class="text-input" name="pass1" type="password" id="pass1"/>
                    </p><!-- .form-password -->
                    <p class="form-password">
                        <label for="pass2"><?php _e('Repeat Password *', 'profile'); ?></label>
                        <input class="text-input" name="pass2" type="password" id="pass2" />
                    </p><!-- .form-password -->					
				
		
			
			
			</div>
			<div class="middleaccount">
			<p class="accountheader"><strong>Personal Details</strong></p>
			
                    <p class="form-username">
                        <label for="first-name"><?php _e('First Name', 'profile'); ?></label>
                        <input class="text-input" name="first-name" type="text" id="first-name" value="<?php the_author_meta( 'first_name', $current_user->ID ); ?>" />
                    </p><!-- .form-username -->
                    <p class="form-username">
                        <label for="last-name"><?php _e('Last Name', 'profile'); ?></label>
                        <input class="text-input" name="last-name" type="text" id="last-name" value="<?php the_author_meta( 'last_name', $current_user->ID ); ?>" />
                    </p><!-- .form-username -->
                    <p class="form-username">
                        <label for="billing_address_1"><?php _e('Address', 'profile'); ?></label>
                        <input class="text-input" name="billing_address_1" type="text" id="billing_address_1" value="<?php the_author_meta( 'billing_address_1', $current_user->ID ); ?>" />
                    </p><!-- .form-username -->
                    <p class="form-username">
                        <label for="billing_address_2"><?php _e('House No', 'profile'); ?></label>
                        <input class="text-input" name="billing_address_2" type="text" id="billing_address_2" value="<?php the_author_meta( 'billing_address_2', $current_user->ID ); ?>" />
                    </p><!-- .form-username -->
                    <p class="form-username">
                        <label for="billing_postcode"><?php _e('ZIP Code', 'profile'); ?></label>
                        <input class="text-input" name="billing_postcode" type="text" id="billing_postcode" value="<?php the_author_meta( 'billing_postcode', $current_user->ID ); ?>" />
                    </p><!-- .form-username -->		
					
                    <p class="form-username">
                        <label for="billing_city"><?php _e('City', 'profile'); ?></label>
                        <input class="text-input" name="billing_city" type="text" id="billing_city" value="<?php the_author_meta( 'billing_city', $current_user->ID ); ?>" />
                    </p><!-- .form-username -->		
                    <p class="form-username"><?php $ipadres = $_SERVER['REMOTE_ADDR']; $countrycheck = PepakIpToCountry::IP_to_Country_XX($ipadres); ?>
                        <label for="billing_country"><?php _e('Country', 'profile'); ?></label>	<?php //echo the_author_meta( 'billing_country', $current_user->ID ); ?>				

					<?php $connn = WC()->countries->countries; ?>
					<?php $country = get_the_author_meta( 'billing_country', $current_user->ID ); ?>
					<?php if ($country == "") { $country = $countrycheck; }  ?>
					<select name="billing_country" id="billing_country">
					<?php foreach ($connn as $key => $value):
					if ($key == $country) {
					echo '<option value="'.$key.'" selected>'.$value.'</option>';	
					} else { 
					echo '<option value="'.$key.'">'.$value.'</option>';
					}
					endforeach;
					?>
					</select> 
					</p><!-- .form-username -->	
					
																							
					
					
                    <p class="form-username">
                        <label for="billing_phone"><?php _e('Telephone', 'profile'); ?></label>
                        <input class="text-input" name="billing_phone" type="text" id="billing_phone" value="<?php the_author_meta( 'billing_phone', $current_user->ID ); ?>" />
                    </p><!-- .form-username -->																									
			
			</div>	
			<div class="rightaccount">
			<p class="accountheader"><strong>Peduli Anak Newsletter</strong></p>
                    <p class="form-username">
                        
                        <input name="newsletter" type="checkbox" id="newsletter" value="<?php the_author_meta( 'newsletter', $current_user->ID ); ?>" /> Subscribe
                    </p><!-- .form-username -->			
			</div>	
			
			
                      <p class="form-submit centeraccount">
                        <?php echo $referer; ?>
                        <input name="updateuser" type="submit" id="updateuser" class="btn btn-donatie" value="<?php _e('SAVE CHANGES', 'profile'); ?>" />
                        <?php wp_nonce_field( 'update-user' ) ?>
						
                        <input name="action" type="hidden" id="action" value="update-user" />
						
                    </p><!-- .form-submit -->			
							
			</div>

        </div>
</form>		