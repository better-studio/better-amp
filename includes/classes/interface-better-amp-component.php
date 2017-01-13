<?php

/**
 * Base Contact of the amp component
 *
 *
 * @since 1.0.0
 */
interface Better_AMP_Component_Interface {

	/**
	 * Prepare HTML content before printing
	 *
	 * @param string $content post_content html
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function transform( $content );


	/**
	 * Component config array {
	 *
	 * @type array $shortcodes array {
	 *   'shortcode name' => 'shortcode handler callback'
	 *    ...
	 *  }
	 *  'scripts' => array(
	 *               'handle name' =>  'amp script url',
	 *              ...
	 *      )
	 *
	 * }
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function config();
}
