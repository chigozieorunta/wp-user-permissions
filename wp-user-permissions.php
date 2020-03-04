<?php
/**
 * Plugin Name: User Permissions
 * Plugin URI:  https://github.com/chigozieorunta/wp-user-permissions
 * Description: A simple WordPress plugin that helps restrict unauthorized users to specific pages.
 * Version:     1.0.0
 * Author:      Chigozie Orunta
 * Author URI:  https://github.com/chigozieorunta
 * License:     MIT
 * Text Domain: wp-user-permissions
 * Domain Path: ./
 */

//Define Plugin Path
define("WPUP", plugin_dir_path(__FILE__));

wpUserPermissions::getInstance();

/**
 * Class wpUserPermissions
 */
class wpUserPermissions {
    /**
	 * Constructor
	 *
	 * @since  1.0.0
	 */
    public function __construct() {
		add_action('plugins_loaded', array($this, 'showPermissions'));
	}
	
	/**
	 * Load only when pluggable.php is ready
	 *
     * @access public 
	 * @since  1.0.0
	 */
    public static function showPermissions() {
		//Get user role
		$user = wp_get_current_user();
		
		//Test Condition
		if(is_user_logged_in() && isset($user->roles[0])) {
			if($user->roles[0] != 'administrator' && $user->roles[0] != 'shop_manager' && $user->roles[0] != 'wpseo_manager') {
				add_action('wp_enqueue_scripts', array(get_called_class(), 'registerScripts'));
			}
		} else {
			add_action('wp_enqueue_scripts', array(get_called_class(), 'registerScripts'));
		}
	}
	
	/**
	 * Custom Modal Method
	 *
     * @access public 
	 * @since  1.0.0
	 */
    public static function customModal() {
        require_once('wp-user-permissions-html.php');
    }

    /**
	 * Register Scripts Method
	 *
     * @access public 
	 * @since  1.0.0
	 */
    public static function registerScripts() {
		wp_register_style('wp-user-permissions-css', plugin_dir_url(__FILE__).'css/wp-user-permissions.css');
		wp_enqueue_style('wp-user-permissions-css');
		wp_register_script('wp-user-permissions-js', plugin_dir_url(__FILE__).'js/wp-user-permissions.js', array('jquery'), '1', true);
		wp_enqueue_script('wp-user-permissions-js');
    }

    /**
	 * Points the class, singleton.
	 *
	 * @access public
	 * @since  1.0.0
	 */
    public static function getInstance() {
        static $instance;
        if($instance === null) $instance = new self();
        return $instance;
    }
}

?>
