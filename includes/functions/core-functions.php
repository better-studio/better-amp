<?php
/**
 * Core functions for Better AMP
 *
 * @package    BetterAMP
 * @author     BetterStudio <info@betterstudio.com>
 * @copyright  Copyright (c) 2016, BetterStudio
 */

if ( ! function_exists( 'is_better_amp' ) ) {
	/**
	 * Detect is the query for an AMP page?
	 *
	 * @since 1.0.0
	 *
	 * @param null $wp_query
	 *
	 * @return bool true when amp page requested
	 */
	function is_better_amp( $wp_query = null ) {

		if ( $wp_query instanceof WP_Query ) {

			return false !== $wp_query->get( Better_AMP::STARTPOINT, false );
		}

		if ( did_action( 'template_redirect' ) && ! is_404() ) {

			global $wp_query;

			// check the $wp_query
			if ( is_null( $wp_query ) ) {

				return false;
			}

			return false !== $wp_query->get( Better_AMP::STARTPOINT, false );

		} elseif ( better_amp_using_permalink_structure() ) {

			$path = trim( dirname( $_SERVER['SCRIPT_NAME'] ), '/' );

			/**
			 * WPML Compatibility
			 *
			 * Append the language code after the path string when
			 *
			 * use 'Different languages in directories' wpml setting
			 */
			if ( function_exists( 'wpml_get_setting_filter' ) &&
			     wpml_get_setting_filter( false, 'language_negotiation_type' ) ) {

				if ( $current_lang = apply_filters( 'wpml_current_language', false ) ) {

					$path .= "/$current_lang";
				}
			}

			$amp_qv = defined( 'AMP_QUERY_VAR' ) ? AMP_QUERY_VAR : 'amp';

			return preg_match( "#^/?$path/*(.*?)/$amp_qv/*$#", $_SERVER['REQUEST_URI'] )
			       ||
			       preg_match( "#^/?$path/*$amp_qv/*#", $_SERVER['REQUEST_URI'] );

		} else {

			return ! empty( $_GET[ Better_AMP::STARTPOINT ] );
		}
	}
}

if ( ! function_exists( 'is_amp_endpoint' ) ) {

	/**
	 * Alias name for is_better_amp()
	 *
	 * @since 1.8.0
	 * @return bool
	 */
	function is_amp_endpoint() {

		return is_better_amp();
	}
}


/**
 * @param string $component_class component class name
 * @param array  $settings        component settings array {
 *
 * @type array   $tags            component amp tag. Example: amp-img
 * @type array   $scripts_url     component javascript URL. Example: https://cdn.ampproject.org/v0/..
 * }
 *
 * @global array $better_amp_registered_components
 *                                better-amp components information array
 *
 * @since 1.0.0
 *
 * @return bool|WP_Error true on success or WP_Error on failure.
 */
function better_amp_register_component( $component_class, $settings = array() ) {

	global $better_amp_registered_components;

	if ( ! isset( $better_amp_registered_components ) ) {
		$better_amp_registered_components = array();
	}

	try {
		if ( ! class_exists( $component_class ) ) {
			throw new Exception( __( 'invalid component class name.', 'better-amp' ) );
		}

		$interfaces = class_implements( 'Better_AMP_IMG_Component' );

		if ( ! isset( $interfaces ['Better_AMP_Component_Interface'] ) ) {
			throw new Exception( sprintf( __( 'Error! class %s must implements %s contracts!', 'better-amp' ), $component_class, 'Better_AMP_Component_Interface' ) );
		}

		$better_amp_registered_components[] = compact( 'component_class', 'settings' ); // maybe need add some extra indexes like __FILE__ in the future!

		return true;
	} catch( Exception $e ) {

		return new WP_Error( 'error', $e->getMessage() );
	}
} // better_amp_register_component


/**
 * Initialize $better_amp_scripts if it has not been set.
 *
 * @global Better_AMP_Scripts $better_amp_scripts
 *
 * @since 1.0.0
 *
 * @return Better_AMP_Scripts Better_AMP_Scripts instance.
 */
function better_amp_scripts() {

	global $better_amp_scripts;

	if ( ! ( $better_amp_scripts instanceof Better_AMP_Scripts ) ) {
		$better_amp_scripts = new Better_AMP_Scripts();
	}

	return $better_amp_scripts;
}


/**
 * Enqueue a js file for amp version.
 *
 * @see   wp_enqueue_script
 *
 * @param string $handle
 * @param string $src
 * @param array  $deps
 * @param string $media
 *
 * @since 1.0.0
 */
function better_amp_enqueue_script( $handle, $src = '', $deps = array(), $media = 'all' ) {

	$better_amp_scripts = better_amp_scripts();

	if ( $src ) {
		$_handle = explode( '?', $handle );
		$better_amp_scripts->add( $_handle[0], $src, $deps, false, $media );
	}

	$better_amp_scripts->enqueue( $handle );
}

/**
 * Check whether a script has been added to the queue.
 *
 * @param   string $handle
 * @param string   $list
 *
 * @since 1.0.0
 *
 * @return bool
 */
function better_amp_script_is( $handle, $list = 'enqueued' ) {

	return (bool) better_amp_scripts()->query( $handle, $list );
}


/**
 * Callback: Generate and echo scripts HTML tags
 * action  : better-amp/template/head
 *
 * @since 1.0.0
 */
function better_amp_print_scripts() {

	better_amp_scripts()->do_items();
}


/**
 * Callback: Custom hook for enqueue scripts action
 * action  : better-amp/template/head
 *
 * @since 1.0.0
 */
function better_amp_enqueue_scripts() {

	do_action( 'better-amp/template/enqueue-scripts' );
}


/**
 * Initialize $better_amp_styles if it has not been set.
 *
 * @global Better_AMP_Styles $better_amp_styles
 *
 * @since 1.0.0
 *
 * @return Better_AMP_Styles Better_AMP_Styles instance.
 */
function better_amp_styles() {

	global $better_amp_styles;

	if ( ! ( $better_amp_styles instanceof Better_AMP_Styles ) ) {
		$better_amp_styles = new Better_AMP_Styles();
	}

	return $better_amp_styles;
}


/**
 * Enqueue a css file for amp version.
 *
 * @see   wp_enqueue_style
 *
 * @param string           $handle
 * @param string           $src
 * @param array            $deps
 * @param string|bool|null $ver
 * @param string           $media
 *
 *
 * @since 1.0.0
 */
function better_amp_enqueue_style( $handle, $src = '', $deps = array(), $ver = false, $media = 'all' ) {

	$better_amp_styles = better_amp_styles();

	if ( $src ) {
		$_handle = explode( '?', $handle );
		$better_amp_styles->add( $_handle[0], $src, $deps, $ver, $media );
	}

	$better_amp_styles->enqueue( $handle );
}


/**
 * Check whether a style has been added to the queue.
 *
 * @param string $handle
 * @param string $list
 *
 * @since 1.1.0
 *
 * @return bool
 */
function better_amp_style_is( $handle, $list = 'enqueued' ) {

	return (bool) better_amp_styles()->query( $handle, $list );
}


/**
 * Handy function used to enqueue style and scripts of ads
 *
 * @since 1.1.0
 *
 * @param string $ad_type Ad type, needed to know the js should be printed or not
 *
 * @return void
 */
function better_amp_enqueue_ad( $ad_type = 'adsense' ) {

	if ( empty( $ad_type ) ) {
		return;
	}

	better_amp_enqueue_block_style( 'amd-ad', 'css/ads' );

	if ( $ad_type !== 'custom_code' || $ad_type !== 'image' ) {
		better_amp_enqueue_script( 'amp-ad', 'https://cdn.ampproject.org/v0/amp-ad-0.1.js' );
	}
}


/**
 * Callback: Generate and echo stylesheet HTML tags
 * action  : better-amp/template/head
 *
 * @since 1.0.0
 */
function better_amp_print_styles() {

	better_amp_styles()->do_items();
}


/**
 * Add extra CSS styles to a registered stylesheet.
 *
 * @see   wp_add_inline_style for more information
 *
 * @param string $handle Name of the stylesheet to add the extra styles to.
 * @param string $data   String containing the CSS styles to be added.
 *
 * @since 1.0.0
 *
 * @return bool True on success, false on failure.
 */
function better_amp_add_inline_style( $data, $handle = '' ) {

	if ( false !== stripos( $data, '</style>' ) ) {
		_doing_it_wrong( __FUNCTION__, sprintf(
			__( 'Do not pass %1$s tags to %2$s.', 'better-amp' ),
			'<code>&lt;style&gt;</code>',
			'<code>better_amp_add_inline_style()</code>'
		), '1.0.0' );
		$data = trim( preg_replace( '#<style[^>]*>(.*)</style>#is', '$1', $data ) );
	}

	$data = preg_replace( '/\s*!\s*important/', '', $data ); // Remove !important

	better_amp_styles()->add_inline_style( $handle, $data );
}


/**
 * Add css file data as inline style
 *
 * @see   wp_add_inline_style for more information
 *
 * @param string $handle Name of the stylesheet to add the extra styles to.
 * @param string $file   css file path
 *
 * @since 1.0.0
 *
 * @return bool True on success, false on failure.
 */
function better_amp_enqueue_inline_style( $file, $handle = '' ) {

	static $printed_files;

	if ( is_null( $printed_files ) ) {
		$printed_files = array();
	}

	if ( isset( $printed_files[ $file ] ) ) {
		return true;
	}

	ob_start();

	better_amp_locate_template( $file, true );

	$code = ob_get_clean();

	$code = apply_filters( "better-amp/style-files/{$file}", $code );

	better_amp_add_inline_style( $code, $handle );

	return $printed_files[ $file ] = true;
}


/**
 * Add css file data of block
 *
 * @see   wp_add_inline_style for more information
 *
 * @param string  $handle Name of the stylesheet to add the extra styles to.
 * @param string  $file   css file path
 * @param boolean $rtl    add rtl
 *
 * @since 1.0.0
 *
 * @return bool True on success, false on failure.
 */
function better_amp_enqueue_block_style( $handle, $file = '', $rtl = true ) {

	if ( empty( $handle ) ) {
		return false;
	}

	if ( empty( $file ) ) {
		if ( $handle === 'woocommerce' ) {
			$file = 'css/wc';
		} else {
			$file = 'css/' . $handle;
		}
	}

	static $printed_files;

	if ( is_null( $printed_files ) ) {
		$printed_files = array();
	}

	if ( isset( $printed_files[ $file ] ) ) {
		return true;
	}

	better_amp_enqueue_inline_style( better_amp_min_suffix( $file, '.css' ), $handle );

	if ( $rtl && is_rtl() ) {
		better_amp_enqueue_inline_style( better_amp_min_suffix( $file . '.rtl', '.css' ), $handle . '-rtl' );
	}

	return $printed_files[ $file ] = true;
}


/**
 * Get url of plugin directory
 *
 * @param string $path path to append the following url
 *
 * @since 1.0.0
 *
 * @return string
 */
function better_amp_plugin_url( $path = '' ) {

	$url = plugin_dir_url( __BETTER_AMP_FILE__ );

	if ( $path ) {
		$url .= $path;
	}

	return $url;
}

/**
 * Handle customizer static files in amp version
 * todo: fix javascript issue  - Live-update changed settings in real time not working :(
 *
 * @param WP_Customize_Manager $customize_manager
 *
 * @since 1.0.0
 */
function better_amp_customize_preview_init( $customize_manager ) {

	//	better_amp_enqueue_script( 'customize-preview' );
	wp_enqueue_script( 'customize-preview' );
	add_action( 'better-amp/template/head', array( $customize_manager, 'customize_preview_base' ) );
	add_action( 'better-amp/template/head', array( $customize_manager, 'customize_preview_html5' ) );
	add_action( 'better-amp/template/head', array( $customize_manager, 'customize_preview_loading_style' ) );
	add_action( 'better-amp/template/footer', array( $customize_manager, 'customize_preview_settings' ), 20 );

	do_action( 'better_amp_customize_preview_init', $customize_manager );
}


/**
 * Detects Non-AMP URL of current page
 *
 * @since 1.0.0
 *
 * @param array $args
 *
 * @return string
 */
function better_amp_guess_none_amp_url( $args = array() ) {

	if ( ! better_amp_using_permalink_structure() ) {

		return home_url( remove_query_arg( 'amp' ) );
	}

	$current_url = better_amp_get_canonical_url();
	$none_amp_url = Better_AMP_Content_Sanitizer::transform_to_none_amp_url( $current_url );

	// Change query args from outside
	if ( isset( $args['query-args'] ) && is_array( $args['query-args'] ) ) {
		foreach ( $args['query-args'] as $arg ) {
			$none_amp_url = add_query_arg( $arg[0], $arg[1], $none_amp_url );
		}
	}

	return $none_amp_url;
}


if ( ! function_exists( 'better_amp_translation_get' ) ) {
	/**
	 * Returns translation of strings from panel
	 *
	 * @param $key
	 *
	 * @since 1.0.0
	 *
	 * @return mixed|string
	 */
	function better_amp_translation_get( $key ) {

		static $option;

		if ( ! $option ) {
			$option = get_option( 'better-amp-translation' );
		}

		if ( ! empty( $option[ $key ] ) ) {
			return $option[ $key ];
		}

		static $std;

		if ( is_null( $std ) ) {
			$std = apply_filters( 'better-amp/translation/std', array() );
		}

		if ( isset( $std[ $key ] ) ) {

			// save it for next time
			$option[ $key ] = $std[ $key ];
			update_option( 'better-amp-translation', $option );

			return $std[ $key ];
		}

		return '';
	}
}


if ( ! function_exists( 'better_amp_translation_echo' ) ) {
	/**
	 * Prints translation of text
	 *
	 * @since 1.0.0
	 *
	 * @param $key
	 */
	function better_amp_translation_echo( $key ) {

		echo better_amp_translation_get( $key );
	}
}


/**
 * Sanitize and prepare css for amp version
 *
 * @param string $css
 *
 * @since 1.1
 * @return string
 */
function better_amp_css_sanitizer( $css ) {

	# -- Remove !important qualifier. --
	$css = preg_replace( '/\s*!\s*important/im', '', $css );

	# -- Remove invalid properties. --
	$invalid_properties = array(
		'behavior',
		'-moz-binding',
		'filter',
		'animation',
		'transition',
	);

	$pattern = '/((?:' . implode( '|', $invalid_properties ) . ')\s* :[^;]+ ;? \n*\t* )+/xs';
	$css     = preg_replace_callback( $pattern, function ( $var ) {

		return substr( $var[1], - 1 ) === '}' ? '}' : '';
	}, $css );

	return $css;
}

/**
 * Converts parsed URL to printable link
 *
 * @param $parsed_url
 *
 * @return string
 */
function better_amp_unparse_url( $parsed_url ) {

	$scheme   = isset( $parsed_url['scheme'] ) ? $parsed_url['scheme'] . '://' : '';
	$host     = isset( $parsed_url['host'] ) ? $parsed_url['host'] : '';
	$port     = isset( $parsed_url['port'] ) ? ':' . $parsed_url['port'] : '';
	$user     = isset( $parsed_url['user'] ) ? $parsed_url['user'] : '';
	$pass     = isset( $parsed_url['pass'] ) ? ':' . $parsed_url['pass'] : '';
	$pass     = ( $user || $pass ) ? "$pass@" : '';
	$path     = isset( $parsed_url['path'] ) ? $parsed_url['path'] : '';
	$query    = isset( $parsed_url['query'] ) ? '?' . $parsed_url['query'] : '';
	$fragment = isset( $parsed_url['fragment'] ) ? '#' . $parsed_url['fragment'] : '';

	//
	// schema has to be relative when there is no schema but host was defined!
	//
	if ( ! empty( $parsed_url['host'] ) && empty( $parsed_url['scheme'] ) ) {
		$scheme = '//';
	}

	return "$scheme$user$pass$host$port$path$query$fragment";
}


if ( ! function_exists( 'bf_get_wp_installation_slug' ) ) {
	/**
	 * TODO :-P >.<
	 *
	 * @since 1.3.1
	 *
	 * @todo  remove this function after adding BF to better-amp
	 * @return string
	 */
	function bf_get_wp_installation_slug() {

		static $path;

		if ( $path ) {
			return $path;
		}

		$abspath_fix         = str_replace( '\\', '/', ABSPATH );
		$script_filename_dir = dirname( $_SERVER['SCRIPT_FILENAME'] );

		if ( $script_filename_dir . '/' == $abspath_fix ) {
			// Strip off any file/query params in the path
			$path = preg_replace( '#/[^/]*$#i', '', $_SERVER['PHP_SELF'] );

		} elseif ( false !== strpos( $_SERVER['SCRIPT_FILENAME'], $abspath_fix ) ) {
			// Request is hitting a file inside ABSPATH
			$directory = str_replace( ABSPATH, '', $script_filename_dir );
			// Strip off the sub directory, and any file/query params
			$path = preg_replace( '#/' . preg_quote( $directory, '#' ) . '/[^/]*$#i', '', $_SERVER['REQUEST_URI'] );
		} elseif ( '' !== $script_filename_dir && false !== strpos( $abspath_fix, $script_filename_dir ) ) {
			// Request is hitting a file above ABSPATH
			$subdirectory = substr( $abspath_fix, strpos( $abspath_fix, $script_filename_dir ) + strlen( $script_filename_dir ) );
			// Strip off any file/query params from the path, appending the sub directory to the install
			$path = preg_replace( '#/[^/]*$#i', '', $_SERVER['REQUEST_URI'] ) . $subdirectory;
		} else {
			$path = '';
		}

		/**
		 * Fix For Multi-site Installation
		 */
		if ( is_multisite() && ! is_main_site() ) {
			$current_site_url = get_site_url();
			$append_path      = str_replace( get_site_url( get_current_site()->blog_id ), '', $current_site_url );

			if ( $append_path !== $current_site_url ) {
				$path .= $append_path;
			}
		}

		return $path;
	}
}

if ( ! function_exists( 'better_amp_wp_amp_compatibility_constants' ) ) {

	/**
	 * Define WP-AMP query constant for themes/plugins compatibility.
	 *
	 * @since 1.8.0
	 */
	function better_amp_wp_amp_compatibility_constants() {

		if ( ! defined( 'AMP_QUERY_VAR' ) ) {
			define( 'AMP_QUERY_VAR', 'amp' );
		}
	}
}

if ( ! function_exists( 'better_amp_permalink_prefix' ) ) {
	/**
	 * Get permalink structure prefix which is fixed in all urls.
	 *
	 * @since 1.8.1
	 *
	 * @return string
	 */
	function better_amp_permalink_prefix() {

		$permalink_structure = get_option( 'permalink_structure' );
		$prefix              = substr( $permalink_structure, 0, strpos( $permalink_structure, '%' ) );

		return ltrim( $prefix, '/' );
	}
}

if ( ! function_exists( 'better_amp_using_permalink_structure' ) ) {

	/**
	 * Is custom permalink activated for this WP installation?
	 *
	 * @since 1.8.1
	 * @return string  Custom structure	if custom permalink activated.
	 */
	function better_amp_using_permalink_structure() {

		return get_option( 'permalink_structure' );
	}
}
