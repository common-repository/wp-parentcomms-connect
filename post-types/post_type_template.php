<?php
if(!class_exists('Post_Type_Template'))
{
	/**
	 * A PostTypeTemplate class that provides 3 additional meta fields
	 */
	class Post_Type_Template
	{
		const POST_TYPE	= "post";
		private $_meta	= array(
			'push_staff',
			'push_opencheck',
		);
		
		private $alert;
        private $alert_type;
		
    	/**
    	 * The Constructor
    	 */
    	public function __construct()
    	{			
    		// register actions
			add_action( 'load-post.php', array(&$this, 'init') );
			add_action( 'load-post-new.php', array(&$this, 'init') );
    	} // END public function __construct()

    	/**
    	 * hook into WP's init action hook
    	 */
    	public function init()
    	{
            //add_action( 'add_meta_boxes', array(&$this, 'add_meta_boxes') );
            add_action( 'post_submitbox_misc_actions', array(&$this, 'post_settings') );
			add_action( 'save_post', array( $this, 'save_post' ) );
			add_action( 'admin_notices', array( $this,'my_admin_notices') );
			add_action( 'admin_enqueue_scripts', array( $this,'my_enqueue') );
    	} // END public function init()    	
	
		function my_enqueue($hook) {
			wp_enqueue_script( 'opencheck_validation', plugins_url() . '/wp-parentcomms-connect/scripts/validation.js' );
		}
			
        
        function post_settings() {
            global $post;
            if (get_post_type($post) == 'post') {
               include(sprintf("%s/../templates/%s_metabox.php", dirname(__FILE__), self::POST_TYPE));		
            }
        }

    	/**
    	 * Save the metaboxes for this custom post type
    	 */
    	public function save_post($post_id)
    	{
            // verify if this is an auto save routine. 
            // If it is our form has not been submitted, so we dont want to do anything
            if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            {
                return;
            }
			
            $staff = 0;
			$opencheck = 0;
				
			if ($_POST["push_staff"])
				$staff = 1;
				
			if ($_POST["push_opencheck"])
				$opencheck = 1;
					
            
			$status = get_post_status( $post_id );
            
    		if(isset($_POST['post_type']) && $_POST['post_type'] == self::POST_TYPE && current_user_can('edit_post', $post_id) && $status == "publish")
    		{
              
				if ($opencheck == 0 && $staff == 0)
				    return;
				
				$url = 'https://my.uso.im/api/opencheck.ashx?action=postmessage';
				$data = array('title' => $_POST["post_title"], 'message' => stripslashes(nl2br($_POST["content"]."<br><br>".get_option("setting_signature"))),'opencheck' => $opencheck,'staff' => $staff,'token' =>get_option("setting_token"));

				// use key 'http' even if you send the request to https://...
				$options = array(
					'http' => array(
						'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
						'method'  => 'POST',
						'content' => http_build_query($data),
					),
				);
				$context  = stream_context_create($options);
				$result= file_get_contents($url, false, $context);
                
               if ($result === false){
                    $this->alert = "Failed to push the message.";
                    $this->alert_type = "error";
               }
               else{
                    $this->alert = "Message pushed successfully.";
                    $this->alert_type = "updated";
               }
               
                update_post_meta($post_id, "alert",  $this->alert);		
                update_post_meta($post_id, "alert_type", $this->alert_type);		
    		}
            else if ($opencheck > 0 || $staff > 0){
                 $this->alert = "Message was not pushed. Please publish the post in order to use this feature.";
                 $this->alert_type = "error";
                 
                update_post_meta($post_id, "alert",  $this->alert);		
                update_post_meta($post_id, "alert_type", $this->alert_type);	
            }
    		else
    		{
    			return;
    		} // if($_POST['post_type'] == self::POST_TYPE && current_user_can('edit_post', $post_id))
    	} // END public function save_post($post_id)

    	
		function my_admin_notices(){
             global $post;
             $this->alert = get_post_meta($post->ID,"alert",true);
             $this->alert_type = get_post_meta($post->ID,"alert_type",true);
        
            if (isset($this->alert)){
			?>
				<div class="<?php echo $this->alert_type ?>">
					<p><?php echo $this->alert ?></p>
				</div>
			<?php
            
             update_post_meta($post->ID, "alert",  "");		
             update_post_meta($post->ID, "alert_type", "");	
            }           
		}	
              
		
	} // END class Post_Type_Template
} // END if(!class_exists('Post_Type_Template'))
