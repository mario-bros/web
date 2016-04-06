<?php
/**
 * Charity - Twiitter Widget
 * @package charity
 * @version     v.1.0
 * 
 */

class Charity_Twitter_Widget extends WP_Widget {

	var $prefix;
	var $textdomain;


	/**
	 * Set up the widget's unique name, ID, class, description, and other options
	 * @since 1.2.1
	 **/
	function __construct() {

		// Set default variable for the widget instances
		$this->prefix = 'twitter-charity';
		$this->textdomain = 'charity';

		// Set up the widget control options
		$control_options = array(
				'width' => 444,
				'height' => 350,
				'id_base' => $this->prefix
		);

		// Add some informations to the widget
		$widget_options = array('classname' => 'charity_twitter_widget', 'description' => __( 'Displays a Twitter Feed from an Screen Name', "charity" ) );

		// Create the widget
		$this->WP_Widget($this->prefix, __('Charity Twitter Feed', "charity"), $widget_options, $control_options );
		//add_action( 'save_post', array($this, 'flush_widget_cache') );
		//add_action( 'deleted_post', array($this, 'flush_widget_cache') );
		//add_action( 'switch_theme', array($this, 'flush_widget_cache') );

	}
	function flush_widget_cache() {
		wp_cache_delete('widget_recent_posts', 'widget');
	}

	/**
	 * Push the widget 
	 * @since 1.0
	 **/
	function widget( $args, $instance ) {
		extract( $args );

		// Set up the arguments for wp_list_categories().
		$cur_arg = array(
				'title'			=> (!empty($instance['title']))?  $instance['title'] : "",
				'username'		=> (!empty($instance['username']))?  $instance['username']: "",
				'key'			=> (!empty($instance['key']))?  $instance['key']: "",
				'secret'		=> (!empty($instance['secret']))?  $instance['secret']: "",
				'token'			=> (!empty($instance['token']))?  $instance['token']: "",
				'tokensecret'	=> (!empty($instance['tokensecret']))?  $instance['tokensecret']: "",
				'count'			=> (!empty($instance['count']))?  (int) $instance['count']: 0,
		);
                
                

		$tweets = $this->tweetsOAuth($cur_arg);
		
                
		
		extract( $cur_arg );

		if (count($tweets) > 0):
		// print the before widget
		echo $before_widget;
		
		if ( $title )
			echo $before_title . $title . $after_title;

		// Get the user direction, rtl or ltr
		if ( function_exists( 'is_rtl' ) )
			$dir = is_rtl() ? 'rtl' : 'ltr';


		// echo "<div class='charity-twitter-feed-wrapper charity-twitter-wrap-$dir'>";
		// echo "<ul>";

		$i = 0;
		foreach ($tweets as $tweet):
			if(!empty($tweet->text)):
			$text = $tweet->text;
			$time = $tweet->created_at;
			// echo "<li>";
                 /*if (!empty($tweet->entities->media[0]->media_url)): ?> 
                        <div class="share-image" style="background-image: url('<?php echo $tweet->entities->media[0]->media_url; ?>');">

                        </div>
                    <?php endif;
                    
                    <!-- <div class="profile-image">
                        <div class="image">
                            <img src=" <?php echo $tweet->user->profile_image_url; ?> " alt=" " title=" " />
                        </div>
                        <div class="name-handle">
                            <a href="https://twitter.com/<?php echo $tweet->user->name; ?>/statuses/<?php echo $tweet->id_str; ?>" ><p class="uname"><?php echo $tweet->user->name; ?></p></a>
                            <a class="handle" href="https://twitter.com/<?php echo $tweets[0]->user->screen_name; ?>" target="_blank"><?php echo "@" . $tweets[0]->user->screen_name; ?></a>
                        </div>
                    </div>
                    <div class="tweet-content">			
                        <p class="original-tweet tweet<?php echo $i; ?>"><?php echo $this->tweetLinkify($text); ?></p>
                        <p class="time"><?php echo $this->timeShortElapsed($time); //date("g:i A M j, Y ", strtotime($time)); ?></p>
                    </div> -->
                    */ ?>
                    <p>
                    	<a href="https://twitter.com/<?php echo $tweets[0]->user->screen_name; ?>" target="_blank"> 
                    		<span class="charity"><?php echo "@" . $tweets[0]->user->screen_name; ?> </span> 
                    	</a>
                    	<?php echo $this->tweetLinkify($text); ?> 
                    	<span class="comment-time"> <?php echo $this->timeShortElapsed($time);?></span> 
                    	
					</p>
                    
                 <?php   
               // </li> 
                
                endif;
		endforeach;
		       
		// echo "</ul>";
// 
		// echo '</div>';
		
		
							
							

		// Print the after widget
		echo $after_widget;
		endif;
	}

	/**
	 * Tweets datas
	 * @param type $user
	 * @param type $count
	 * @return type array
	 */
	function tweetsOAuth($args) {
		$tweets = array();
		if (!function_exists('curl_version')) {
			return $tweets;
		}
		if (!class_exists('TwitterOAuth')) {
			require_once("TwitterOAuth/TwitterOAuth.php"); 
		}
	
		try {
			$key = $args["key"];
			$secret = $args['secret'];
			$token = $args["token"];
			$tokenSecret = $args["tokensecret"];
	
			$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=' . $args['username'] . '&count=' . $args['count'];
                       
			$connection = new TwitterOAuth($key, $secret, $token, $tokenSecret);
			$tweets = $connection->get($url);
		} catch (Exception $ex) {
			echo "<p>" . __("Notice ", 'Charity') . ": {$ex}<p>";
		}
		return $tweets;
	}
	
	/**
	 * Tweet Linkify
	 * @param type $tweet
	 * @return type string
	 */
	function tweetLinkify($tweet) {
	
		$tweet = preg_replace("/([\w]+\:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/", "<a target=\"_blank\" href=\"$1\">$1</a>", $tweet);
		$tweet = preg_replace("/#([A-Za-z0-9\/\.]*)/", "<a target=\"_blank\" href=\"http://twitter.com/search?q=$1\">#$1</a>", $tweet);
		$tweet = preg_replace("/@([A-Za-z0-9\/\.]*)/", "<a href=\"http://www.twitter.com/$1\">@$1</a>", $tweet);
	
		return $tweet;
	}
	
	
	/**
	 * Time Elapsed
	 * 6 days ago.
	 * @param type $secs
	 * @return type string
	 */
	function timeShortElapsed($secs) {
		//print_r(strtotime($secs));
		$secs = time() - date("U", strtotime($secs));
		$bit = array(
				' year' => $secs / 31556926 % 12,
				' week' => $secs / 604800 % 52,
				' day' => $secs / 86400 % 7,
				' hour' => $secs / 3600 % 24,
				' minute' => $secs / 60 % 60,
				' second' => $secs % 60
		);
	
		foreach ($bit as $k => $v) {
			if ($v > 0) {
				$ret = $v . $k;
				break;
			}
		}
		return $ret . ' ago.';
	}
	
	/**
	 * Time Elapsed
	 * 6 days 15 hours 48 minutes and 19 seconds ago.
	 * @param type $secs
	 * @return type string
	 */
	function timeElapsed($secs) {
		$secs = time() - date("U", strtotime($secs));
		$bit = array(
				' year' => $secs / 31556926 % 12,
				' week' => $secs / 604800 % 52,
				' day' => $secs / 86400 % 7,
				' hour' => $secs / 3600 % 24,
				' minute' => $secs / 60 % 60,
				' second' => $secs % 60
		);
	
		foreach ($bit as $k => $v) {
			if ($v > 1)
				$ret[] = $v . $k . 's';
			if ($v == 1)
				$ret[] = $v . $k;
		}
		array_splice($ret, count($ret) - 1, 0, 'and');
		$ret[] = 'ago.';
	
		return join(' ', $ret);
	}
	


	/**
	 * Widget update functino
	 * @since 1.2.1
	 **/
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] 			= strip_tags($new_instance['title']);
		$instance['username'] 		= strip_tags($new_instance['username']);
		$instance['key'] 		= strip_tags($new_instance['key']);
		$instance['secret'] 		= strip_tags($new_instance['secret']);
		$instance['token'] 		= strip_tags($new_instance['token']);
		$instance['tokensecret'] 		= strip_tags($new_instance['tokensecret']);
		$instance['count'] 			= (int) $new_instance['count'];

		return $instance;
	}



	/**
	 * Widget form function
	 * @since 1.2.1
	 **/
	function form( $instance ) {
		// Set up the default form values.
		$defaults = array(
				'title'			=> esc_attr__( 'Twitter Widget', "charity" ),
				'username'			=> '',
				'key'		=> '', 
				'secret'		=> '', 
				'token'		=> '', 
				'tokensecret'		=> '', 
				'count'			=> 2,
		);

		/* Merge the user-selected arguments with the defaults. */
		$instance = wp_parse_args( (array) $instance, $defaults );


		?>
		
		<div id="charity-twitter-<?php echo $this->id ; ?>" class="totalControls charity-flicker-form">
					<ul>
						<li>
							<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', "charity"); ?></label>
							
							<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
						</li>
						<li>
							<label for="<?php echo $this->get_field_id('username'); ?>"><?php _e( '', "charity" ); ?></label><span class="controlDesc"><?php _e( 'The Twitter user name', "charity" ); ?></span>					<input class="widefat" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" type="text" value="<?php echo esc_attr( $instance['username'] ); ?>" />
							
						</li>
						<li>
							<label for="twitter-apps">&nbsp;</label>							
							<span class="controlDesc"><?php _e( 'Twitter App here, go to', "charity"); ?> <a href="<?php echo esc_url("https://dev.twitter.com/apps/"); ?>" target="_blank"><?php _e("Twiiter App", "charity"); ?></a></span>
						</li>
						<li>
							<label for="<?php echo $this->get_field_id('key'); ?>"><?php _e('Key', "charity"); ?></label>							
							<input class="widefat" id="<?php echo $this->get_field_id('key'); ?>" name="<?php echo $this->get_field_name('key'); ?>" type="text" value="<?php echo esc_attr( $instance['key'] ); ?>" />
						</li>
						<li>
							<label for="<?php echo $this->get_field_id('secret'); ?>"><?php _e('Secret', "charity"); ?></label>							
							<input class="widefat" id="<?php echo $this->get_field_id('secret'); ?>" name="<?php echo $this->get_field_name('secret'); ?>" type="text" value="<?php echo esc_attr( $instance['secret'] ); ?>" />
						</li>
						<li>
							<label for="<?php echo $this->get_field_id('token'); ?>"><?php _e('Token', "charity"); ?></label>							
							<input class="widefat" id="<?php echo $this->get_field_id('token'); ?>" name="<?php echo $this->get_field_name('token'); ?>" type="text" value="<?php echo esc_attr( $instance['token'] ); ?>" />
						</li>
						<li>
							<label for="<?php echo $this->get_field_id('tokensecret'); ?>"><?php _e('Token Secret', "charity"); ?></label>							
							<input class="widefat" id="<?php echo $this->get_field_id('tokensecret'); ?>" name="<?php echo $this->get_field_name('tokensecret'); ?>" type="text" value="<?php echo esc_attr( $instance['tokensecret'] ); ?>" />
						</li>						
						<li>
							<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Number of feed shown from 1 to 10', "charity"); ?></label>
							
							<input class="column-last" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo esc_attr( $instance['count'] ); ?>" size="3" />
						</li>
					</ul>
				</div>			
		<?php
	}
}
function twitter_feed_widget_init() {
	register_widget( 'Charity_Twitter_Widget' );
}

add_action( 'widgets_init', 'twitter_feed_widget_init' );