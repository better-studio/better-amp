<?php
/*
Plugin Name: Better AMP
Plugin URI: http://demo.betterstudio.com/publisher/amp-demo/
Description: Add FULL AMP support to your WordPress site.
Author: Better Studio
Version: 1.1.1
Author URI: http://betterstudio.com
*/

/***
 *  BetterAMP is BetterStudio solution for implementing Google AMP completely in WordPress.
 *
 *  ______      _   _             ___ ___  ________
 *  | ___ \    | | | |           / _ \|  \/  | ___ \
 *  | |_/ / ___| |_| |_ ___ _ __/ /_\ \ .  . | |_/ /
 *  | ___ \/ _ \ __| __/ _ \ '__|  _  | |\/| |  __/
 *  | |_/ /  __/ |_| ||  __/ |  | | | | |  | | |
 *  \____/ \___|\__|\__\___|_|  \_| |_|_|  |_|_|
 *
 *  Copyright © 2017 Better Studio
 *
 *
 *  Our portfolio is here: http://themeforest.net/user/Better-Studio/portfolio
 *
 *  \--> BetterStudio, 2017 <--/
 */

// Fire up!
Better_AMP::get_instance();

/**
 * Main class for BetterAMP
 *
 * @since 1.0.0
 */
class Better_AMP {

	/**
	 * Main Better AMP instance
	 *
	 * @since 1.0.0
	 *
	 * @var self
	 */
	private static $instance;


	/**
	 * Better AMP version number
	 *
	 * @since 1.0.0
	 */
	const VERSION = '1.1.1';


	/**
	 * Default endpoint for AMP URL of site.
	 * this cna can overridden by filter
	 *
	 * @since 1.0.0
	 */
	const STARTPOINT = 'amp';


	/**
	 * Default template directory
	 * This can be overridden by filter
	 *
	 * @since 1.0.0
	 */
	const TEMPLATE_DIR = 'better-amp';


	/**
	 * pre_get_posts hook priority
	 *
	 * @since 1.1
	 */
	const ISOLATE_QUERY_HOOK_PRIORITY = 100;


	/**
	 * Store better_amp_head action callbacks
	 *
	 * @see   collect_and_remove_better_amp_head_actions
	 * @var array
	 *
	 * @since 1.0.0
	 */
	private $_head_actions;


	/**
	 * Store array of posts id to exlucde transform permalinks to amp
	 *
	 * Array structure: array {
	 *      'post id' => dont care,
	 *      ...
	 * }
	 *
	 * @see   transform_post_link_to_amp
	 *
	 * @since 1.1
	 * @var array
	 */
	public $excluded_posts_id = array();


	/**
	 * Get live instance of Better AMP
	 *
	 * @since 1.0.0
	 *
	 * @return self
	 */
	public static function get_instance() {

		if ( ! self::$instance instanceof self ) {
			self::$instance = new self();
			self::$instance->init();
		}

		return self::$instance;
	} // get_instance


	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cloning Better_AMP is forbidden', 'better-amp' ), '' );
	}


	/**
	 * Unserializing is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Unserializing Better_AMP is forbidden', 'better-amp' ), '' );
	}


	/**
	 * Initialize
	 *
	 * @since 1.0.0
	 */
	public function init() {

		$this->initial_constants();

		$this->load_text_domain();

		$this->register_autoload();

		$this->include_files();

		$this->apply_hooks();

		$this->admin_hooks();

		$this->metaboxes();

		$this->admin_init();

	}


	/**
	 * Define core constants
	 *
	 * @since 1.0.0
	 */
	protected function initial_constants() {

		define( 'BETTER_AMP_PATH', dirname( __FILE__ ) . '/' );
		define( 'BETTER_AMP_INC', dirname( __FILE__ ) . '/includes/' );
		define( '__BETTER_AMP_FILE__', __FILE__ );

		define( 'BETTER_AMP_OVERRIDE_TPL_DIR', apply_filters( 'better-amp/template/dir-name', Better_AMP::TEMPLATE_DIR ) );
		define( 'BETTER_AMP_TPL_COMPAT_ABSPATH', BETTER_AMP_PATH . 'theme-compat/' );

	}


	public function admin_styles() {
		?>
		<style>
			.toplevel_page_better-amp-translation .wp-menu-image img {
				width: 12px;
				padding-top: 7px !important;
			}

			#adminmenu li#toplevel_page_better-studio-better-ads-manager + .toplevel_page_better-amp-translation,
			#adminmenu li#toplevel_page_better-studio-rebuild-thumbnails + .toplevel_page_better-amp-translation {
				margin-top: -10px;
				margin-bottom: 10px;
			}
		</style>
		<?php
	}


	/**
	 * Load plugin textdomain file
	 *
	 * @since 1.0.0
	 */
	protected function load_text_domain() {
		load_plugin_textdomain( 'better-amp', FALSE, plugin_basename( BETTER_AMP_PATH ) . '/languages' );
	}


	/**
	 * Include Dependencies
	 *
	 * @since 1.0.0
	 */
	protected function include_files() {
		require_once BETTER_AMP_PATH . 'bootstrap.php';
	}


	/**
	 * Register WP filters and actions
	 *
	 * @since 1.0.0
	 */
	protected function apply_hooks() {

		// Registers the AMP rewrite rules
		add_filter( 'init', array( $this, 'add_rewrite' ) );
		add_filter( 'init', array( $this, 'append_index_rewrite_rule' ) );

		add_filter( 'template_redirect', array( $this, 'plugins_compatibility' ) );

		// Initialize AMP components
		add_filter( 'init', array( $this, 'include_components' ) );

		// Changes page template file with AMP template file
		add_action( 'template_include', array( $this, 'include_template_file' ), 9999 );
		// Override WooCommerce template files
		add_action( 'wc_get_template', array( $this, 'include_wc_template_file' ), 9999, 2 );


		// override template file
		add_filter( 'comments_template', array( $this, 'override_comments_template' ), 9999 );

		// Initialize AMP theme and it's functionality
		add_action( 'after_setup_theme', array( $this, 'include_template_functions_php' ), 1 );

		// Register the AMP special shortcode ports
		add_filter( 'the_content', array( $this, 'register_components_shortcodes' ), 1 );

		// Replace all links inside contents to AMP version.
		// Stops user to go outside of AMP version.
		add_filter( 'better-amp/template/include', array( $this, 'replace_internal_links_with_amp_version' ) );

		// Registers all components scripts into the header style and scripts
		add_action( 'better-amp/template/enqueue-scripts', array( $this, 'enqueue_components_scripts' ) );

		// Let the components to do their functionality in head
		add_action( 'better-amp/template/head', array( $this, 'trigger_component_head' ), 0 );

		// Collect all output to can enqueue only needed scripts and styles in pages.
		add_action( 'better-amp/template/head', array( $this, 'buffer_better_amp_head_start' ), 1 );
		add_action( 'better-amp/template/footer', array( $this, 'buffer_better_amp_head_end' ), 999 );

		// Collect and rollback all main query posts to disable thirdparty codes to change main query!
		// action after 1000 priority can work
		add_action( 'pre_get_posts', array( $this, 'isolate_pre_get_posts_start' ), 1 );
		add_action( 'pre_get_posts', array( $this, 'isolate_pre_get_posts_end' ), self::ISOLATE_QUERY_HOOK_PRIORITY );

		add_action( 'pre_get_posts', array( $this, 'compatible_plugins_themes' ), 0 );


		register_activation_hook( __FILE__, array( $this, 'install' ) );

		add_action( 'template_redirect', array( $this, 'redirect_amp_endpoint_url' ) );
		add_filter( 'redirect_canonical', array( $this, '_fix_prevent_extra_redirect_single_pagination' ) );

		add_action( 'request', array( $this, 'fix_search_page_queries' ) );
	} // apply_hooks


	/**
	 * Prevent redirect pages within single post
	 *
	 * @param $redirect
	 *
	 * @since 1.1
	 * @return bool
	 */
	public function _fix_prevent_extra_redirect_single_pagination( $redirect ) {

		if ( $redirect && is_better_amp() && get_query_var( 'page' ) > 1 ) {
			return FALSE;
		}

		return $redirect;
	}

	public function admin_hooks() {
		if ( ! is_admin() ) {
			return;
		}

		if ( get_transient( 'better-amp-flush-rules' ) ) {
			add_action( 'admin_init', 'flush_rewrite_rules' );

			delete_transient( 'better-amp-flush-rules' );
		}

		add_action( 'admin_head', array( $this, 'admin_styles' ) );
		add_action( 'admin_menu', array( $this, 'fix_admin_sub_menu' ), 999 );
	}

	/**
	 * Register rewrite rules and flush permalink in installation
	 *
	 * @since 1.0.0
	 */
	public function install() {
		$this->add_rewrite();

		set_transient( 'better-amp-flush-rules', TRUE );
	}

	/**
	 * "Automattic AMP" plugin compatibility
	 *
	 * Redirect AMP urls with amp endpoint to new amp url
	 *
	 * @since 1.0.0
	 */
	public function redirect_amp_endpoint_url() {
		$amp_qv = defined( 'AMP_QUERY_VAR' ) ? AMP_QUERY_VAR : 'amp';
		if ( get_query_var( $amp_qv, FALSE ) === FALSE ) {
			return;
		}
		$abspath_fix         = str_replace( '\\', '/', ABSPATH );
		$script_filename_dir = dirname( $_SERVER['SCRIPT_FILENAME'] );

		if ( $script_filename_dir . '/' == $abspath_fix ) {
			// Strip off any file/query params in the path
			$path = preg_replace( '#/[^/]*$#i', '', $_SERVER['PHP_SELF'] );

		} else {

			if ( FALSE !== strpos( $_SERVER['SCRIPT_FILENAME'], $abspath_fix ) ) {
				// Request is hitting a file inside ABSPATH
				$directory = str_replace( ABSPATH, '', $script_filename_dir );
				// Strip off the sub directory, and any file/query params
				$path = preg_replace( '#/' . preg_quote( $directory, '#' ) . '/[^/]*$#i', '', $_SERVER['REQUEST_URI'] );
			} elseif ( FALSE !== strpos( $abspath_fix, $script_filename_dir ) ) {
				// Request is hitting a file above ABSPATH
				$subdirectory = substr( $abspath_fix, strpos( $abspath_fix, $script_filename_dir ) + strlen( $script_filename_dir ) );
				// Strip off any file/query params from the path, appending the sub directory to the install
				$path = preg_replace( '#/[^/]*$#i', '', $_SERVER['REQUEST_URI'] ) . $subdirectory;
			} else {
				$path = $_SERVER['REQUEST_URI'];
			}
		}

		/**
		 * Fix For Multisite Installation
		 */
		if ( is_multisite() && ! is_main_site() ) {
			$current_site_url = get_site_url();
			$append_path      = str_replace( get_site_url( get_current_site()->blog_id ), '', $current_site_url );

			if ( $append_path !== $current_site_url ) {
				$path .= $append_path;
			}
		}


		if ( preg_match( "#^$path/*(.*?)/$amp_qv/*$#", $_SERVER['REQUEST_URI'], $matched ) ) {
			$new_amp_url = '/' . self::STARTPOINT . '/' . $matched[1];

			if ( $new_amp_url && trim( $path . $new_amp_url, '/' ) !== trim( $_SERVER['REQUEST_URI'], '/' ) ) {

				wp_redirect( site_url( $new_amp_url ), 301 );
				exit;
			}
		}

		if ( is_singular() ) {
			$post_id = get_queried_object_id();

			if (
				get_post_meta( $post_id, 'disable-better-amp', TRUE )
				||
				isset( $this->excluded_posts_id[ $post_id ] )
			) {

				if ( preg_match( "#^$path/*$amp_qv/+(.*?)/*$#", $_SERVER['REQUEST_URI'], $matched ) ) {

					wp_redirect( site_url( $matched[1] ) );
					exit;
				}
			}
		}
	}


	/**
	 * Check AMP version of the posts exists
	 *
	 * @since 1.1
	 * @return bool of exists
	 */
	public static function amp_version_exists() {

		$exits = TRUE;

		if ( is_singular() ) {
			$post_id = get_queried_object_id();
			$exits   = ! get_post_meta( $post_id, 'disable-better-amp', TRUE );
		}

		return $exits;
	}


	/**
	 * Callback: Prevent third party codes to change the main query on AMP version
	 *
	 * You can add action to 'pre_get_posts' with priority grater than 1000 to change it.
	 *
	 * Action: pre_get_posts
	 * @see   isolate_pre_get_posts_end
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Query $wp_query
	 */
	public function isolate_pre_get_posts_start( $wp_query ) {

		global $better_amp_isolate_pre_get_posts;


		if ( is_better_amp( $wp_query ) && ! is_admin() && $wp_query->is_main_query() ) {
			$better_amp_isolate_pre_get_posts = $wp_query->query_vars;
		}

	}


	/**
	 * Rollback the main query vars.
	 * @see   isolate_pre_get_posts_end for more documentation
	 *
	 * Action: pre_get_posts
	 * @since 1.0.0
	 *
	 * @param WP_Query $wp_query
	 */
	public function isolate_pre_get_posts_end( &$wp_query ) {

		global $better_amp_isolate_pre_get_posts;

		if ( is_better_amp( $wp_query ) && ! is_admin() && $wp_query->is_main_query() ) {
			if ( $better_amp_isolate_pre_get_posts ) {
				$wp_query->query_vars = $better_amp_isolate_pre_get_posts;
				unset( $better_amp_isolate_pre_get_posts );
			}
		}

	}


	/**
	 * Register missed hooks for supported plugins/themes to bypass isolation
	 *
	 * @see   isolate_pre_get_posts_start
	 * @see   isolate_pre_get_posts_end
	 *
	 * @since 1.1
	 */
	public function compatible_plugins_themes() {

		if ( ! is_better_amp() ) {
			return;
		}

		$priority = self::ISOLATE_QUERY_HOOK_PRIORITY + 1;

		// WooCommerce compatibility
		if ( class_exists( 'WooCommerce' ) ) {

			$callback = array( WooCommerce::instance()->query, 'pre_get_posts' );

			if ( is_callable( $callback ) ) {
				add_action( 'pre_get_posts', $callback, $priority );
			}

			// WooCommerce didn't change main query in "shop" page
			add_action( 'pre_get_posts', array( $this, '_fix_woocommerce_shop_page_query' ), $priority + 1 );

			$this->excluded_posts_id[ wc_get_page_id( 'checkout' ) ] = TRUE;
		}

		// BetterStudio themes compatibility
		add_action( 'better-framework/menu/walker/init', array( $this, 'disable_bf_mega_menu' ) );
	}


	/**
	 * Fixes global WP_Query for shop pages in AMP!
	 *
	 * @param $q
	 */
	function _fix_woocommerce_shop_page_query( $q ) {

		// We only want to affect the main query
		if ( ! $q->is_main_query() ) {
			return;
		}

		if ( isset( $q->queried_object->ID ) && $q->queried_object->ID === wc_get_page_id( 'shop' ) ) {
			$q->set( 'post_type', 'product' );
			$q->set( 'posts_per_page', 8 );
			$q->set( 'page', '' );
			$q->set( 'pagename', '' );

			// Fix conditional Functions
			$q->is_archive           = TRUE;
			$q->is_post_type_archive = TRUE;
			$q->is_singular          = FALSE;
			$q->is_page              = FALSE;
		}
	}


	/**
	 * Disable BetterStudio themes mega menu in AMP pages
	 *
	 * @param BF_Menu_Walker $walker
	 *
	 * @since 1.1
	 */
	public function disable_bf_mega_menu( &$walker ) {
		$fields = $walker->get_mega_menu_fields_id();

		unset( $fields['mega_menu'] );
		$walker->set_mega_menu_fields_id( $fields );
	}


	/**
	 * Callback: Add rewrite rules
	 * Action: init
	 *
	 * @since 1.0.0
	 */
	public function add_rewrite() {
		better_amp_add_rewrite_startpoint( self::STARTPOINT, EP_ALL );

		/**
		 * automattic amp compatibility
		 */
		$amp_qv = defined( 'AMP_QUERY_VAR' ) ? AMP_QUERY_VAR : 'amp';
		add_rewrite_endpoint( $amp_qv, EP_PERMALINK );
	}


	/**
	 * Add a rewrite rule to detect site.com/amp/ requests
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function append_index_rewrite_rule() {
		add_rewrite_rule( self::STARTPOINT . '/?$', "index.php?amp=index", 'top' );
	}

	/**
	 * Callback: Include AMP template file in AMP pages
	 * Action  : template_include
	 *
	 * @param string $template_file_path original template file path
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function include_template_file( $template_file_path ) {

		if ( is_better_amp() ) {

			$include = $this->template_loader();

			if ( $include = apply_filters( 'better-amp/template/include', $include ) ) {
				return $include;
			} else if ( current_user_can( 'switch_themes' ) ) {
				wp_die( __( 'Better-AMP Theme Was Not Found!', 'better-amp' ) );
			} else {
				return BETTER_AMP_TPL_COMPAT_ABSPATH . '/no-template.php';
			}

		}

		return $template_file_path;
	}


	/**
	 * Include WooCommerce AMP templates in AMP pages
	 *
	 * @param string $located
	 * @param string $template_name
	 *
	 * @since 1.1
	 * @return string
	 */
	public function include_wc_template_file( $located, $template_name ) {
		$template_name = 'woocommerce/' . ltrim( $template_name, '/' );

		if ( $new_path = better_amp_locate_template( $template_name, FALSE, FALSE ) ) {
			return $new_path;
		}

		return $located;
	}

	/**
	 * Replace amp comment file with theme file
	 *
	 * @param string $file
	 *
	 * @since 1.1
	 * @return string
	 */
	public function override_comments_template( $file ) {

		if ( is_better_amp() ) {

			if ( $path = better_amp_locate_template( basename( $file ) ) ) {

				return $path;
			}
		}

		return $file;
	}


	/**
	 * Get AMP template file base on page of WordPress
	 *
	 * @link         https://developer.wordpress.org/themes/basics/template-hierarchy/
	 * @copyright    credit goes to WordPress team @see wp-includes/template-loader.php
	 * @access       private
	 *
	 * @since        1.0.0
	 *
	 * @return  string
	 */
	protected function template_loader() {

		if ( function_exists( 'is_embed' ) && is_embed() && $template = better_amp_embed_template() ) :
		elseif ( function_exists( 'is_woocommerce' ) && is_woocommerce() && is_page( wc_get_page_id( 'shop' ) ) && $template = better_amp_locate_template( 'woocommerce.php' ) ) :
		elseif ( is_404() && $template = better_amp_404_template() ) :
		elseif ( is_search() && $template = better_amp_search_template() ) :
		elseif ( is_front_page() && $template = better_amp_front_page_template() ) :
		elseif ( is_home() && $template = better_amp_home_template() ) :
		elseif ( is_post_type_archive() && $template = better_amp_post_type_archive_template() ) :
		elseif ( is_tax() && $template = better_amp_taxonomy_template() ) :
		elseif ( is_attachment() && $template = better_amp_attachment_template() ) :
			remove_filter( 'the_content', 'prepend_attachment' );
		elseif ( is_single() && $template = better_amp_single_template() ) :
		elseif ( is_page() && $template = better_amp_page_template() ) :
		elseif ( is_singular() && $template = better_amp_singular_template() ) :
		elseif ( is_category() && $template = better_amp_category_template() ) :
		elseif ( is_tag() && $template = better_amp_tag_template() ) :
		elseif ( is_author() && $template = better_amp_author_template() ) :
		elseif ( is_date() && $template = better_amp_date_template() ) :
		elseif ( is_archive() && $template = better_amp_archive_template() ) :
		elseif ( is_paged() && $template = better_amp_paged_template() ) :
		else :
			$template = better_amp_index_template();
		endif;

		return $template;

	}


	/**
	 * Include active template functions.php file if exits
	 *
	 * Callback: include
	 * action  : after_setup_theme
	 *
	 * @since 1.0.0
	 */
	public function include_template_functions_php() {

		if ( $theme_root = better_amp_get_template_directory() ) {

			if ( file_exists( $theme_root . '/functions.php' ) ) {
				include $theme_root . '/functions.php';
			}

			apply_filters( 'better-amp/template/init', $theme_root );

		}

	}


	/**
	 * Callback: Include registered AMP components
	 * Action: init
	 *
	 * @since 1.0.0
	 */
	public function include_components() {

		include BETTER_AMP_INC . 'components/class-better-amp-img-component.php';
		include BETTER_AMP_INC . 'components/class-better-amp-iframe-component.php';
		include BETTER_AMP_INC . 'components/class-better-amp-carousel-component.php';

	}

	/**
	 * Transforms HTML content to AMP content
	 *
	 * todo: Add file caching
	 *
	 * @param Better_AMP_HTML_Util $instance
	 * @param boolean              $sanitize
	 *
	 * @since 1.0.0
	 */
	public function render_content( Better_AMP_HTML_Util $instance, $sanitize = FALSE ) {

		$this->call_components_method( 'render', $instance );

		if ( $sanitize ) {
			$sanitizer = new Better_AMP_Content_Sanitizer( $instance );
			$sanitizer->sanitize();
		}

	} // render_content


	/**
	 * Register an autoloader for Better AMP classes
	 *
	 * @since 1.0.0
	 */
	public function register_autoload() {
		spl_autoload_register( array( __CLASS__, 'autoload_amp_classes' ) );
	}


	/**
	 * Autoload handler for better AMP classes only
	 *
	 * @param string $class_name class to include
	 *
	 * @since 1.0.0
	 */
	public static function autoload_amp_classes( $class_name ) {

		if ( substr( $class_name, 0, 11 ) !== 'Better_AMP_' ) {
			return;
		}

		$is_interface         = substr( $class_name, - 10 ) === '_Interface';
		$class_name_prefix    = $is_interface ? 'interface-' : 'class-';
		$sanitized_class_name = strtolower( $class_name );
		$sanitized_class_name = str_replace( '_', '-', $sanitized_class_name );

		// Remove interface suffix
		if ( $is_interface ) {
			$sanitized_class_name = substr( $sanitized_class_name, 0, - 10 );
		}

		$class_file = BETTER_AMP_INC . 'classes/' . $class_name_prefix . $sanitized_class_name . '.php';

		if ( file_exists( $class_file ) ) {
			require_once $class_file;
		}

	}


	/**
	 * Transform HTML content to AMP version when AMP version requested
	 *
	 * @param string $content html
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function convert_content_to_amp( $content ) {

		if ( is_better_amp() ) {
			$content = $this->render_content( $content );
		}

		return $content;

	} // convert_content_to_amp


	/**
	 * Determines that method exists and is callable on object instance
	 *
	 * @param Better_AMP_Component $instance    Live object of Better_AMP_Component
	 * @param string               $method_name Method of object
	 * @param array                $args
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function _can_call_component_method( &$instance, &$method_name, &$args ) {

		$return = is_callable( array( $instance, $method_name ) );

		switch ( $method_name ) {
			case 'enqueue_amp_scripts':
				$return = $instance->can_enqueue_scripts();
				break;
		}

		return $return;
	}


	/**
	 * Fire specific method of all components
	 *
	 * @param string $method_name component method
	 *
	 * @param mixed  $param
	 *
	 * @return mixed
	 * @since 1.0.0
	 *
	 */
	public function call_components_method( $method_name, $param = NULL ) {

		global $better_amp_registered_components;

		if ( ! $better_amp_registered_components ) {
			return $param;
		}

		// collect and prepare method arguments
		$args = func_get_args();
		$args = array_slice( $args, 1 );
		if ( ! isset( $args[0] ) ) {
			$args[0] = NULL;
		}

		// iterate registered components and call method on them
		foreach ( $better_amp_registered_components as $component ) {

			$instance = Better_AMP_Component::instance( $component['component_class'] );

			if ( $this->_can_call_component_method( $instance, $method_name, $args ) ) {
				$args[0] = call_user_func_array( array( $instance, $method_name ), $args );
			}
		}

		return $args[0];

	}


	/**
	 * Add components shortcode before do_shortcodes
	 *
	 * @param string $content
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function register_components_shortcodes( $content = '' ) {

		if ( ! is_better_amp() ) {
			return $content;
		}

		$this->call_components_method( 'register_shortcodes' );

		return $content;
	}


	/**
	 * Callback: Fire head method of component for following purpose:
	 * 1) Component able to add_filter or add_action if needed
	 * 2) Create fresh instance of each component and cache
	 *
	 * Action  : better-amp/template/head
	 *
	 * @since 1.0.0
	 */
	public function trigger_component_head() {
		$this->call_components_method( 'head' );
	}


	/**
	 * Clean components instances and free up the memory
	 *
	 * @param string $content
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function component_actions_finished( $content = '' ) {

		Better_AMP_Component::flush_instances();

		return $content;
	}


	/**
	 * Append AMP components javascript if AMP version requested
	 *
	 * @since 1.0.0
	 */
	public function enqueue_components_scripts() {

		$deps = array( 'ampproject' );

		better_amp_enqueue_script( $deps[0], 'https://cdn.ampproject.org/v0.js' );

		// Enqueues all needed scripts of components with 'ampproject' dependency
		$this->call_components_method( 'enqueue_amp_scripts', $deps );

		if ( current_theme_supports( 'better-amp-navigation' ) && current_theme_supports( 'better-amp-has-nav-child' ) ) {
			better_amp_enqueue_script( 'amp-accordion', 'https://cdn.ampproject.org/v0/amp-accordion-0.1.js' );
		}

		if ( current_theme_supports( 'better-amp-form' ) ) {
			better_amp_enqueue_script( 'amp-form', 'https://cdn.ampproject.org/v0/amp-form-0.1.js' );
		}

	}


	/**
	 * Callback: Replaces all website internal links with AMP version
	 * Filter  : better-amp/template/include
	 * We used this filter to insure replacement process just fire in AMP version.
	 *
	 * @param string $template
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function replace_internal_links_with_amp_version( $template ) {

		add_filter( 'nav_menu_link_attributes', array( 'Better_AMP_Content_Sanitizer', 'replace_href_with_amp' ) );
		add_filter( 'the_content', array( 'Better_AMP_Content_Sanitizer', 'transform_all_links_to_amp' ) );

		add_filter( 'author_link', array( 'Better_AMP_Content_Sanitizer', 'transform_to_amp_url' ) );
		add_filter( 'term_link', array( 'Better_AMP_Content_Sanitizer', 'transform_to_amp_url' ) );

		add_filter( 'post_link', array( $this, 'transform_post_link_to_amp' ), 20, 2 );
		add_filter( 'page_link', array( $this, 'transform_post_link_to_amp' ), 20, 2 );
		add_filter( 'attachment_link', array( 'Better_AMP_Content_Sanitizer', 'transform_to_amp_url' ) );
		add_filter( 'post_type_link', array( 'Better_AMP_Content_Sanitizer', 'transform_to_amp_url' ) );

		return $template;
	}

	/**
	 * Callback: Starts the collecting output to enable components to add style into head
	 * Print theme completely then fire better_amp_head() callbacks and append it before </head>
	 *
	 * Action  : better-amp/template/head
	 *
	 * @see   buffer_better_amp_head_end
	 *
	 * @since 1.0.0
	 */
	public function buffer_better_amp_head_start() {

		remove_action( current_action(), array( $this, __FUNCTION__ ), 1 );

		$this->collect_and_remove_better_amp_head_actions();

		ob_start();

	}

	/**
	 * Collect better_amp_head actions and remove those actions
	 *
	 * @see   better_amp_head
	 *
	 * @since 1.0.0
	 */
	public function collect_and_remove_better_amp_head_actions() {

		$actions = &$GLOBALS['wp_filter']['better-amp/template/head'];

		$this->_head_actions = $actions;
		$actions             = array();
	}


	/**
	 * Callback: Fire better_amp_head() and print buffered output
	 * Action  : better-amp/template/head
	 *
	 * @see   buffer_better_amp_head_start
	 *
	 * @since 1.0.0
	 */
	public function buffer_better_amp_head_end() {

		$content = ob_get_clean();
		$prepend = '';

		if ( ! better_amp_is_customize_preview() ) {

			$prepend .= '</head>';

			/**
			 * Convert output to valid amp html
			 */
			$instance = new Better_AMP_HTML_Util();
			$instance->loadHTML( '<!DOCTYPE html><html><head><meta http-equiv="content-type" content="text/html; charset=utf-8">' . $content . '</body></html>', NULL, FALSE );

			preg_match( '#(<\s*body[^>]*>)#isx', $content, $match );
			$prepend .= isset( $match[1] ) ? $match[1] : '<body>'; // open body tag

			$this->render_content( $instance, TRUE ); // Convert HTML top amp html
			$content = $instance->get_content( TRUE );
			// End convert output to valid amp html
		}

		$GLOBALS['wp_filter']['better-amp/template/head'] = $this->_head_actions;
		$this->_head_actions                              = array();

		do_action( 'better-amp/template/head' );

		echo $prepend, $content;
	}

	/**
	 * Init metaboxes
	 *
	 * @since 1.0.0
	 */
	public function metaboxes() {
		if ( is_admin() ) {
			add_action( 'add_meta_boxes', array( $this, 'append_metaboxes' ) );
			add_action( 'save_post', array( $this, 'save_metaboxes' ) );
		}
	}


	/**
	 * Adds meta box for posts
	 *
	 * @since 1.0.0
	 */
	public function append_metaboxes() {
		add_meta_box(
			'better-amp-settings',
			esc_html__( 'Better AMP Settings', 'better-amp' ),
			array( $this, 'metabox_output' ),
			array(
				'post',
				'page'
			),
			'side',
			'low'
		);
	}


	/**
	 * Prints post metabox
	 *
	 * @since 1.0.0
	 *
	 * @param $post
	 */
	public function metabox_output( $post ) {
		?>
		<div class="inside">

			<p>
				<label for="better-amp-enable">
					<?php _e( 'Disable amp version', 'better-amp' ) ?>
				</label>
				<input type="checkbox" name="better-amp-enable" id="better-amp-enable"
				       value="1" <?php checked( TRUE, get_post_meta( $post->ID, 'disable-better-amp', TRUE ) ) ?>>
			</p>
		</div>
		<?php
	}


	/**
	 * Callback to save post metabox
	 *
	 * @since 1.0.0
	 *
	 * @param $post_id
	 */
	public function save_metaboxes( $post_id ) {

		if ( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
			return;
		}

		if ( empty( $_POST['better-amp-enable'] ) ) {

			delete_post_meta( $post_id, 'disable-better-amp' );
		} else {

			update_post_meta( $post_id, 'disable-better-amp', '1' );
		}
	}


	/**
	 * Admin functionality
	 *
	 * @since 1.0.0
	 */
	public function admin_init() {

		if ( ! is_admin() ) {
			return;
		}

		if ( ! class_exists( 'Redux' ) ) {

			require_once BETTER_AMP_INC . 'redux/redux-framework.php';
		}

		require_once BETTER_AMP_INC . 'admin-fields.php';
	}


	/**
	 * Fix to change first menu name!
	 * @since 1.0.0
	 */
	public function fix_admin_sub_menu() {

		global $submenu;

		if ( isset( $submenu['better-amp-translation'][0] ) ) {
			$submenu['better-amp-translation'][0][0] = __( 'Translation', 'better-amp' );
		} elseif ( isset( $submenu['better-amp-translation'][1] ) ) {
			$submenu['better-amp-translation'][1][0] = __( 'Translation', 'better-amp' );
		}

	}


	/**
	 * Handy fix for changing search query
	 *
	 * @param $q
	 *
	 * @since 1.0.0
	 * @return mixed
	 */
	public function fix_search_page_queries( $q ) {

		if ( ! empty( $q['amp'] ) && ! empty( $q['s'] ) ) {

			$q['post_type'] = array( 'post' );
		}

		return $q;
	}


	/**
	 * Change most poplular cache plugins in amp version to compaibile with it
	 *
	 * @since 1.0.0
	 */
	public function plugins_compatibility() {

		if ( ! is_better_amp() ) {
			return;
		}

		/**
		 * W3 total cache
		 */
		add_filter( 'w3tc_minify_js_enable', array( $this, '_return_false' ) );
		add_filter( 'w3tc_minify_css_enable', array( $this, '_return_false' ) );


		/**
		 * WP Rocket
		 */
		if ( defined( 'WP_ROCKET_VERSION' ) ) {

			if ( ! defined( 'DONOTMINIFYCSS' ) ) {
				define( 'DONOTMINIFYCSS', TRUE );
			}

			if ( ! defined( 'DONOTMINIFYJS' ) ) {
				define( 'DONOTMINIFYJS', TRUE );
			}

			// Disable WP Rocket lazy load
			add_filter( 'do_rocket_lazyload', '__return_false', PHP_INT_MAX );
			add_filter( 'do_rocket_lazyload_iframes', '__return_false', PHP_INT_MAX );
		}


		/**
		 * Lazy Load
		 * https://wordpress.org/plugins/lazy-load/
		 */
		if ( class_exists( 'LazyLoad_Images' ) ) {
			add_filter( 'lazyload_is_enabled', '__return_false', PHP_INT_MAX );
		}


		/**
		 * Lazy Load XT
		 * https://wordpress.org/plugins/lazy-load-xt/
		 */
		if ( class_exists( 'Image_Lazy_Load' ) ) {

			global $lazyloadxt;

			if ( is_object( $lazyloadxt ) ) {
				remove_filter( 'the_content', array( $lazyloadxt, 'filter_html' ) );
				remove_filter( 'widget_text', array( $lazyloadxt, 'filter_html' ) );
				remove_filter( 'post_thumbnail_html', array( $lazyloadxt, 'filter_html' ) );
				remove_filter( 'get_avatar', array( $lazyloadxt, 'filter_html' ) );
			}
		}

	}

	/**
	 * Just return false in amp version
	 *
	 * @param bool $current
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function _return_false_in_amp( $current ) {

		if ( is_better_amp() ) {
			return FALSE;
		}

		return $current;
	}


	/**
	 * Transform allowed posts url to amp
	 *
	 * @param string      $url  The post's permalink.
	 * @param WP_Post|int $post The post object/id  of the post.
	 *
	 * @since 1.1
	 * @return string
	 */
	public function transform_post_link_to_amp( $url, $post ) {

		$post_id = isset( $post->ID ) ? $post->ID : $post;

		if ( isset( $this->excluded_posts_id[ $post_id ] ) ) {
			return $url;
		}

		return Better_AMP_Content_Sanitizer::transform_to_amp_url( $url );
	}

}
