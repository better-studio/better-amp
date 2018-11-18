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

		$low_priority_rules = array();

		if ( stristr( get_option( 'permalink_structure' ), '%category%/%postname%' ) ) {

			$low_priority_rules[] = '.?.+?/([^/]+)/' . Better_AMP::STARTPOINT . '(/(.*))?/?$';
			$low_priority_rules[] = Better_AMP::STARTPOINT . '(/(.*))?/?$';
		}

		if ( ! $low_priority_rules ) {

			return $rules;
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

			$high_level_rules[ substr( $match, 0,- 17 ) . '/' . $amp_qv . '/([0-9]+)/?$' ] = $query . '&' . $amp_qv . '=';
		}

		return array_merge( $high_level_rules, $rules );
	}
}
