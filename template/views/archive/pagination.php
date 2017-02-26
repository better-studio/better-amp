<?php

better_amp_enqueue_block_style( 'pagination' );

if ( is_rtl() ) {
	$prev = '<i class="fa fa-arrow-right" aria-hidden="true"></i>' . better_amp_translation_get( 'prev' );
	$next = better_amp_translation_get( 'next' ) . '<i class="fa fa-arrow-left" aria-hidden="true"></i>';
} else {
	$prev = '<i class="fa fa-arrow-left" aria-hidden="true"></i>' . better_amp_translation_get( 'prev' );
	$next = better_amp_translation_get( 'next' ) . '<i class="fa fa-arrow-right" aria-hidden="true"></i>';
}

the_posts_pagination( array(
	'mid_size'           => 0,
	'prev_text'          => $prev,
	'next_text'          => $next,
	'before_page_number' => '<span class="meta-nav screen-reader-text">' . better_amp_translation_get( 'page' ) . ' ',
	'after_page_number'  => ' ' . sprintf( better_amp_translation_get( 'page_of' ), better_amp_get_query()->max_num_pages ) . ' </span>',
) );
