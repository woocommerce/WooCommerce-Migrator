<?php
/**
 * Test Utilities
 *
 * @package WooCommerce\Migrator\Tests
 */

declare(strict_types=1);

namespace WooCommerce\Migrator\Platforms\Shopify;

use WP_Error;

$mock_wp_remote_request_args     = array();
$mock_wp_remote_request_response = array();
$mock_is_wp_error                = false;

/**
 * Mock for wp_remote_request.
 *
 * @param string $_url  The URL to request.
 * @param array  $_args The arguments for the request.
 *
 * @return array|WP_Error
 */
function wp_remote_request( $_url, $_args ) { // phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	global $mock_wp_remote_request_args, $mock_wp_remote_request_response;
	$mock_wp_remote_request_args = func_get_args();
	return $mock_wp_remote_request_response;
}

/**
 * Mock for is_wp_error.
 *
 * @param mixed $thing The item to check.
 *
 * @return bool
 */
function is_wp_error( $thing ) {
	global $mock_is_wp_error;
	if ( $mock_is_wp_error ) {
		return true;
	}
	return $thing instanceof WP_Error;
}

/**
 * Mock for wp_remote_retrieve_response_code.
 *
 * @param array|WP_Error $response The response.
 *
 * @return int
 */
function wp_remote_retrieve_response_code( $response ) {
	return $response['response']['code'] ?? 200;
}

/**
 * Mock for wp_remote_retrieve_body.
 *
 * @param array|WP_Error $response The response.
 *
 * @return string
 */
function wp_remote_retrieve_body( $response ) {
	return $response['body'] ?? '';
}

/**
 * Mock for wp_json_encode.
 *
 * @param mixed $data The data to encode.
 *
 * @return string
 */
function wp_json_encode( $data ) {
	// phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode
	return json_encode( $data, JSON_UNESCAPED_SLASHES );
}

/**
 * Mock for add_query_arg.
 *
 * @param array  $params The parameters to add.
 * @param string $url    The URL to add to.
 *
 * @return string
 */
function add_query_arg( $params, $url ) {
	return $url . '?' . http_build_query( $params );
}
