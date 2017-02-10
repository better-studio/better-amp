<?php

$featured_args = array(
	'post_type'           => 'post',
	'posts_per_page'      => 3,
	'ignore_sticky_posts' => TRUE,
	'meta_query'          => array( // only posts with thumbnail
		'key' => '_thumbnail_id'
	)
);

$featured_query = new WP_Query( apply_filters( 'better-amp/home/featured', $featured_args ) );
better_amp_set_query( $featured_query );

// Enqueue AMP carousel script
better_amp_enqueue_script( 'amp-carousel', 'https://cdn.ampproject.org/v0/amp-carousel-0.1.js' );

?>
	<amp-carousel class="amp-slider amp-featured-slider" layout="responsive"
	              type="slides" <?php better_amp_hw_attr( '', 500 ) ?> delay="3500" autoplay>
		<?php

		while( better_amp_have_posts() ) {

			better_amp_the_post();

			$img = better_amp_get_thumbnail( 'better-amp-large' );

			$id = better_amp_element_uni_id();

			better_amp_add_inline_style( '.' . $id . ' .img-holder{background-image:url(' . $img['src'] . ')}' );

			?>
			<div class="<?php echo $id; ?>">
				<div class="img-holder"></div>
				<div class="content-holder">
					<h3><a href="<?php the_permalink() ?>"><?php echo get_the_title(); ?></a></h3>
				</div>
			</div>
			<?php

		}

		?>
	</amp-carousel>
<?php

better_amp_clear_query();
