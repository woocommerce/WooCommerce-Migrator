<?php
/**
 * Base command class.
 *
 * @package WooCommerce\Migrator\CLI\Commands
 */

declare( strict_types=1 );

namespace WooCommerce\Migrator\CLI\Commands;

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
}
