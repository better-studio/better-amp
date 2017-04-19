<?php

/**
 * Core class to add new feature to WordPress Rewrite API (such as start points)
 *
 * todo: replace EP_* constants with SP_*
 *
 * @since 1.0.0
 */
class Better_AMP_Rewrite_Rules {

	/**
	 * Store start pints rules like <amp>/category/slug
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public $startpints = array();


	/**
	 *
	 * Store list of permastructs keys
	 *
	 * @see   register_extra_permastruct_hooks
	 * @since 1.1
	 *
	 * @var array
	 */
	protected $exclude_extra_permastructs = array(
		'category'          => TRUE,
		'post_tag'          => TRUE,
		'post_format'       => TRUE,

		# Woocommerce
		'product_variation' => TRUE,
		'shop_order_refund' => TRUE,

		# Visual Composer
		'vc_grid_item'      => TRUE,
	);

	/**
	 * Better_AMP_Rewrite_Rules constructor.
	 */
	public function __construct() {
		$this->add_rewrite_rules_hooks();
	}


	/**
	 * Append hooks to when generating rewrite rules
	 *
	 * @since 1.0.0
	 */
	public function add_rewrite_rules_hooks() {

		foreach (
			array(
				'post_rewrite_rules',
				'date_rewrite_rules',
				'root_rewrite_rules',
				'comments_rewrite_rules',
				'search_rewrite_rules',
				'author_rewrite_rules',
				'page_rewrite_rules',
				'category_rewrite_rules',
				'post_tag_rewrite_rules',
				'post_format_rewrite_rules',
			) as $hook
		) {
			add_filter( $hook, array( $this, 'generate_rewrite_rules' ), 9999 );
		}

		add_action( 'root_rewrite_rules', array( $this, 'register_extra_permastruct_hooks' ) );
	}

	/**
	 * Register "{$permastructname}_rewrite_rules" Filters
	 *
	 * @param      array  $rules      Root rewrite rules
	 *
	 * @global WP_Rewrite $wp_rewrite WordPress rewrite component.
	 * @since 1.1
	 *
	 * @return array
	 */
	public function register_extra_permastruct_hooks( $rules ) {
		global $wp_rewrite;

		// Remove exluced items from extra_permastructs
		$extra_permastruct = array_diff_key( $wp_rewrite->extra_permastructs, $this->get_exclude_extra_permastructs() );

		foreach ( $extra_permastruct as $permastructname => $struct ) {

			if ( empty( $struct['walk_dirs'] ) ) {
				continue;
			}

			if ( ! has_filter( "{$permastructname}_rewrite_rules", array( $this, 'generate_rewrite_rules' ) ) ) {

				add_filter( "{$permastructname}_rewrite_rules", array( $this, 'generate_rewrite_rules' ), 9999 );
			}
		}

		return $rules;
	}

	/**
	 * Get list of extra_permastructs to skip append startpoint
	 * @see   exclude_extra_permastructs
	 *
	 * @since 1.1
	 * @return array
	 */
	public function get_exclude_extra_permastructs() {
		return $this->exclude_extra_permastructs;
	}


	/**
	 * Set list of extra_permastructs to skip append startpoint
	 *
	 * @since 1.1
	 *
	 * @param string|array $permastructname
	 */
	public function set_exclude_extra_permastructs( $permastructname ) {

		foreach ( (array) $permastructname as $name ) {
			$this->exclude_extra_permastructs[ $name ] = TRUE;
		}
	}


	/**
	 * Flush exclude permastructs storage
	 *
	 * @since 1.1
	 *
	 * @return bool always true
	 */
	public function flush_exclude_extra_permastructs() {

		$this->exclude_extra_permastructs = array();

		return TRUE;
	}


	/**
	 * Get Endpoint mask of rewrite groups
	 *
	 * todo: add support for EP_DAY,EP_MONTH,EP_YEAR
	 * todo: detect EP_ATTACHMENT
	 *
	 * @global WP_Rewrite $wp_rewrite WordPress Rewrite Component.
	 * @since 1.0.0
	 *
	 * @return int
	 */
	protected function get_current_sp_mask() {
		global $wp_rewrite;

		$current_filter = current_filter();

		switch ( $current_filter ) {

			case 'post_rewrite_rules':
				$ep_mask = EP_PERMALINK;
				break;

			case 'date_rewrite_rules':
				$ep_mask = EP_DATE;
				break;

			case 'root_rewrite_rules':
				$ep_mask = EP_ROOT;
				break;

			case 'comments_rewrite_rules':
				$ep_mask = EP_COMMENTS;
				break;

			case 'search_rewrite_rules':
				$ep_mask = EP_SEARCH;
				break;

			case 'author_rewrite_rules':
				$ep_mask = EP_AUTHORS;
				break;

			case 'page_rewrite_rules':
				$ep_mask = EP_PAGES;
				break;

			case 'category_rewrite_rules':
				$ep_mask = EP_CATEGORIES;
				break;

			case 'post_tag_rewrite_rules':
				$ep_mask = EP_TAGS;
				break;

			default:

				$ep_mask = EP_NONE;

				if ( preg_match( '/(.+)_rewrite_rules$/', $current_filter, $matched ) ) {

					if ( isset( $wp_rewrite->extra_permastructs[ $matched[1] ]['ep_mask'] ) ) {

						$ep_mask = max(
							$wp_rewrite->extra_permastructs[ $matched[1] ]['ep_mask'],
							1
						);
					}
				}
		}


		return $ep_mask;
	} // get_current_ep_mask


	/**
	 * Get list of start points to append
	 *
	 * @since 1.0.0
	 */
	public function get_start_points() {

		static $query_append;

		if ( is_null( $query_append ) ) {
			$query_append = array();

			foreach ( (array) $this->startpints as $endpoint ) {
				if ( $endpoint[3] ) {
					$spmatch = $endpoint[1] . '/';
				} else {
					$spmatch = $endpoint[1] . '/([^/]+)?/?';
				}

				$epquery                  = '&' . $endpoint[2] . '=';
				$query_append[ $spmatch ] = array( $endpoint[0], $epquery, $endpoint[3] );
			}
		}

		return $query_append;
	}


	/**
	 * Callback for preg_replace_callback to
	 * Increase rewrite query vars preg_index index number
	 *
	 * @see   increase_pattern_preg_index
	 * @see   \WP_Rewrite::preg_index
	 *
	 * @private
	 *
	 * @param string      $matched
	 *
	 * @global WP_Rewrite $wp_rewrite WordPress Rewrite Component.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function _increase_preg_index_replace_callback( $matched ) {

		global $wp_rewrite;

		$index = intval( $matched[1] );

		return $wp_rewrite->preg_index( $index + 1 );
	}

	/**
	 * Generate startpoint rewrite rules
	 *
	 * @param array       $rewrite_rules
	 *
	 * @global WP_Rewrite $wp_rewrite WordPress Rewrite Component.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function generate_rewrite_rules( $rewrite_rules ) {

		global $wp_rewrite;

		$current_SP = $this->get_current_sp_mask();

		$startpints = $this->get_start_points();

		if ( ! $startpints ) {
			return $rewrite_rules;
		}

		$results = array();

		foreach ( $rewrite_rules as $regex => $query ) {

			$vars = array();

			wp_parse_str( $query, $vars );

			if ( ! isset( $vars['feed'] ) ) { //skip feeds regex
				foreach ( $startpints as $spregex => $sp ) {
					if ( $sp[0] & $current_SP ) {

						if ( $sp[2] ) {
							$startpint_query = $query . $sp[1] . '1';
						} else {
							$startpint_query = $this->increase_pattern_preg_index( $query ) . $sp[1] . $wp_rewrite->preg_index( 1 );
						}

						$results[ $spregex . $regex ] = $startpint_query;
					}

				}
			}

			$results[ $regex ] = $query;
		}

		return $results;
	} // generate_rewrite_rules

	/**
	 * Increase rewrite query vars preg_index index number
	 *
	 * @param string      $query
	 *
	 * @global WP_Rewrite $wp_rewrite WordPress Rewrite Component.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function increase_pattern_preg_index( $query ) {

		global $wp_rewrite;

		$pattern = preg_quote( $wp_rewrite->preg_index( 'PLACEHOLDER' ) );
		$pattern = '/' . str_replace( 'PLACEHOLDER', '(\\d+)', $pattern ) . '/';

		$query = preg_replace_callback( $pattern, array( $this, '_increase_preg_index_replace_callback' ), $query );

		return $query;
	}


	/**
	 * Add a start point
	 *
	 *
	 * @param string      $name
	 * @param int         $places
	 * @param string|bool $query_var
	 * @param bool        $single_match {@see better_amp_add_rewrite_startpoint for documentation}
	 *
	 * @see   add_rewrite_endpoint for parameters documentation
	 *
	 * @global WP         $wp           Current WordPress environment instance.
	 *
	 * @since 1.0.0
	 */
	public function add_startpint( $name, $places, $query_var = TRUE, $single_match = TRUE ) {

		global $wp;

		// For backward compatibility, if null has explicitly been passed as `$query_var`, assume `true`.
		if ( TRUE === $query_var || NULL === func_get_arg( 2 ) ) {
			$query_var = $name;
		}

		$this->startpints[] = array( $places, $name, $query_var, $single_match );

		if ( $query_var ) {
			$wp->add_query_var( $query_var );
		}

	} // add_startpint

} // Better_AMP_Rewrite_Rules

$GLOBALS['better_amp_rewrite_rules'] = new Better_AMP_Rewrite_Rules();

/**
 * Add a startpoint to rewrite rules
 *
 *
 * @since 1.0.0
 *
 * @param string                    $name
 * @param int                       $places
 * @param string|bool               $query_var
 * @param bool                      $single_match             if false: user can pass any thing after start pint
 *                                                            for Ex: my-start-point/<something> if true:  just $name
 *                                                            at start of the url will accept  for Ex: my-start/ WP
 *                                                            Category URL
 *
 * @global Better_AMP_Rewrite_Rules $better_amp_rewrite_rules BetterAMP Rewrite API
 *
 * @see   add_rewrite_endpoint for parameters documentation
 *
 * @since 1.0.0
 */
function better_amp_add_rewrite_startpoint( $name, $places, $query_var = TRUE, $single_match = TRUE ) {

	global $better_amp_rewrite_rules;

	$better_amp_rewrite_rules->add_startpint( $name, $places, $query_var, $single_match );
}
