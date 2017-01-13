<?php

class AMP_Customize_Social_Sorter_Control extends WP_Customize_Control {

	/**
	 *
	 * @var string
	 */
	public $type = 'sorter-checkbox';

	/**
	 * Enqueue scripts/styles for the better studio custom switch
	 */
	public function enqueue() {

		wp_enqueue_script( 'bs-sorter-checkbox', better_amp_plugin_url( 'template/customizer/js/sorter-checkbox.js' ), array(
			'jquery',
			'jquery-ui-sortable'
		) );
		wp_enqueue_style( 'bs-sorter-checkbox', better_amp_plugin_url( 'template/customizer/css/sorter-checkbox.css' ) );
	}

	public function to_json() {
		parent::to_json();

		$this->json['choices'] = $this->get_choices();

		$this->json['display_id'] = $this->id;

		$this->json['selected'] = array_keys( array_filter( $this->value() ) );
	}

	protected function get_choices() {

		$enable_items = array();
		$all_items    = $this->get_items();

		if ( $items = $this->value() ) {

			foreach ( $items as $key => $status ) {

				if ( $status ) {

					if ( isset( $all_items[ $key ] ) ) {

						$enable_items[ $key ] = $all_items[ $key ];
					}
				}
			}
		}


		$choices = $enable_items;

		// Collect rest of the indexes
		foreach ( array_diff_key( $all_items, $choices ) as $key => $stat ) {
			$choices[ $key ] = $all_items[ $key ];
		}

		return $choices;
	}

	protected function render_content() {

	}

	protected function content_template() {

		if ( ! empty( $this->label ) ) : ?>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		<?php endif; ?>

		<div class="bf-sorter-groups-container">
			<ul id="bf-sorter-group-key"
			    class="bf-sorter-list bf-sorter-checkbox-list bf-sorter-{{ data.display_id }}">

				<# for ( key in data.choices ) { #>
					<li class="item-{{key}}
					<# if ( _.contains( data.selected, key ) ) { #> checked-item
						<# } #>">
						<label>
							<input type="checkbox" value="{{ key }}" class="sorter-checkbox"
							<# if ( _.contains( data.selected, key ) ) { #> checked
								<# } #> />
									{{{ data.choices[ key ] }}}
						</label>
					</li>
					<# } #>
			</ul>
		</div>
		<?php
	}


	public function get_items() {
		return array(
			'facebook'    => '<i class="fa fa-facebook"></i> ' . __( 'Facebook', 'better-amp' ),
			'twitter'     => '<i class="fa fa-twitter"></i> ' . __( 'Twitter', 'better-amp' ),
			'google_plus' => '<i class="fa fa-google-plus"></i> ' . __( 'Google+', 'better-amp' ),
			'pinterest'   => '<i class="fa fa-pinterest"></i> ' . __( 'Pinterest', 'better-amp' ),
			'reddit'      => '<i class="fa fa-reddit-alien"></i> ' . __( 'ReddIt', 'better-amp' ),
			'linkedin'    => '<i class="fa fa-linkedin"></i> ' . __( 'Linkedin', 'better-amp' ),
			'tumblr'      => '<i class="fa fa-tumblr"></i> ' . __( 'Tumblr', 'better-amp' ),
			'telegram'    => '<i class="fa fa-send"></i> ' . __( 'Telegram', 'better-amp' ),
			'whatsapp'    => '<i class="fa fa-whatsapp"></i> ' . __( 'Whatsapp (Only Mobiles)', 'better-amp' ),
			'email'       => '<i class="fa fa-envelope"></i> ' . __( 'Email', 'better-amp' ),
			'stumbleupon' => '<i class="fa fa-stumbleupon"></i> ' . __( 'StumbleUpon', 'better-amp' ),
			'vk'          => '<i class="fa fa-vk"></i> ' . __( 'VK', 'better-amp' ),
			'digg'        => '<i class="fa fa-digg"></i> ' . __( 'Digg', 'better-amp' ),
		);
	}

}
