<?php

better_amp_enqueue_block_style( 'social-list' );

?>
<div class="social-list-wrapper">
	<ul class="social-list clearfix">
		<?php

		foreach ( array( 'facebook', 'twitter', 'google_plus' ) as $k ) :

			$theme_mod = 'better-amp-' . $k;

			$value = better_amp_get_theme_mod( $theme_mod );

			if ( ! $value ) {
				continue;
			}

			?>
			<li class="social-item <?php echo $k ?>" <?php better_amp_customizer_hidden_attr( $theme_mod ) ?>>
				<a href="<?php echo esc_url( $value ) ?>" target="_blank">
					<i class="fa fa-<?php echo str_replace( '_', '-', $k ) ?>"></i>
					<span class="item-title"><?php echo $k ?></span>
				</a>
			</li>
			<?php

		endforeach;

		if ( $email = better_amp_get_theme_mod( 'better-amp-email' ) ) :
			?>
			<li class="social-item email" <?php better_amp_customizer_hidden_attr( $theme_mod ) ?>>
				<a href="mailto:<?php echo esc_attr( $email ) ?>"
				   target="_blank">
					<i class="fa fa-envelope-open"></i>
					<span class="item-title"><?php _e( 'Email', 'better-amp' ) ?></span>
				</a>
			</li>
		<?php endif ?>
	</ul>
</div>
