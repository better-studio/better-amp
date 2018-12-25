<?php

add_action( 'customize_preview_init', 'better_amp_enqueue_customizer_js' );

/**
 * Callback: enqueue customizer preview javascript
 * Action  : customize_preview_init
 *
 * @since 1.0.0
 */
function better_amp_enqueue_customizer_js() {

	//	better_amp_enqueue_script(
	wp_enqueue_script(
		'better-amp-customizer',
		better_amp_plugin_url( 'template/customizer/customize-preview.js' ),
		array( 'customize-preview', 'jquery' )
	);

}

add_action( 'customize_register', 'better_amp_register_custom_controls' );

function better_amp_register_custom_controls( $wp_customize ) {

	$wp_customize->register_control_type( 'AMP_Customize_Social_Sorter_Control' );
}


add_action( 'customize_controls_enqueue_scripts', 'better_amp_add_customizer_script' );

function better_amp_add_customizer_script() {

	global $wpdb;

	//	better_amp_enqueue_script(
	wp_enqueue_script(
		'better-amp-customizer',
		better_amp_plugin_url( 'template/customizer/customizer.js' ),
		array( 'jquery' )
	);
	wp_enqueue_style(
		'better-amp-customizer-style',
		better_amp_plugin_url( 'template/customizer/customizer.css' )
	);


	$sql    = 'SELECT term_id FROM ' . $wpdb->term_taxonomy . ' WHERE taxonomy=\'category\' ORDER BY count DESC LIMIT 1';
	$cat_ID = (int) $wpdb->get_var( $sql );

	$sql     = 'SELECT ID FROM ' . $wpdb->posts . ' as p INNER JOIN ' . $wpdb->postmeta . ' as pm on(p.ID = pm.post_id)' .
	           ' WHERE p.post_type=\'post\' AND p.post_status=\'publish\' AND pm.meta_value != \'\'' .
	           ' AND NOT EXISTS( SELECT post_id FROM ' . $wpdb->postmeta . ' WHERE post_id = p.ID AND meta_key = \'disable-better-amp\')' .
	           ' AND pm.meta_key = \'_thumbnail_id\' LIMIT 1';
	$post_ID = (int) $wpdb->get_var( $sql );

	wp_localize_script( 'better-amp-customizer', 'better_amp_customizer', array(
		'amp_url'     => better_amp_site_url(),
		'archive_url' => Better_AMP_Content_Sanitizer::transform_to_amp_url( get_category_link( $cat_ID ) ),
		'post_url'    => Better_AMP_Content_Sanitizer::transform_to_amp_url( get_the_permalink( $post_ID ) ),
	) );
}


add_action( 'customize_register', 'better_amp_customize_register' );

/**
 * Callback: Register customizer input fields
 * Action  : customize_register
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Manager $wp_customizer
 */
function better_amp_customize_register( $wp_customizer ) {

	include BETTER_AMP_PATH . 'template/customizer/class-amp-customize-controls.php';
	include BETTER_AMP_PATH . 'template/customizer/class-amp-customize-social-sorter-control.php';

	/**
	 * 0. AMP Panel
	 */
	$wp_customizer->add_panel(
		new WP_Customize_Panel(
			$wp_customizer,
			'better-amp-panel',
			array(
				'title'    => __( 'AMP Theme', 'better-amp' ),
				'priority' => 10,
			)
		)
	);


	/**
	 * 1. Add Header section
	 */
	$wp_customizer->add_section( 'better-amp-header-section', array(
		'title'    => better_amp_translation_get( 'header' ),
		'priority' => 5,
		'panel'    => 'better-amp-panel'
	) );


	/**
	 * 1.1 Logo section
	 */
	$wp_customizer->add_setting( 'better-amp-header-logo-text', array(
		'default'   => better_amp_get_default_theme_setting( 'better-amp-header-logo-text' ),
		'transport' => 'postMessage',
	) );
	$wp_customizer->add_control( 'better-amp-header-logo-text', array(
		'label'    => __( 'Text Logo', 'better-amp' ),
		'section'  => 'better-amp-header-section',
		'priority' => 8,
	) );

	if ( $wp_customizer->selective_refresh ) {

		$wp_customizer->selective_refresh->add_partial( 'better-amp-header-logo-text', array(
			'settings'            => array( 'better-amp-header-logo-text' ),
			'selector'            => '.branding',
			'render_callback'     => 'better_amp_default_theme_logo',
			'container_inclusive' => true,
		) );
	}


	$wp_customizer->add_setting( 'better-amp-header-logo-img', array(
		'default'   => better_amp_get_default_theme_setting( 'better-amp-header-logo-img' ),
		'transport' => 'postMessage',
	) );
	$logo_settings = better_amp_get_default_theme_setting( 'logo' );

	$control_class = class_exists( 'WP_Customize_Cropped_Image_Control' ) ? 'WP_Customize_Cropped_Image_Control' : 'WP_Customize_Image_Control';
	$wp_customizer->add_control( new $control_class( $wp_customizer, 'better-amp-header-logo-img', array(
		'label'         => __( 'Logo', 'better-amp' ),
		'section'       => 'better-amp-header-section',
		'priority'      => 10,
		'height'        => $logo_settings['height'],
		'width'         => $logo_settings['width'],
		'flex_height'   => $logo_settings['flex-height'],
		'flex_width'    => $logo_settings['flex-width'],
		'button_labels' => array(
			'select'       => __( 'Select logo', 'better-amp' ),
			'change'       => __( 'Change logo', 'better-amp' ),
			'remove'       => __( 'Remove', 'better-amp' ),
			'default'      => __( 'Default', 'better-amp' ),
			'placeholder'  => __( 'No logo selected', 'better-amp' ),
			'frame_title'  => __( 'Select logo', 'better-amp' ),
			'frame_button' => __( 'Choose logo', 'better-amp' ),
		),
	) ) );

	if ( $wp_customizer->selective_refresh ) {

		$wp_customizer->selective_refresh->add_partial( 'better-amp-header-logo-img', array(
			'settings'            => array( 'better-amp-header-logo-img' ),
			'selector'            => '.branding',
			'render_callback'     => 'better_amp_default_theme_logo',
			'container_inclusive' => true,
		) );
	}


	/**
	 * 1.2 Divider
	 */
	$wp_customizer->add_setting( 'better-amp-header-divider-1', array() );
	$wp_customizer->add_control( new AMP_Customize_Divider_Control( $wp_customizer, 'better-amp-header-divider-1', array(
		'section'  => 'better-amp-header-section',
		'priority' => 12,
	) ) );


	/**
	 * 1.3 Toggle Search
	 */
	$wp_customizer->add_setting( 'better-amp-header-show-search', array(
		'transport' => 'postMessage',
		'default'   => better_amp_get_default_theme_setting( 'better-amp-header-show-search' ),
	) );
	$wp_customizer->add_control( new AMP_Customize_Switch_Control( $wp_customizer, 'better-amp-header-show-search', array(
		'label'    => __( 'Show Search', 'better-amp' ),
		'section'  => 'better-amp-header-section',
		'priority' => 14,
	) ) );


	/**
	 * 1.4 Sticky Header
	 */
	$wp_customizer->add_setting( 'better-amp-header-sticky', array(
		'transport' => 'postMessage',
		'default'   => better_amp_get_default_theme_setting( 'better-amp-header-sticky' ),
	) );
	$wp_customizer->add_control( new AMP_Customize_Switch_Control( $wp_customizer, 'better-amp-header-sticky', array(
		'label'    => __( 'Sticky Header', 'better-amp' ),
		'section'  => 'better-amp-header-section',
		'priority' => 14,
	) ) );


	/**
	 * 2. Add Sidebar section
	 */
	$wp_customizer->add_section( 'better-amp-sidebar-section', array(
		'title'    => __( 'Sidebar', 'better-amp' ),
		'priority' => 7,
		'panel'    => 'better-amp-panel'
	) );


	/**
	 * 2.1 Toggle Sidebar
	 */
	$wp_customizer->add_setting( 'better-amp-sidebar-show', array(
		'transport' => 'postMessage',
		'default'   => better_amp_get_default_theme_setting( 'better-amp-sidebar-show' ),
	) );
	$wp_customizer->add_control( new AMP_Customize_Switch_Control( $wp_customizer, 'better-amp-sidebar-show', array(
		'label'    => __( 'Show Sidebar', 'better-amp' ),
		'section'  => 'better-amp-sidebar-section',
		'priority' => 8,
	) ) );


	/**
	 * 2.2 Divider
	 */
	$wp_customizer->add_setting( 'better-amp-sidebar-divider-1', array() );
	$wp_customizer->add_control( new AMP_Customize_Divider_Control( $wp_customizer, 'better-amp-sidebar-divider-1', array(
		'section'  => 'better-amp-sidebar-section',
		'priority' => 10,
	) ) );


	/**
	 * 2.3 Logo section
	 */
	$wp_customizer->add_setting( 'better-amp-sidebar-logo-text', array(
		'default'   => better_amp_get_default_theme_setting( 'better-amp-sidebar-logo-text' ),
		'transport' => 'postMessage',
	) );
	$wp_customizer->add_control( 'better-amp-sidebar-logo-text', array(
		'label'    => __( 'Text Logo', 'better-amp' ),
		'section'  => 'better-amp-sidebar-section',
		'priority' => 12,
	) );
	if ( $wp_customizer->selective_refresh ) {

		$wp_customizer->selective_refresh->add_partial( 'better-amp-sidebar-logo-text', array(
			'settings'            => array( 'better-amp-sidebar-logo-text' ),
			'selector'            => '.sidebar-brand .brand-name .logo',
			'render_callback'     => 'better_amp_default_theme_sidebar_logo',
			'container_inclusive' => true,
		) );
	}

	$wp_customizer->add_setting( 'better-amp-sidebar-logo-img', array(
		'default'   => better_amp_get_default_theme_setting( 'better-amp-sidebar-logo-img' ),
		'transport' => 'postMessage',
	) );
	$logo_settings = better_amp_get_default_theme_setting( 'sidebar-logo' );

	$control_class = class_exists( 'WP_Customize_Cropped_Image_Control' ) ? 'WP_Customize_Cropped_Image_Control' : 'WP_Customize_Image_Control';
	$wp_customizer->add_control( new $control_class( $wp_customizer, 'better-amp-sidebar-logo-img', array(
		'label'         => __( 'Logo', 'better-amp' ),
		'section'       => 'better-amp-sidebar-section',
		'priority'      => 14,
		'height'        => $logo_settings['height'],
		'width'         => $logo_settings['width'],
		'flex_height'   => $logo_settings['flex-height'],
		'flex_width'    => $logo_settings['flex-width'],
		'button_labels' => array(
			'select'       => __( 'Select logo', 'better-amp' ),
			'change'       => __( 'Change logo', 'better-amp' ),
			'remove'       => __( 'Remove', 'better-amp' ),
			'default'      => __( 'Default', 'better-amp' ),
			'placeholder'  => __( 'No logo selected', 'better-amp' ),
			'frame_title'  => __( 'Select logo', 'better-amp' ),
			'frame_button' => __( 'Choose logo', 'better-amp' ),
		),
	) ) );

	if ( $wp_customizer->selective_refresh ) {

		$wp_customizer->selective_refresh->add_partial( 'better-amp-sidebar-logo-img', array(
			'settings'            => array( 'better-amp-sidebar-logo-img' ),
			'selector'            => '.sidebar-brand .brand-name .logo',
			'render_callback'     => 'better_amp_default_theme_sidebar_logo',
			'container_inclusive' => true,
		) );
	}


	/**
	 * 2.4 Social icons
	 */
	$wp_customizer->add_setting( 'better-amp-sidebar-divider-2', array() );
	$wp_customizer->add_control( new AMP_Customize_Divider_Control( $wp_customizer, 'better-amp-sidebar-divider-2', array(
		'section'  => 'better-amp-sidebar-section',
		'priority' => 16,
	) ) );
	$wp_customizer->add_setting( 'better-amp-facebook', array(
		'default'   => '#',
		'transport' => 'postMessage',
	) );
	$wp_customizer->add_control( 'better-amp-facebook', array(
		'label'    => __( 'Facebook', 'better-amp' ),
		'section'  => 'better-amp-sidebar-section',
		'priority' => 18,
	) );
	$wp_customizer->add_setting( 'better-amp-twitter', array(
		'default'   => '#',
		'transport' => 'postMessage',
	) );
	$wp_customizer->add_control( 'better-amp-twitter', array(
		'label'    => __( 'Twitter', 'better-amp' ),
		'section'  => 'better-amp-sidebar-section',
		'priority' => 20,
	) );
	$wp_customizer->add_setting( 'better-amp-google_plus', array(
		'default'   => '#',
		'transport' => 'postMessage',
	) );
	$wp_customizer->add_control( 'better-amp-google_plus', array(
		'label'    => __( 'Google Plus', 'better-amp' ),
		'section'  => 'better-amp-sidebar-section',
		'priority' => 22,
	) );
	$wp_customizer->add_setting( 'better-amp-email', array(
		'default'   => '#',
		'transport' => 'postMessage',
	) );
	$wp_customizer->add_control( 'better-amp-email', array(
		'label'    => __( 'email', 'better-amp' ),
		'section'  => 'better-amp-sidebar-section',
		'priority' => 24,
	) );


	/**
	 * 2.5 Copyright text
	 */
	$wp_customizer->add_setting( 'better-amp-sidebar-footer-text', array(
		'default'   => better_amp_get_default_theme_setting( 'better-amp-sidebar-footer-text' ),
		'transport' => 'postMessage',
	) );
	$wp_customizer->add_control( 'better-amp-sidebar-footer-text', array(
		'label'    => __( 'Copyright text', 'better-amp' ),
		'section'  => 'better-amp-sidebar-section',
		'priority' => 26,
		'type'     => 'textarea',
	) );


	/**
	 * 1.2 Divider
	 */
	$wp_customizer->add_setting( 'better-amp-sidebar-divider-3', array() );
	$wp_customizer->add_control( new AMP_Customize_Divider_Control( $wp_customizer, 'better-amp-sidebar-divider-3', array(
		'section'  => 'better-amp-sidebar-section',
		'priority' => 27,
	) ) );


	/**
	 * 3. Footer
	 */
	$wp_customizer->add_section( 'better-amp-footer-section', array(
		'title'    => __( 'Footer', 'better-amp' ),
		'priority' => 7,
		'panel'    => 'better-amp-panel'
	) );


	/**
	 * 3.1 Footer copyright text
	 */
	$wp_customizer->add_setting( 'better-amp-footer-copyright-show', array(
		'default'   => better_amp_get_default_theme_setting( 'better-amp-footer-copyright-show' ),
		'transport' => 'postMessage',
	) );
	$wp_customizer->add_control( new AMP_Customize_Switch_Control( $wp_customizer, 'better-amp-footer-copyright-show', array(
		'label'    => __( 'Show Footer Copyright?', 'better-amp' ),
		'section'  => 'better-amp-footer-section',
		'priority' => 17,
	) ) );
	$wp_customizer->add_setting( 'better-amp-footer-copyright-text', array(
		'default'   => better_amp_get_default_theme_setting( 'better-amp-footer-copyright-text' ),
		'transport' => 'postMessage',
	) );
	$wp_customizer->add_control( 'better-amp-footer-copyright-text', array(
		'label'    => __( 'Copyright text', 'better-amp' ),
		'section'  => 'better-amp-footer-section',
		'priority' => 18,
		'type'     => 'textarea',
	) );


	/**
	 * 3.2 Footer toggle none AMP version link
	 */
	$wp_customizer->add_setting( 'better-amp-footer-main-link', array(
		'default'   => better_amp_get_default_theme_setting( 'better-amp-footer-main-link' ),
		'transport' => 'postMessage',
	) );
	$wp_customizer->add_control( new AMP_Customize_Switch_Control( $wp_customizer, 'better-amp-footer-main-link', array(
		'label'    => __( 'Show none AMP version link', 'better-amp' ),
		'section'  => 'better-amp-footer-section',
		'priority' => 21,
	) ) );


	/**
	 * 4. Archive pages
	 */
	$wp_customizer->add_section( 'better-amp-archive-section', array(
		'title'    => __( 'Archive', 'better-amp' ),
		'priority' => 9,
		'panel'    => 'better-amp-panel'
	) );


	/**
	 * 4.1 Archive listing
	 */
	$wp_customizer->add_setting( 'better-amp-archive-listing', array(
		'default' => better_amp_get_default_theme_setting( 'better-amp-archive-listing' ),
	) );
	$wp_customizer->add_control( 'better-amp-archive-listing', array(
		'label'    => __( 'Archive listing', 'better-amp' ),
		'section'  => 'better-amp-archive-section',
		'priority' => 20,
		'type'     => 'select',
		'choices'  => array(
			'listing-1' => __( 'Small Image Listing', 'better-amp' ),
			'listing-2' => __( 'Large Image Listing', 'better-amp' ),
		)
	) );


	/**
	 * 5. Post
	 */
	$wp_customizer->add_section( 'better-amp-post-section', array(
		'title'    => __( 'Posts', 'better-amp' ),
		'priority' => 11,
		'panel'    => 'better-amp-panel'
	) );


	/**
	 * 5.1 Post thumbnail
	 */
	$wp_customizer->add_setting( 'better-amp-post-show-thumbnail', array(
		'default'   => better_amp_get_default_theme_setting( 'better-amp-post-show-thumbnail' ),
		'transport' => 'postMessage',
	) );
	$wp_customizer->add_control( new AMP_Customize_Switch_Control( $wp_customizer, 'better-amp-post-show-thumbnail', array(
		'label'    => __( 'Show Thumbnail', 'better-amp' ),
		'section'  => 'better-amp-post-section',
		'priority' => 2,
	) ) );


	/**
	 * 5.2 Show comments
	 */
	$wp_customizer->add_setting( 'better-amp-post-show-comment', array(
		'default'   => better_amp_get_default_theme_setting( 'better-amp-post-show-comment' ),
		'transport' => 'postMessage',
	) );
	$wp_customizer->add_control( new AMP_Customize_Switch_Control( $wp_customizer, 'better-amp-post-show-comment', array(
		'label'    => __( 'Show comment', 'better-amp' ),
		'section'  => 'better-amp-post-section',
		'priority' => 4,
	) ) );


	/**
	 * 5.3 Show Related Posts
	 */
	$wp_customizer->add_setting( 'better-amp-post-show-related', array(
		'default'   => better_amp_get_default_theme_setting( 'better-amp-post-show-related' ),
		'transport' => 'postMessage',
	) );
	$wp_customizer->add_control( new AMP_Customize_Switch_Control( $wp_customizer, 'better-amp-post-show-related', array(
		'label'    => __( 'Show Related Posts', 'better-amp' ),
		'section'  => 'better-amp-post-section',
		'priority' => 4,
	) ) );
	$wp_customizer->add_setting( 'better-amp-post-related-algorithm', array(
		'default'   => better_amp_get_default_theme_setting( 'better-amp-post-related-algorithm' ),
		'transport' => 'postMessage',
	) );
	$wp_customizer->add_control( 'better-amp-post-related-algorithm', array(
		'label'    => __( 'Related Posts Algorithm', 'better-amp' ),
		'section'  => 'better-amp-post-section',
		'priority' => 5,
		'type'     => 'select',
		'choices'  => array(
			'cat'            => __( 'by Category', 'better-amp' ),
			'tag'            => __( 'by Tag', 'better-amp' ),
			'author'         => __( 'by Author', 'better-amp' ),
			'cat-tag'        => __( 'by Category & Tag', 'better-amp' ),
			'cat-tag-author' => __( 'by Category, Tag & Author', 'better-amp' ),
			'random'         => __( 'Randomly', 'better-amp' ),
		)
	) );
	$wp_customizer->add_setting( 'better-amp-post-related-count', array(
		'default'   => better_amp_get_default_theme_setting( 'better-amp-post-related-count' ),
		'transport' => 'postMessage',
	) );

	$wp_customizer->add_control( 'better-amp-post-related-count', array(
		'label'    => __( 'Related Posts Count', 'better-amp' ),
		'section'  => 'better-amp-post-section',
		'priority' => 4,
	) );

	/**
	 * 5.4 Divider
	 */
	$wp_customizer->add_setting( 'better-amp-post-divider-1', array() );
	$wp_customizer->add_control( new AMP_Customize_Divider_Control( $wp_customizer, 'better-amp-post-divider-1', array(
		'section'  => 'better-amp-post-section',
		'priority' => 6,
	) ) );

	/**
	 * 5.5 Show Share Box
	 */
	$wp_customizer->add_setting( 'better-amp-post-social-share-show', array(
		'transport' => 'postMessage',
		'default'   => better_amp_get_default_theme_setting( 'better-amp-post-social-share-show' ),
	) );
	$wp_customizer->add_control( 'better-amp-post-social-share-show', array(
		'label'    => __( 'Show Share Box In Posts?', 'better-amp' ),
		'section'  => 'better-amp-post-section',
		'priority' => 7,
		'type'     => 'select',
		'choices'  => array(
			'show' => __( 'Show', 'better-amp' ),
			'hide' => __( 'Hide', 'better-amp' ),
		)
	) );

	/**
	 * 5.6 Show share count
	 */
	$wp_customizer->add_setting( 'better-amp-post-social-share-count', array(
		'transport' => 'postMessage',
		'default'   => better_amp_get_default_theme_setting( 'better-amp-post-social-share-count' ),
	) );
	$wp_customizer->add_control( 'better-amp-post-social-share-count', array(
		'label'    => __( 'Show share count?', 'better-amp' ),
		'section'  => 'better-amp-post-section',
		'priority' => 8,
		'type'     => 'select',
		'choices'  => array(
			'total'          => __( 'Show, Total share count', 'better-amp' ),
			'total-and-site' => __( 'Show, Total share count + Each site count', 'better-amp' ),
			'hide'           => __( 'No, Don\'t show.', 'better-amp' ),
		)
	) );


	/**
	 * 5.7 Social share sorter
	 */
	$wp_customizer->add_setting( 'better-amp-post-social-share', array(
		'default'   => better_amp_get_default_theme_setting( 'better-amp-post-social-share' ),
		'transport' => 'postMessage',
	) );
	$wp_customizer->add_control( new AMP_Customize_Social_Sorter_Control( $wp_customizer, 'better-amp-post-social-share', array(
		'label'    => __( 'Drag and Drop To Sort The share sites', 'better-amp' ),
		'section'  => 'better-amp-post-section',
		'priority' => 9,
	) ) );


	/**
	 * 5.8 Social share for page
	 */
	$wp_customizer->add_setting( 'better-amp-page-social-share-show', array(
		'transport' => 'postMessage',
		'default'   => better_amp_get_default_theme_setting( 'better-amp-page-social-share-show' ),
	) );
	$wp_customizer->add_control( 'better-amp-page-social-share-show', array(
		'label'    => __( 'Show Share Box In Pages?', 'better-amp' ),
		'section'  => 'better-amp-post-section',
		'priority' => 10,
		'type'     => 'select',
		'choices'  => array(
			'show' => __( 'Show', 'better-amp' ),
			'hide' => __( 'Hide', 'better-amp' ),
		)
	) );

	/**
	 * 5.9 Share link format
	 */
	$wp_customizer->add_setting( 'better-amp-post-social-share-link-format', array(
		'transport' => 'postMessage',
		'default'   => better_amp_get_default_theme_setting( 'better-amp-post-social-share-link-format' ),
	) );
	$wp_customizer->add_control( 'better-amp-post-social-share-link-format', array(
		'label'    => __( 'Share box link format?', 'better-amp' ),
		'section'  => 'better-amp-post-section',
		'priority' => 12,
		'type'     => 'select',
		'choices'  => array(
			'standard' => __( 'Standard wordpress permalink', 'better-amp' ),
			'short'    => __( 'Short link', 'better-amp' ),
		)
	) );


	/**
	 * 5.10 Featured Video/Audio meta key
	 */
	$wp_customizer->add_setting( 'better-amp-featured-va-key', array(
		'transport' => 'postMessage',
		'default'   => better_amp_get_default_theme_setting( 'better-amp-featured-va-key' ),
	) );
	$wp_customizer->add_control( 'better-amp-featured-va-key', array(
		'label'    => __( 'Featured Video/Audio Meta Key', 'better-amp' ),
		'section'  => 'better-amp-post-section',
		'priority' => 11,
	) );

	/**
	 * 6. Homepage
	 */
	$wp_customizer->add_section( 'better-amp-home-section', array(
		'title'    => __( 'Homepage', 'better-amp' ),
		'priority' => 11,
		'panel'    => 'better-amp-panel'
	) );


	/**
	 * 6.1 SlideShow toggle
	 */
	$wp_customizer->add_setting( 'better-amp-home-show-slide', array(
		'default'   => better_amp_get_default_theme_setting( 'better-amp-home-show-slide' ),
		'transport' => 'postMessage',
	) );
	$wp_customizer->add_control( new AMP_Customize_Switch_Control( $wp_customizer, 'better-amp-home-show-slide', array(
		'label'    => __( 'Show slider?', 'better-amp' ),
		'section'  => 'better-amp-home-section',
		'priority' => 4,
	) ) );


	/**
	 * 6.2 Homepage listing
	 */
	$wp_customizer->add_setting( 'better-amp-home-listing', array(
		'default' => better_amp_get_default_theme_setting( 'better-amp-home-listing' ),
	) );
	$wp_customizer->add_control( 'better-amp-home-listing', array(
		'label'    => __( 'Homepage listing', 'better-amp' ),
		'section'  => 'better-amp-home-section',
		'priority' => 20,
		'type'     => 'select',
		'choices'  => array(
			'default'   => __( '-- Default Listing --', 'better-amp' ),
			'listing-1' => __( 'Small Image Listing', 'better-amp' ),
			'listing-2' => __( 'Large Image Listing', 'better-amp' ),
		)
	) );

	/**
	 * 6.3 Homepage listing
	 */
	$wp_customizer->add_setting( 'better-amp-show-on-front', array(
		'default' => better_amp_get_default_theme_setting( 'better-amp-show-on-front' ),
	) );
	$wp_customizer->add_control( 'better-amp-show-on-front', array(
		'label'   => __( 'Front page displays', 'better-amp' ),
		'section' => 'better-amp-home-section',
		'type'    => 'radio',
		'choices' => array(
			'posts' => __( 'Your latest posts', 'better-amp' ),
			'page'  => __( 'A static page (select below)', 'better-amp' ),
		)
	) );

	$pages = get_pages( array(
		'echo'        => 0,
		'value_field' => 'ID',
	) );

	$page_choices = array();
	if ( $pages && ! is_wp_error( $pages ) ) {
		foreach ( $pages as $page ) {
			$page_choices[ $page->ID ] = $page->post_title ? $page->post_title : '#' . $page->ID . ' (no title)';
		}
	}
	$pages = null;
	$wp_customizer->add_setting( 'better-amp-page-on-front', array(
		'default' => better_amp_get_default_theme_setting( 'better-amp-page-on-front' ),
	) );
	$wp_customizer->add_control( 'better-amp-page-on-front', array(
		'label'   => __( 'Front page', 'better-amp' ),
		'section' => 'better-amp-home-section',
		'type'    => 'select',
		'choices' => $page_choices
	) );

	/**
	 * 7. Color
	 */
	$wp_customizer->add_section( 'better-amp-color-section', array(
		'title'    => __( 'Color', 'better-amp' ),
		'priority' => 13,
		'panel'    => 'better-amp-panel'
	) );


	/**
	 * 7.1 Theme Color
	 */
	$wp_customizer->add_setting( 'better-amp-color-theme', array(
		'default'              => better_amp_get_default_theme_setting( 'better-amp-color-theme' ),
		'sanitize_js_callback' => 'maybe_hash_hex_color',
		'transport'            => 'postMessage',
	) );
	$wp_customizer->add_control( new WP_Customize_Color_Control( $wp_customizer, 'better-amp-color-theme', array(
		'label'   => __( 'Theme Color', 'better-amp' ),
		'section' => 'better-amp-color-section',
	) ) );


	/**
	 * 7.2 BG Color
	 */
	$wp_customizer->add_setting( 'better-amp-color-bg', array(
		'default'              => better_amp_get_default_theme_setting( 'better-amp-color-bg' ),
		'sanitize_js_callback' => 'maybe_hash_hex_color',
		'transport'            => 'postMessage',
	) );
	$wp_customizer->add_control( new WP_Customize_Color_Control( $wp_customizer, 'better-amp-color-bg', array(
		'label'   => __( 'Background Color', 'better-amp' ),
		'section' => 'better-amp-color-section',
	) ) );


	/**
	 * 7.3 Content BG Color
	 */
	$wp_customizer->add_setting( 'better-amp-color-content-bg', array(
		'default'              => better_amp_get_default_theme_setting( 'better-amp-color-content-bg' ),
		'sanitize_js_callback' => 'maybe_hash_hex_color',
		'transport'            => 'postMessage',
	) );
	$wp_customizer->add_control( new WP_Customize_Color_Control( $wp_customizer, 'better-amp-color-content-bg', array(
		'label'   => __( 'Content Background Color', 'better-amp' ),
		'section' => 'better-amp-color-section',
	) ) );


	/**
	 * 7.4 Footer BG
	 */
	$wp_customizer->add_setting( 'better-amp-color-footer-bg', array(
		'default'              => better_amp_get_default_theme_setting( 'better-amp-color-footer-bg' ),
		'sanitize_js_callback' => 'maybe_hash_hex_color',
		'transport'            => 'postMessage',
	) );
	$wp_customizer->add_control( new WP_Customize_Color_Control( $wp_customizer, 'better-amp-color-footer-bg', array(
		'label'   => __( 'Footer Background', 'better-amp' ),
		'section' => 'better-amp-color-section',
	) ) );


	/**
	 * 7.5 Footer nav BG
	 */
	$wp_customizer->add_setting( 'better-amp-color-footer-nav-bg', array(
		'default'              => better_amp_get_default_theme_setting( 'better-amp-color-footer-nav-bg' ),
		'sanitize_js_callback' => 'maybe_hash_hex_color',
		'transport'            => 'postMessage',
	) );
	$wp_customizer->add_control( new WP_Customize_Color_Control( $wp_customizer, 'better-amp-color-footer-nav-bg', array(
		'label'   => __( 'Footer Navigation Color', 'better-amp' ),
		'section' => 'better-amp-color-section',
	) ) );


	/**
	 * 7.6 Text color
	 */
	$wp_customizer->add_setting( 'better-amp-color-text', array(
		'default'              => better_amp_get_default_theme_setting( 'better-amp-color-text' ),
		'sanitize_js_callback' => 'maybe_hash_hex_color',
		'transport'            => 'postMessage',
	) );
	$wp_customizer->add_control( new WP_Customize_Color_Control( $wp_customizer, 'better-amp-color-text', array(
		'label'   => __( 'Text Color', 'better-amp' ),
		'section' => 'better-amp-color-section',
	) ) );


	/**
	 * 8. Google Analytics
	 */
	$wp_customizer->add_section( 'better-amp-analytic-section', array(
		'title'    => __( 'Google Analytics', 'better-amp' ),
		'priority' => 14,
		'panel'    => 'better-amp-panel'
	) );

	/**
	 * 8.1 Google Analytics
	 */
	$wp_customizer->add_setting( 'better-amp-footer-analytics', array(
		'default' => better_amp_get_default_theme_setting( 'better-amp-footer-analytics' ),
	) );
	$wp_customizer->add_control( 'better-amp-footer-analytics', array(
		'label'       => __( 'Google Analytics', 'better-amp' ),
		'section'     => 'better-amp-analytic-section',
		'priority'    => 24,
		'description' => __( 'Insert google analytics account number.<br/> It’ll be in the format UA-XXXXXXXX-X', 'better-amp' ),
	) );


	/**
	 * 9. Additional CSS
	 */
	$wp_customizer->add_section( 'better-amp-css-section', array(
		'title'    => __( 'Custom CSS Code', 'better-amp' ),
		'priority' => 15,
		'panel'    => 'better-amp-panel'
	) );


	/**
	 * 9.1 Additional CSS
	 */
	$wp_customizer->add_setting( 'better-amp-additional-css', array(
		'sanitize_callback' => 'better_amp_css_sanitizer'
	) );

	$wp_customizer->add_control( 'better-amp-additional-css', array(
		'section'     => 'better-amp-css-section',
		'priority'    => 26,
		'type'        => 'textarea',
		'input_attrs' => array(
			'class' => 'better-amp-code',
		),
	) );


	/**
	 * 11. Custom Code
	 */
	$wp_customizer->add_section( 'better-amp-custom-code-section', array(
		'title'    => __( 'Custom HTML Code', 'better-amp' ),
		'priority' => 16,
		'panel'    => 'better-amp-panel'
	) );
	$wp_customizer->add_setting( 'better-amp-code-head', array(
		'default' => better_amp_get_default_theme_setting( 'better-amp-code-head' ),
	) );
	$wp_customizer->add_control( 'better-amp-code-head', array(
		'label'       => __( 'Codes between &#x3C;head&#x3E; and &#x3C;/head&#x3E; tags', 'better-amp' ),
		'section'     => 'better-amp-custom-code-section',
		'priority'    => 29,
		'type'        => 'textarea',
		'description' => __( 'Please be careful. Bad codes can make invalidation issue for your AMP pages.', 'better-amp' ),
	) );
	$wp_customizer->add_setting( 'better-amp-code-body-start', array(
		'default' => better_amp_get_default_theme_setting( 'better-amp-code-body-start' ),
	) );
	$wp_customizer->add_control( 'better-amp-code-body-start', array(
		'label'       => __( 'Codes right after &#x3C;body&#x3E; tag', 'better-amp' ),
		'section'     => 'better-amp-custom-code-section',
		'priority'    => 29,
		'type'        => 'textarea',
		'description' => __( 'Please be careful. Bad codes can make invalidation issue for your AMP pages.', 'better-amp' ),
	) );
	$wp_customizer->add_setting( 'better-amp-code-body-stop', array(
		'default' => better_amp_get_default_theme_setting( 'better-amp-code-body-stop' ),
	) );
	$wp_customizer->add_control( 'better-amp-code-body-stop', array(
		'label'       => __( 'Codes right before &#x3C;/body&#x3E; tag', 'better-amp' ),
		'section'     => 'better-amp-custom-code-section',
		'priority'    => 29,
		'type'        => 'textarea',
		'description' => __( 'Please be careful. Bad codes can make invalidation issue for your AMP pages.', 'better-amp' ),
	) );

	/**
	 * 10. Advanced Settings
	 */
	$wp_customizer->add_section( 'better-amp-advanced-section', array(
		'title'    => __( 'Advanced Settings', 'better-amp' ),
		'priority' => 17,
		'panel'    => 'better-amp-panel'
	) );

	/**
	 * 10.1 Mobile redirect
	 */
	$wp_customizer->add_setting( 'better-amp-mobile-auto-redirect', array(
		'default' => better_amp_get_default_theme_setting( 'better-amp-mobile-auto-redirect' ),
	) );

	$wp_customizer->add_control( new AMP_Customize_Switch_Control( $wp_customizer, 'better-amp-mobile-auto-redirect', array(
		'label'       => __( 'Show AMP for Mobile Visitors', 'better-amp' ),
		'description' => __( 'All mobile visitor will be redirected to AMP version of site automatically. Works with all cache plugins.', 'better-amp' ),
		'section'     => 'better-amp-advanced-section',
		'priority'    => 19,
	) ) );

	/**
	 * 10.2 Mobile redirect
	 */

	$wp_customizer->add_setting( 'better-amp-url-struct', array(
		'default' => better_amp_get_default_theme_setting( 'better-amp-url-struct' ),
	) );

	$wp_customizer->add_control( 'better-amp-url-struct', array(
		'label'       => __( 'AMP URL Format', 'better-amp' ),
		'section'     => 'better-amp-advanced-section',
		'description'      => __( 'Start Point: yoursite.com/amp/post/ <br>End Point: yoursite.com/post/amp/', 'better-amp' ),
		'priority'    => 20,
		'type'        => 'select',
		'choices'     => array(
			'start-point' => __( 'Start Point - At the beginning of the URL', 'better-amp' ),
			'end-point'   => __( 'End Point - At the end of the URL', 'better-amp' ),
		),
	) );


	/**
	 * 10.3 Exclude URL
	 */
	$wp_customizer->add_setting( 'better-amp-exclude-urls', array(
		'default' => better_amp_get_default_theme_setting( 'better-amp-exclude-urls' ),
	) );
	$wp_customizer->add_control( 'better-amp-exclude-urls', array(
		'label'       => __( 'Exclude URL From Auto Link Converting', 'better-amp' ),
		'section'     => 'better-amp-advanced-section',
		'priority'    => 21,
		'type'        => 'textarea',
		'description' => sprintf(
			__( 'You can exclude URL\'s of your site to prevent converting them into AMP URL inside your site. You can use * in the end of URL to exclude all URL\'s that start with it. Eg. <strong>%stest/*</strong><br><br> You can add multiple URL\s in multiple lines.', 'better-amp' ),
			home_url( '/' )
		),
	) );


	/**
	 * 11. AMP Pages
	 */
	$wp_customizer->add_section( 'better-amp-filter-section', array(
		'title'    => __( 'AMP Pages', 'better-amp' ),
		'priority' => 18,
		'panel'    => 'better-amp-panel'
	) );

	/**
	 * 11.1 Disabled post types
	 */
	$wp_customizer->add_setting( 'better-amp-filter-post-types', array(
		'transport' => 'postMessage',
		'default'   => better_amp_get_default_theme_setting( 'better-amp-filter-post-types' ),
	) );

	$wp_customizer->add_control( new AMP_Customize_Multiple_Select_Control( $wp_customizer, 'better-amp-filter-post-types', array(
		'label'            => __( 'Disabled post types', 'better-amp' ),
		'section'          => 'better-amp-filter-section',
		'description'      => __( 'AMP will not working out on selected post types.', 'better-amp' ),
		'priority'         => 22,
		'type'             => 'select',
		'deferred_choices' => 'better_amp_list_post_types',
	) ) );

	/**
	 * 11.2 Disabled taxonomies
	 */
	$wp_customizer->add_setting( 'better-amp-filter-taxonomies', array(
		'default'   => better_amp_get_default_theme_setting( 'better-amp-filter-taxonomies' ),
		'transport' => 'postMessage',
	) );
	$wp_customizer->add_control( new AMP_Customize_Multiple_Select_Control( $wp_customizer, 'better-amp-filter-taxonomies', array(
		'label'            => __( 'Disabled taxonomies', 'better-amp' ),
		'section'          => 'better-amp-filter-section',
		'description'      => __( 'Disable amp for this taxonomies.', 'better-amp' ),
		'priority'         => 23,
		'type'             => 'select',
		'deferred_choices' => 'better_amp_list_taxonomies',
	) ) );

	/**
	 * 11.3 Disabled homepage
	 */
	$wp_customizer->add_setting( 'better-amp-on-home', array(
		'default'   => better_amp_get_default_theme_setting( 'better-amp-on-home' ),
		'transport' => 'postMessage',
	) );

	$wp_customizer->add_control( new AMP_Customize_Switch_Control( $wp_customizer, 'better-amp-on-home', array(
		'priority' => 24,
		'section'  => 'better-amp-filter-section',
		'label'    => __( 'Enable on HomePage', 'better-amp' ),
	) ) );

	/**
	 * 11.4 Disabled search page
	 */
	$wp_customizer->add_setting( 'better-amp-on-search', array(
		'default'   => better_amp_get_default_theme_setting( 'better-amp-on-search' ),
		'transport' => 'postMessage',
	) );

	$wp_customizer->add_control( new AMP_Customize_Switch_Control( $wp_customizer, 'better-amp-on-search', array(
		'priority' => 25,
		'section'  => 'better-amp-filter-section',
		'label'    => __( 'Enable on search results', 'better-amp' ),
	) ) );

	$wp_customizer->add_setting( 'better-amp-excluded-url-struct', array(
		'default' => better_amp_get_default_theme_setting( 'better-amp-excluded-url-struct' ),
	) );

	$wp_customizer->add_control( 'better-amp-excluded-url-struct', array(
		'label'       => __( 'Exclude AMP by URL', 'better-amp' ),
		'section'     => 'better-amp-filter-section',
		'description' => __( 'Disable AMP version by the page URL. <hr> for instance /product/* will disable all amp pages starting with product in the URL.<br/> You can use <strong>*</strong> to include all characters except slash and You can also add multiple URL\s in multiple lines.', 'better-amp' ),
		'priority'    => 30,
		'type'        => 'textarea',
	) );


}

add_action( 'admin_menu', 'better_amp_add_customizer_admin_link', 999 );

function better_amp_add_customizer_admin_link() {

	$customize_url = add_query_arg( array(
		'return'    => urlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) ),
		'url'       => urlencode( better_amp_site_url() ),
		'autofocus' => array( 'panel' => 'better-amp-panel' )
	), 'customize.php' );

	add_submenu_page(
		'better-amp-translation',
		_x( 'Customize AMP Theme', 'better-amp' ),
		_x( 'Customize AMP', 'better-amp' ),
		'manage_options',
		$customize_url
	);

}
