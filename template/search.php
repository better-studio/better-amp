<?php

better_amp_get_header();

better_amp_get_search_form();

// Show search result only when user searched!
better_amp_template_part( 'posts-' . better_amp_page_listing() );
better_amp_template_part( 'pagination' );

better_amp_get_footer();
