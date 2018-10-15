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

		/**
		 * WPML Plugin
		 *
		 * @link  https://wpml.org
		 *
		 * @since 1.6.0
		 */

		add_action( 'init', array( __CLASS__, 'fix_wpml_template_hooks' ) );

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


		/**
		 * WP-Optimize Plugin
		 * https://wordpress.org/plugins/wp-optimize/
		 */
		if ( class_exists( 'WP_Optimize' ) ) {
			bf_remove_class_action( 'plugins_loaded', 'WP_Optimize', 'plugins_loaded', 1 );
		}


		/**
		 * WP Speed Grades Lite
		 *
		 * http://www.wp-speed.com/
		 */
		if ( defined( 'WP_SPEED_GRADES_VERSION' ) ) {
			add_action( 'init', array( 'Better_AMP_Plugin_Compatibility', 'pre_init' ), 0 );
		}


		self::$plugins = NULL; // Clear memory

		add_action( 'plugins_loaded', 'Better_AMP_Plugin_Compatibility::plugins_loaded' );


		/**
		 * WPML Plugin
		 *
		 * @link  https://wpml.org
		 *
		 * @since 1.6.0
		 */

		add_action( 'template_redirect', array( __CLASS__, 'fix_wpml_template_hooks' ) );


		/**
		 * Pretty Links Compatibility
		 *
		 * @link  https://wordpress.org/plugins/pretty-link/
		 * @since 1.7.0
		 */

		add_filter( 'prli-check-if-slug', 'Better_AMP_Plugin_Compatibility::pretty_links_compatibility', 2, 2 );

		/**
		 * Polylang compatibility
		 *
		 * @since 1.8.0
		 */
		add_filter( 'pll_check_canonical_url', '__return_false' );


		/**
		 * New Relic compatibility
		 *
		 * @since 1.8.0
		 * @link  https://docs.newrelic.com/docs/agents/php-agent/getting-started/introduction-new-relic-php
		 */

		if ( extension_loaded( 'newrelic' ) && function_exists( 'newrelic_disable_autorum' ) ) {
			newrelic_disable_autorum();
		}


		/**
		 * Squirrly SEO Plugin
		 *
		 * @since 1.8.3
		 * @link  https://wordpress.org/plugins/squirrly-seo/
		 */
		add_action( 'template_redirect', array( __CLASS__, 'squirrly_seo' ) );

	}


	/**
	 * Pre init action
	 */
	public static function pre_init() {

		remove_action( 'init', 'wpspgrpro_init_minify_html', 1 );
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
	 * Plugin loaded hook
	 */
	public static function plugins_loaded() {

		/**
		 * Initialize Custom permalinks support
		 */
		if ( function_exists( 'custom_permalinks_request' ) ) { // Guess is custom permalinks installed and active
			add_filter( 'request', 'Better_AMP_Plugin_Compatibility::custom_permalinks', 15 );
		}

		/**
		 * NextGEN Gallery Compatibility
		 */

		add_filter( 'run_ngg_resource_manager', '__return_false', 999 );


		/**
		 * WPML Compatibility
		 */
		if ( defined( 'WPML_PLUGIN_BASENAME' ) && WPML_PLUGIN_BASENAME ) {

			add_action( 'wpml_is_redirected', '__return_false' );
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


	/**
	 * WPML plugin compatibility fixes
	 */
	public static function fix_wpml_template_hooks() {

		global $wpml_language_resolution;

		/**
		 * @var SitePress $sitepress
		 */
		$sitepress = isset( $GLOBALS['sitepress'] ) ? $GLOBALS['sitepress'] : '';
		$callback  = array( $sitepress, 'display_wpml_footer' );

		if ( ! $sitepress || ! $sitepress instanceof SitePress ) {
			return;
		}


		if ( has_action( 'wp_footer', $callback ) ) {

			add_action( 'better-amp/template/footer', $callback );
		}

		if ( $sitepress->get_setting( 'language_negotiation_type' ) == '1' ) {

			add_filter( 'better-amp/transformer/exclude-subdir', array(
				$wpml_language_resolution,
				'get_active_language_codes'
			) );
		}
	}


	/**
	 * Drop amp start-point from pretty link slug
	 *
	 * @param bool|object $is_pretty_link
	 * @param string      $slug
	 *
	 * @since 1.7.0
	 * @return bool|object
	 */
	public static function pretty_links_compatibility( $is_pretty_link, $slug ) {

		if ( isset( $GLOBALS['prli_link'] ) && $GLOBALS['prli_link'] instanceof PrliLink ) {

			if ( preg_match( '#^/*' . Better_AMP::STARTPOINT . '/+(.+)$#i', $slug, $match ) ) {

				/**
				 * @var PrliLink $instance
				 */
				$instance = $GLOBALS['prli_link'];
				$callback = array( $instance, 'getOneFromSlug' );

				if ( is_callable( $callback ) ) {

					return call_user_func( $callback, $match[1] );
				}
			}
		}

		return $is_pretty_link;
	}

	/**
	 * Squirrly SEO Compatibility
	 *
	 * @since 1.8.3
	 */
	public static function squirrly_seo() {

		if ( ! is_callable( 'SQ_Classes_ObjController::getClass' ) ) {
			return;
		}

		$object = SQ_Classes_ObjController::getClass( 'SQ_Models_Services_Canonical' );

		remove_filter( 'sq_canonical', array( $object, 'packCanonical' ), 99 );

		add_action( 'sq_canonical', array( __class__, 'return_rel_canonical' ), 99 );
	}

	public static function return_rel_canonical() {

		if ( $canonical = better_amp_rel_canonical_url() ) {

			return '<link rel="canonical" href="' . $canonical . '"/>';
		}
	}
}


/**
 * Speed Booster Pack
 * https://wordpress.org/plugins/speed-booster-pack/
 */
if ( is_better_amp() && ! class_exists( 'Speed_Booster_Pack_Core' ) ) {
	/**
	 * Disables plugin fucntionality by overriding "Speed_Booster_Pack_Core" class
	 */
	class Speed_Booster_Pack_Core {

	}
}
