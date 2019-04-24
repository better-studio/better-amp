<?php

$site_branding = better_amp_get_branding_info( 'sidebar' );

better_amp_enqueue_block_style( 'sidebar' );

?>
<amp-sidebar id="better-ampSidebar" class="better-amp-sidebar" layout="nodisplay"
             side="<?php better_amp_direction() ?>">
	<div class="sidebar-container">

		<button on="tap:better-ampSidebar.close" class="close-sidebar">
			<i class="fa fa-caret-<?php better_amp_direction() ?>" aria-hidden="true"></i>
		</button>

		<div class="sidebar-brand type-<?php echo empty( $site_branding['logo'] ) ? 'text' : 'logo'; ?>">

			<?php if ( ! empty( $site_branding['logo'] ) ) { ?>
				<div class="logo">
					<?php
					echo $site_branding['logo-tag']; // escaped before
					?>
				</div>
			<?php } ?>

			<div class="brand-name">
				<?php echo $site_branding['name']; // escaped before ?>
			</div>

			<?php if ( better_amp_get_theme_mod( 'better-amp-tagline-show' ) ) { ?>

				<div class="brand-description">
					<?php echo $site_branding['description']; // escaped before ?>
				</div>

			<?php } ?>

		</div>

		<?php

		if ( has_nav_menu( 'amp-sidebar-nav' ) ) {

			wp_nav_menu( array(
				'theme_location' => 'amp-sidebar-nav',
				'items_wrap'     => '<nav id="%1$s" itemscope itemtype="http://schema.org/SiteNavigationElement" class="%2$s">%3$s</nav>',
				'container'      => FALSE,
				'menu_id'        => 'menu',
				'menu_class'     => 'amp-menu',
			) );

		} elseif ( is_user_logged_in() ) {

			$user_can_edit_menu = current_user_can( 'edit_theme_options' );

			if ( $user_can_edit_menu ) {
				printf( '<a href="%s" class="wrap">', esc_attr( admin_url( '/nav-menus.php?action=locations' ) ) );
			}

			esc_html_e( 'Select a menu for "AMP Sidebar"', 'better-amp' );

			if ( $user_can_edit_menu ) {
				echo '</a>';
			}

		}

		?>
		<div class="sidebar-footer">
			<?php

			$text = better_amp_get_theme_mod( 'better-amp-sidebar-footer-text', FALSE );

			if ( $text ) { ?>
				<p class="sidebar-footer-text">
					<?php echo $text; ?>
				</p>
				<?php
			}

			better_amp_template_part( 'views/misc/social-links' );

			?>
		</div>

	</div>
</amp-sidebar>
