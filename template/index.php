<?php

better_amp_get_header();

// Home slider
if ( better_amp_get_theme_mod( 'better-amp-home-show-slide' ) ) {

	better_amp_enqueue_block_style( 'slider', '', FALSE ); // no rtl style

	?>
	<div class="homepage-slider" <?php better_amp_customizer_hidden_attr( 'better-amp-home-show-slide' ) ?>>
		<?php
		better_amp_template_part( 'views/home/featured' );
		?>
	</div>
	<?php
}

better_amp_template_part( 'views/loop/' . better_amp_page_listing() );

better_amp_template_part( 'views/archive/pagination' );

better_amp_get_footer();
