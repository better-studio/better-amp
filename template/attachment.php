<?php

better_amp_get_header();

better_amp_enqueue_block_style( 'attachment' );

better_amp_the_post();

$attachment_id = get_the_ID();
$parent        = better_amp_get_post_parent( $attachment_id );

?>
	<div <?php better_amp_post_classes( 'single-post clearfix attachment' ) ?>>
		<?php

		if ( $parent ) {
			?>
			<div class="return-to">
				<a href="<?php the_permalink( $parent ); ?>" class="button">
					<i class="fa fa-angle-<?php echo is_rtl() ? 'right' : 'left'; ?>"></i> <?php
					echo esc_html( sprintf( better_amp_translation_get( 'attachment-return-to' ), wp_html_excerpt( get_the_title( $parent ), 100 ) ) )
					?></a>
			</div>
			<?php
		}

		if ( wp_attachment_is( 'image' ) ) {

			if ( $img = wp_get_attachment_image_src( $attachment_id, 'full' ) ) {

				better_amp_enqueue_script( 'amp-image-lightbox', 'https://cdn.ampproject.org/v0/amp-image-lightbox-0.1.js' );

				?>
				<amp-image-lightbox id="attachment-lightbox" layout="nodisplay"></amp-image-lightbox>

				<amp-img on="tap:attachment-lightbox"
				         role="button"
				         tabindex="0"
				         layout="responsive"
				         src="<?php echo esc_attr( $img[0] ) ?>"
				         width="<?php echo esc_attr( $img[1] ) ?>"
				         height="<?php echo esc_attr( $img[2] ) ?>"
				>
				</amp-img>
			<?php }

		} else {

			$click_here = sprintf( '<a href="%s">%s</a>', wp_get_attachment_url( $attachment_id ), better_amp_translation_get( 'click-here' ) );

			if ( wp_attachment_is( 'video' ) ) {

				printf( better_amp_translation_get( 'attachment-play-video' ), $click_here );

			} else if ( wp_attachment_is( 'audio' ) ) {

				printf( better_amp_translation_get( 'attachment-play-audio' ), $click_here );

			} else {

				printf( better_amp_translation_get( 'attachment-download-file' ), $click_here );

			}
		}

		?>

		<h3 class="post-title"><?php the_title() ?></h3>

		<?php
		if ( is_rtl() ) {
			$older_text = '<i class="fa fa-angle-double-right"></i> ' . better_amp_translation_get( 'attachment-next' );
			$next_text  = better_amp_translation_get( 'attachment-prev' ) . ' <i class="fa fa-angle-double-left"></i>';
		} else {
			$next_text  = '<i class="fa fa-angle-double-left"></i> ' . better_amp_translation_get( 'attachment-prev' );
			$older_text = better_amp_translation_get( 'attachment-next' ) . ' <i class="fa fa-angle-double-right"></i>';
		}

		?>
		<div class="pagination bs-links-pagination clearfix">
			<div class="newer"><?php next_image_link( FALSE, $older_text ); ?></div>
			<div class="older"><?php previous_image_link( FALSE, $next_text ); ?></div>
		</div>
		<?php

		// Show all images inside parent post here
		if ( $parent ) {

			$images = get_attached_media( 'image', $parent );

			?>
			<div class="parent-images clearfix">
			<ul class="listing-attachment-siblings clearfix">
				<?php foreach ( (array) $images as $img ) {

					$src = wp_get_attachment_image_src( $img->ID, 'better-amp-small' );

					?>
					<li class="listing-item item-<?php echo esc_attr( $img->ID ); ?>">
						<a itemprop="url" rel="bookmark"
						   href="<?php echo get_permalink( $img->ID ); ?>">
							<amp-img src="<?php echo esc_url( $src[0] ); ?>"
							         width="<?php echo esc_attr( $src[1] ); ?>"
							         height="<?php echo esc_attr( $src[2] ); ?>"></amp-img>
						</a>
					</li>
				<?php } ?>
			</ul>
			</div><?php
		}

		?>
	</div>
<?php

better_amp_get_footer();
