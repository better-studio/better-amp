<?php

class Better_AMP_Panel {

	/**
	 * Store self instance.
	 *
	 * @var self
	 * @since 1.12.0
	 */
	protected static $instance;

	/**
	 * Store unique panel id.
	 *
	 * @var string
	 * @since 1.12.0
	 */
	protected $panel_id = 'better-amp-translation';

	/**
	 * Store panel required user access.
	 *
	 * @var string
	 * @since 1.12.0
	 */
	protected $capability = 'manage_options';


	/**
	 * Get singleton instance.
	 *
	 * @since 1.12.0
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
	 * @since 1.12.0
	 */
	public function init() {

		add_action( 'wp_ajax_better-amp-panel-save', [ $this, 'panel_save' ] );
		add_action( 'wp_ajax_better-amp-panel-reset', [ $this, 'ajax_panel_reset' ] );
		add_action( 'wp_ajax_better-amp-panel-export', [ $this, 'panel_export' ] );
		add_action( 'wp_ajax_better-amp-panel-import', [ $this, 'panel_import' ] );

		add_action( 'admin_menu', [ $this, 'setup_menu' ] );

		if ( ! class_exists( 'Better_AMP_Panel_Render' ) ) {

			require __DIR__ . '/class-better-amp-panel-render.php';
		}

		add_action( 'admin_enqueue_scripts', [ $this, 'print_assets' ] );
	}

	/**
	 * Setup admin menu.
	 *
	 * @since 1.12.0
	 */
	public function setup_menu() {

		add_menu_page(
			__( 'Translation', 'better-amp' ),
			__( '<strong>Better</strong> AMP', 'better-amp' ),
			$this->capability,
			$this->panel_id, [
			$this,
			'render_translation'
		],
			better_amp_plugin_url( '/assets/images/better-amp-symbol.svg' ),
			59
		);

	}

	/**
	 * Panel HTML Markup.
	 *
	 * @since 1.12.0
	 */
	public function render_translation() {

		include __DIR__ . '/panel-view.php';
	}

	/**
	 * Get panel fields list.
	 *
	 * @since 1.12.0
	 * @return array[]
	 */
	public function panel_fields() {

		return apply_filters( 'better-amp/translation/fields', [] );
	}


	/**
	 * Get default values of fields.
	 *
	 * @since 1.12.0
	 * @return string[]
	 */
	public function panel_stds() {

		return apply_filters( 'better-amp/translation/std', [] );
	}

	/**
	 * Save panel functionality.
	 *
	 * @hooked wp_ajax_better-amp-panel-save
	 *
	 * @since  1.12.0
	 */
	public function panel_save() {

		$this->ajax_check();

		if ( empty( $_POST['better-amp'] ) || ! is_array( $_POST['better-amp'] ) ) {

			wp_send_json_error( 'invalid-request' );

			return;
		}

		$new_values = array_map( 'sanitize_text_field', $_POST['better-amp'] );
		$new_values = array_map( 'wp_unslash', $new_values );

		update_option( $this->panel_id, array_merge( $this->panel_values(), $new_values ) );

		wp_send_json_success();
	}

	/**
	 * Is option panel empty?
	 *
	 * @since 1.12.0
	 * @return bool
	 */
	public function is_fresh_install() {

		return ! get_option( $this->panel_id );
	}

	/**
	 * Panel reset functionality.
	 *
	 * @hooked wp_ajax_better-amp-panel-reset
	 *
	 * @since  1.12.0
	 */
	public function ajax_panel_reset() {

		$this->ajax_check();
		$this->panel_reset();
		wp_send_json_success();
	}

	/**
	 * Panel reset functionality.
	 *
	 * @hooked wp_ajax_better-amp-panel-reset
	 *
	 * @since  1.12.0
	 */
	public function panel_reset() {

		return update_option( $this->panel_id, $this->panel_stds() );
	}


	/**
	 * Export panel functionality.
	 *
	 * @hooked wp_ajax_better-amp-panel-export
	 *
	 * @since  1.12.0
	 */
	public function panel_export() {

		$this->ajax_check();

		header( 'Content-disposition: attachment; filename=better-amp-translation-panel.json' );

		wp_send_json( $this->panel_values() );
	}

	/**
	 * Import panel functionality.
	 *
	 * @hooked wp_ajax_better-amp-panel-import
	 *
	 * @since  1.12.0
	 */
	public function panel_import() {

		$this->ajax_check();

		if ( empty( $_FILES['file']['tmp_name'] ) || $_FILES['file']['type'] !== 'application/json' ) {

			wp_send_json_error( 'invalid-request' );

			return;
		}

		if ( ! function_exists( 'WP_Filesystem_Base' ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
		}
		if ( ! class_exists( 'WP_Filesystem_Direct' ) ) {

			require ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
		}
		$file = new WP_Filesystem_Direct( [] );

		$new_values = json_decode( trim( $file->get_contents( $_FILES['file']['tmp_name'] ) ), true );

		if ( empty( $new_values ) ) {

			wp_send_json_error( 'invalid-file' );

			return;
		}

		$new_values = array_map( 'sanitize_text_field', $new_values );
		$new_values = array_map( 'wp_unslash', $new_values );

		update_option( $this->panel_id, array_merge( $this->panel_values(), $new_values ) );

		wp_send_json_success();
	}

	/**
	 * Get panel saved-values.
	 *
	 * @since 1.12.0
	 * @return array
	 */
	public function panel_values() {

		return get_option( $this->panel_id, [] );
	}


	/**
	 * Enqueue static assets.
	 *
	 * @since 1.12.0
	 */
	public function print_assets() {

		global $pagenow;

		if ( $pagenow !== 'admin.php' || ! isset( $_GET['page'] ) || $_GET['page'] !== $this->panel_id ) {

			return;
		}

		wp_enqueue_style( 'fontawesome', better_amp_plugin_url( 'includes/panel/assets/font-awesome.min.css' ) );
		wp_enqueue_style( 'better-amp-panel', better_amp_plugin_url( 'includes/panel/assets/panel-styles.css' ) );
		wp_enqueue_script( 'better-amp-panel', better_amp_plugin_url( 'includes/panel/assets/panel-scripts.js' ), [
			'jquery',
		] );
	}

	/**
	 * Check token & capability.
	 *
	 * @since 1.12.0
	 */
	protected function ajax_check() {

		check_ajax_referer( 'save-panel' );

		if ( ! current_user_can( $this->capability ) ) {

			wp_send_json_error( 'invalid-access' );

			return;
		}
	}
}