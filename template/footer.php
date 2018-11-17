<?php

better_amp_show_ad_location( 'amp_footer_before' );

?>
</div> <!-- /wrap -->

<footer class="better-amp-footer <?php echo better_amp_get_global( 'footer-custom-class', '' ); ?>">
	<?php

	wp_nav_menu( array(
		'theme_location'  => 'better-amp-footer',
		'menu_class'      => 'footer-navigation',
		'container_class' => 'better-amp-footer-nav'
	) );

	?>
	<div class="better-amp-copyright">
		<?php

		if ( better_amp_get_theme_mod( 'better-amp-footer-main-link' ) ) :

			$args = array();

			// disable auto redirect for this link
			if ( better_amp_get_theme_mod( 'better-amp-mobile-auto-redirect' ) ) {
				$args['query-args'] = array(
					array(
						'bamp-skip-redirect',
						TRUE,
					)
				);
			}

			?>
			<div class="better-amp-main-link" <?php better_amp_customizer_hidden_attr( 'better-amp-footer-main-link' ) ?>>
				<a href="<?php echo esc_attr( better_amp_guess_none_amp_url( $args ) ) ?>"><i
							class="fa fa-external-link-square"></i> <?php better_amp_translation_echo( 'view_desktop' ); ?>
				</a>
			</div>
			<?php
		endif;

		if ( better_amp_get_theme_mod( 'better-amp-footer-copyright-show' ) ) {
			echo better_amp_get_theme_mod( 'better-amp-footer-copyright-text' );
		}

		?>
	</div>

	<?php
	if ( $ga_code = better_amp_get_theme_mod( 'better-amp-footer-analytics' ) ) :
		?>
		<amp-analytics type="googleanalytics">
			<script type="application/json">
				{
					"vars": {
						"account": "<?php echo esc_attr( $ga_code ) ?>"
					},
					"triggers": {
						"trackPageview": {
							"on": "visible",
							"request": "pageview"
						}
					}
				}
			</script>
		</amp-analytics>

	<?php endif ?>

</footer>
</div>

<?php better_amp_footer() ?>
</body>
</html>