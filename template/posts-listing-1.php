<div class="posts-listing posts-listing-1">
	<?php while( better_amp_have_posts() ) {
		better_amp_the_post(); ?>
		<article <?php better_amp_post_classes( 'listing-item listing-1-item clearfix' ) ?>>

			<?php if ( has_post_thumbnail() ): ?>
				<div class="post-thumbnail">
					<a href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>">
						<?php the_post_thumbnail( 'better-amp-small' ) ?>
					</a>
				</div>
			<?php endif ?>

			<h3 class="post-title">
				<a href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>">
					<?php the_title() ?>
				</a>
			</h3>

			<a class="post-read-more" href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>">
				<?php better_amp_translation_echo( 'read_more' ); ?>
				<i class="fa fa-arrow-<?php better_amp_direction( TRUE ); ?>" aria-hidden="true"></i>
			</a>

		</article>
	<?php } ?>
</div>
