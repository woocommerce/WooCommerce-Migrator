<?php
/**
 * Test Utilities - Mock WordPress functions for testing
 *
 * @package WooCommerce\Migrator\Tests
 */

declare(strict_types=1);

namespace WooCommerce\Migrator\Platforms\Shopify;

use WP_Error;

/**
 * Initialize mock globals.
 */
function init_test_mocks(): void {
	$GLOBALS['mock_wp_remote_request_args']     = array();
	$GLOBALS['mock_wp_remote_request_response'] = array();
	$GLOBALS['mock_is_wp_error']                = false;
}

/**
 * Mock for wp_remote_request.
 *
 * @param string $_url  The URL to request.
 * @param array  $_args The arguments for the request.
 *
 * @return array|WP_Error
 */
function wp_remote_request( $_url, $_args ) { // phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	$GLOBALS['mock_wp_remote_request_args'] = func_get_args();
	return $GLOBALS['mock_wp_remote_request_response'];
}

/**
 * Mock for wp_remote_retrieve_response_code.
 *
 * @param array $_response The response array.
 *
 * @return int
 */
function wp_remote_retrieve_response_code( $_response ): int { // phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter.FoundInAfterLastUsed
	return $_response['response']['code'] ?? 200;
}

/**
 * Mock for wp_remote_retrieve_body.
 *
 * @param array $_response The response array.
 *
 * @return string
 */
function wp_remote_retrieve_body( $_response ): string { // phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter.FoundInAfterLastUsed
	return $_response['body'] ?? '';
}

/**
 * Mock for is_wp_error.
 *
 * @param mixed $thing The thing to check.
 *
 * @return bool
 */
function is_wp_error( $thing ): bool {
	// If mock is explicitly set to true, return true regardless of input.
	if ( $GLOBALS['mock_is_wp_error'] ?? false ) {
		return true;
	}
	// Otherwise, check if it's actually a WP_Error instance.
	return $thing instanceof \WP_Error;
}

/**
 * Mock for wp_json_encode.
 *
 * @param mixed $_data The data to encode.
 *
 * @return string
 */
function wp_json_encode( $_data ): string { // phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter.FoundInAfterLastUsed
	// phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode
	return json_encode( $_data );
}

/**
 * Mock for add_query_arg.
 *
 * @param array  $args The query arguments.
 * @param string $url  The URL.
 *
 * @return string
 */
function add_query_arg( array $args, string $url ): string {
	return $url . '?' . http_build_query( $args );
}

// Initialize mocks when this file is loaded.
init_test_mocks();
