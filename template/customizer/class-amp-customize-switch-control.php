<?php


class AMP_Customize_Switch_Control extends WP_Customize_Control {

	/**
	 * Enqueue scripts/styles for the better studio custom switch
	 */
	public function enqueue() {

		wp_enqueue_script( 'bs-switch', better_amp_plugin_url( 'template/customizer/js/bs-switch.js' ), array( 'jquery' ) );
		wp_enqueue_style( 'bs-switch', better_amp_plugin_url( 'template/customizer/css/bs-switch.css' ) );
	}


	protected function render_content() {

		$val = $this->value();

		?>
		<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>

		<?php if ( ! empty( $this->description ) ) { ?>
			<span class="description customize-control-description"><?php echo $this->description; ?></span>
		<?php } ?>

		<div class="bf-switch bf-clearfix">
			<label class="cb-enable<?php if ( $val )
				echo ' selected' ?>"><span><?php echo isset( $this->on_label ) ? esc_attr( $this->on_label ) : 'Yes' ?></span></label>
			<label class="cb-disable<?php if ( ! $val )
				echo ' selected' ?>"><span><?php echo isset( $this->off_label ) ? esc_attr( $this->off_label ) : 'No' ?></span></label>

			<input type="hidden" class="checkbox" value="<?php echo esc_attr( $val ); ?>" <?php $this->link(); ?> />
		</div>
		<?php
	}
}
