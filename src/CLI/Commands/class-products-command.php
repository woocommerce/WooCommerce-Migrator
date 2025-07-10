<?php
/**
 * The products command.
 *
 * @package WooCommerce\Migrator\CLI\Commands
 */

declare( strict_types=1 );

namespace WooCommerce\Migrator\CLI\Commands;

use WooCommerce\Migrator\CLI\CredentialManager;
use WP_CLI;

/**
 * The class for the `wc migrate products` command.
 */
class ProductsCommand extends BaseCommand {

	/**
	 * The handler for the `wc migrate products` command.
	 *
	 * This is a placeholder and will be replaced by the controller logic.
	 *
	 * [--platform=<platform>]
	 * : The platform to migrate products from.
	 * ---
	 * default: shopify
	 * ---
	 *
	 * ## EXAMPLES
	 *
	 * @param array $args       Positional arguments.
	 * @param array $assoc_args Associative arguments.
	 */
	public function __invoke( array $args, array $assoc_args ) {
		$platform = $this->get_platform( $assoc_args );
		$manager  = new CredentialManager( $platform );

		if ( ! $manager->has_credentials() ) {
			WP_CLI::log( "Credentials for '{$platform}' not found. Let's set them up." );

			// For now, we only support Shopify.
			if ( 'shopify' !== $platform ) {
				WP_CLI::error( "The specified platform '{$platform}' is not supported for automatic setup." );
			}

			$required_fields = array(
				'api_key'  => 'Enter your Shopify API Access Token:',
				'shop_url' => 'Enter your Shopify store URL (e.g., my-store.myshopify.com):',
			);

			$credentials = $manager->prompt_for_credentials( $required_fields );
			$manager->save_credentials( $credentials );

			WP_CLI::success( 'Credentials saved successfully. Please run the command again to begin the migration.' );
			return;
		}

		// The logic will be handled by the Products_Controller.
		// For now, we just show a success message if credentials exist.
		WP_CLI::success( 'Credentials found. Proceeding with migration...' );
	}
}
