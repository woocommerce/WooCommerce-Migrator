<?php
/**
 * PHPUnit bootstrap file.
 *
 * @package WooCommerce\Migrator
 */

// Load the Composer autoloader to make all dependencies available.
require_once dirname( __DIR__ ) . '/vendor/autoload.php';

// First, check if we're loaded from within a typical WP test environment.
if ( ! defined( 'WP_TESTS_DIR' ) ) {
	$wp_tests_dir = getenv( 'WP_TESTS_DIR' );
	if ( ! $wp_tests_dir ) {
		// Try to guess the location.
		$wp_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
	}
}

// Check if the WordPress test library exists.
if ( ! file_exists( $wp_tests_dir . '/includes/functions.php' ) ) {
	echo "Could not find {$wp_tests_dir}/includes/functions.php, have you run bin/install-wp-tests.sh ?" . PHP_EOL; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	exit( 1 );
}

// Give access to tests_add_filter() function.
require_once $wp_tests_dir . '/includes/functions.php';

// Start up the WP testing environment.
require $wp_tests_dir . '/includes/bootstrap.php'; 