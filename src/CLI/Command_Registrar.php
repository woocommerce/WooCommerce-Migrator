<?php
/**
 * A wrapper class for WP-CLI's static methods for easier testing.
 *
 * @package WooCommerce\Migrator\CLI
 */

namespace WooCommerce\Migrator\CLI;

use WP_CLI;

/**
 * Handles the registration of commands with WP-CLI.
 */
class Command_Registrar {

	/**
	 * A simple wrapper for WP_CLI::add_command.
	 *
	 * @param string $name       The name of the command.
	 * @param mixed  $callable   The callable to execute for the command.
	 * @param array  $args       Optional args to pass to add_command.
	 */
	public function add_command( $name, $callable, $args = [] ) {
		WP_CLI::add_command( $name, $callable, $args );
	}
} 