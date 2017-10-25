<?php


/**
 * The Better_AMP_Styles is a clone of the WP_Styles
 *
 * @see   WP_Styles
 *
 * @since 1.0.0
 */
class Better_AMP_Styles extends WP_Styles {

	/**
	 * Store inline css codes
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public $inline_styles = array();


	/**
	 * Register inline css code
	 *
	 * @param string $handle name of the stylesheet to
	 * @param string $code   the CSS styles to be added
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function add_inline_style( $handle = '', $code ) {

		if ( empty( $handle ) ) {
			$this->inline_styles[] = $code;
		} else {
			$this->inline_styles[ $handle ] = $code;
		}

	}


	/**
	 * Processes the items
	 *
	 * @see   WP_Dependencies::do_items for more documentation
	 *
	 * @param bool $handles
	 * @param bool $group
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function do_items( $handles = FALSE, $group = FALSE ) {

		$this->print_inline_styles();

		parent::do_items( $handles, $group );
	}


	/**
	 * Print inline css styles in single <style> tag
	 *
	 * AMP just accept single <style> tag
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function print_inline_styles() {

		if ( $this->inline_styles ) {

			echo '<style amp-custom>';

			foreach ( $this->inline_styles as $code ) {
				echo "\n", $code, "\n";
			}

			echo '</style>';
		}

	}


	/**
	 * Determines style dependencies.
	 *
	 * @param mixed          $handles   Item handle and argument (string) or item handles and arguments (array of
	 *                                  strings).
	 * @param bool           $recursion Internal flag that function is calling itself.
	 * @param bool|false|int $group     Group level: (int) level, (false) no groups.
	 *
	 * @since 1.2.1
	 * @return bool True on success, false on failure.
	 */
	public function all_deps( $handles, $recursion = FALSE, $group = FALSE ) {

		return WP_Dependencies::all_deps( $handles, $recursion, $group );
	}
}
