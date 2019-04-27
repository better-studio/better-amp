<?php

/**
 * amp-instagram Component
 *
 * @since 1.0.0
 */
class Better_AMP_Instagram_Component implements Better_AMP_Component_Interface {

	/**
	 * @see   Better_AMP_Component_Base::$enable_enqueue_scripts
	 *
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	public $enable_enqueue_scripts = false;


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
				'amp-instagram' => 'https://cdn.ampproject.org/v0/amp-instagram-0.1.js'
			)
		);
	}

	/**
	 * Transform instagram embedded code to <amp-instagram> tags
	 *
	 *
	 * @since 1.0.0
	 *
	 * @param Better_AMP_HTML_Util $instance
	 *
	 * @return Better_AMP_HTML_Util
	 */
	public function transform( Better_AMP_HTML_Util $instance ) {

		$elements = $instance->getElementsByTagName( 'blockquote' );

		/**
		 * @var DOMElement $element
		 */
		if ( ! $nodes_count = $elements->length ) {

			return $instance;
		}

		for ( $i = $nodes_count - 1; $i >= 0; $i -- ) {

			if ( ! $element = $elements->item( $i ) ) {

				continue;
			}

			$post_url = $element->getAttribute( 'data-instgrm-permalink' );

			if ( ! preg_match( '#^(?: https?:)?// (?: w{3}.)? instagram.com /+ p/+ ([^/]+) /*#ix', $post_url, $match ) ) {
				continue;
			}

			$this->enable_enqueue_scripts = true;

			$attributes = array(
				'data-shortcode' => $match[1],
				'width'          => '1',
				'height'         => '1',
				'layout'         => 'responsive',
			);

			$instance->replace_node( $element, 'amp-instagram', $attributes );
		}

		return $instance;
	}
}


// Register component class
better_amp_register_component( 'Better_AMP_Instagram_Component' );
