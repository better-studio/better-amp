<?php

better_amp_get_header();

// Home slider
if ( better_amp_get_default_theme_mod( 'better-amp-home-show-slide' ) ) {
	?>
	<div class="homepage-slider" <?php better_amp_customizer_hidden_attr( 'better-amp-home-show-slide' ) ?>>
		<?php
		better_amp_template_part( 'featured' );
		?>
	</div>
	<?php
}

better_amp_template_part( 'posts-' . better_amp_page_listing() );

better_amp_template_part( 'pagination' );

better_amp_get_footer();
