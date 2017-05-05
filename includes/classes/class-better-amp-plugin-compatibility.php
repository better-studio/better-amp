<?php

Better_AMP_Plugin_Compatibility::init();

/**
 * Third Party Plugins Compatibility
 *
 * @since 1.3.1
 */
class Better_AMP_Plugin_Compatibility {

	/**
	 * List of all plugins
	 *
	 * @var array
	 */
	public static $plugins = array();

	/**
	 * Initialization
	 */
	public static function init() {

		if ( ! is_better_amp() ) {
			return;
		}

		self::$plugins = array_flip( wp_get_active_and_valid_plugins() );

		/**
		 * WordPress Fastest Cache
		 */
		if ( isset( self::$plugins[ WP_PLUGIN_DIR . '/wp-fastest-cache/wpFastestCache.php' ] ) && ! isset( $GLOBALS["wp_fastest_cache_options"] ) ) {
			self::wpfc_fix_options();
		}


		/**
		 * Convert Plug plugin
		 *
		 * http://convertplug.com/
		 */
		if ( class_exists( 'Convert_Plug' ) ) {
			add_filter( 'after_setup_theme', 'Better_AMP_Plugin_Compatibility::convert_plug' );
		}


		self::$plugins = NULL; // Clear memory

	}


	/**
	 * Convert Plug plugin
	 *
	 * http://convertplug.com/
	 */
	public static function convert_plug() {
		bf_remove_class_filter( 'the_content', 'Convert_Plug', 'cp_add_content', 10 );
	}


	/**
	 *
	 * WordPress Fastest Cache Plugins Fixes
	 *
	 */

	/**
	 * Disables minify features if WPFC plugin in AMP
	 */
	public static function wpfc_fix_options() {

		if ( $wp_fastest_cache_options = get_option( "WpFastestCache" ) ) {

			$GLOBALS["wp_fastest_cache_options"] = json_decode( $wp_fastest_cache_options );

			unset( $GLOBALS["wp_fastest_cache_options"]->wpFastestCacheRenderBlocking );
			unset( $GLOBALS["wp_fastest_cache_options"]->wpFastestCacheCombineJsPowerFul );
			unset( $GLOBALS["wp_fastest_cache_options"]->wpFastestCacheMinifyJs );
			unset( $GLOBALS["wp_fastest_cache_options"]->wpFastestCacheCombineJs );
			unset( $GLOBALS["wp_fastest_cache_options"]->wpFastestCacheCombineCss );
			unset( $GLOBALS["wp_fastest_cache_options"]->wpFastestCacheLazyLoad );
			unset( $GLOBALS["wp_fastest_cache_options"]->wpFastestCacheGoogleFonts );

		} else {
			$GLOBALS["wp_fastest_cache_options"] = array();
		}

	} // wpfc_fix_options

}
