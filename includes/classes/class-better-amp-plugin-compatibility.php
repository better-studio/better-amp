<?php

Better_AMP_Plugin_Compatibility::init();

/**
 * Third Party Plugins Compatibility
 *
 * @since 1.3.1
 */
class Better_AMP_Plugin_Compatibility {

	/**
	 * Initialization
	 */
	public static function init() {

		if ( ! is_better_amp() ) {
			return;
		}

	}
}
