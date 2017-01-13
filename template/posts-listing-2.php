<div class="posts-listing posts-listing-2">
	<?php while( better_amp_have_posts() ) {
		better_amp_the_post() ?>
		<article <?php better_amp_post_classes( 'listing-item listing-2-item clearfix' ) ?>>

			<?php if ( has_post_thumbnail() ) { ?>
				<div class="post-thumbnail">
					<a href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>">
						<?php the_post_thumbnail( 'better-amp-large' ) ?>
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

			<div class="post-meta">

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
	<?php } ?>
</div>
