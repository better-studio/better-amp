<?php

better_amp_enqueue_block_style( 'listing', 'css/listing' );
better_amp_enqueue_block_style( 'listing-2', 'css/listing-2' );

?>
<div class="posts-listing posts-listing-2">
	<?php

	if ( better_amp_is_ad_plugin_active() ) {
		$ad_after_each = (int) Better_Ads_Manager::get_option( 'amp_archive_after_x_number' );
		$counter       = 1;
	} else {
		$ad_after_each = FALSE;
	}

	while( better_amp_have_posts() ) {
		better_amp_the_post() ?>
		<article <?php better_amp_post_classes( 'listing-item listing-2-item clearfix' ) ?>>

			<?php if ( has_post_thumbnail() ) { ?>
				<div class="post-thumbnail">
					<a href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>">
						<?php better_amp_the_post_thumbnail( 'better-amp-large' ) ?>
					</a>
				</div>
			<?php } ?>

			<h3 class="post-title">
				<a href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>">
					<?php the_title() ?>
				</a>
			</h3>

			<div class="post-excerpt">
				<?php the_excerpt(); ?>
			</div>

			<div class="post-meta clearfix">

				<span class="post-date">
					<i class="fa fa-calendar" aria-hidden="true"></i>
					<?php the_time( better_amp_translation_get( 'listing_2_date' ) ); ?>
				</span>

				<a class="post-read-more" href="<?php the_permalink() ?>"
				   title="<?php the_title_attribute() ?>">
					<?php better_amp_translation_echo( 'read_more' ); ?>
					<i class="fa fa-arrow-<?php better_amp_direction( TRUE ); ?>" aria-hidden="true"></i>
				</a>

			</div>

		</article>
		<?php

		// should be active and also there was another post after this post
		if ( $ad_after_each && better_amp_have_posts() ) {
			if ( $counter === $ad_after_each ) {
				better_amp_show_ad_location( 'amp_archive_after_x' );
				$counter = 1; // reset counter
			} else {
				$counter ++;
			}
		}


	} ?>
</div>
