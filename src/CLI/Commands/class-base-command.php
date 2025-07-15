<?php
/**
 * Base command class.
 *
 * @package WooCommerce\Migrator\CLI\Commands
 */

declare( strict_types=1 );

namespace WooCommerce\Migrator\CLI\Commands;

use WooCommerce\Migrator\CLI\CredentialManager;
use WP_CLI;

/**
 * Abstract base class for migrator commands.
 */
abstract class BaseCommand {

	/**
	 * Determines the platform to use, defaulting to 'shopify'.
	 *
	 * @param array $assoc_args Associative arguments from the command.
	 *
	 * @return string The platform slug.
	 */
	protected function get_platform( array $assoc_args ): string {
		$platform = $assoc_args['platform'] ?? null;
		if ( is_null( $platform ) ) {
			$platform = 'shopify';
			WP_CLI::log( "Platform not specified, using default: '{$platform}'." );
		}

		return $platform;
	}

	/**
	 * Handles the interactive credential setup process.
	 *
	 * @param string $platform The platform slug.
	 *
	 * @return void
	 */
	protected function handle_credential_setup( string $platform ): void {
		$manager = new CredentialManager( $platform );

		// For now, we only support Shopify.
		if ( 'shopify' !== $platform ) {
			WP_CLI::error( "The specified platform '{$platform}' is not supported for setup." );
		}

		WP_CLI::log( 'Configuring credentials for ' . ucfirst( $platform ) . '...' );

		$required_fields = array(
			'api_key'  => 'Enter your Shopify API Access Token:',
			'shop_url' => 'Enter your Shopify store URL (e.g., my-store.myshopify.com):',
		);

		$credentials = $manager->prompt_for_credentials( $required_fields );
		$manager->save_credentials( $credentials );
	}
}
