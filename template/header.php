<!doctype html>
<html <?php better_amp_language_attributes(); ?> amp>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,minimum-scale=1,maximum-scale=1,initial-scale=1">
	<meta name="theme-color" content="<?php echo better_amp_get_theme_mod( 'better-amp-color-theme' ); ?>">

	<?php better_amp_head() ?>
</head>
<?php

$body_class = 'body';

if ( better_amp_get_theme_mod( 'better-amp-header-sticky', FALSE ) ) {
	$body_class .= ' sticky-nav';
}

?>
<body <?php better_amp_body_class( $body_class ) ?>>
<?php

do_action( 'better-amp/template/body/start' );

if ( better_amp_get_theme_mod( 'better-amp-sidebar-show' ) ) {
	better_amp_get_sidebar();
}

?>
<div class="better-amp-wrapper">
	<header itemscope itemtype="https://schema.org/WPHeader" class="site-header">
		<?php

		if ( better_amp_get_theme_mod( 'better-amp-sidebar-show' ) ) {
			?>
			<button class="fa fa-bars navbar-toggle" on="tap:better-ampSidebar.toggle"
				<?php better_amp_customizer_hidden_attr( 'better-amp-sidebar-show' ); ?>></button>
			<?php
		}

		echo better_amp_default_theme_logo();

		if ( better_amp_get_theme_mod( 'better-amp-header-show-search' ) ) {
			?>
			<a href="<?php echo better_amp_get_search_page_url() ?>"
			   class="navbar-search" <?php better_amp_customizer_hidden_attr( 'better-amp-header-show-search' ) ?>><i
						class="fa fa-search" aria-hidden="true"></i>
			</a>
			<?php
		}

		?>
	</header><!-- End Main Nav -->
	<?php

	better_amp_show_ad_location( 'amp_header_after' );

	?>
	<div class="wrap">
