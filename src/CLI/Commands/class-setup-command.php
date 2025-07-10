<?php
/**
 * The Setup command.
 *
 * @package WooCommerce\Migrator\CLI\Commands
 */

declare( strict_types=1 );

namespace WooCommerce\Migrator\CLI\Commands;

use WooCommerce\Migrator\CLI\CredentialManager;
use WP_CLI;

/**
 * The command for interactively setting up platform credentials.
 */
class SetupCommand extends BaseCommand {

	/**
	 * Sets up the credentials for a given platform.
	 *
	 * ## OPTIONS
	 *
	 * [--platform=<platform>]
	 * : The platform to set up credentials for. Defaults to 'shopify'.
	 *
	 * ## EXAMPLES
	 *
	 *     wp wc migrate setup
	 *
	 * @param array $args       Positional arguments.
	 * @param array $assoc_args Associative arguments.
	 */
	public function __invoke( array $args, array $assoc_args ) {
		$platform = $this->get_platform( $assoc_args );
		$manager  = new CredentialManager( $platform );

		// For now, we only support Shopify.
		// This can be extended later with a factory or registry.
		if ( 'shopify' !== $platform ) {
			WP_CLI::error( "The specified platform '{$platform}' is not supported." );
		}

		WP_CLI::log( 'Configuring credentials for ' . ucfirst( $platform ) . '...' );

		$required_fields = array(
			'api_key'  => 'Enter your Shopify API Access Token:',
			'shop_url' => 'Enter your Shopify store URL (e.g., my-store.myshopify.com):',
		);

		$credentials = $manager->prompt_for_credentials( $required_fields );
		$manager->save_credentials( $credentials );

		WP_CLI::success( 'Credentials saved successfully.' );
	}
}
