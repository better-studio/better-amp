<?php

add_filter( 'better-amp/template/active-template', 'better_amp_get_default_template_info', 1 );

function better_amp_get_default_template_info() {
	return apply_filters( 'better-amp/template/default-template', array(
		'Name'         => __( 'Default Template', 'better-amp' ),
		'ThemeURI'     => 'http://betterstudio.com',
		'Description'  => 'Better-AMP default template',
		'Author'       => 'BetterStudio',
		'AuthorURI'    => 'http://betterstudio.com',
		'Version'      => '1.0.0',
		'ScreenShot'   => 'screenshot.png',
		'TemplateRoot' => dirname( __FILE__ ),
		'MaxWidth'     => 780
	) );
}
