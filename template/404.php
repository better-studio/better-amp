<?php

better_amp_get_header();

better_amp_enqueue_inline_style( 'css/404.css', '404' );

?>
	<header class="mr-404-suit">

		<p class="mr-404">404</p>

		<p class="mr-404-bio"><?php better_amp_translation_echo( 'mr_404' ); ?></p>

	</header>
<?php

better_amp_get_footer();
