<?php

better_amp_get_header();

better_amp_enqueue_block_style( 'archive', 'css/archive' );
better_amp_enqueue_block_style( 'woocommerce', 'css/wc' );

better_amp_template_part( 'views/archive/title' );

wc_print_notices();

better_amp_template_part( 'woocommerce/loop' );

better_amp_template_part( 'views/archive/pagination' );

better_amp_get_footer();
