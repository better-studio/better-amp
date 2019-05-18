<?php


/**
 * amp-instagram Component
 *
 * @since 1.0.0
 */
class Better_AMP_Playbuzz_Component implements Better_AMP_Component_Interface {

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
				'amp-playbuzz' => 'https://cdn.ampproject.org/v0/amp-playbuzz-0.1.js'
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

		$finder     = new DomXPath( $instance );
		$class_name = 'playbuzz';
		$elements   = $finder->query( "//*[contains(@class, '$class_name')]" );

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

			$id = $element->getAttribute( 'data-id' );

			if ( empty( $id ) ) {
				continue;
			}

			$this->enable_enqueue_scripts = true;

			$attributes = array(
				'data-item' => $id,
				'height'    => 500,
			);

			$instance->replace_node( $element, 'amp-playbuzz', $attributes );
		}

		return $instance;
	}
}


// Register component class
better_amp_register_component( 'Better_AMP_Playbuzz_Component' );
