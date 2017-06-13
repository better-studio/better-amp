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


		/***
		 * Above The Fold Plugin
		 */
		if ( class_exists( 'Abovethefold' ) ) {
			if ( ! defined( 'DONOTABTF' ) ) {
				define( 'DONOTABTF', TRUE );
			}
			$GLOBALS['Abovethefold']->disable = TRUE;

			bf_remove_class_action( 'init', 'Abovethefold_Optimization', 'html_output_hook', 99999 );
			bf_remove_class_action( 'wp_head', 'Abovethefold_Optimization', 'header', 1 );
			bf_remove_class_action( 'wp_print_footer_scripts', 'Abovethefold_Optimization', 'footer', 99999 );
		}


		self::$plugins = NULL; // Clear memory


		/**
		 *  Custom Permalinks
		 */

		add_action( 'plugins_loaded', 'Better_AMP_Plugin_Compatibility::custom_permalinks_init' );

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


	/**
	 * Initialize Custom permalinks support
	 */
	public static function custom_permalinks_init() {

		// Guess is custom permalinks installed and active
		if ( function_exists( 'custom_permalinks_request' ) ) {
			add_filter( 'request', 'Better_AMP_Plugin_Compatibility::custom_permalinks', 15 );
		}
	}


	/**
	 * Add Custom permalinks compatibility
	 *
	 * @param array $query_vars
	 *
	 * @return array
	 */
	public static function custom_permalinks( $query_vars ) {

		$amp_qv = defined( 'AMP_QUERY_VAR' ) ? AMP_QUERY_VAR : 'amp';
		$path   = bf_get_wp_installation_slug();

		if ( ! (
			preg_match( "#^$path/*$amp_qv/(.*?)/*$#", $_SERVER['REQUEST_URI'], $matched )
			||
			preg_match( "#^$path/*(.*?)/$amp_qv/*$#", $_SERVER['REQUEST_URI'], $matched )
		)
		) {
			return $query_vars;
		}

		if ( empty( $matched[1] ) ) {
			return $query_vars;
		}

		remove_filter( 'request', 'Better_AMP_Plugin_Compatibility::custom_permalinks', 15 );

		$_SERVER['REQUEST_URI'] = '/' . $matched[1] . '/';
		$query_vars ['amp']     = '1';
		$_REQUEST['amp']        = '1';


		if ( $new_qv = custom_permalinks_request( $query_vars ) ) {

			$new_qv['amp'] = '1';

			// prevent redirect amp post to none-amp version
			remove_filter( 'template_redirect', 'custom_permalinks_redirect', 5 );

			return $new_qv;
		}

		return $query_vars;
	} // custom_permalinks
}
