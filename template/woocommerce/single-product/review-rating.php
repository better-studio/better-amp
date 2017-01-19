<?php

global $comment;
$rating = intval( get_comment_meta( $comment->comment_ID, 'rating', TRUE ) );

if ( $rating && get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) {

	$average = ( esc_attr( $rating ) / 5 ) * 100;
	better_amp_add_inline_style( '.comment-' . $comment->comment_ID . '-rating-stars .rating-stars-active{width:' . $average . '%}' );

	?>

	<div class="rating rating-stars <?php echo '.comment-' . $comment->comment_ID . '-rating-stars' ?>">
		<span class="rating-stars-active"></span>
	</div>

<?php }
