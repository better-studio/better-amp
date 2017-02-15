<?php

/**
 * Base component class
 *
 * AMP components can extend this class and use the utility methods
 *
 * Methods:
 *
 *
 * ├── Cache Methods:
 *      ├── cache_get: Fetch data from cache storage
 *      │
 *      └── cache_set: Store data in cache
 *
 * @since 1.0.0
 */
abstract class Better_AMP_Component_Base {

	/**
	 * Flag to enable component scripts
	 *
	 * IF( TRUE ):
	 *   The component script will print before </head> tag
	 *   scripts list should added in config method
	 * @see   Better_AMP_Component_Interface::config documentation
	 *
	 * IF( false )
	 *   The scripts will not append into theme head
	 *
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	public $enable_enqueue_scripts = FALSE;


	/**
	 * Retrieve the content of the highest priority amp template file that exists.
	 *
	 * @param array|string $template_names
	 * @param array        $props
	 * @param bool         $load         If true the template file will be loaded if it is found.
	 * @param bool         $require_once Whether to require_once or require. Default true. Has no effect if $load is
	 *                                   false.
	 *
	 * @since 1.0.0
	 *
	 * @return string file content
	 */
	protected function locate_template( $template_names, $props = array(), $load = TRUE, $require_once = TRUE ) {

		ob_start();

		better_amp_set_prop( get_class( $this ), $props );

		better_amp_locate_template( $template_names, $load, $require_once );

		return ob_get_clean();
	}

}
