<?php

/**
 * Class Better_AMP_Panel_Render
 *
 * @since 1.12.0
 */
class Better_AMP_Panel_Render {


	/**
	 * Store list of fields.
	 *
	 * @var array
	 * @since 1.12.0
	 */
	protected $fields;

	/**
	 * Store fields values.
	 *
	 * @var array
	 * @since 1.12.0
	 */
	protected $values;

	/**
	 * Store configuration array.
	 *
	 * @var array
	 * @since 1.12.0
	 */
	protected $config;

	/**
	 * Better_AMP_Panel_Render constructor.
	 *
	 * @param array $fields
	 * @param array $values
	 * @param array $settings
	 *
	 * @since 1.12.0
	 */
	public function __construct( array $fields, array $values = [], $settings = [] ) {

		$this->fields = $fields;
		$this->values = $values;

		$this->config = wp_parse_args( [
			'panel_id'   => 'better-amp',
			'fields_dir' => __DIR__ . '/fields/',
		], $settings );
	}


	/**
	 * Render fields list.
	 *
	 * @since 1.12.0
	 */
	public function render() {

		foreach ( $this->fields as $field ) {

			$this->render_field( $field );
		}
	}

	/**
	 * Render a field.
	 *
	 * @param array $field
	 *
	 * @since 1.12.0
	 * @return bool
	 */
	protected function render_field( array $field ) {

		$field_file = sprintf( '%s/%s.php', $this->config['fields_dir'], $field['type'] );

		if ( ! file_exists( $field_file ) ) {

			return false;
		}

		$id    = $field['id'] ?? null;
		$name  = sprintf( '%s[%s]', $this->config['panel_id'], $id );
		$value = $this->values[ $id ] ?? null;

		ob_start();

		include $field_file;

		$rendered_field = ob_get_clean();

		if ( substr( $field['type'], 0, 6 ) === 'group_' ) { // is group

			echo $rendered_field;

		} else {

			include sprintf( '%s/container.php', $this->config['fields_dir'] );
		}

		return true;
	}
}