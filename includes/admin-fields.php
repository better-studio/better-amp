<?php

if ( ! is_admin() ) {
	return;
}

add_filter( 'after_setup_theme', 'better_amp_panels_setup' );

if ( ! function_exists( 'better_amp_panels_setup' ) ) {
	/**
	 * Setup BetterAMP admin functionality
	 */
	function better_amp_panels_setup() {

		$args = array(
			'opt_name'           => 'better-amp-translation',
			'display_name'       => __( 'Better AMP', 'better-amp' ),
			'display_version'    => Better_AMP::VERSION,
			'menu_type'          => 'menu',
			'allow_sub_menu'     => TRUE,
			//
			'menu_title'         => '<strong>Better</strong> AMP',
			'page_title'         => __( 'Better AMP', 'better-amp' ),
			'global_variable'    => '',
			'admin_bar'          => FALSE,
			'dev_mode'           => FALSE,
			'update_notice'      => FALSE,
			'customizer'         => FALSE,
			'page_priority'      => '59.5',
			'page_permissions'   => 'manage_options',
			'menu_icon'          => better_amp_plugin_url( '/assets/images/better-amp-symbol.svg' ),
			'last_tab'           => '',
			'page_icon'          => 'icon-themes',
			'page_slug'          => 'better-amp-translation',
			'save_defaults'      => TRUE,
			'default_show'       => TRUE,
			'default_mark'       => '',
			'show_import_export' => TRUE,
			'transient_time'     => 60 * MINUTE_IN_SECONDS,
			'output'             => TRUE,
			'output_tag'         => TRUE,
			'database'           => '',
			'use_cdn'            => TRUE,
		);

		Redux::setArgs( 'better-amp-translation', $args );

		// Get translation fields from themes and plugins
		$fields = apply_filters( 'better-amp/translation/fields', array() );

		Redux::setSection( 'better-amp-translation', array(
			'title'  => __( 'Translation', 'better-amp' ),
			'id'     => 'basic',
			'icon'   => 'el el-globe',
			'fields' => $fields
		) );

	} // better_amp_panels_setup
}

add_filter( 'redux/better-amp-translation/aURL_filter', 'better_amp_option_panel_ads' );
function better_amp_option_panel_ads() {
	return '<a href="http://themeforest.net/item/x/15801051?ref=Better-Studio"><img src="' . better_amp_plugin_url( '/assets/images/publisher-ad.jpg' ) . '" alt="Publisher- The edge of art & power in publishing"></a>';
}
