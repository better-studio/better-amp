<?php

add_action( 'better-amp/template/head', 'better_amp_enqueue_scripts' );
add_action( 'better-amp/template/head', 'better_amp_print_styles' );
add_action( 'better-amp/template/head', 'better_amp_print_scripts' );
add_action( 'better-amp/template/head', 'better_amp_enqueue_boilerplate_style' );
add_action( 'better-amp/template/head', 'wp_site_icon' );

add_action( 'better-amp/template/head', 'better_amp_print_rel_canonical' );

add_action( 'better-amp/template/head', '_wp_render_title_tag' );

//add_action( 'better-amp/template/enqueue-scripts', 'better_amp_enqueue_rtl_style', 999 );
add_action( 'wp_head', 'better_amp_print_rel_amphtml' );

add_filter( 'wp_nav_menu_args', 'better_amp_theme_set_menu_walker', 9999 );

add_action( 'init', 'better_amp_fix_customizer_statics', 3 );

add_action( 'after_setup_theme', 'better_amp_wp_amp_compatibility_constants' );
