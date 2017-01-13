<?php

better_amp_get_header();

better_amp_get_search_form();

// Show search result only when user searched!
if ( get_search_query( FALSE ) !== '' ) {
	better_amp_template_part( 'posts-' . better_amp_page_listing() );
	better_amp_template_part( 'pagination' );
} else {
	better_amp_set_global( 'footer-custom-class', 'sticky-footer' );
}

better_amp_get_footer();
