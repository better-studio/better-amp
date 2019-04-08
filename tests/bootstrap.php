<?php

$_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $_tests_dir ) {

	$_tests_dir = realpath('../../../../../wordpress-tests-lib');
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

function _plugin_file() {

	$dir = dirname( __DIR__ );

	return $dir . '/' . basename( $dir ) . '.php';
}

/**
 * Manually load the plugin being tested.
 */
function _manually_load_plugin() {

	require _plugin_file();
}

tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';

require __DIR__ . '/class-amp-testcase.php';
