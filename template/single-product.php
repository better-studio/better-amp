<?php

global $product, $post;

better_amp_get_header();

better_amp_the_post();

better_amp_enqueue_script( 'amp-image-lightbox', 'https://cdn.ampproject.org/v0/amp-image-lightbox-0.1.js' );

?>
	<amp-image-lightbox id="product-images-lightbox" layout="nodisplay"></amp-image-lightbox>

	<div <?php better_amp_post_classes( 'single-post clearfix' ) ?>>

		<?php wc_print_notices(); ?>

		<?php if ( better_amp_get_theme_mod( 'better-amp-post-show-thumbnail' ) && ( $thumb_id = get_post_thumbnail_id() ) ): ?>
			<div class="product-images">
				<div
					class="post-thumbnail" <?php better_amp_customizer_hidden_attr( 'better-amp-post-show-thumbnail' ) ?>>
					<?php
					$img = wp_get_attachment_image_src( $thumb_id, 'better-amp-large' );
					?>

					<amp-img on="tap:product-images-lightbox"
					         role="button"
					         tabindex="0"
					         src="<?php echo esc_attr( $img[0] ) ?>"
					         width="<?php echo esc_attr( $img[1] ) ?>"
					         height="<?php echo esc_attr( $img[2] ) ?>"
					         srcset="<?php echo esc_attr( wp_get_attachment_image_srcset( $thumb_id ) ) ?>"
					>
					</amp-img>

				</div>
			</div>
		<?php endif ?>

		<?php
		$attachment_ids = $product->get_gallery_attachment_ids();

		if ( $attachment_ids ) {
			?>
			<div class="thumbnail"><?php

				foreach ( $attachment_ids as $attachment_id ) {

					$props = wc_get_product_attachment_props( $attachment_id, $post );

					if ( ! $props['url'] ) {
						continue;
					}

					$img = wp_get_attachment_image_src( $attachment_id, 'better-amp-small' );

					?>
					<amp-img on="tap:product-images-lightbox"
					         role="button"
					         tabindex="0"
					         src="<?php echo esc_attr( $img[0] ) ?>"
					         width="<?php echo esc_attr( $img[1] ) ?>"
					         height="<?php echo esc_attr( $img[2] ) ?>"
					         srcset="<?php echo esc_attr( wp_get_attachment_image_srcset( $attachment_id ) ) ?>"
					>
					</amp-img>
					<?php
				}

				?></div>

		<?php } ?>

		<div class="woocommerce-summary">
			<h3 class="post-title">
				<?php the_title() ?>
			</h3>
			<?php better_amp_post_subtitle(); ?>

			<div class="woocommerce-product-rating">
				<?php
				$average = $product->get_average_rating();
				$average = ( $average / 5 ) * 100;

				better_amp_add_inline_style( '.rating-stars-active.rating-stars-active{width:' . $average . '%}' );
				?>
				<div class="rating rating-stars"><span class="rating-stars-active"></span></div>

				<?php
				$review_count = $product->get_review_count();

				/*
				if ( comments_open() ) { ?>
					<a href="#reviews" class="woocommerce-review-link" rel="nofollow" on="tap:reviews.open">
						(<?php printf( _n( '%s customer review', '%s customer reviews', $review_count, 'woocommerce' ), '<span itemprop="reviewCount" class="count">' . $review_count . '</span>' ); ?>
						)</a>
				<?php } */ ?>
			</div>

			<div class="woocommerce-price"><?php echo $product->get_price_html(); ?></div>

			<div itemprop="description">
				<?php echo apply_filters( 'woocommerce_short_description', $post->post_excerpt ) ?>
			</div>

			<a class="single_add_to_cart_button button alt"
			   href="<?php echo add_query_arg( 'add-to-cart', $post->ID ) ?>"
			><?php echo esc_html( $product->single_add_to_cart_text() ); ?></a>
		</div>

		<?php
		better_amp_enqueue_script( 'amp-accordion', 'https://cdn.ampproject.org/v0/amp-accordion-0.1.js' );
		?>
		<amp-accordion>
			<section expanded>
				<h4 class="accordion-title">
					<?php
					better_amp_translation_echo( 'product-desc' );
					?>
				</h4>
				<div class="post-content entry-content">
					<?php the_content() ?>
				</div>
			</section>

			<section>
				<h4 class="accordion-title"><?php
					printf( better_amp_translation_get( 'product-reviews' ), $product->get_review_count() )
					?></h4>

				<?php
				comments_template( '/reviews.php' );
				?>
			</section>
		</amp-accordion>


		<?php

		the_tags(
			'<div class="post-terms tags"><span class="term-type"><i class="fa fa-tags"></i></span>',
			'',
			'</div>'
		);

		$cats = get_the_category_list( '' );
		if ( ! empty( $cats ) ) {

			?>
			<div class="post-terms cats"><span class="term-type"><i class="fa fa-folder-open"></i></span>
				<?php echo $cats; ?>
			</div>
			<?php
		}


		?>
	</div>

<?php

better_amp_template_part( 'social-share' );

if ( better_amp_get_theme_mod( 'better-amp-post-show-comment' ) && ( comments_open() || get_comments_number() ) ) {
	better_amp_get_footer();
}
