<?php
// Enqueue AMP carousel script
better_amp_enqueue_script( 'amp-carousel', 'https://cdn.ampproject.org/v0/amp-carousel-0.1.js' );

$query_args = better_amp_related_posts_query_args( better_amp_get_theme_mod( 'better-amp-post-related-count' ), better_amp_get_theme_mod( 'better-amp-post-related-algorithm' ), get_the_ID() );

$query = new WP_Query( $query_args );

better_amp_set_query( $query );

?>

	<div class="related-posts-wrapper carousel"<?php better_amp_customizer_hidden_attr( 'better-amp-post-show-related' ) ?>>

		<h5 class="heading"><?php better_amp_translation_echo( 'related_posts' ) ?></h5>

		<amp-carousel class="amp-carousel " layout="responsive" type="carousel" height="260">
			<?php

			while( better_amp_have_posts() ) {

				better_amp_the_post();

				$img = better_amp_get_thumbnail( 'better-amp-normal' );

				$id = better_amp_element_uni_id();

				better_amp_add_inline_style( '.' . $id . ' .img-holder{background-image:url(' . $img['src'] . ');width:205px}' );

				?>
				<div class="<?php echo $id; ?> carousel-item">
					<a class="img-holder" href="<?php the_permalink() ?>"></a>
					<div class="content-holder">
						<h3><a href="<?php the_permalink() ?>"><?php echo get_the_title(); ?></a></h3>
					</div>
				</div>
				<?php
			}
			?>
		</amp-carousel>
	</div>
<?php

better_amp_clear_query();
