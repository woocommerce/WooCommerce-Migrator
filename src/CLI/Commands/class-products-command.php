<?php
/**
 * The products command.
 *
 * @package WooCommerce\Migrator\CLI\Commands
 */

declare( strict_types=1 );

namespace WooCommerce\Migrator\CLI\Commands;

use WP_CLI;

/**
 * The class for the `wc migrate products` command.
 */
class ProductsCommand {

	/**
	 * The handler for the `wc migrate products` command.
	 *
	 * This is a placeholder and will be replaced by the controller logic.
	 *
	 * @param array $args       Positional arguments.
	 * @param array $assoc_args Associative arguments.
	 */
	public function __invoke( $args, $assoc_args ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		// The logic will be handled by the Products_Controller.
		// For now, we just show a success message.
		WP_CLI::success( 'Product migration command registered successfully!' );
	}
}
