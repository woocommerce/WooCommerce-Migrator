<?php
/**
 * Tests for ShopifyClient
 *
 * @package WooCommerce\Migrator\Tests\Platforms\Shopify
 */

declare(strict_types=1);

namespace WooCommerce\Migrator\Tests\Platforms\Shopify;

use WooCommerce\Migrator\Platforms\Shopify\Exceptions\ShopifyClientException;
use WooCommerce\Migrator\Platforms\Shopify\ShopifyClient;
use WooCommerce\Migrator\Tests\TestCase;
use WP_Error;

require_once __DIR__ . '/../../TestUtils.php';

/**
 * Tests for ShopifyClient
 *
 * @package WooCommerce\Migrator\Tests\Platforms\Shopify
 */
class ShopifyClientTest extends TestCase {

	/**
	 * The ShopifyClient instance.
	 *
	 * @var ShopifyClient
	 */
	private $client;

	/**
	 * The test domain.
	 *
	 * @var string
	 */
	private $domain = 'test-shop.myshopify.com';

	/**
	 * The test token.
	 *
	 * @var string
	 */
	private $token = 'test-token';

	/**
	 * Set up the test environment.
	 */
	public function setUp(): void {
		parent::setUp();
		global $mock_wp_remote_request_args, $mock_wp_remote_request_response, $mock_is_wp_error;
		$mock_wp_remote_request_args     = array();
		$mock_wp_remote_request_response = array();
		$mock_is_wp_error                = false;

		$this->client = new ShopifyClient( $this->domain, $this->token );
	}

	/**
	 * Test GraphQL request URL construction.
	 */
	public function test_graphql_request_url_construction() {
		global $mock_wp_remote_request_args, $mock_wp_remote_request_response;
		$mock_wp_remote_request_response = array(
			'body'     => '{"data":{"shop":{"name":"Test Shop"}}}',
			'response' => array( 'code' => 200 ),
		);
		$this->client->graphql_request( '{ shop { name } }' );
		$this->assertSame( 'https://test-shop.myshopify.com/admin/api/2025-07/graphql.json', $mock_wp_remote_request_args[0] );
	}

	/**
	 * Test GraphQL request headers and body.
	 */
	public function test_graphql_request_headers_and_body() {
		global $mock_wp_remote_request_args, $mock_wp_remote_request_response;
		$mock_wp_remote_request_response = array(
			'body'     => '{"data":{"shop":{"name":"Test Shop"}}}',
			'response' => array( 'code' => 200 ),
		);
		$query                           = '{ products(first: 1) { edges { node { id } } } }';
		$variables                       = array( 'first' => 1 );

		$this->client->graphql_request( $query, $variables );

		$this->assertSame( 'POST', $mock_wp_remote_request_args[1]['method'] );
		$this->assertSame( 'application/json', $mock_wp_remote_request_args[1]['headers']['Content-Type'] );
		$this->assertSame( $this->token, $mock_wp_remote_request_args[1]['headers']['X-Shopify-Access-Token'] );

		$expected_body = wp_json_encode(
			array(
				'query'     => $query,
				'variables' => $variables,
			)
		);
		$this->assertSame( $expected_body, $mock_wp_remote_request_args[1]['body'] );
	}

	/**
	 * Test successful GraphQL response parsing.
	 */
	public function test_graphql_request_successful_response_parsing() {
		global $mock_wp_remote_request_response;
		$mock_wp_remote_request_response = array(
			'body'     => '{"data":{"shop":{"name":"Test Shop"}}}',
			'response' => array( 'code' => 200 ),
		);

		$result = $this->client->graphql_request( '{ shop { name } }' );
		$this->assertEquals( (object) array( 'shop' => (object) array( 'name' => 'Test Shop' ) ), $result );
	}

	/**
	 * Test that the GraphQL request throws an exception on WP_Error.
	 */
	public function test_graphql_request_throws_exception_on_wp_error() {
		global $mock_wp_remote_request_response, $mock_is_wp_error;
		$mock_is_wp_error                = true;
		$mock_wp_remote_request_response = new WP_Error( 'http_error', 'Failed to connect' );

		$this->expectException( ShopifyClientException::class );
		$this->expectExceptionMessage( 'Failed to connect' );
		$this->client->graphql_request( '' );
	}

	/**
	 * Test that the GraphQL request throws an exception on HTTP error.
	 */
	public function test_graphql_request_throws_exception_on_http_error() {
		global $mock_wp_remote_request_response;
		$mock_wp_remote_request_response = array(
			'body'     => 'Server error',
			'response' => array( 'code' => 500 ),
		);

		$this->expectException( ShopifyClientException::class );
		$this->expectExceptionMessage( 'Server error' );
		$this->client->graphql_request( '' );
	}

	/**
	 * Test that the GraphQL request throws an exception on GraphQL error.
	 */
	public function test_graphql_request_throws_exception_on_graphql_error() {
		global $mock_wp_remote_request_response;
		$mock_wp_remote_request_response = array(
			'body'     => '{"errors":[{"message":"Field \'product\' is missing"}]}',
			'response' => array( 'code' => 200 ),
		);

		$this->expectException( ShopifyClientException::class );
		$this->client->graphql_request( '' );
	}

	/**
	 * Test REST request URL construction.
	 */
	public function test_rest_request_url_construction() {
		global $mock_wp_remote_request_args, $mock_wp_remote_request_response;
		$mock_wp_remote_request_response = array(
			'body'     => '{}',
			'response' => array( 'code' => 200 ),
		);
		$this->client->rest_request( 'products.json', array( 'limit' => 10 ) );
		$this->assertSame( 'https://test-shop.myshopify.com/admin/api/2025-07/products.json?limit=10', $mock_wp_remote_request_args[0] );
	}

	/**
	 * Test successful REST response parsing.
	 */
	public function test_rest_request_successful_response_parsing() {
		global $mock_wp_remote_request_response;
		$mock_wp_remote_request_response = array(
			'body'     => '{"products":[{"id":123}]}',
			'response' => array( 'code' => 200 ),
		);

		$result = $this->client->rest_request( 'products.json' );
		$this->assertEquals( (object) array( 'products' => array( (object) array( 'id' => 123 ) ) ), $result );
	}

	/**
	 * Test that the REST request throws an exception on WP_Error.
	 */
	public function test_rest_request_throws_exception_on_wp_error() {
		global $mock_wp_remote_request_response, $mock_is_wp_error;
		$mock_is_wp_error                = true;
		$mock_wp_remote_request_response = new WP_Error( 'http_error', 'REST Failed' );

		$this->expectException( ShopifyClientException::class );
		$this->expectExceptionMessage( 'REST Failed' );
		$this->client->rest_request( 'products.json' );
	}

	/**
	 * Test that the REST request throws an exception on HTTP error.
	 */
	public function test_rest_request_throws_exception_on_http_error() {
		global $mock_wp_remote_request_response;
		$mock_wp_remote_request_response = array(
			'body'     => 'Not found',
			'response' => array( 'code' => 404 ),
		);

		$this->expectException( ShopifyClientException::class );
		$this->expectExceptionMessage( 'Not found' );
		$this->client->rest_request( 'products.json' );
	}
}
