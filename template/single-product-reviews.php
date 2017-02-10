<div id="reviews" class="woocommerce-Reviews">
	<div id="comments">
		<ol class="commentlist">
			<?php

			$args = array();

			if ( function_exists( 'is_product' ) && is_product() ) {
				$args = apply_filters( 'woocommerce_product_review_list_args', array( 'callback' => 'woocommerce_comments' ) );
			}

			wp_list_comments( $args );

			?>
		</ol>
	</div>
	<div class="clear"></div>
</div>
