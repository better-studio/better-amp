<?php
global $post;

$columns = apply_filters( 'woocommerce_product_thumbnails_columns', 3 );
$loop    = 0;

?>
<div class="posts-listing posts-listing-grid product-archive clearfix">
	<?php
	while ( better_amp_have_posts() ) {
		better_amp_the_post();
		$product = wc_get_product( get_the_ID() );
		$classes = array( 'listing-item', 'listing-grid-item' );

		if ( $loop === 0 || $loop % $columns === 0 ) {
			$classes[] = 'first';
		}

		if ( ( $loop + 1 ) % $columns === 0 ) {
			$classes[] = 'last';
		}

		?>

		<article <?php better_amp_post_classes( $classes ) ?>>

			<?php if ( $product->is_on_sale() ) : ?>

				<?php echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . __( 'Sale!', 'woocommerce' ) . '</span>', $post, $product ); ?>

			<?php endif; ?>

			<?php if ( has_post_thumbnail() ) { ?>
				<div class="post-thumbnail">
					<a href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>">
						<?php the_post_thumbnail( 'medium' ) ?>
					</a>
				</div>
			<?php } ?>

			<h3 class="post-title">
				<a href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>">
					<?php the_title() ?>
				</a>
			</h3>

			<div class="woocommerce-product-rating">
				<?php
				$average = $product->get_average_rating();
				$average = ( $average / 5 ) * 100;

				better_amp_add_inline_style( '.rating-stars-' . get_the_ID() . ' .rating-stars-active{width:' . $average . '%}' );
				?>
				<div class="rating rating-stars rating-stars-<?php the_ID() ?>"><span
						class="rating-stars-active"></span></div>
			</div>

			<div class="woocommerce-price"><?php echo $product->get_price_html(); ?></div>

			<a class="single_add_to_cart_button button alt"
			   href="<?php echo add_query_arg( 'add-to-cart', get_the_ID() ) ?>"
			><?php echo esc_html( $product->single_add_to_cart_text() ); ?></a>

		</article>
	<?php } ?>
</div>
