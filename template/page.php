<?php

better_amp_get_header();

better_amp_enqueue_block_style( 'single' );
better_amp_enqueue_block_style( 'page' );

better_amp_the_post();

?>
	<div <?php better_amp_post_classes( 'single-page clearfix' ) ?>>

		<h1 class="page-title"><?php the_title() ?></h1>

		<?php if ( better_amp_get_theme_mod( 'better-amp-post-show-thumbnail' ) && has_post_thumbnail() ): ?>
			<div class="page-thumbnail" <?php better_amp_customizer_hidden_attr( 'better-amp-post-show-thumbnail' ) ?>>
				<?php better_amp_the_post_thumbnail( 'better-amp-large' ); ?>
			</div>
		<?php endif ?>

		<div class="page-content entry-content">
			<?php the_content() ?>
		</div>
		<?php

		if ( better_amp_get_theme_mod( 'better-amp-page-social-share-show' ) != 'hide' && ! ( function_exists( 'is_woocommerce' ) && is_cart() ) ) {
			better_amp_template_part( 'views/post/social-share' );
		}

		?>
	</div>
<?php

better_amp_get_footer();
