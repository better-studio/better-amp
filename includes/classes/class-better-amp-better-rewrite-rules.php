<?php

/**
 * Some fix and improvement for WordPress rewirte endpoint.
 *
 * @since 1.9.4
 */


class Better_AMP_Better_Rewrite_Rules {

	/**
	 * Store self instance
	 *
	 * @var self
	 *
	 * @since 1.9.4
	 */
	protected static $instance;


	/**
	 * Store some rewire rule that must be in top of the rewrite rules.
	 *
	 * @var array
	 *
	 * @since 1.9.4
	 */
	protected $high_level_rules = array();


	/**
	 * Get singleton instance of the class.
	 *
	 * @since 1.9.4
	 * @return self
	 */
	public static function Run() {

		if ( ! self::$instance instanceof self ) {

			self::$instance = new self();
			self::$instance->init();
		}

		return self::$instance;
	}


	/**
	 * Initialize the module.
	 *
	 * @since 1.9.4
	 */
	public function init() {

		add_filter( 'post_rewrite_rules', array( $this, 'collect_high_level_rules' ), 999 );
		add_filter( 'page_rewrite_rules', array( $this, 'collect_high_level_rules' ), 999 );
		add_filter( 'rewrite_rules_array', array( $this, 'append_high_level_rules' ), 1000 );

		add_filter( 'rewrite_rules_array', array( $this, 'fix_end_point_rewrites' ), 100 );
		add_filter( 'category_rewrite_rules', array( $this, 'append_category_missing_rules' ), 100 );

		add_action( 'init', array( $this, 'append_rewrite_rules_filters' ) );
	}

	/**
	 * Change WP rewrite rules priority to works properly with end-point URL structure.
	 *
	 * @param array $rules The compiled array of rewrite rules.
	 *
	 * @hooked rewrite_rules_array
	 *
	 * @since  1.9.3
	 * @return array
	 */
	public function fix_end_point_rewrites( $rules ) {

		$low_priority_rules = array(
			Better_AMP::STARTPOINT . '(/(.*))?/?$'
		);

		if ( stristr( get_option( 'permalink_structure' ), '%category%/%postname%' ) ) {

			$low_priority_rules[] = '.?.+?/([^/]+)/' . Better_AMP::STARTPOINT . '(/(.*))?/?$';
		}

		$low_priority_rules = array_flip( array_unique( $low_priority_rules ) );

		return array_merge(
			array_diff_key( $rules, $low_priority_rules ),
			array_intersect_key( $rules, $low_priority_rules )
		);
	}


	/**
	 * @param array $rules
	 *
	 * @hooked rewrite_rules_array
	 *
	 * @since  1.9.4
	 * @return array
	 */
	public function collect_high_level_rules( $rules ) {

		foreach ( $rules as $match => $query ) {

			if ( ! preg_match( '/&page\=/', $query ) ) {
				continue;
			}

			$this->high_level_rules[ $match ] = $query;
		}

		return $rules;
	}


	/**
	 * Append high order rewrite rules at the of the other ones.
	 *
	 * @param array $rules
	 *
	 * @since 1.9.4
	 * @return array
	 */
	public function append_high_level_rules( $rules ) {

		if ( ! $this->high_level_rules ) {
			return $rules;
		}

		$high_level_rules = [];
		$amp_qv           = defined( 'AMP_QUERY_VAR' ) ? AMP_QUERY_VAR : Better_AMP::STARTPOINT;

		foreach ( $this->high_level_rules as $match => $query ) {

			if ( substr( $match, - 17 ) !== '(?:/([0-9]+))?/?$' ) {
				continue;
			}

			$high_level_rules[ substr( $match, 0, - 17 ) . '/' . $amp_qv . '/([0-9]+)/?$' ] = $query . '&' . $amp_qv . '=';
		}

		return array_merge( $high_level_rules, $rules );
	}

	/**
	 * Append some rewrite rules that add_rewrite_endpoint should do that
	 *
	 * todo: add support for custom taxonomies
	 *
	 * @param array $rules
	 *
	 * @since 1.9.4
	 * @return array
	 */
	public function append_category_missing_rules( $rules ) {

		global $wp_rewrite;

		$pattern = trim(
			str_replace( $wp_rewrite->rewritecode, $wp_rewrite->rewritereplace, $wp_rewrite->get_category_permastruct() ),
			'/'
		);
		$pattern .= '/' . Better_AMP::SLUG;
		$pattern .= '/page/?([0-9]{1,})/?$';

		$rules = array_merge( array(
			$pattern => $wp_rewrite->index . '?category_name=$matches[1]&paged=$matches[2]&' . Better_AMP::SLUG . '='
		), $rules );

		return $rules;
	}

	/**
	 * Add some filters to generate rules for custom taxonomies.
	 *
	 * @since 1.9.5
	 */
	public function append_rewrite_rules_filters() {

		global $wp_rewrite;


		if ( ! isset( $wp_rewrite->extra_permastructs ) ) {
			return;
		}

		$skip     = array( 'post_tag', 'post_format', 'category' );
		$callback = array( $this, 'append_missing_taxonomy_permastructs' );

		foreach ( $wp_rewrite->extra_permastructs as $permastructname => $_ ) {

			if ( in_array( $permastructname, $skip ) ) {
				continue;
			}

			if ( ! taxonomy_exists( $permastructname ) ) {
				continue;
			}

			add_filter( $permastructname . '_rewrite_rules', $callback );
		}
	}


	/**
	 * Append AMP rewrite rules for custom taxonomies.
	 *
	 * @param array $rules
	 *
	 * @since 1.9.5
	 * @return array
	 */
	public function append_missing_taxonomy_permastructs( $rules ) {

		$amp_rules = array();
		$next_page = '/page/?([0-9]{1,})/';

		foreach ( $rules as $match => $query ) {

			// Do not generate amp end-point rule for feeds
			if ( strstr( $query, '&feed=' ) ) {
				continue;
			}

			$e = explode( $next_page, $match );

			if ( isset( $e[1] ) ) {

				$pattern = $e[0] . '/' . BETTER_AMP::SLUG . $next_page . $e[1];

				$amp_rules[ $pattern ] = $query . '&' . BETTER_AMP::SLUG . '=';

			} elseif ( substr( $match, - 3 ) === '/?$' ) {

				$pattern = substr( $match, 0, - 3 );
				$pattern .= '/' . BETTER_AMP::SLUG . '/?$';

				$amp_rules[ $pattern ] = $query . '&' . BETTER_AMP::SLUG . '=';
			}
		}

		return array_merge( $amp_rules, $rules );
	}
}
