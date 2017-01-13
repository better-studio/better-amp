<?php

/**
 * Utility class for WordPress 'wp-admin' actions
 *
 * @since 1.0.0
 */
class Better_AMP_Backend_Util {

	const PAGE_SLUG = 'better-amp';

	/**
	 * Store add_menu_page function arguments
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public static $admin_menus = array();


	/**
	 * Store add_submenu_page function arguments
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public static $admin_submenus = array();


	/**
	 * Apply dependency hooks
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'append_menus' ) );
	}


	/**
	 *Add a top-level menu page
	 *
	 * @see   add_menu_page for more documentation
	 *
	 * @param string   $page_title
	 * @param string   $menu_title
	 * @param string   $capability
	 * @param string   $menu_slug
	 * @param callable $function
	 * @param string   $icon_url
	 * @param null     $position
	 *
	 * @since 1.0.0
	 */
	public static function add_admin_menu( $page_title, $menu_title, $capability, $menu_slug, $function = NULL, $icon_url = '', $position = NULL ) {

		$params = func_get_args();

		array_push( self::$admin_menus, $params );

	}

	/**
	 * Add a submenu page.
	 *
	 * @see   add_submenu_page for more documentation
	 *
	 * @param string   $page_title
	 * @param string   $menu_title
	 * @param string   $capability
	 * @param string   $menu_slug
	 * @param callable $function
	 * @param string   $parent_slug optional. default better-amp main menu
	 *
	 * @since 1.0.0
	 */
	public static function add_admin_submenu( $page_title, $menu_title, $capability, $menu_slug, $function, $parent_slug = '' ) {

		$params = func_get_args();
		array_unshift( $params, ( $parent_slug ? $parent_slug : Better_AMP_Backend_Util::PAGE_SLUG ) );
		unset( $params[6] );

		array_push( self::$admin_submenus, $params );
	}


	/**
	 * Callback: Register admin menus
	 * Action  : admin_menu
	 *
	 * @since 1.0.0
	 */
	public static function append_menus() {
		foreach ( self::$admin_menus as $admin_menu ) {
			call_user_func_array( 'add_menu_page', $admin_menu );
		}
		foreach ( self::$admin_submenus as $admin_submenu ) {
			call_user_func_array( 'add_submenu_page', $admin_submenu );
		}
	}

}

Better_AMP_Backend_Util::init();
