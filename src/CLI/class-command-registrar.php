<?php
/**
 * A wrapper class for WP-CLI's static methods for easier testing.
 *
 * @package WooCommerce\Migrator\CLI
 */

declare( strict_types=1 );

namespace WooCommerce\Migrator\CLI;

use WP_CLI;

/**
 * Handles the registration of commands with WP-CLI.
 */
class CommandRegistrar {

	/**
	 * A simple wrapper for WP_CLI::add_command.
	 *
	 * @param string $name       The name of the command.
	 * @param mixed  $callback   The callable to execute for the command.
	 * @param array  $args       Optional args to pass to add_command.
	 */
	public function add_command( $name, $callback, $args = array() ) {
		WP_CLI::add_command( $name, $callback, $args );
	}
}
