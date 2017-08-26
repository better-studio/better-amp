<?php

global $product, $post;

better_amp_enqueue_block_style( 'single' );
better_amp_enqueue_block_style( 'wc' );
better_amp_enqueue_block_style( 'wc-single' );

better_amp_get_header();

better_amp_the_post();

better_amp_enqueue_script( 'amp-image-lightbox', 'https://cdn.ampproject.org/v0/amp-image-lightbox-0.1.js' );

?>
	<amp-image-lightbox id="product-images-lightbox" layout="nodisplay"></amp-image-lightbox>

	<div <?php better_amp_post_classes( 'single-product clearfix' ) ?>>
		<?php

		wc_print_notices();

		if ( $thumb_id = get_post_thumbnail_id() ) { ?>
			<div class="product-thumbnail">
				<?php

				$img = wp_get_attachment_image_src( $thumb_id, 'better-amp-large' );

				$srcset = wp_get_attachment_image_srcset( $attachment_id );

				?>
				<amp-img on="tap:product-images-lightbox"
				         role="button"
				         tabindex="0"
				         layout="responsive"
				         src="<?php echo esc_attr( $img[0] ) ?>"
				         width="<?php echo esc_attr( $img[1] ) ?>"
				         height="<?php echo esc_attr( $img[2] ) ?>"
					<?php if ( ! empty( $srcset ) ) { ?>
						srcset="<?php echo esc_attr( $srcset ) ?>"
					<?php } ?>
				>
				</amp-img>
			</div>
		<?php } ?>

		<?php

		$attachment_ids = $product->get_gallery_image_ids();

		if ( $attachment_ids ) { ?>
			<div class="product-gallery clearfix"><?php

				foreach ( $attachment_ids as $attachment_id ) {

					$props = wc_get_product_attachment_props( $attachment_id, $post );

					if ( ! $props['url'] ) {
						continue;
					}

					$img = wp_get_attachment_image_src( $attachment_id, 'better-amp-small' );

					$srcset = wp_get_attachment_image_srcset( $attachment_id );

					?>
					<div class="product-gallery-image">
						<amp-img on="tap:product-images-lightbox"
						         role="button"
						         tabindex="0"
						         layout="responsive"
						         src="<?php echo esc_attr( $img[0] ) ?>"
						         width="<?php echo esc_attr( $img[1] ) ?>"
						         height="<?php echo esc_attr( $img[2] ) ?>"
							<?php if ( ! empty( $srcset ) ) { ?>
								srcset="<?php echo esc_attr( $srcset ) ?>"
							<?php } ?>
						>
						</amp-img>
					</div>
					<?php
				}

				?></div>
		<?php } ?>

		<div class="woocommerce-summary">
			<?php

			$average = $product->get_average_rating();

			if ( $average ) {
				?>
				<div class="woocommerce-product-rating">
					<?php

					$average = ( $average / 5 ) * 100;

					better_amp_add_inline_style( '.rating-stars-' . get_the_ID() . ' .rating-stars-active.rating-stars-active{width:' . $average . '%}' );

					?>
					<div class="rating rating-stars rating-stars-<?php the_ID() ?>"><span
								class="rating-stars-active"></span></div>
				</div>
				<?php
			}

			?>

			<h3 class="post-title"><?php the_title() ?></h3>

			<div class="woocommerce-price"><?php echo $product->get_price_html(); ?></div>

			<div class="post-content entry-content" itemprop="description">
				<?php echo apply_filters( 'woocommerce_short_description', $post->post_excerpt ) ?>
			</div>

			<a class="single_add_to_cart_button button alt"
			   href="<?php echo add_query_arg( 'add-to-cart', $post->ID ) ?>"
			><?php echo esc_html( $product->single_add_to_cart_text() ); ?></a>
			<?php

			better_amp_template_part( 'views/post/social-share' );

			?>
		</div>
		<?php

		better_amp_enqueue_script( 'amp-accordion', 'https://cdn.ampproject.org/v0/amp-accordion-0.1.js' );

		$reviews_count = $product->get_review_count();

		?>
		<amp-accordion class="product-accordion">
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

			<?php if ( $reviews_count ) { ?>
				<section>
					<h4 class="accordion-title"><?php
						printf( better_amp_translation_get( 'product-reviews' ), $reviews_count )
						?></h4>
					<?php

					comments_template( '/single-product-reviews.php' );

					?>
				</section>
			<?php } ?>
		</amp-accordion>
	</div>
<?php

better_amp_enqueue_block_style( 'post-terms' );

echo wc_get_product_tag_list(
	$product->get_id(),
	'',
	'<div class="post-terms tags"><span class="term-type"><i class="fa fa-tags"></i></span>',
	'</div>'
);

echo wc_get_product_category_list(
	$product->get_id(),
	'',
	'<div class="post-terms cats"><span class="term-type"><i class="fa fa-folder-open"></i></span>',
	'</div>'
);

better_amp_get_footer();
