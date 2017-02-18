<?php

better_amp_get_header();

better_amp_enqueue_block_style( 'search' );

better_amp_get_search_form();

// Show search result only when user searched!
if ( get_search_query( FALSE ) !== '' ) {
	better_amp_template_part( 'views/loop/' . better_amp_page_listing() );
	better_amp_template_part( 'views/archive/pagination' );
}

better_amp_get_footer();
