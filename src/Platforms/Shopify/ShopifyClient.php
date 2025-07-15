<?php
/**
 * Shopify Client
 *
 * @package WooCommerce\Migrator\Platforms\Shopify
 */

declare(strict_types=1);

namespace WooCommerce\Migrator\Platforms\Shopify;

use WooCommerce\Migrator\Platforms\Shopify\Exceptions\ShopifyClientException;
use WP_Error;

/**
 * A client for interacting with the Shopify API.
 */
class ShopifyClient {

	private const API_VERSION = '2025-07';

	/**
	 * The Shopify domain.
	 *
	 * @var string
	 */
	private $domain;

	/**
	 * The Shopify access token.
	 *
	 * @var string
	 */
	private $access_token;

	/**
	 * ShopifyClient constructor.
	 *
	 * @param string $domain       The Shopify domain.
	 * @param string $access_token The Shopify access token.
	 */
	public function __construct( string $domain, string $access_token ) {
		$this->domain       = $domain;
		$this->access_token = $access_token;
	}

	/**
	 * Perform a GraphQL request to the Shopify API.
	 *
	 * @param string $query     The GraphQL query.
	 * @param array  $variables The variables for the query.
	 *
	 * @return object
	 * @throws ShopifyClientException When the request fails.
	 */
	public function graphql_request( string $query, array $variables = array() ): object {
		$url      = sprintf( 'https://%s/admin/api/%s/graphql.json', $this->domain, self::API_VERSION );
		$response = wp_remote_request(
			$url,
			array(
				'method'  => 'POST',
				'headers' => array(
					'Content-Type'           => 'application/json',
					'X-Shopify-Access-Token' => $this->access_token,
				),
				'body'    => wp_json_encode(
					array(
						'query'     => $query,
						'variables' => $variables,
					)
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
			throw new ShopifyClientException( $response->get_error_message() );
		}

		$response_code = wp_remote_retrieve_response_code( $response );
		$response_body = wp_remote_retrieve_body( $response );

		if ( $response_code >= 300 ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
			throw new ShopifyClientException( $response_body );
		}

		$decoded_body = json_decode( $response_body );

		if ( isset( $decoded_body->errors ) ) {
			throw new ShopifyClientException( wp_json_encode( $decoded_body->errors ) );
		}

		return $decoded_body->data;
	}

	/**
	 * Perform a REST request to the Shopify API.
	 *
	 * @param string $path         The path for the REST request.
	 * @param array  $query_params The query parameters for the request.
	 * @param string $method       The HTTP method for the request.
	 *
	 * @return object
	 * @throws ShopifyClientException When the request fails.
	 */
	public function rest_request( string $path, array $query_params = array(), string $method = 'GET' ): object {
		$url = sprintf( 'https://%s/admin/api/%s/%s', $this->domain, self::API_VERSION, $path );
		$url = add_query_arg( $query_params, $url );

		$response = wp_remote_request(
			$url,
			array(
				'method'  => $method,
				'headers' => array(
					'Content-Type'           => 'application/json',
					'X-Shopify-Access-Token' => $this->access_token,
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
			throw new ShopifyClientException( $response->get_error_message() );
		}

		$response_code = wp_remote_retrieve_response_code( $response );
		$response_body = wp_remote_retrieve_body( $response );

		if ( $response_code >= 300 ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
			throw new ShopifyClientException( $response_body );
		}

		return json_decode( $response_body );
	}
}
