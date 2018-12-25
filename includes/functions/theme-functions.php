<?php
/**
 * Theme functions for Better AMP
 *
 * @package    Better AMP
 * @author     BetterStudio <info@betterstudio.com>
 * @copyright  Copyright (c) 2016, BetterStudio
 */

// Used to save all template properties
$GLOBALS['better_amp_theme_core_props_cache'] = array();

// Used to save globals variables
$GLOBALS['better_amp_theme_core_globals_cache'] = array();

// Used to save template query
$GLOBALS['better_amp_theme_core_query'] = null;

if ( ! function_exists( 'better_amp_locate_template' ) ) {
	/**
	 * Retrieve the name of the highest priority amp template file that exists.
	 *
	 * @see   locate_template for more doc
	 *
	 * @param string|array $template_names Template file(s) to search for, in order.
	 * @param bool         $load           If true the template file will be loaded if it is found.
	 * @param bool         $require_once   Whether to require_once or require. Default true. Has no effect if $load is false.
	 *
	 * @since 1.0.0
	 *
	 * @return string The template filename if one is located.
	 */
	function better_amp_locate_template( $template_names, $load = false, $require_once = true ) {

		$wp_theme_can_override = current_theme_supports( 'better-amp-template' );

		/**
		 * Scan WordPress theme directory at first, if override feature was enabled
		 */
		if ( $wp_theme_can_override ) {
			$scan_directories = array(
				STYLESHEETPATH . '/' . BETTER_AMP_OVERRIDE_TPL_DIR . '/',
				TEMPLATEPATH . '/' . BETTER_AMP_OVERRIDE_TPL_DIR . '/',
				better_amp_get_template_directory()
			);
		} else {
			$scan_directories = array(
				better_amp_get_template_directory(),
				STYLESHEETPATH . '/' . BETTER_AMP_OVERRIDE_TPL_DIR . '/',
				TEMPLATEPATH . '/' . BETTER_AMP_OVERRIDE_TPL_DIR . '/',
			);
		}

		$scan_directories = array_unique( array_filter( $scan_directories ) );

		foreach ( $scan_directories as $theme_directory ) {
			if ( $theme_file_path = better_amp_load_templates( $template_names, $theme_directory, $load, $require_once ) ) {
				return $theme_file_path;
			}
		}

		// fallback: scan into theme-compat folder
		return better_amp_load_templates( $template_names, BETTER_AMP_TPL_COMPAT_ABSPATH, $load, $require_once );
	}
}

if ( ! function_exists( 'better_amp_load_templates' ) ) {
	/**
	 * Require the template file
	 *
	 * @param string|array $templates
	 * @param string       $theme_directory base directory. scan $templates files into this directory
	 * @param bool         $load
	 * @param bool         $require_once
	 *
	 * @see   better_amp_locate_template for parameters documentation
	 *
	 * @since 1.0.0
	 *
	 * @return bool|string
	 */
	function better_amp_load_templates( $templates, $theme_directory, $load = false, $require_once = true ) {

		foreach ( (array) $templates as $theme_file ) {

			$theme_file      = ltrim( $theme_file, '/' );
			$theme_directory = trailingslashit( $theme_directory );

			if ( file_exists( $theme_directory . $theme_file ) ) {

				if ( $load ) {
					if ( $require_once ) {
						require_once $theme_directory . $theme_file;
					} else {
						require $theme_directory . $theme_file;
					}
				}

				return $theme_directory . $theme_file;
			}
		}

		return false;
	}
}


if ( ! function_exists( 'better_amp_get_view' ) ) {
	/**
	 * Used to print view/partials.
	 *
	 * todo needs test
	 *
	 * @param   string $folder Folder name
	 * @param   string $file   File name
	 * @param   string $style  Style
	 * @param   bool   $echo   Echo the result or not
	 *
	 * @since 1.0.0
	 *
	 * @return null|string
	 */
	function better_amp_get_view( $folder, $file = '', $style = '', $echo = true ) {

		// If file name passed as folder argument for short method call
		if ( ! empty( $folder ) && empty( $file ) ) {
			$file   = $folder;
			$folder = '';
		}

		$templates = array();

		// File is inside another folder
		if ( ! empty( $folder ) ) {

			$templates[] = $folder . '/' . $file . '.php';

		} // File is inside style base folder
		else {

			$templates[] = $file . '.php';

		}

		$template = better_amp_locate_template( $templates, false, false );

		if ( $echo == false ) {
			ob_start();
		}

		//do_action( 'themename-theme-core/view/before/' . $file );

		if ( ! empty( $template ) ) {
			include $template;
		}

		//do_action( 'themename-theme-core/view/after/' . $file );

		if ( $echo == false ) {
			return ob_get_clean();
		}

	} // better_amp_get_view
}


//
//
// Blocks properties
//
//

if ( ! function_exists( 'better_amp_get_prop' ) ) {
	/**
	 * Used to get a property value.
	 *
	 * @param   string $id
	 * @param   mixed  $default
	 *
	 * @since 1.0.0
	 *
	 * @return  mixed
	 */
	function better_amp_get_prop( $id, $default = null ) {

		global $better_amp_theme_core_props_cache;

		if ( isset( $better_amp_theme_core_props_cache[ $id ] ) ) {
			return $better_amp_theme_core_props_cache[ $id ];
		} else {
			return $default;
		}
	}
}


if ( ! function_exists( 'better_amp_echo_prop' ) ) {
	/**
	 * Used to print a property value.
	 *
	 * @param   string $id
	 * @param   mixed  $default
	 *
	 * @since 1.0.0
	 *
	 * @return  mixed
	 */
	function better_amp_echo_prop( $id, $default = null ) {

		global $better_amp_theme_core_props_cache;

		if ( isset( $better_amp_theme_core_props_cache[ $id ] ) ) {
			echo $better_amp_theme_core_props_cache[ $id ]; // escaped before
		} else {
			echo $default; // escaped before
		}
	}
}


if ( ! function_exists( 'better_amp_get_prop_class' ) ) {
	/**
	 * Used to get block class property.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function better_amp_get_prop_class() {

		global $better_amp_theme_core_props_cache;

		if ( isset( $better_amp_theme_core_props_cache['class'] ) ) {
			return $better_amp_theme_core_props_cache['class'];
		} else {
			return '';
		}
	}
}


if ( ! function_exists( 'better_amp_get_prop_thumbnail_size' ) ) {
	/**
	 * Used to get block thumbnail size property.
	 *
	 * @param   string $default
	 *
	 * @since 1.0.0
	 *
	 * @return  string
	 */
	function better_amp_get_prop_thumbnail_size( $default = 'thumbnail' ) {

		global $better_amp_theme_core_props_cache;

		if ( isset( $better_amp_theme_core_props_cache['thumbnail-size'] ) ) {
			return $better_amp_theme_core_props_cache['thumbnail-size'];
		} else {
			return $default;
		}
	}
}


if ( ! function_exists( 'better_amp_set_prop' ) ) {
	/**
	 * Used to set a block property value.
	 *
	 * @param   string $id
	 * @param   mixed  $value
	 *
	 * @since 1.0.0
	 *
	 * @return  mixed
	 */
	function better_amp_set_prop( $id, $value ) {

		global $better_amp_theme_core_props_cache;

		$better_amp_theme_core_props_cache[ $id ] = $value;
	}
}


if ( ! function_exists( 'better_amp_set_prop_class' ) ) {
	/**
	 * Used to set a block class property value.
	 *
	 * @param   mixed $value
	 * @param   bool  $clean
	 *
	 * @since 1.0.0
	 *
	 * @return  mixed
	 */
	function better_amp_set_prop_class( $value, $clean = false ) {

		global $better_amp_theme_core_props_cache;

		if ( $clean ) {
			$better_amp_theme_core_props_cache['class'] = $value;
		} else {
			$better_amp_theme_core_props_cache['class'] = $value . ' ' . better_amp_get_prop_class();
		}
	}
}


if ( ! function_exists( 'better_amp_set_prop_thumbnail_size' ) ) {
	/**
	 * Used to set a block property value.
	 *
	 * @param   mixed $value
	 *
	 * @since 1.0.0
	 *
	 * @return  mixed
	 */
	function better_amp_set_prop_thumbnail_size( $value = 'thumbnail' ) {

		global $better_amp_theme_core_props_cache;

		$better_amp_theme_core_props_cache['thumbnail-size'] = $value;
	}
}


if ( ! function_exists( 'better_amp_unset_prop' ) ) {
	/**
	 * Used to remove a property from block property list.
	 *
	 * @param   string $id
	 *
	 * @since 1.0.0
	 *
	 * @return  mixed
	 */
	function better_amp_unset_prop( $id ) {

		global $better_amp_theme_core_props_cache;

		unset( $better_amp_theme_core_props_cache[ $id ] );
	}
}


if ( ! function_exists( 'better_amp_clear_props' ) ) {
	/**
	 * Used to clear all properties.
	 *
	 * @since 1.0.0
	 *
	 * @return  void
	 */
	function better_amp_clear_props() {

		global $better_amp_theme_core_props_cache;

		$better_amp_theme_core_props_cache = array();
	}
}


//
//
// Global Variables
//
//


if ( ! function_exists( 'better_amp_set_global' ) ) {
	/**
	 * Used to set a global variable.
	 *
	 * @param   string $id
	 * @param   mixed  $value
	 *
	 * @since 1.0.0
	 *
	 * @return  mixed
	 */
	function better_amp_set_global( $id, $value ) {

		global $better_amp_theme_core_globals_cache;

		$better_amp_theme_core_globals_cache[ $id ] = $value;
	}
}


if ( ! function_exists( 'better_amp_unset_global' ) ) {
	/**
	 * Used to remove a global variable.
	 *
	 * @param   string $id
	 *
	 * @since 1.0.0
	 *
	 * @return  mixed
	 */
	function better_amp_unset_global( $id ) {

		global $better_amp_theme_core_globals_cache;

		unset( $better_amp_theme_core_globals_cache[ $id ] );
	}
}


if ( ! function_exists( 'better_amp_get_global' ) ) {
	/**
	 * Used to get a global value.
	 *
	 * @param   string $id
	 * @param   mixed  $default
	 *
	 * @since 1.0.0
	 *
	 * @return  mixed
	 */
	function better_amp_get_global( $id, $default = null ) {

		global $better_amp_theme_core_globals_cache;

		if ( isset( $better_amp_theme_core_globals_cache[ $id ] ) ) {
			return $better_amp_theme_core_globals_cache[ $id ];
		} else {
			return $default;
		}
	}
}


if ( ! function_exists( 'better_amp_echo_global' ) ) {
	/**
	 * Used to print a global value.
	 *
	 * @param   string $id
	 * @param   mixed  $default
	 *
	 * @since 1.0.0
	 *
	 * @return  mixed
	 */
	function better_amp_echo_global( $id, $default = null ) {

		global $better_amp_theme_core_globals_cache;

		if ( isset( $better_amp_theme_core_globals_cache[ $id ] ) ) {
			echo $better_amp_theme_core_globals_cache[ $id ]; // escaped before
		} else {
			echo $default; // escaped before
		}
	}
}


if ( ! function_exists( 'better_amp_clear_globals' ) ) {
	/**
	 * Used to clear all properties.
	 *
	 * @since 1.0.0
	 *
	 * @return  void
	 */
	function better_amp_clear_globals() {

		global $better_amp_theme_core_globals_cache;

		$better_amp_theme_core_globals_cache = array();
	}
}


//
//
// Queries
//
//

if ( ! function_exists( 'better_amp_get_query' ) ) {
	/**
	 * Used to get current query.
	 *
	 * @since 1.0.0
	 *
	 * @return  WP_Query|null
	 */
	function better_amp_get_query() {

		global $better_amp_theme_core_query;

		// Add default query to ThemeName query if its not added or default query is used.
		if ( ! is_a( $better_amp_theme_core_query, 'WP_Query' ) ) {
			global $wp_query;

			$better_amp_theme_core_query = &$wp_query;
		}

		return $better_amp_theme_core_query;
	}
}


if ( ! function_exists( 'better_amp_set_query' ) ) {
	/**
	 * Used to get current query.
	 *
	 * @param   WP_Query $query
	 *
	 * @since 1.0.0
	 *
	 */
	function better_amp_set_query( &$query ) {

		global $better_amp_theme_core_query;

		$better_amp_theme_core_query = $query;
	}
}


if ( ! function_exists( 'better_amp_clear_query' ) ) {
	/**
	 * Used to get current query.
	 *
	 * @param   bool $reset_query
	 *
	 * @since 1.0.0
	 *
	 */
	function better_amp_clear_query( $reset_query = true ) {

		global $better_amp_theme_core_query;

		$better_amp_theme_core_query = null;

		// This will remove obscure bugs that occur when the previous wp_query object is not destroyed properly before another is set up.
		if ( $reset_query ) {
			wp_reset_query();
		}
	}
}


if ( ! function_exists( 'better_amp_have_posts' ) ) {
	/**
	 * Used for checking have posts in advanced way!
	 *
	 * @since 1.0.0
	 */
	function better_amp_have_posts() {

		// Add default query to better_template query if its not added or default query is used.
		if ( ! better_amp_get_query() instanceof WP_Query ) {
			global $wp_query;

			better_amp_set_query( $wp_query );
		}

		// If count customized
		if ( better_amp_get_prop( 'posts-count', null ) != null ) {
			if ( better_amp_get_prop( 'posts-counter', 1 ) > better_amp_get_prop( 'posts-count' ) ) {
				return false;
			} else {
				if ( better_amp_get_query()->current_post + 1 < better_amp_get_query()->post_count ) {
					return true;
				} else {
					return false;
				}
			}
		} else {
			return better_amp_get_query()->current_post + 1 < better_amp_get_query()->post_count;
		}
	}
}


if ( ! function_exists( 'better_amp_the_post' ) ) {
	/**
	 * Custom the_post for custom counter functionality
	 *
	 * @since 1.0.0
	 */
	function better_amp_the_post() {

		// If count customized
		if ( better_amp_get_prop( 'posts-count', null ) != null ) {
			better_amp_set_prop( 'posts-counter', absint( better_amp_get_prop( 'posts-counter', 1 ) ) + 1 );
		}

		// Do default the_post
		better_amp_get_query()->the_post();
	}
}


if ( ! function_exists( 'better_amp_the_post_thumbnail' ) ) {
	/**
	 * Display the post thumbnail.
	 *
	 * @since 1.1.0
	 *
	 * @param string $size
	 * @param string $attr
	 */
	function better_amp_the_post_thumbnail( $size = 'post-thumbnail', $attr = '' ) {

		if ( empty( $attr ) ) {
			$attr = array(
				'alt'    => the_title_attribute( array( 'echo' => false ) ),
				'layout' => 'responsive',
			);
		}

		the_post_thumbnail( $size, $attr );
	}
}


if ( ! function_exists( 'better_amp_is_main_query' ) ) {
	/**
	 * Detects and returns that current query is main query or not? with support of better_{get|set}_query
	 *
	 * @since 1.0.0
	 *
	 * @return  WP_Query|null
	 */
	function better_amp_is_main_query() {

		global $better_amp_theme_core_query;

		// Add default query to better_template query if its not added or default query is used.
		if ( ! is_a( $better_amp_theme_core_query, 'WP_Query' ) ) {
			global $wp_query;

			return $wp_query->is_main_query();
		}

		return $better_amp_theme_core_query->is_main_query();
	}
}


if ( ! function_exists( 'better_amp_head' ) ) {
	/**
	 * Fire the better_amp_head action.
	 *
	 * @since 1.0.0
	 */
	function better_amp_head() {

		do_action( 'better-amp/template/head' );
	}
}


if ( ! function_exists( 'better_amp_footer' ) ) {
	/**
	 * Fire the better_amp_footer action.
	 *
	 * @since 1.0.0
	 */
	function better_amp_footer() {

		do_action( 'better-amp/template/footer' );
	}
}


if ( ! function_exists( 'better_amp_body_class' ) ) {
	/**
	 * Display the classes for the body element.
	 *
	 * @param string|array $class One or more classes to add to the class list.
	 *
	 * @since 1.0.0
	 */
	function better_amp_body_class( $class = '' ) {

		echo 'class="' . join( ' ', get_body_class( $class ) ) . '"';
	}
}


if ( ! function_exists( 'better_amp_get_header' ) ) {
	/**
	 * Load footer template.
	 *
	 * @param string $name The name of the specialised header.
	 *
	 * @since 1.0.0
	 */
	function better_amp_get_header( $name = null ) {

		$templates = array();

		$name = (string) $name;

		if ( '' !== $name ) {
			$templates[] = "header-{$name}.php";
		}

		$templates[] = 'header.php';

		better_amp_locate_template( $templates, true );
	}
}


if ( ! function_exists( 'better_amp_get_footer' ) ) {
	/**
	 * Load footer template.
	 *
	 * @param string $name Name of the specific footer file to use.
	 *
	 * @since 1.0.0
	 */
	function better_amp_get_footer( $name = null ) {

		$templates = array();

		$name = (string) $name;

		if ( '' !== $name ) {
			$templates[] = "footer-{$name}.php";
		}

		$templates[] = 'footer.php';

		better_amp_locate_template( $templates, true );
	}
}


if ( ! function_exists( 'better_amp_get_sidebar' ) ) {
	/**
	 * Load sidebar template.
	 *
	 * @param string $name The name of the specialised sidebar.
	 *
	 * @since 1.0.0
	 */
	function better_amp_get_sidebar( $name = null ) {

		$templates = array();

		$name = (string) $name;

		if ( '' !== $name ) {
			$templates[] = "sidebar-{$name}.php";
		}

		$templates[] = 'sidebar.php';

		better_amp_locate_template( $templates, true );
	}
}


if ( ! function_exists( 'better_amp_get_template_info' ) ) {
	/**
	 * Get active amp theme information
	 *
	 * array {
	 *
	 * @type string     $Version      Template Semantic Version  Number {@link http://semver.org/}
	 * @type string     $ScreenShot   -optional: screenshot.png- Relative Path to ScreenShot.
	 * @type int|string $MaxWidth     -optional:600- Maximum Template Container Width.
	 * @type string     $TemplateRoot Absolute Path to Template Directory
	 * @type string     $Description  Template Description
	 * @type string     $AuthorURI    Template Author URL
	 * @type string     $Author       Template Author
	 * @type string     $Name         Template name
	 * @type string     $ThemeURI     Template URL
	 * }
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function better_amp_get_template_info() {

		return wp_parse_args(
			apply_filters( 'better-amp/template/active-template', array() ),
			array(
				'ScreenShot' => 'screenshot.png',
				'MaxWidth'   => 780,
				'view'       => 'general'
			)
		);
	}
}


if ( ! function_exists( 'better_amp_get_template_directory' ) ) {
	/**
	 * Get absolute path to active better-amp theme directory
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function better_amp_get_template_directory() {

		if ( $theme_info = better_amp_get_template_info() ) {
			return $theme_info['TemplateRoot'];
		}

		return '';
	}
}


if ( ! function_exists( 'better_amp_get_container_width' ) ) {
	/**
	 * Get maximum container width
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	function better_amp_get_container_width() {

		$info = better_amp_get_template_info();

		return (int) $info['MaxWidth'];
	}
}


if ( ! function_exists( 'better_amp_guess_height' ) ) {
	/**
	 * Calculate height fits to width
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	function better_amp_guess_height() {

		return better_amp_get_container_width() * 0.75;
	}
}


if ( ! function_exists( 'better_amp_get_hw_attr' ) ) {
	/**
	 * Get width & height attribute
	 *
	 * @param string $width  Custom width
	 * @param string $height Custom height
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function better_amp_get_hw_attr( $width = '', $height = '' ) {

		$attr = '';

		if ( empty( $width ) ) {
			$width = better_amp_get_container_width();
		}

		if ( $width ) {
			$attr .= 'width="' . intval( $width ) . '" ';
		}

		if ( empty( $height ) ) {
			$height = better_amp_guess_height();
		}

		if ( $height ) {
			$attr .= 'height="' . intval( $height ) . '" ';
		}

		return $attr;
	}
}


if ( ! function_exists( 'better_amp_hw_attr' ) ) {
	/**
	 * Get width & height attribute
	 *
	 * @param string $width
	 * @param string $height
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	function better_amp_hw_attr( $width = '', $height = '' ) {

		echo better_amp_get_hw_attr( $width, $height );
	}
}


if ( ! function_exists( 'better_amp_get_comment_link' ) ) {
	/**
	 * Returns Non-AMP comment link for AMP post
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	function better_amp_get_comment_link() {

		$prev = Better_AMP_Content_Sanitizer::turn_url_transform_off_on( false );

		$comments_url = get_permalink() . '#respond';

		Better_AMP_Content_Sanitizer::turn_url_transform_off_on( $prev );

		return $comments_url;
	}
}

if ( ! function_exists( 'better_amp_comment_reply_link' ) ) {
	/**
	 * Retrieve the HTML content for reply to comment link.
	 *
	 * @param array $args @see comment_reply_link for documentation
	 *
	 * @since 1.5.0
	 * @return void|false|string
	 */
	function better_amp_comment_reply_link( $args = array() ) {

		$current_value                                      = Better_AMP_Content_Sanitizer::$enable_url_transform;
		Better_AMP_Content_Sanitizer::$enable_url_transform = false;

		$result                                             = comment_reply_link( $args );
		Better_AMP_Content_Sanitizer::$enable_url_transform = $current_value;

		return $result;
	}
}


if ( ! function_exists( 'better_amp_comment_link' ) ) {
	/**
	 * Non-AMP comment link for AMP post
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	function better_amp_comment_link() {

		echo esc_attr( better_amp_get_comment_link() );
	}
}

if ( ! function_exists( 'better_amp_rel_canonical_url' ) ) {

	/**
	 * Get rel=canonical tag url.
	 *
	 * @since 1.8.3
	 * @return string
	 */
	function better_amp_rel_canonical_url() {

		$canonical_url = better_amp_get_canonical_url();

		if ( ! $canonical_url ) {
			$canonical_url = better_amp_site_url();
		}

		return Better_AMP_Content_Sanitizer::transform_to_none_amp_url( $canonical_url );
	}
}

if ( ! function_exists( 'better_amp_print_rel_canonical' ) ) {
	/**
	 * Print rel=canonical tag in AMP version
	 *
	 * @param bool $echo
	 *
	 * @since 1.0.0
	 * @return string
	 */
	function better_amp_print_rel_canonical() {


		if ( $canonical = better_amp_rel_canonical_url() ) {
			?>
			<link rel="canonical" href="<?php echo esc_attr( $canonical ) ?>"/>
			<?php
		}
	}
}


if ( ! function_exists( 'better_amp_get_canonical_url' ) ) {
	/**
	 * Get the active page url
	 *
	 * @copyright we used WPSEO_Frontend::generate_canonical codes
	 *
	 * @since     1.0.0
	 * @return string the url page on success or empty string otherwise.
	 */
	function better_amp_get_canonical_url() {

		$parse = parse_url( home_url() );

		return sprintf( '%s://%s%s', $parse['scheme'], $parse['host'], add_query_arg( false, false ) );
	}
}


if ( ! function_exists( 'better_amp_print_rel_amphtml' ) ) {
	/**
	 * Print rel=amphtml tag
	 *
	 * @since 1.0.0
	 */
	function better_amp_print_rel_amphtml() {

		if ( ! Better_AMP::get_instance()->amp_version_exists() ) {
			return;
		}

		$page_url  = better_amp_get_canonical_url();
		$canonical = Better_AMP_Content_Sanitizer::transform_to_amp_url(
			$page_url
		);

		if ( $canonical !== $page_url ) {
			?>
			<link rel="amphtml" href="<?php echo esc_attr( $canonical ) ?>"/>
			<?php
		}
	}
}


if ( ! function_exists( 'better_amp_enqueue_boilerplate_style' ) ) {
	/**
	 * Print required amp style to head
	 *
	 * @link  https://github.com/ampproject/amphtml/blob/master/spec/amp-boilerplate.md
	 *
	 * @since 1.0.0
	 */
	function better_amp_enqueue_boilerplate_style() {

		echo <<<AMP_Boilerplate
<style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
AMP_Boilerplate;

	}
}


if ( ! function_exists( 'better_amp_get_search_page_url' ) ) {
	/**
	 * Get AMP index page url
	 *
	 * @param string $path            Optional. Path relative to the site URL. Default empty.
	 * @param string $before_sp .     Custom string to append before amp start point. Default empty.
	 * @param bool   $front_page_url  Optional. see the following comment.
	 *
	 * @return string
	 * @since 1.0.0
	 *
	 */
	function better_amp_site_url( $path = '', $before_sp = '', $front_page_url = null ) {

		if ( better_amp_using_permalink_structure() ) {

			$url_prefix = better_amp_permalink_prefix();

			if(!isset($front_page_url)) {
				$front_page_url = $path === '';
			}

			/**
			 * Prepend permalink structure prefix before amp cause 404 error in search page
			 * So we added $front_page_url parameter to bypass this functionality.
			 *
			 * @see     better_amp_permalink_prefix
			 * @see     better_amp_get_search_page_url
			 *
			 * @example when structure is /topics/%post_id%/%postname/ and $front_page_url = false
			 * Then the search page will be /topics/amp/?s which cause 404 error
			 */
			$url = trailingslashit( home_url( $front_page_url ? '' : $url_prefix ) );
			$url .= $before_sp ? trailingslashit( $before_sp ) : '';
			$url .= Better_AMP::STARTPOINT;


			if ( ! empty( $path ) && $url_prefix && preg_match( '#^' . preg_quote( $url_prefix, '#' ) . '(.+)$#i', $path, $match ) ) {

				$path = rtrim( $match[1], '/' );
			}

			if ( $path ) {

				$path = ltrim( $path, '/' );
				$url  .= "/$path";
			}

		} else {

			$url = add_query_arg( Better_AMP::STARTPOINT, true, home_url( $path ) );
		}

		return $url;
	}
}


if ( ! function_exists( 'better_amp_do_shortcode' ) ) {
	/**
	 * Do component shortcodes like wordpress: do_shortcode function
	 *
	 * @since 1.0.0
	 *
	 * @return string Content with shortcodes filtered out
	 */
	function better_amp_do_shortcode() {

		static $registered;

		$args = func_get_args();

		if ( ! $registered ) {

			Better_AMP::get_instance()->call_components_method( 'register_shortcodes' );

			$registered = true;
		}

		return call_user_func_array( 'do_shortcode', $args );
	}
}


if ( ! function_exists( 'better_amp_theme_set_menu_walker' ) ) {
	/**
	 * Change menu walker only for main amp site navigation
	 *
	 * Walker of navigation menu with 'amp-sidebar-nav' theme_location  going to change' Better_AMP_Menu_Walker'.
	 *
	 *
	 * @param array $args Array of wp_nav_menu() arguments.
	 *
	 * @see    Better_AMP_Menu_Walker
	 * @see    default-filters.php file
	 *
	 * @since  1.0.0
	 * @return array modified $args
	 */
	function better_amp_theme_set_menu_walker( $args ) {

		if ( ! is_better_amp() | ! has_nav_menu( $args['theme_location'] ) ) {
			return $args;
		}

		if ( apply_filters( 'better-amp/template/set-menu-walker', $args['theme_location'] === 'amp-sidebar-nav', $args ) ) {

			add_theme_support( 'better-amp-navigation' );

			$args['walker'] = new Better_AMP_Menu_Walker;
		}

		return $args;
	}
}


if ( ! function_exists( 'better_amp_enqueue_rtl_style' ) ) {
	/**
	 * Print rtl.css content as inline css in RTL version if file exists
	 *
	 * @since 1.0.0
	 */
	function better_amp_enqueue_rtl_style() {

		if ( ! is_rtl() ) {
			return;
		}

		$theme_info = better_amp_get_template_info();

		$rtl_style = trailingslashit( $theme_info['TemplateRoot'] ) . 'rtl.css';

		if ( file_exists( $rtl_style ) ) {
			better_amp_enqueue_inline_style( $rtl_style, 'better-amp-rtl' );
		}
	}
}


if ( ! function_exists( 'better_amp_direction' ) ) {
	/**
	 * Handy function to print 'right' string on rtl mode and 'left' otherwise!
	 *
	 *     * @param bool $reverse
	 *
	 * @since 1.0.0
	 *
	 */
	function better_amp_direction( $reverse = false ) {

		if ( $reverse ) {
			echo is_rtl() ? 'left' : 'right';
		} else {
			echo is_rtl() ? 'right' : 'left';
		}

	}
}


if ( ! function_exists( 'better_amp_fix_customizer_statics' ) ) {
	/**
	 * Fix for loading js/css static files in customize.php page
	 *
	 * @since 1.0.0
	 */
	function better_amp_fix_customizer_statics() {

		if ( is_customize_preview() ) {
			add_action( 'better-amp/template/head', 'wp_head', 1, 1 );
			add_action( 'better-amp/template/footer', 'wp_footer', 1, 1 );
		}

	} // better_amp_fix_customizer_statics
}


/**
 * Better-AMP Template functions
 *
 * We used wordpress core functions and renamed some get_* functions to better_amp_*
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @see  wp-includes/template.php
 *
 * |           Original Name            |                  AMP Name                  |
 * | ---------------------------------- | ------------------------------------------ |
 * |   get_embed_template               |     better_amp_embed_template              |
 * |   get_404_template                 |     better_amp_404_template                |
 * |   get_search_template              |     better_amp_search_template             |
 * |   get_front_page_template          |     better_amp_front_page_template         |
 * |   get_home_template                |     better_amp_home_template               |
 * |   get_post_type_archive_template   |     better_amp_post_type_archive_template  |
 * |   get_taxonomy_template            |     better_amp_taxonomy_template           |
 * |   get_attachment_template          |     better_amp_attachment_template         |
 * |   get_single_template              |     better_amp_single_template             |
 * |   get_page_template                |     better_amp_page_template               |
 * |   get_singular_template            |     better_amp_singular_template           |
 * |   get_category_template            |     better_amp_category_template           |
 * |   get_tag_template                 |     better_amp_tag_template                |
 * |   get_author_template              |     better_amp_author_template             |
 * |   get_date_template                |     better_amp_date_template               |
 * |   get_archive_template             |     better_amp_archive_template            |
 * |   get_paged_template               |     better_amp_paged_template              |
 * |   get_archive_template             |     better_amp_archive_template            |
 * |   get_index_template               |     better_amp_index_template              |
 * |   get_template_part                |     better_amp_template_part               |
 */


if ( ! function_exists( 'better_amp_embed_template' ) ) {
	/**
	 * Retrieves an embed template path in the current or parent template.
	 *
	 * @since 1.0.0
	 *
	 * @see   get_embed_template
	 *
	 * @return string Full path to embed template file.
	 */
	function better_amp_embed_template() {

		$object = get_queried_object();

		$templates = array();

		if ( ! empty( $object->post_type ) ) {

			$post_format = get_post_format( $object );

			if ( $post_format ) {
				$templates[] = "embed-{$object->post_type}-{$post_format}.php";
			}

			$templates[] = "embed-{$object->post_type}.php";
		}

		$templates[] = "embed.php";

		return better_amp_locate_template( $templates );
	}
}


if ( ! function_exists( 'better_amp_404_template' ) ) {
	/**
	 * Retrieve path of 404 template in current or parent template.
	 *
	 * @see   get_404_template()
	 *
	 * @since 1.0.0
	 *
	 * @return string Full path to 404 template file.
	 */
	function better_amp_404_template() {

		return better_amp_locate_template( '404.php' );
	}
}


if ( ! function_exists( 'better_amp_search_template' ) ) {
	/**
	 * Retrieve path of search template in current or parent template.
	 *
	 * @see   get_search_template()
	 *
	 * @since 1.0.0
	 *
	 * @return string Full path to search template file.
	 */
	function better_amp_search_template() {

		return better_amp_locate_template( 'search.php' );
	}
}


if ( ! function_exists( 'better_amp_front_page_template' ) ) {
	/**
	 * Retrieve path of front-page template in current or parent template.
	 *
	 * @see   get_front_page_template()
	 *
	 * @since 1.0.0
	 *
	 * @return string Full path to front page template file.
	 */
	function better_amp_front_page_template() {

		return better_amp_locate_template( 'front-page.php' );
	}
}

if ( ! function_exists( 'better_amp_static_home_page_template' ) ) {
	/**
	 * Retrieve path of static homepage template in current or parent template.
	 *
	 * @since 1.2.4
	 * @return string Full path to static home page template file.
	 */
	function better_amp_static_home_page_template() {

		if ( $template = better_amp_front_page_template() ):
		elseif ( $template = better_amp_page_template() ):
		elseif ( $template = better_amp_singular_template() ):
		endif;

		return $template;
	}
}


if ( ! function_exists( 'better_amp_home_template' ) ) {
	/**
	 * Retrieve path of home template in current or parent template.
	 *
	 * @see   get_home_template()
	 *
	 * @since 1.0.0
	 *
	 * @return string Full path to home template file.
	 */
	function better_amp_home_template() {

		$templates = array( 'home.php', 'index.php' );

		return better_amp_locate_template( $templates );
	}
}


if ( ! function_exists( 'better_amp_archive_template' ) ) {
	/**
	 * Retrieve path of archive template in current or parent template.
	 *
	 * @see   get_archive_template()
	 *
	 * @since 1.0.0
	 *
	 * @return string Full path to archive template file.
	 */
	function better_amp_archive_template() {

		$post_types = array_filter( (array) get_query_var( 'post_type' ) );

		$templates = array();

		if ( count( $post_types ) == 1 ) {
			$post_type   = reset( $post_types );
			$templates[] = "archive-{$post_type}.php";
		}

		$templates[] = 'archive.php';

		return better_amp_locate_template( $templates );
	}
}


if ( ! function_exists( 'better_amp_post_type_archive_template' ) ) {
	/**
	 * Retrieve path of post type archive template in current or parent template.
	 *
	 * @see   better_amp_archive_template()
	 * @see   get_post_type_archive_template()
	 *
	 * @since 1.0.0
	 *
	 * @return string Full path to archive template file.
	 */
	function better_amp_post_type_archive_template() {

		$post_type = get_query_var( 'post_type' );

		if ( is_array( $post_type ) ) {
			$post_type = reset( $post_type );
		}

		$obj = get_post_type_object( $post_type );

		if ( ! $obj->has_archive ) {
			return '';
		}

		return better_amp_archive_template();
	}
}


if ( ! function_exists( 'better_amp_taxonomy_template' ) ) {
	/**
	 * Retrieve path of taxonomy template in current or parent template.
	 *
	 * @since 1.0.0
	 *
	 * @return string Full path to taxonomy template file.
	 */
	function better_amp_taxonomy_template() {

		$term = get_queried_object();

		$templates = array();

		if ( ! empty( $term->slug ) ) {
			$taxonomy    = $term->taxonomy;
			$templates[] = "taxonomy-$taxonomy-{$term->slug}.php";
			$templates[] = "taxonomy-$taxonomy.php";
		}

		$templates[] = 'taxonomy.php';

		return better_amp_locate_template( $templates );
	}
}


if ( ! function_exists( 'better_amp_attachment_template' ) ) {
	/**
	 * Retrieve path of attachment template in current or parent template.
	 *
	 * @global array $posts
	 *
	 * @since 1.0.0
	 *
	 * @return string Full path to attachment template file.
	 */
	function better_amp_attachment_template() {

		$attachment = get_queried_object();

		$templates = array();

		if ( $attachment ) {

			if ( false !== strpos( $attachment->post_mime_type, '/' ) ) {
				list( $type, $subtype ) = explode( '/', $attachment->post_mime_type );
			} else {
				list( $type, $subtype ) = array( $attachment->post_mime_type, '' );
			}

			if ( ! empty( $subtype ) ) {
				$templates[] = "{$type}-{$subtype}.php";
				$templates[] = "{$subtype}.php";
			}
			$templates[] = "{$type}.php";

		}

		$templates[] = 'attachment.php';

		return better_amp_locate_template( $templates );
	}
}


if ( ! function_exists( 'better_amp_single_template' ) ) {
	/**
	 * Retrieve path of single template in current or parent template.
	 *
	 * @since 1.0.0
	 *
	 * @return string Full path to single template file.
	 */
	function better_amp_single_template() {

		$object = get_queried_object();

		$templates = array();

		if ( ! empty( $object->post_type ) ) {
			$templates[] = "single-{$object->post_type}-{$object->post_name}.php";
			$templates[] = "single-{$object->post_type}.php";
		}

		$templates[] = "single.php";

		return better_amp_locate_template( $templates );
	}
}


if ( ! function_exists( 'better_amp_page_template' ) ) {
	/**
	 * Retrieve path of page template in current or parent template.
	 *
	 * @see   get_page_template()
	 *
	 * @since 1.0.0
	 *
	 * @return string Full path to page template file.
	 */
	function better_amp_page_template() {

		$id       = get_queried_object_id();
		$template = get_page_template_slug();
		$pagename = get_query_var( 'pagename' );

		if ( ! $pagename && $id ) {

			// If a static page is set as the front page, $pagename will not be set. Retrieve it from the queried object
			$post = get_queried_object();

			if ( $post ) {
				$pagename = $post->post_name;
			}

		}

		$templates = array();

		if ( $template && 0 === validate_file( $template ) ) {
			$templates[] = $template;
		}

		if ( $pagename ) {
			$templates[] = "page-$pagename.php";
		}

		if ( $id ) {
			$templates[] = "page-$id.php";
		}

		$templates[] = 'page.php';

		return better_amp_locate_template( $templates );
	}
}


if ( ! function_exists( 'better_amp_singular_template' ) ) {
	/**
	 * Retrieves the path of the singular template in current or parent template.
	 *
	 * @since 1.0.0
	 *
	 * @return string Full path to singular template file
	 */
	function better_amp_singular_template() {

		return better_amp_locate_template( 'singular.php' );
	}
}


if ( ! function_exists( 'better_amp_category_template' ) ) {
	/**
	 * Retrieve path of category template in current or parent template.
	 *
	 * @since 1.0.0
	 *
	 * @return string Full path to category template file.
	 */
	function better_amp_category_template() {

		$category = get_queried_object();

		$templates = array();

		if ( ! empty( $category->slug ) ) {
			$templates[] = "category-{$category->slug}.php";
			$templates[] = "category-{$category->term_id}.php";
		}

		$templates[] = 'category.php';

		return better_amp_locate_template( $templates );
	}
}


if ( ! function_exists( 'better_amp_tag_template' ) ) {
	/**
	 * Retrieve path of tag template in current or parent template.
	 *
	 * @see   get_query_template()
	 *
	 * @since 1.0.0
	 *
	 * @return string Full path to tag template file.
	 */
	function better_amp_tag_template() {

		$tag = get_queried_object();

		$templates = array();

		if ( ! empty( $tag->slug ) ) {
			$templates[] = "tag-{$tag->slug}.php";
			$templates[] = "tag-{$tag->term_id}.php";
		}

		$templates[] = 'tag.php';

		return better_amp_locate_template( $templates );
	}
}


if ( ! function_exists( 'better_amp_author_template' ) ) {

	/**
	 * Retrieve path of author template in current or parent template.
	 *
	 * @since 1.0.0
	 *
	 * @return string Full path to author template file.
	 */
	function better_amp_author_template() {

		$author = get_queried_object();

		$templates = array();

		if ( $author instanceof WP_User ) {
			$templates[] = "author-{$author->user_nicename}.php";
			$templates[] = "author-{$author->ID}.php";
		}

		$templates[] = 'author.php';

		return better_amp_locate_template( $templates );
	}
}


if ( ! function_exists( 'better_amp_date_template' ) ) {
	/**
	 * Retrieve path of date template in current or parent template.
	 *
	 * @since 1.0.0
	 *
	 * @return string Full path to date template file.
	 */
	function better_amp_date_template() {

		return better_amp_locate_template( 'date.php' );
	}
}


if ( ! function_exists( 'better_amp_paged_template' ) ) {
	/**
	 * Retrieve path of paged template in current or parent template.
	 *
	 * @since 1.0.0
	 *
	 * @return string Full path to paged template file.
	 */
	function better_amp_paged_template() {

		return better_amp_locate_template( 'paged.php' );
	}
}


if ( ! function_exists( 'better_amp_index_template' ) ) {
	/**
	 * Retrieve path of index template in current or parent template.
	 *
	 * @since 1.0.0
	 *
	 * @return string Full path to index template file.
	 */
	function better_amp_index_template() {

		return better_amp_locate_template( 'index.php' );
	}
}


if ( ! function_exists( 'better_amp_get_search_form' ) ) {
	/**
	 * Retrieve path of search form in current or parent template.
	 *
	 * @since 1.0.0
	 *
	 * @return string Full path to index template file.
	 */
	function better_amp_get_search_form() {

		add_theme_support( 'better-amp-form' );

		return better_amp_locate_template( 'searchform.php', true );
	}
}


if ( ! function_exists( 'better_amp_template_part' ) ) {
	/**
	 * Load a template part into a template
	 *
	 * @see   get_template_part for more documentation
	 *
	 * @param string $slug The slug name for the generic template.
	 * @param string $name The name of the specialised template.
	 *
	 * @since 1.0.0
	 */
	function better_amp_template_part( $slug, $name = null ) {

		$templates = array();
		$name      = (string) $name;
		if ( '' !== $name ) {
			$templates[] = "{$slug}-{$name}.php";
		}

		$templates[] = "{$slug}.php";

		better_amp_locate_template( $templates, true, false );
	}
}


if ( ! function_exists( 'better_amp_get_search_page_url' ) ) {
	/**
	 * Get search page url
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function better_amp_get_search_page_url() {

		/**
		 * The s query var must always add to AMP front-page url. for more information see the following function.
		 *
		 * @see better_amp_site_url
		 */
		$front_page_url = true;

		return esc_url( add_query_arg( 's', '', better_amp_site_url( '', '', $front_page_url ) ) );
	}
}


if ( ! function_exists( 'better_amp_get_thumbnail' ) ) {
	/**
	 * Used to get thumbnail image for posts with support of default thumbnail image
	 *
	 * @param string $thumbnail_size
	 * @param null   $post_id
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function better_amp_get_thumbnail( $thumbnail_size = 'thumbnail', $post_id = null ) {

		if ( is_null( $post_id ) ) {
			$post_id = get_the_ID();
		}

		$thumbnail_id = get_post_thumbnail_id( $post_id );

		$img = wp_get_attachment_image_src( $thumbnail_id, $thumbnail_size );

		if ( $img ) {
			return array(
				'src'    => $img[0],
				'width'  => $img[1],
				'height' => $img[2],
			);
		}

		$img = array(
			'src'    => '',
			'width'  => '',
			'height' => '',
		);

		// todo add default thumbnail functionality or extension here

		return $img;

	} // better_amp_get_thumbnail
} // if


if ( ! function_exists( 'better_amp_element_uni_id' ) ) {
	/**
	 * Create unique id for element
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function better_amp_element_uni_id() {

		return uniqid( 'element-' . rand() . '-' );
	}
}


if ( ! function_exists( 'better_amp_get_branding_info' ) ) {
	/**
	 * Returns site branding info
	 *
	 * @param string $position
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function better_amp_get_branding_info( $position = 'header' ) {

		if ( $info = better_amp_get_global( $position . '-site-info', false ) ) {
			return $info;
		} else {
			$info = array(
				'logo'             => '',
				'logo-tag'         => '',
				'sidebar-logo'     => '',
				'sidebar-logo-tag' => '',
				'footer-logo'      => '',
				'footer-logo-tag'  => '',
				'name'             => get_bloginfo( 'name', 'display' ),
				'description'      => get_bloginfo( 'description', 'display' ),
			);
		}

		if ( $name = better_amp_get_option( 'better-amp-' . $position . '-logo-text', false ) ) {
			$info['name'] = $name;
		}

		if ( $logo = better_amp_get_option( 'better-amp-' . $position . '-logo-img' ) ) {

			$logo = wp_get_attachment_image_src( $logo, 'full' );

			if ( $logo ) {
				$logo = array(
					'src'    => $logo[0],
					'width'  => $logo[1],
					'height' => $logo[2],
				);
			}

			if ( ! empty( $logo['src'] ) ) {
				$info['logo']        = $logo;
				$info['logo']['alt'] = $info['name'] . ' - ' . $info['description'];

				$info['logo-tag'] = better_amp_create_image( $info['logo'], false );
			}
		}


		better_amp_set_global( $position . '-site-info', $info );

		return $info;
	}
}


if ( ! function_exists( 'better_amp_get_option' ) ) {
	/**
	 * Returns option value
	 *
	 * @param string $option_key
	 * @param string $default_value
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function better_amp_get_option( $option_key = '', $default_value = null ) {

		if ( empty( $option_key ) ) {
			return $default_value;
		}

		if ( is_null( $default_value ) ) {
			$default_value = apply_filters( 'better-amp/template/default-theme-mod', $default_value, $option_key );
		}

		return get_theme_mod( $option_key, $default_value );
	}
}


if ( ! function_exists( 'better_amp_get_theme_mod' ) ) {
	/**
	 * Returns saved value of option or default from config
	 *
	 * @param      $name
	 * @param bool $check_customize_preview
	 *
	 * @todo  remove this function and use better_amp_get_option instead
	 * @since 1.0.
	 *
	 * @return bool|string
	 */
	function better_amp_get_theme_mod( $name, $check_customize_preview = true ) {

		$result = get_theme_mod( $name, better_amp_get_default_theme_setting( $name ) );

		if ( ! $result && $check_customize_preview ) {
			$result = better_amp_is_customize_preview();
		}

		return $result;
	}
}

if ( ! function_exists( 'better_amp_get_server_ip_address' ) ) {
	/**
	 * Handy function for get server ip
	 *
	 * @since 1.0.0
	 *
	 * @return string|null ip address on success or null on failure.
	 */
	function better_amp_get_server_ip_address() {

		// This function is fork of "bf_get_server_ip_address" function and it's better to use
		// the main function if that was available. (IF BetterFramework was available)
		if ( function_exists( 'bf_get_server_ip_address' ) ) {
			return bf_get_server_ip_address();
		}

		global $is_IIS;

		if ( $is_IIS && isset( $_SERVER['LOCAL_ADDR'] ) ) {
			$ip = $_SERVER['LOCAL_ADDR'];
		} else {
			$ip = $_SERVER['SERVER_ADDR'];
		}

		//if ( $ip === '::1' || filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) !== FALSE ) {
		if ( $ip === '::1' || filter_var( $ip, FILTER_VALIDATE_IP ) !== false ) {
			return $ip;
		}
	}
}


if ( ! function_exists( 'better_amp_is_localhost' ) ) {
	/**
	 * Utility function to detect is site currently running on localhost?
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	function better_amp_is_localhost() {

		// This function is fork of "bf_is_localhost" function and it's better to use
		// the main function if that was available. (IF BetterFramework was available)
		if ( function_exists( 'bf_is_localhost' ) ) {
			return bf_is_localhost();
		}

		$server_ip      = better_amp_get_server_ip_address();
		$server_ip_long = ip2long( $server_ip );

		return $server_ip === '::1' || ( $server_ip_long >= 2130706433 && $server_ip_long <= 2147483646 );
	}
}


if ( ! function_exists( 'better_amp_human_number_format' ) ) {
	/**
	 * Format number to human friendly style
	 *
	 * @param $number
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function better_amp_human_number_format( $number ) {

		if ( ! is_numeric( $number ) ) {
			return $number;
		}

		if ( $number >= 1000000 ) {
			return round( ( $number / 1000 ) / 1000, 1 ) . "M";
		} elseif ( $number >= 100000 ) {
			return round( $number / 1000, 0 ) . "k";
		} else {
			return @number_format( $number );
		}

	}
}


if ( ! function_exists( 'better_amp_get_archive_title_fields' ) ) {
	/**
	 * Handy function used to get archive pages title fields
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function better_amp_get_archive_title_fields() {

		$icon        = '';
		$pre_title   = '';
		$title       = '';
		$description = '';

		if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {

			if ( is_product_category() ) {

				$icon        = '<i class="fa fa-shopping-basket"></i>';
				$pre_title   = better_amp_translation_get( 'browsing_product_category' );
				$title       = single_term_title( '', false );
				$description = term_description( get_queried_object() );

			} elseif ( is_product_tag() ) {

				$icon        = '<i class="fa fa-shopping-basket"></i>';
				$pre_title   = better_amp_translation_get( 'browsing_product_tag' );
				$title       = single_term_title( '', false );
				$description = term_description( get_queried_object() );

			} else {

				$icon      = '<i class="fa fa-truck"></i>';
				$pre_title = better_amp_translation_get( 'browsing' );
				$title     = better_amp_translation_get( 'product-shop' );
			}

		} elseif ( is_category() ) {

			$icon        = '<i class="fa fa-folder"></i>';
			$pre_title   = better_amp_translation_get( 'browsing_category' );
			$title       = single_cat_title( '', false );
			$description = category_description();

		} elseif ( is_tag() ) {

			$icon        = '<i class="fa fa-tag"></i>';
			$pre_title   = better_amp_translation_get( 'browsing_tag' );
			$title       = single_tag_title( '', false );
			$description = tag_description();

		} elseif ( is_author() ) {

			$icon        = '<i class="fa fa-user-circle"></i>';
			$pre_title   = better_amp_translation_get( 'browsing_author' );
			$title       = '<span class="vcard">' . get_the_author() . '</span>';
			$description = get_the_author_meta( 'description' );

		} elseif ( is_year() ) {

			$icon      = '<i class="fa fa-calendar"></i>';
			$pre_title = better_amp_translation_get( 'browsing_yearly' );
			$title     = get_the_date( _x( 'Y', 'yearly archives date format', 'better-amp' ) );

		} elseif ( is_month() ) {

			$icon      = '<i class="fa fa-calendar"></i>';
			$pre_title = better_amp_translation_get( 'browsing_monthly' );
			$title     = get_the_date( _x( 'F Y', 'monthly archives date format', 'better-amp' ) );

		} elseif ( is_day() ) {

			$icon      = '<i class="fa fa-calendar"></i>';
			$pre_title = better_amp_translation_get( 'browsing_daily' );
			$title     = get_the_date( _x( 'F j, Y', 'daily archives date format', 'better-amp' ) );

		} elseif ( is_tax( 'post_format' ) ) {

			if ( is_tax( 'post_format', 'post-format-aside' ) ) {

				$icon      = '<i class="fa fa-pencil"></i>';
				$pre_title = better_amp_translation_get( 'browsing_archive' );
				$title     = better_amp_translation_get( 'asides' );

			} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {

				$icon      = '<i class="fa fa-camera"></i>';
				$pre_title = better_amp_translation_get( 'browsing_archive' );
				$title     = better_amp_translation_get( 'galleries' );

			} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {

				$icon      = '<i class="fa fa-camera"></i>';
				$pre_title = better_amp_translation_get( 'browsing_archive' );
				$title     = better_amp_translation_get( 'images' );

			} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {

				$icon      = '<i class="fa fa-video-camera"></i>';
				$pre_title = better_amp_translation_get( 'browsing_archive' );
				$title     = better_amp_translation_get( 'videos' );

			} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {

				$icon      = '<i class="fa fa-quote-' . better_amp_direction() . '"></i>';
				$pre_title = better_amp_translation_get( 'browsing_archive' );
				$title     = better_amp_translation_get( 'quotes' );

			} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {

				$icon      = '<i class="fa fa-link"></i>';
				$pre_title = better_amp_translation_get( 'browsing_archive' );
				$title     = better_amp_translation_get( 'links' );

			} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {

				$icon      = '<i class="fa fa-refresh"></i>';
				$pre_title = better_amp_translation_get( 'browsing_archive' );
				$title     = better_amp_translation_get( 'statuses' );

			} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {

				$icon      = '<i class="fa fa-music"></i>';
				$pre_title = better_amp_translation_get( 'browsing_archive' );
				$title     = better_amp_translation_get( 'audio' );

			} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {

				$icon      = '<i class="fa fa-coffee"></i>';
				$pre_title = better_amp_translation_get( 'browsing_archive' );
				$title     = better_amp_translation_get( 'chats' );
			}

		} elseif ( is_post_type_archive() ) {

			$icon        = '<i class="fa fa-archive"></i>';
			$pre_title   = better_amp_translation_get( 'browsing_archive' );
			$title       = post_type_archive_title( '', false );
			$description = function_exists( 'get_the_post_type_description' ) ?
				get_the_post_type_description() : '';
		} elseif ( is_tax() ) {

			$tax         = get_taxonomy( get_queried_object()->taxonomy );
			$icon        = '<i class="fa fa-archive"></i>';
			$pre_title   = better_amp_translation_get( 'browsing_archive' );
			$title       = sprintf( __( '%1$s: %2$s', 'beetter-amp' ), $tax->labels->singular_name, single_term_title( '', false ) );
			$description = term_description();

		} else {

			$icon      = '<i class="fa fa-archive"></i>';
			$pre_title = better_amp_translation_get( 'browsing' );
			$title     = better_amp_translation_get( 'archive' );
		}

		return compact( 'icon', 'pre_title', 'title', 'description' );
	}
}


if ( ! function_exists( 'better_amp_post_classes' ) ) {
	/**
	 * Handy function to generate class attribute for posts
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $append One or more classes to add to the class list.
	 */
	function better_amp_post_classes( $append = '' ) {

		$class = get_post_class( $append );

		if ( ! has_post_thumbnail() ) {
			$class[] = 'no-thumbnail';
		} else {
			$class[] = 'have-thumbnail';
		}

		$class[] = 'clearfx';

		$class = str_replace( 'hentry', '', join( ' ', $class ) );

		echo 'class="' . $class . '"';

		unset( $class );
	}
}


if ( ! function_exists( 'better_amp_post_subtitle' ) ) {
	/**
	 * Post subtitle.
	 *
	 * Supports
	 * "BetterStudio" Themes
	 * "WP Subtitle" plugin
	 *
	 * @since 1.0.0
	 */
	function better_amp_post_subtitle() {

		if ( function_exists( 'publisher_the_subtitle' ) ) {
			publisher_the_subtitle( '<h5 class="post-subtitle">', '</h5>' );
		} elseif ( function_exists( 'the_subtitle' ) ) {
			the_subtitle( '<h5 class="post-subtitle">', '</h5>' );
		}

	}
}


if ( ! function_exists( 'better_amp_social_share_fetch_count' ) ) {
	/**
	 * Fetches share count for URL
	 *
	 * @param $site_id
	 * @param $url
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	function better_amp_social_share_fetch_count( $site_id, $url ) {

		// This function is fork of "bf_social_share_fetch_count" function and it's better to use
		// the main function if that was available. (IF BetterFramework was available)
		if ( function_exists( 'bf_social_share_fetch_count' ) ) {
			return bf_social_share_fetch_count( $site_id, $url );
		}

		$count       = 0;
		$remote_args = array(
			'sslverify' => false
		);

		switch ( $site_id ) {

			case 'facebook':
				$remote = wp_remote_get( 'http://graph.facebook.com/?fields=og_object{id},share&id=' . $url, $remote_args );

				if ( ! is_wp_error( $remote ) ) {

					$response = json_decode( wp_remote_retrieve_body( $remote ), true );

					if ( isset( $response['share']['share_count'] ) ) {
						$count = $response['share']['share_count'];
					}

				}


				break;

			case 'twitter':

				$remote = wp_remote_get( 'http://public.newsharecounts.com/count.json?callback=&url=' . $url, $remote_args );

				if ( ! is_wp_error( $remote ) ) {

					$response = json_decode( wp_remote_retrieve_body( $remote ), true );

					if ( isset( $response['count'] ) ) {
						$count = $response['count'];
					}

				}

				break;

			case 'google_plus':
				$post_data = '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . rawurldecode( $url ) . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]';

				$remote = wp_remote_post( 'https://clients6.google.com/rpc', array(
					'body'      => $post_data,
					'headers'   => 'Content-type: application/json',
					'sslverify' => false,
				) );

				if ( ! is_wp_error( $remote ) ) {

					$response = json_decode( wp_remote_retrieve_body( $remote ), true );

					if ( isset( $response[0]['result']['metadata']['globalCounts']['count'] ) ) {
						$count = $response[0]['result']['metadata']['globalCounts']['count'];
					}

				}

				break;

			case 'pinterest':
				$remote = wp_remote_get( 'http://api.pinterest.com/v1/urls/count.json?callback=CALLBACK&url=' . $url, $remote_args );

				if ( ! is_wp_error( $remote ) ) {

					if ( preg_match( '/^\s*CALLBACK\s*\((.+)\)\s*$/', wp_remote_retrieve_body( $remote ), $match ) ) {
						$response = json_decode( $match[1], true );

						if ( isset( $response['count'] ) ) {
							$count = $response['count'];
						}
					}

				}

				break;

			case 'linkedin':
				$remote = wp_remote_get( 'https://www.linkedin.com/countserv/count/share?format=json&url=' . $url, $remote_args );

				if ( ! is_wp_error( $remote ) ) {

					$response = json_decode( wp_remote_retrieve_body( $remote ), true );

					if ( isset( $response['count'] ) ) {
						$count = $response['count'];
					}

				}

				break;

			case 'tumblr':
				$remote = wp_remote_get( 'http://api.tumblr.com/v2/share/stats?url=' . $url, $remote_args );

				if ( ! is_wp_error( $remote ) ) {

					$response = json_decode( wp_remote_retrieve_body( $remote ), true );

					if ( isset( $response['response']['note_count'] ) ) {
						$count = $response['response']['note_count'];
					}

				}

				break;


			case 'reddit':
				$remote = wp_remote_get( 'http://www.reddit.com/api/info.json?url=' . $url, $remote_args );

				if ( ! is_wp_error( $remote ) ) {

					$response = json_decode( $remote['body'], true );

					if ( isset( $response['data']['children']['0']['data']['score'] ) ) {
						$count = $response['data']['children']['0']['data']['score'];
					}

				}

				break;

			case 'stumbleupon':
				$remote = wp_remote_get( 'http://www.stumbleupon.com/services/1.01/badge.getinfo?url=' . $url, $remote_args );

				if ( ! is_wp_error( $remote ) ) {

					$response = json_decode( $remote['body'], true );

					if ( isset( $response['result']['views'] ) ) {
						$count = $response['result']['views'];
					}

				}


				break;

		}

		return $count;
	} // better_amp_social_share_fetch_count
}


if ( ! function_exists( 'better_amp_social_shares_count' ) ) {
	/**
	 * Returns all social share count for post.
	 *
	 * @param $sites
	 *
	 * @since 1.0.0
	 *
	 * @return array|mixed|void
	 */
	function better_amp_social_shares_count( $sites ) {

		// This function is fork of "bf_social_shares_count" function and it's better to use
		// the main function if that was available. (IF BetterFramework was available)
		if ( function_exists( 'bf_social_share_fetch_count' ) ) {
			return bf_social_shares_count( $sites );
		}

		$sites = array_intersect_key( $sites, array(
			// Valid sites
			'facebook'    => '',
			'twitter'     => '',
			'google_plus' => '',
			'pinterest'   => '',
			'linkedin'    => '',
			'tumblr'      => '',
			'reddit'      => '',
			'stumbleupon' => '',
		) );

		// Disable social share in localhost
		if ( better_amp_is_localhost() ) {
			return array();
		}

		$post_id = get_queried_object_id();
		$expired = (int) get_post_meta( $post_id, 'bs_social_share_interval', true );
		$results = array();

		$update_cache = false;

		if ( $expired < time() ) {
			$update_cache = true;
		} else {

			// get count from cache storage
			foreach ( $sites as $site_id => $is_active ) {
				if ( ! $is_active ) {
					continue;
				}

				$count_number = get_post_meta( $post_id, 'bs_social_share_' . $site_id, true );
				$update_cache = $count_number === '';

				if ( $update_cache ) {
					break;
				}

				$results[ $site_id ] = $count_number;
			}
		}

		if ( $update_cache ) { // Update cache storage if needed
			$current_page = better_amp_social_share_guss_current_page();

			foreach ( $sites as $site_id => $is_active ) {
				if ( ! $is_active ) {
					continue;
				}

				$count_number = better_amp_social_share_fetch_count( $site_id, $current_page['page_permalink'] );

				update_post_meta( $post_id, 'bs_social_share_' . $site_id, $count_number );

				$results[ $site_id ] = $count_number;
			}

			/**
			 *
			 * This filter can be used to change share count time.
			 *
			 */
			$cache_time = apply_filters( 'bs-social-share/cache-time', MINUTE_IN_SECONDS * 120, $post_id );

			update_post_meta( $post_id, 'bs_social_share_interval', time() + $cache_time );
		}

		return apply_filters( 'bs-social-share/shares-count', $results );
	} // better_amp_social_shares_count
}


if ( ! function_exists( 'better_amp_social_share_guss_current_page' ) ) {
	/**
	 * Detects and returns current page info for social share
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function better_amp_social_share_guss_current_page() {

		$page_permalink  = '';
		$need_short_link = better_amp_get_theme_mod( 'better-amp-post-social-share-link-format' ) === 'short';

		if ( is_home() || is_front_page() ) {
			$page_title = get_bloginfo( 'name' );
		} elseif ( is_single( get_the_ID() ) && ! ( is_front_page() ) ) {
			$page_title = get_the_title();

			if ( $need_short_link ) {
				$page_permalink = wp_get_shortlink();
			}

		} elseif ( is_page() ) {
			$page_title = get_the_title();

			if ( $need_short_link ) {
				$page_permalink = wp_get_shortlink();
			}

		} elseif ( is_category() || is_tag() || is_tax() ) {
			$page_title = single_term_title( '', false );

			if ( $need_short_link ) {

				$queried_object = get_queried_object();

				if ( ! empty( $queried_object->taxonomy ) ) {

					if ( 'category' == $queried_object->taxonomy ) {
						$page_permalink = "?cat=$queried_object->term_id";
					} else {
						$tax = get_taxonomy( $queried_object->taxonomy );

						if ( $tax->query_var ) {
							$page_permalink = "?$tax->query_var=$queried_object->slug";
						} else {
							$page_permalink = "?taxonomy=$queried_object->taxonomy&term=$queried_object->term_id";
						}
					}

					$page_permalink = home_url( $page_permalink );
				}
			}

		} else {
			$page_title = get_bloginfo( 'name' );
		}

		if ( ! $page_permalink ) {
			$page_permalink = better_amp_guess_none_amp_url();
		}

		return compact( 'page_title', 'page_permalink' );
	}
}


if ( ! function_exists( 'better_amp_social_share_get_li' ) ) {
	/**
	 * Used for generating lis for social share list
	 *
	 * @param string  $id
	 * @param    bool $show_title
	 * @param    int  $count_label
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	function better_amp_social_share_get_li( $id = '', $show_title = true, $count_label = 0 ) {

		if ( empty( $id ) ) {
			return '';
		}

		static $initialized;
		static $page_title;
		static $page_permalink;

		wp_reset_postdata(); // fix for after other loops

		if ( is_null( $initialized ) ) {
			$cur_page       = better_amp_social_share_guss_current_page();
			$page_title     = esc_attr( $cur_page['page_title'] );
			$page_permalink = urlencode( $cur_page['page_permalink'] );
			$initialized    = true;
		}

		switch ( $id ) {

			case 'facebook':
				$link  = 'https://www.facebook.com/sharer.php?u=' . $page_permalink;
				$title = __( 'Facebook', 'better-amp' );
				$icon  = '<i class="fa fa-facebook"></i>';
				break;

			case 'twitter':

				$by = '';
				if ( class_exists( 'Better_Social_Counter' ) ) {
					$by = Better_Social_Counter::get_option( 'twitter_username' );

					if ( $by === 'BetterSTU' && ! class_exists( 'BS_Demo_Helper' ) ) {
						$by = '';
					}

					if ( ! empty( $by ) ) {
						$by = ' @' . $by;
					} else {
						$by = '';
					}
				}

				$link  = 'https://twitter.com/share?text=' . $page_title . $by . '&url=' . $page_permalink;
				$title = __( 'Twitter', 'better-amp' );
				$icon  = '<i class="fa fa-twitter"></i>';
				break;

			case 'google_plus':
				$link  = 'https://plus.google.com/share?url=' . $page_permalink;
				$title = __( 'Google+', 'better-amp' );
				$icon  = '<i class="fa fa-google"></i>';
				break;

			case 'pinterest':
				$_img_src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
				$link     = 'https://pinterest.com/pin/create/button/?url=' . $page_permalink . '&media=' . $_img_src[0] . '&description=' . $page_title;
				$title    = __( 'Pinterest', 'better-amp' );
				$icon     = '<i class="fa fa-pinterest"></i>';
				break;

			case 'linkedin':
				$link  = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $page_permalink . '&title=' . $page_title;
				$title = __( 'Linkedin', 'better-amp' );
				$icon  = '<i class="fa fa-linkedin"></i>';
				break;

			case 'tumblr':
				$link  = 'https://www.tumblr.com/share/link?url=' . $page_permalink . '&name=' . $page_title;
				$title = __( 'Tumblr', 'better-amp' );
				$icon  = '<i class="fa fa-tumblr"></i>';
				break;

			case 'email':
				$link  = "mailto:?subject=" . $page_title . "&body=" . $page_permalink;
				$title = __( 'Email', 'better-amp' );
				$icon  = '<i class="fa fa-envelope-open"></i>';
				break;

			case 'telegram':
				$link  = 'https://telegram.me/share/url?url=' . $page_permalink . '&text=' . $page_title;
				$title = __( 'Telegram', 'better-amp' );
				$icon  = '<i class="fa fa-send"></i>';
				break;

			case 'whatsapp':
				$link  = 'whatsapp://send?text=' . $page_title . ' %0A%0A ' . $page_permalink;
				$title = __( 'WhatsApp', 'better-amp' );
				$icon  = '<i class="fa fa-whatsapp"></i>';
				break;

			case 'digg':
				$link  = 'https://www.digg.com/submit?url=' . $page_permalink;
				$title = __( 'Digg', 'better-amp' );
				$icon  = '<i class="fa fa-digg"></i>';
				break;

			case 'reddit':
				$link  = 'https://reddit.com/submit?url=' . $page_permalink . '&title=' . $page_title;
				$title = __( 'ReddIt', 'better-amp' );
				$icon  = '<i class="fa fa-reddit-alien"></i>';
				break;

			case 'stumbleupon':
				$link  = 'https://www.stumbleupon.com/submit?url=' . $page_permalink . '&title=' . $page_title;
				$title = __( 'StumbleUpon', 'better-amp' );
				$icon  = '<i class="fa fa-stumbleupon"></i>';
				break;

			case 'vk':
				$link  = 'https://vkontakte.ru/share.php?url=' . $page_permalink;
				$title = __( 'VK', 'better-amp' );
				$icon  = '<i class="fa fa-vk"></i>';
				break;

			default:
				return '';
		}

		$extra_classes = $count_label ? ' has-count' : '';
		$output        = '<li class="social-item ' . esc_attr( $id ) . $extra_classes . '"><a href="' . $link . '" target="_blank" rel="nofollow" class="bs-button-el">';

		$output .= $icon;

		if ( $show_title ) {
			$output .= '<span class="item-title">' . wp_kses( $title, bf_trans_allowed_html() ) . '</span>';
		}

		if ( $count_label ) {
			$output .= sprintf( '<span class="number">%s</span>', better_amp_human_number_format( $count_label ) );
		}

		$output .= '</a></li>';

		return $output;

	}// better_amp_social_share_get_li
}// if


if ( ! function_exists( 'better_amp_is_customize_preview' ) ) {
	/**
	 * Handy function customizer preview state for current page
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	function better_amp_is_customize_preview() {

		static $is_customizer;

		if ( is_null( $is_customizer ) ) {
			$is_customizer = is_customize_preview();
		}

		return $is_customizer;
	}
}


if ( ! function_exists( 'better_amp_customizer_hidden_attr' ) ) {
	/**
	 * Helper for customizer preview
	 *
	 * @since 1.0.0
	 *
	 * @param $theme_mod
	 */
	function better_amp_customizer_hidden_attr( $theme_mod ) {

		if ( better_amp_is_customize_preview() && ! better_amp_get_theme_mod( $theme_mod, false ) ) {
			echo ' style="display:none"';
		}
	}
}

if ( ! function_exists( 'better_amp_language_attributes' ) ) {
	/**
	 * Gets the language attributes for the html tag.
	 *
	 * @since 1.0.0
	 */
	function better_amp_language_attributes() {

		$attributes = array();

		if ( function_exists( 'is_rtl' ) && is_rtl() ) {
			$attributes[] = 'dir="rtl"';
		}

		if ( $lang = get_bloginfo( 'language' ) ) {
			$attributes[] = "lang=\"$lang\"";

		}

		$output = implode( ' ', $attributes );

		echo $output;
	}
}

if ( ! function_exists( 'better_amp_get_post_parent' ) ) {
	/**
	 * Get post parent
	 *
	 * @param int $attachment_id
	 *
	 * @since 1.1
	 * @return bool|WP_Post WP_Post on success or false on failure
	 */
	function better_amp_get_post_parent( $attachment_id = null ) {

		if ( empty( $attachment_id ) && isset( $GLOBALS['post'] ) ) {
			$attachment = $GLOBALS['post'];
		} else {
			$attachment = get_post( $attachment_id );
		}

		// Validate attachment
		if ( ! $attachment || is_wp_error( $attachment ) ) {
			return false;
		}

		$parent = false;

		if ( ! empty( $attachment->post_parent ) ) {
			$parent = get_post( $attachment->post_parent );
			if ( ! $parent || is_wp_error( $parent ) ) {
				$parent = false;
			}
		}

		return $parent;
	}
}

if ( ! function_exists( 'better_amp_is_static_home_page' ) ) {
	/**
	 * Is current page static home page
	 *
	 * @return bool true on success or false on failure
	 * @since 1.2.4
	 */
	function better_amp_is_static_home_page() {

		return is_home() && apply_filters( 'better-amp/template/show-on-front', 'posts' ) === 'page' &&
		       apply_filters( 'better-amp/template/page-on-front', 0 );
	}
}

if ( ! function_exists( 'better_amp_comments_template' ) ) {
	/**
	 * Retrieves comments template path in the current or parent template.
	 *
	 * @since 1.5.0
	 */
	function better_amp_comments_template() {

		better_amp_locate_template( 'comments.php', true );
	}
}

if ( ! function_exists( 'better_amp_list_comments' ) ) {
	/**
	 * List comments for a particular post.
	 *
	 * @see   wp_list_comments for more documentation
	 *
	 * @param string|array $args               wp_list_comments first argument
	 * @param array        $comment_query_args comment query arguments
	 *
	 * @global WP_Query    $wp_query           Global WP_Query instance.
	 * @return string|void
	 * @since 1.5.0
	 *
	 */
	function better_amp_list_comments( $args = array(), $comment_query_args = array() ) {

		global $wp_query;

		$post_id = get_the_ID();

		$comment_args = array(
			'orderby'       => 'comment_date_gmt',
			'order'         => 'ASC',
			'status'        => 'approve',
			'post_id'       => $post_id,
			'no_found_rows' => false,
		);

		if ( empty( $args['callback'] ) && better_amp_locate_template( 'comment-item.php' ) ) {
			$args['callback']     = 'better_amp_comment_item';
			$args['end-callback'] = 'better_amp_comment_item_end';
		}

		$comments = new WP_Comment_Query( array_merge( $comment_args, $comment_query_args ) );

		/**
		 * Filters the comments array.
		 *
		 * @see comments_template
		 *
		 * @param array $comments Array of comments supplied to the comments template.
		 * @param int   $post_ID  Post ID.
		 */
		$comments_list = apply_filters( 'comments_array', $comments->comments, $post_id );

		// Save comments list to comments property of the main query to enable wordpress core
		// function such as get_next_comments_link works in comments page
		$wp_query->comments = $comments_list;

		return wp_list_comments( $args );
	}
}

if ( ! function_exists( 'better_amp_comments_paginate' ) ) {
	/**
	 * Displays pagination links for the comments on the current post.
	 *
	 * @see   wp_list_comments for more documentation
	 *
	 * @since 1.5.0
	 *
	 */
	function better_amp_comments_paginate() {

		// Nav texts with RTL support
		if ( is_rtl() ) {
			$prev = '<i class="fa fa-angle-double-right"></i> ' . better_amp_translation_get( 'comment_previous' );
			$next = better_amp_translation_get( 'comment_next' ) . ' <i class="fa fa-angle-double-left"></i>';
		} else {
			$next = better_amp_translation_get( 'comment_next' ) . ' <i class="fa fa-angle-double-right"></i>';
			$prev = '<i class="fa fa-angle-double-left"></i> ' . better_amp_translation_get( 'comment_previous' );
		}

		previous_comments_link( $prev );

		next_comments_link( $next );
	}
}


if ( ! function_exists( 'better_amp_comment_item' ) ) {

	/**
	 * Load comment-item.php file in the current or parent template.
	 *
	 * @since 1.5.0
	 *
	 * @param WP_Comment_Query $comment
	 */
	function better_amp_comment_item( $comment ) {

		static $path;

		if ( is_null( $path ) ) {
			$path = better_amp_locate_template( 'comment-item.php' );
		}

		if ( $path ) {
			include $path;
		}
	}
}


if ( ! function_exists( 'better_amp_comment_item_end' ) ) {
	/**
	 * Print li closing tag
	 *
	 * @since 1.5.0
	 */
	function better_amp_comment_item_end() {

		echo '</li>';
	}
}


if ( ! function_exists( 'better_amp_related_posts_query_args' ) ) {
	/**
	 * Get Related Posts
	 *
	 * @param integer      $count  number of posts to return
	 * @param string       $type
	 * @param integer|null $post_id
	 * @param array        $params query extra arguments
	 *
	 * @return array  query args array
	 */
	function better_amp_related_posts_query_args( $count = 5, $type = 'cat', $post_id = null, $params = array() ) {

		$post = get_post( $post_id );

		if ( ! $post_id && isset( $post->ID ) ) {
			$post_id = $post->ID;
		}

		$args = array(
			'posts_per_page'      => $count,
			'post__not_in'        => array( $post_id ),
			'ignore_sticky_posts' => true,
		);

		switch ( $type ) {

			case 'cat':
				$args['category__in'] = wp_get_post_categories( $post_id );
				break;

			case 'tag':
				$tag_in = wp_get_object_terms( $post_id, 'post_tag', array( 'fields' => 'ids' ) );
				if ( $tag_in && ! is_wp_error( $tag_in ) ) {

					$args['tag__in'] = $tag_in;
				}
				break;

			case 'author':
				if ( isset( $post->post_author ) ) {
					$args['author'] = $post->post_author;
				}
				break;

			case 'cat-tag':
				$args['category__in'] = wp_get_post_categories( $post_id );
				$args['tag__in']      = wp_get_object_terms( $post_id, 'post_tag', array( 'fields' => 'ids' ) );
				break;

			case 'cat-tag-author':
				$args['category__in'] = wp_get_post_categories( $post_id );

				if ( isset( $post->post_author ) ) {
					$args['author'] = $post->post_author;
				}

				$tag_in = wp_get_object_terms( $post_id, 'post_tag', array( 'fields' => 'ids' ) );

				if ( $tag_in && ! is_wp_error( $tag_in ) ) {
					$args['tag__in'] = $tag_in;
				}
				break;

			case 'rand':
			case 'random':
			case 'randomly':
				$args['orderby'] = 'rand';
				break;

		}

		if ( $params ) {
			$args = array_merge( $args, $params );
		}

		return $args;

	} // better_amp_related_posts_query_args
} // if


if ( ! function_exists( 'better_amp_min_suffix' ) ) {
	/**
	 * Returns appropriate suffix for static files (min or not)
	 *
	 * @param string $before
	 * @param string $after
	 *
	 * @return string
	 */
	function better_amp_min_suffix( $before = '', $after = '' ) {

		static $suffix;

		if ( ! $suffix ) {
			if ( ( defined( 'WP_DEBUG' ) && WP_DEBUG ) || ( defined( 'BF_DEV_MODE' ) && BF_DEV_MODE ) ) {
				$suffix = '';
			} else {
				$suffix = '.min';
			}
		}

		return "$before$suffix$after";
	}
}

if ( ! function_exists( 'better_amp_url_format' ) ) {

	/**
	 * Get the structure url of AMP pages permalink.
	 *
	 * @since 1.8.3
	 * @return string start-point or end-pint
	 */
	function better_amp_url_format() {

		return apply_filters( 'better-amp/url/format', 'start-point' );
	}
}

if ( ! function_exists( 'better_amp_excluded_urls_format' ) ) {

	/**
	 * Get list of url paths which is not available in AMP version.
	 *
	 * @since 1.9.8
	 * @return array
	 */
	function better_amp_excluded_urls_format() {

		return apply_filters( 'better-amp/url/excluded', array() );
	}
}
