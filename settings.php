<?php
if(!class_exists('WP_Plugin_Template_Settings'))
{
	class WP_Plugin_Template_Settings
	{
		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{
			// register actions
            add_action('admin_init', array(&$this, 'admin_init'));
        	add_action('admin_menu', array(&$this, 'add_menu'));
		} // END public function __construct
		
        /**
         * hook into WP's admin_init action hook
         */
        public function admin_init()
        {
        	// register your plugin's settings
        	register_setting('wp_plugin_template-group', 'setting_token');
            
            // register your plugin's settings
        	register_setting('wp_plugin_template-group', 'setting_signature');

        	// add your settings section
        	add_settings_section(
        	    'wp_plugin_template-section', 
        	    '', 
        	    array(&$this, 'settings_section_wp_plugin_template'), 
        	    'wp_plugin_template'
        	);
        	
        	// add your setting's fields
            add_settings_field(
                'wp_opencheck-setting_token', 
                'Token', 
                array(&$this, 'settings_field_input_text'), 
                'wp_plugin_template', 
                'wp_plugin_template-section',
                array(
                    'field' => 'setting_token'
                )
            );           
            
            // add your setting's fields
            add_settings_field(
                'wp_opencheck-setting_signature', 
                'Signature', 
                array(&$this, 'settings_field_textarea'), 
                'wp_plugin_template', 
                'wp_plugin_template-section',
                array(
                    'field' => 'setting_signature'
                )
            );           
            
            // Possibly do additional admin_init tasks
        } // END public static function activate
        
        public function settings_section_wp_plugin_template()
        {
            // Think of this as help text for the section.
            echo 'These settings are required.';
        }
        
        /**
         * This function provides text inputs for settings fields
         */
        public function settings_field_input_text($args)
        {
            // Get the field name from the $args array
            $field = $args['field'];
            // Get the value of this setting
            $value = get_option($field);
            // echo a proper input type="text"
            echo sprintf('<input type="text" name="%s" id="%s" value="%s" style="width:300px"/>', $field, $field, $value);
        } // END public function settings_field_input_text($args)
        
        /**
         * This function provides text inputs for settings fields
         */
        public function settings_field_textarea($args)
        {
            // Get the field name from the $args array
            $field = $args['field'];
            // Get the value of this setting
            $value = get_option($field);
            // echo a proper input type="text"
            echo sprintf('<textarea name="%s" id="%s" style="width:300px;min-height:100px">%s</textarea>', $field, $field, $value);
        } // END public function settings_field_input_text($args)
        
        /**
         * add a menu
         */		
        public function add_menu()
        {
            // Add a page to manage this plugin's settings
        	add_options_page(
        	    'WP OpenCheck Settings', 
        	    'WP ParentComms', 
        	    'manage_options', 
        	    'wp_plugin_template', 
        	    array(&$this, 'plugin_settings_page')
        	);
        } // END public function add_menu()
    
        /**
         * Menu Callback
         */		
        public function plugin_settings_page()
        {
        	if(!current_user_can('manage_options'))
        	{
        		wp_die(__('You do not have sufficient permissions to access this page.'));
        	}
	
        	// Render the settings template
        	include(sprintf("%s/templates/settings.php", dirname(__FILE__)));
        } // END public function plugin_settings_page()
    } // END class WP_Plugin_Template_Settings
} // END if(!class_exists('WP_Plugin_Template_Settings'))
