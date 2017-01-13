<?php

/**
 * amp-img Component
 *
 * @since 1.0.0
 */
class Better_AMP_iFrame_Component implements Better_AMP_Component_Interface {

	/**
	 * @see   Better_AMP_Component_Base::$enable_enqueue_scripts
	 *
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	public $enable_enqueue_scripts = FALSE;


	/**
	 * Contract implementation
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function config() {
		return array(
			'scripts' => array(
				'amp-iframe' => 'https://cdn.ampproject.org/v0/amp-iframe-0.1.js'
			)
		);
	}


	/**
	 * Transform <img> tags to the <amp-img> or <img-anim> tags
	 *
	 * @param string $content
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function transform( $content ) {

		$instance = new Better_AMP_HTML_Util( $content );
		$elements = $instance->getElementsByTagName( 'iframe' );

		/**
		 * @var DOMElement $element
		 */
		if ( $nodes_count = $elements->length ) {
			$this->enable_enqueue_scripts = TRUE;

			for ( $i = $nodes_count - 1; $i >= 0; $i -- ) {
				$element = $elements->item( $i );

				$attributes = $instance->filter_attributes( $instance->get_node_attributes( $element ) );
				$attributes = $this->filter_attributes( $attributes );

				$instance->replace_node( $element, 'amp-iframe', $attributes );
			}
		}

		return $instance->get_content();
	}


	/**
	 * This is our workaround to enforce max sizing with layout=responsive.
	 *
	 * We want elements to not grow beyond their width and shrink to fill the screen on viewports smaller than their
	 * width.
	 *
	 * See https://github.com/ampproject/amphtml/issues/1280#issuecomment-171533526
	 * See https://github.com/Automattic/amp-wp/issues/101
	 *
	 * @copyright credit goes to automattic amp - github.com/Automattic/amp-wp
	 *
	 * @since     1.0.0
	 */
	public function enforce_sizes_attribute( $attributes ) {

		if ( ! isset( $attributes['width'], $attributes['height'] ) ) {
			return $attributes;
		}

		$max_width = $attributes['width'];

		if ( ( $_max_width = better_amp_get_container_width() ) && $max_width > $_max_width ) {
			$max_width = $_max_width;
		}

		$attributes['sizes'] = sprintf( '(min-width: %1$dpx) %1$dpx, 100vw', absint( $max_width ) );

		return $attributes;
	}


	/**
	 * Filter iFrame attributes
	 *
	 * @param array $attributes
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function filter_attributes( $attributes ) {

		$results = array();

		foreach ( $attributes as $key => $value ) {

			switch ( $key ) {

				case 'frameborder':
					if ( $value === 'no' ) {
						$value = '0';
					} else if ( '0' !== $value && '1' !== $value ) {
						$value = '0';
					}

					if ( $value !== '0' ) {
						$results[ $key ] = $value;
					}
					break;

				case 'allowfullscreen':
				case 'allowtransparency':
				case 'class':
				case 'sandbox':
				case 'src':
				case 'sizes':

					if ( $value !== '0' ) {
						$results[ $key ] = $value;
					}
					break;

			}
		}

		if ( ! isset( $results['sandbox'] ) ) {
			$results['sandbox'] = 'allow-scripts allow-same-origin';
		}

		if ( empty( $results['height'] ) ) { // height is required
			$results['height'] = $this->get_frame_height( $attributes['src'] );
		}

		return $results;
	}


	/**
	 * Returns appropriate frame height
	 *
	 * @param $url
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	protected function get_frame_height( $url ) {

		$height = 400;  // default height

		if ( preg_match( '#^https?://.*?\.soundcloud\.com#i', $url ) ) {
			$height = 156;
		}

		return $height;
	}
}

// Register component class
better_amp_register_component( 'Better_AMP_iFrame_Component' );
