<?php


/**
 * Class to generate custom start/end point.
 *
 * @since 1.9.5
 */
class Better_AMP_Rewrite_Rule_Generator {

	/**
	 * Store list of permastructs keys to exclude.
	 *
	 * @see   register_extra_permastruct_hooks
	 * @since 1.9.5
	 *
	 * @var array
	 */
	protected $exclude_extra_permastructs = array(

		# Default WP Functionality
		'category'          => true,
		'post_tag'          => true,
		'post_format'       => true,

		# Woocommerce
		'product_variation' => true,
		'shop_order_refund' => true,

		# Visual Composer
		'vc_grid_item'      => true,
	);

	/**
	 * Store high priority rewrite rules.
	 *
	 * @since 1.9.5
	 *
	 * @var array
	 */
	protected $top_level_rules = array();

	/**
	 * Store custom start pints list.
	 *
	 * @since 1.9.5
	 *
	 * @var array
	 */
	protected $start_points = array();

	/**
	 * Store custom end pints rules.
	 *
	 * @since 1.9.5
	 *
	 * @var array
	 */
	protected $end_points = array();


	/**
	 * Better_AMP_End_Point constructor.
	 *
	 * @since 1.9.5
	 */
	public function init() {

		add_action( 'init', array( $this, 'add_rewrite_rules_hooks' ), 9e4 );
	}


	/**
	 * Add new rewrite rule.
	 *
	 * @param string $name
	 * @param int    $places
	 *
	 * @see   add_rewrite_endpoint for parameters documentation
	 *
	 * @global WP    $wp Current WordPress environment instance.
	 *
	 * @since 1.9.5
	 */
	public function add_start_point( $name, $places ) {

		global $wp;

		$query_var = $name;

		$this->start_points[] = array( $places, $name, $query_var );

		$wp->add_query_var( $query_var );

	} // add_start_point

	/**
	 * Add new rewrite rule.
	 *
	 * @param string $name
	 * @param int    $places
	 *
	 * @see   add_rewrite_endpoint for parameters documentation
	 *
	 * @global WP    $wp Current WordPress environment instance.
	 *
	 * @since 1.9.5
	 */
	public function add_end_point( $name, $places ) {

		global $wp;

		$query_var = $name;

		$this->end_points[] = array( $places, $name, $query_var );

		$wp->add_query_var( $query_var );

	} // add_end_point


	protected function post_type_archive_start_point_rule( $regex, $query, $sp ) {

		$query = $query . '&' . $sp[2] . '=1';
		$match = $sp[1] . '/' . ltrim( $regex, '/' );

		return array( $match, $query );
	}


	/**
	 * @return bool
	 */
	protected function post_type_archive_end_point_rule( $regex, $query, $ep ) {

		$match = trim( $regex, '/?$' ) . '/';
		$match .= $ep[1] . '/?$';
		//
		$query = $query . '&' . $ep[2] . '=1';

		return array( $match, $query );
	}


	/**
	 * @param string $regex
	 * @param string $query
	 * @param array  $ep
	 *
	 * @since 1.9.5
	 * @return array
	 */
	protected function generate_start_point_rule( $regex, $query, $ep ) {

		$url_prefix = self::url_prefix();
		$epregex    = $ep[1] . '/';

		if ( $url_prefix && preg_match( "#^($url_prefix)(.+)$#", $regex, $match ) ) {

			$match = $match[1] . $epregex . ltrim( $match[2], '/' );
		} else {

			$match = $epregex . ltrim( $regex, '/' );
		}

		$query = $query . '&' . $ep[2] . '=1';

		return array( $match, $query );
	}

	/**
	 * @param string $regex
	 * @param string $query
	 * @param array  $ep
	 *
	 * @since 1.9.5
	 * @return array
	 */
	public function generate_end_point_rule( $regex, $query, $ep ) {

		$query = $query . '&' . $ep[2] . '=1';

		if ( substr( $regex, - 3 ) === '/?$' ) {

			$match = substr( $regex, 0, - 3 );

		} else {

			$match = $regex;
		}

		if ( strstr( $regex, '([^/]+)(?:/([0-9]+))?' ) ) {


			list( $before, $after ) = explode( '([^/]+)(?:/([0-9]+))?', $regex );

			$match = $before . '([^/]+)/' . $ep[1] . '(?:/([0-9]+))?' . $after;

		} elseif ( strstr( $regex, 'page/?([0-9]{1,})' ) ) {

			list( $before, $after ) = explode( 'page/?([0-9]{1,})', $regex );

			$match = $before . $ep[1] . '/page/?([0-9]{1,})' . $after;

		} elseif ( strstr( $regex, '/comment-page-([0-9]{1,})' ) ) {

			list( $before, $after ) = explode( 'comment-page-([0-9]{1,})', $regex );

			$match = $before . $ep[1] . '/comment-page-([0-9]{1,})' . $after;

		} else {

			$match = rtrim( $match, '/' ) . '/' . $ep[1] . '/?$';
		}

		return array( $match, $query );
	}

	/**
	 * Append hooks to when generating rewrite rules
	 *
	 * @hooked init
	 * @since  1.0.0
	 */
	public function add_rewrite_rules_hooks() {

		$this->append_post_type_archive_rules();

		$this->register_generator_hooks();
	}

	/**
	 * Add rewrite rules for post type archive pages.
	 */
	protected function append_post_type_archive_rules() {

		global $wp_rewrite;

		$post_type_archive_ep_mask = EP_ROOT; // i'm not sure!

		foreach ( get_post_types( array( '_builtin' => false ) ) as $post_type ) {

			if ( isset( $wp_rewrite->extra_rules_top[ $post_type . '/?$' ] ) ) {

				$regex = $post_type . '/?$';
				$query = $wp_rewrite->extra_rules_top[ $post_type . '/?$' ];

			} elseif ( isset( $wp_rewrite->extra_rules_top[ '/' . $post_type . '/?$' ] ) ) {

				$regex = '/' . $post_type . '/?$';
				$query = $wp_rewrite->extra_rules_top[ '/' . $post_type . '/?$' ];

			} else {

				continue;
			}


			foreach ( $this->start_points as $sp ) {

				if ( ! $sp[0] & $post_type_archive_ep_mask ) {

					continue;
				}

				if ( ! $rule = $this->post_type_archive_start_point_rule( $regex, $query, $sp ) ) {
					continue;
				}

				$wp_rewrite->extra_rules_top[ $rule[0] ] = $rule[1];
			}

			foreach ( $this->end_points as $sp ) {

				if ( ! $sp[0] & $post_type_archive_ep_mask ) {

					continue;
				}

				if ( ! $rule = $this->post_type_archive_end_point_rule( $regex, $query, $sp ) ) {
					continue;
				}

				$wp_rewrite->extra_rules_top[ $rule[0] ] = $rule[1];
			}
		}
	}

	protected function register_generator_hooks() {

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

		add_filter( 'rewrite_rules_array', array( $this, 'append_high_priority_rules' ) );
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

		// Remove exclude items from extra_permastructs
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
	 *
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
			$this->exclude_extra_permastructs[ $name ] = true;
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

		return true;
	}


	/**
	 * Get Endpoint mask of rewrite groups
	 *
	 * todo: add support for EP_DAY,EP_MONTH,EP_YEAR
	 * todo: detect EP_ATTACHMENT
	 *
	 * @global WP_Rewrite $wp_rewrite WordPress Rewrite Component.
	 * @since 1.9.5
	 *
	 * @return int
	 */
	protected function current_ep_mask() {

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
	 * Generate startpoint rewrite rules.
	 *
	 * @param array $rewrite_rules
	 *
	 * @since 1.9.5
	 * @return array
	 */
	public function generate_rewrite_rules( $rewrite_rules ) {

		$current_ep = $this->current_ep_mask();
		$results    = array();

		/**
		 * Iterate through all WordPress rewrite rules.
		 */
		foreach ( $rewrite_rules as $regex => $query ) {

			wp_parse_str( $query, $vars );

			if ( isset( $vars['feed'] ) ) { # Skip feeds regex

				$results[ $regex ] = $query;
				continue;
			}

			/**
			 * Generate start points for current rule.
			 */
			foreach ( $this->start_points as $ep ) {

				// Skip duplicated items
				if ( preg_match( '/' . preg_quote( $ep[1] ) . '/', $query ) ) {
					continue;
				}

				if ( ! ( $ep[0] & $current_ep ) ) {
					continue;
				}

				if ( ! $rule = $this->generate_start_point_rule( $regex, $query, $ep ) ) {
					continue;
				}

				$results[ $rule[0] ] = $rule[1];
			}

			/**
			 * Generate end points for current rule.
			 */
			foreach ( $this->end_points as $ep ) {

				// Skip duplicated items
				if ( preg_match( '/' . preg_quote( $ep[1] ) . '/', $query ) ) {
					continue;
				}

				if ( ! ( $ep[0] & $current_ep ) ) {
					continue;
				}

				if ( ! $rule = $this->generate_end_point_rule( $regex, $query, $ep ) ) {
					continue;
				}


				if ( strstr( $regex, '(.?.+?)(?:/([0-9]+))?' ) ) {

					$this->top_level_rules[ $rule[0] ] = $rule[1];

				} elseif ( strstr( $rule[0], '[^/]+' ) || substr( $rule[0], 0, 6 ) === '(.+?)/' || strstr( $rule[0], ']+)' ) ) {

					$results[ $rule[0] ] = $rule[1];

				} else {

					$this->top_level_rules[ $rule[0] ] = $rule[1];
				}
			}

			$results[ $regex ] = $query;
		}

		return $results;
	} // generate_rewrite_rules


	/**
	 * @param array $rules
	 *
	 * @since 1.9.5
	 * @return array
	 */
	public function append_high_priority_rules( $rules ) {

		return array_merge( $this->top_level_rules, $rules );
	}


	/**
	 * Increase rewrite query vars preg_index index number
	 *
	 * @param string      $query
	 *
	 * @global WP_Rewrite $wp_rewrite WordPress Rewrite Component.
	 *
	 * @since 1.9.5
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
	 * Callback for preg_replace_callback to
	 * Increase rewrite query vars preg_index index number
	 *
	 * @see   increase_pattern_preg_index
	 * @see   WP_Rewrite::preg_index
	 *
	 * @private
	 *
	 * @param string      $matched
	 *
	 * @global WP_Rewrite $wp_rewrite WordPress Rewrite Component.
	 *
	 * @since 1.9.5
	 *
	 * @return string
	 */
	protected function _increase_preg_index_replace_callback( $matched ) {

		global $wp_rewrite;

		$index = intval( $matched[1] );

		return $wp_rewrite->preg_index( $index + 1 );
	}


	/**
	 * @since 1.9.5
	 * @return string
	 */
	protected static function url_prefix() {

		static $url_prefix;

		if ( ! isset( $url_prefix ) ) {

			$permalink_structure = get_option( 'permalink_structure' );
			$url_prefix          = substr( $permalink_structure, 0, strpos( $permalink_structure, '%' ) );
			$url_prefix          = preg_quote( ltrim( $url_prefix, '/' ), '#' );
		}

		return $url_prefix;
	}
}


$GLOBALS['better_amp_rewrite_rule_generator'] = new Better_AMP_Rewrite_Rule_Generator();
$GLOBALS['better_amp_rewrite_rule_generator']->init();

/**
 * Add a start-point to rewrite rules
 *
 *
 * @since 1.0.0
 *
 * @param string                             $name
 * @param int                                $places
 *
 * @global Better_AMP_Rewrite_Rule_Generator $better_amp_rewrite_rule_generator BetterAMP Rewrite API
 *
 * @see   add_rewrite_endpoint for parameters documentation
 *
 * @since 1.0.0
 */
function better_amp_add_rewrite_startpoint( $name, $places ) {

	global $better_amp_rewrite_rule_generator;

	$better_amp_rewrite_rule_generator->add_start_point( $name, $places );
}

/**
 * Add a end-point to rewrite rules
 *
 *
 * @since 1.0.0
 *
 * @param string                             $name
 * @param int                                $places
 *
 * @global Better_AMP_Rewrite_Rule_Generator $better_amp_rewrite_rule_generator BetterAMP Rewrite API
 *
 * @see   add_rewrite_endpoint for parameters documentation
 *
 * @since 1.0.0
 */
function better_amp_add_rewrite_endpoint( $name, $places ) {

	global $better_amp_rewrite_rule_generator;

	$better_amp_rewrite_rule_generator->add_end_point( $name, $places );
}
