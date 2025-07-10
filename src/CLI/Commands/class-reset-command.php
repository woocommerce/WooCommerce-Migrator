<?php
/**
 * The Reset command.
 *
 * @package WooCommerce\Migrator\CLI\Commands
 */

declare( strict_types=1 );

namespace WooCommerce\Migrator\CLI\Commands;

use WooCommerce\Migrator\CLI\CredentialManager;
use WP_CLI;

/**
 * The command for resetting platform credentials.
 */
class ResetCommand extends BaseCommand {

	/**
	 * Resets (deletes) the credentials for a given platform.
	 *
	 * ## OPTIONS
	 *
	 * [--platform=<platform>]
	 * : The platform to reset credentials for. Defaults to 'shopify'.
	 *
	 * ## EXAMPLES
	 *
	 *     wp wc migrate reset
	 *
	 * @param array $args       Positional arguments.
	 * @param array $assoc_args Associative arguments.
	 */
	public function __invoke( array $args, array $assoc_args ) {
		$platform = $this->get_platform( $assoc_args );
		$manager  = new CredentialManager( $platform );

		if ( ! $manager->has_credentials() ) {
			WP_CLI::warning( "No credentials found for '{$platform}' to reset." );
			return;
		}

		$manager->delete_credentials();

		WP_CLI::success( "Credentials for the '{$platform}' platform have been cleared." );
	}
}
