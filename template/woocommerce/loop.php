<?php

global $post;

better_amp_enqueue_block_style( 'listing' );
better_amp_enqueue_block_style( 'listing-grid' );

?>
<div class="posts-listing posts-listing-grid product-archive clearfix">
	<?php

	while( better_amp_have_posts() ) {
		better_amp_the_post();

		$product = wc_get_product( get_the_ID() );

		?>
		<article <?php better_amp_post_classes( array( 'listing-item', 'listing-grid-item' ) ) ?>>
			<div class="listing-grid-item-inner">
				<?php

				if ( $product->is_on_sale() ) {
					echo apply_filters(
						'woocommerce_sale_flash',
						'<span class="onsale">' . better_amp_translation_get( 'product-sale' ) . '</span>',
						$post,
						$product
					);
				}

				if ( has_post_thumbnail() ) { ?>
					<div class="post-thumbnail">
						<a href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>">
							<?php better_amp_the_post_thumbnail( 'better-amp-normal' ); ?>
						</a>
					</div>
				<?php } ?>

				<h3 class="post-title">
					<a href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>">
						<?php the_title() ?>
					</a>
				</h3>

				<?php if ( $average = $product->get_average_rating() ) { ?>
					<div class="woocommerce-product-rating">
						<?php

						$average = ( $average / 5 ) * 100;

						better_amp_add_inline_style( '.rating-stars-' . get_the_ID() . ' .rating-stars-active{width:' . $average . '%}' );

						?>
						<div class="rating rating-stars rating-stars-<?php the_ID() ?>"><span
								class="rating-stars-active"></span></div>
					</div>
				<?php } ?>

				<div class="woocommerce-price"><?php echo $product->get_price_html(); ?></div>

				<a class="button alt button-view-product"
				   href="<?php the_permalink(); ?>"
				><?php better_amp_translation_echo( 'product-view' ); ?></a>

				<a class="single_add_to_cart_button button alt"
				   href="<?php echo add_query_arg( 'add-to-cart', get_the_ID() ) ?>"
				><?php echo esc_html( $product->single_add_to_cart_text() ); ?></a>

			</div>
		</article>
	<?php } ?>
</div>
