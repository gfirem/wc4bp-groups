<?php
/**
 * @package        WordPress
 * @subpackage     BuddyPress, Woocommerce, WC4BP
 * @author         ThemKraft Dev Team
 * @copyright      2017, Themekraft
 * @link           http://themekraft.com/store/woocommerce-buddypress-integration-wordpress-plugin/
 * @license        http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class wc4bp_groups_manager {
	
	private static $plugin_slug = 'wc4bp_groups';
	protected static $version = '1.0.0';
	
	public function __construct() {
		require_once WC4BP_GROUP_CLASSES_PATH . 'wc4bp_groups_log.php';
		try {
			//loading_dependency
			require_once WC4BP_GROUP_CLASSES_PATH . 'wc4bp_groups_handler.php';
			require_once WC4BP_GROUP_CLASSES_PATH . 'wc4bp_groups_woo.php';
			new wc4bp_groups_handler();
			new wc4bp_groups_woo();
			
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_js' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_style' ) );
		} catch ( Exception $ex ) {
			wc4bp_groups_log::log( array(
				'action'         => get_class( $this ),
				'object_type'    => wc4bp_groups_manager::getSlug(),
				'object_subtype' => 'loading_dependency',
				'object_name'    => $ex->getMessage(),
			) );
		}
	}
	
	/**
	 * Include styles in admin
	 *
	 * @param $hook
	 */
	public function enqueue_style( $hook ) {
		if ( $hook == 'post.php' ) {
			wp_enqueue_style( 'jquery' );
			wp_enqueue_style( 'wc4bp-groups', WC4BP_GROUP_CSS_PATH . 'wc4bp-groups.css', array(), wc4bp_groups_manager::getVersion() );
		}
	}
	
	/**
	 * Include script
	 *
	 * @param $hook
	 */
	public function enqueue_js( $hook ) {
		if ( $hook == 'post.php' ) {
			wp_register_script( 'wc4bp_groups', WC4BP_GROUP_JS_PATH . 'wc4bp-groups.js', array( "jquery" ), wc4bp_groups_manager::getVersion() );
			wp_enqueue_script( 'wc4bp_groups' );
			wp_localize_script( 'wc4bp_groups', 'wc4bp_groups', array(
				'ajax_url'            => admin_url( 'admin-ajax.php' ),
				'search_groups_nonce' => wp_create_nonce( "wc4bp-nonce" ),
			) );
		}
	}
	
	/**
	 * Get plugins version
	 *
	 * @return mixed
	 */
	static function getVersion() {
		return self::$version;
	}
	
	/**
	 * Get plugins slug
	 *
	 * @return string
	 */
	static function getSlug() {
		return self::$plugin_slug;
	}
}