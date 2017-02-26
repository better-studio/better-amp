<?php

better_amp_get_header();

better_amp_enqueue_block_style( 'archive' );

better_amp_template_part( 'views/archive/title' );

better_amp_show_ad_location( 'amp_archive_title_after' );

better_amp_template_part( 'views/loop/' . better_amp_page_listing() );

better_amp_template_part( 'views/archive/pagination' );

better_amp_get_footer();
