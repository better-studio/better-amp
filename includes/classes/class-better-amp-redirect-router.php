<?php


class Better_Amp_Redirect_Router {

	/**
	 * Store self instance
	 *
	 * @var self
	 *
	 * @since 1.9.4
	 */
	protected static $instance;


	/**
	 * Store AMP query var.
	 *
	 * @var string
	 *
	 * @since 1.9.4
	 */
	protected $query_var;


	/**
	 * Store requested url path.
	 *
	 * @var string
	 *
	 * @since 1.9.4
	 */
	protected $request_url;


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


		add_action( 'template_redirect', array( $this, 'redirect_to_amp_url' ) );
	}


	/**
	 * Redirect AMP like URLs to main valid URL.
	 *
	 * @hooked template_redirect
	 *
	 * @since  1.0.0
	 */
	public function redirect_to_amp_url() {

		if ( ! better_amp_using_permalink_structure() ) {
			return;
		}

		# Disable functionality in customizer preview
		if ( function_exists( 'is_customize_preview' ) && is_customize_preview() ) {

			return;
		}

		list( $req_uri ) = explode( '?', $_SERVER['REQUEST_URI'] );

		$this->query_var   = defined( 'AMP_QUERY_VAR' ) ? AMP_QUERY_VAR : Better_AMP::SLUG;
		$this->request_url = str_replace( bf_get_wp_installation_slug(), '', $req_uri );

		if ( ! Better_AMP::get_instance()->amp_version_exists() ) {

			$new_url = Better_AMP_Content_Sanitizer::transform_to_none_amp_url( better_amp_get_canonical_url(), true );

		} elseif ( better_amp_url_format() === 'start-point' ) {

			$new_url = $this->transform_to_start_point_url();

		} else {

			$new_url = $this->transform_to_end_point_url();
		}

		if ( $this->can_redirect_url( $new_url ) ) {

			wp_redirect( $new_url, 301 );
			exit;
		}
	}

	/**
	 * Whether to check ability to redirect user to given url.
	 *
	 * @since 1.9.4
	 *
	 * @param $url
	 *
	 * @return bool
	 */
	protected function can_redirect_url( $url ) {

		list( $url ) = explode( '?', $url );

		return ! empty( $url ) && trim( str_replace( home_url(), '', $url ), '/' ) !== trim( $this->request_url, '/' );
	}

	/**
	 * Redirect start-point amp urls to end-point
	 *
	 * @since 1.9.0
	 * @return string
	 */
	public function transform_to_end_point_url() {

		if ( ! preg_match( '#^/?([^/]+)(.+)#', $this->request_url, $match ) ) {
			return '';
		}

		$slug = Better_AMP::SLUG;

		if ( $match[1] !== $slug ) {

			return $this->single_post_pagination_amp_url();
		}

		/**
		 * Skip redirection for amp pages because it looks like like start-point!
		 *
		 * EX:
		 *  amp/page/2   ✔
		 *  /page/2/amp  ✘
		 */
		if ( preg_match( "#$slug/page/?([0-9]{1,})/?$#", $this->request_url ) ) {

			return '';
		}

		if ( trim( $match[2], '/' ) !== '' ) {

			return trailingslashit(
				Better_AMP_Content_Sanitizer::transform_to_amp_url(
					home_url( $match[2] )
				)
			);
		}
	}

	/**
	 * Redirect end-point amp urls to start-point
	 *
	 * @since 1.9.0
	 * @return string
	 */
	public function transform_to_start_point_url() {

		# /amp at the end of some urls cause 404 error
		if ( get_query_var( $this->query_var, false ) === false && ! is_404() ) {
			return '';
		}

		$url_prefix = preg_quote( better_amp_permalink_prefix(), '#' );

		preg_match( "#^/*$url_prefix(.*?)/{$this->query_var}/*$#", $this->request_url, $automattic_amp_match );

		if ( ! Better_AMP::get_instance()->amp_version_exists() ) {

			if ( ! empty( $automattic_amp_match[1] ) ) {

				return home_url( $automattic_amp_match[1] );

			} elseif ( preg_match( "#^/*{$this->query_var}/+(.*?)/*$#", $this->request_url, $matched ) ) {

				return home_url( $matched[1] );
			}

			return better_amp_get_canonical_url();
		}

		if ( ! empty( $automattic_amp_match[1] ) ) {

			return trailingslashit(
				Better_AMP_Content_Sanitizer::transform_to_amp_url(
					home_url( $automattic_amp_match[1] )
				)
			);
		}

		return '';
	}


	/**
	 * Convert the following url.
	 *
	 *  [single post]/[page-number]/amp
	 *
	 * to
	 *
	 *  [single post]/amp/[page-number]
	 *
	 *
	 * @since 1.0.
	 * @return string.
	 */
	protected function single_post_pagination_amp_url() {

		if ( is_archive() ) {
			return '';
		}

		global $wp_rewrite;

		$single_post_format = str_replace( $wp_rewrite->rewritecode, $wp_rewrite->rewritereplace, get_option( 'permalink_structure' ) );
		//
		$test_pattern = '(' . $single_post_format . ')'; // Capture as the first item $match[1]
		$test_pattern .= '(\d+)/+';                     //  Capture as the last item array_pop( $match )
		$test_pattern .= $this->query_var . '/?';

		if ( preg_match( "#^$test_pattern$#", $this->request_url, $match ) ) {

			$page_number          = array_pop( $match );
			$none_amp_request_url = $match[1];

			return home_url( $none_amp_request_url . $this->query_var . '/' . $page_number );
		}

		return '';
	}
}
