<?php
/**
 * Main class to register WP-CLI commands.
 *
 * @package WooCommerce\Migrator\CLI
 */

namespace WooCommerce\Migrator\CLI;

use WP_CLI;

/**
 * Main class for registering the WP-CLI commands.
 */
class CLI {

	/**
	 * The command registrar.
	 *
	 * @var Command_Registrar
	 */
	private $registrar;

	/**
	 * Constructor.
	 *
	 * @param Command_Registrar $registrar The command registrar.
	 */
	public function __construct( Command_Registrar $registrar ) {
		$this->registrar = $registrar;
	}

	/**
	 * Registers the WP-CLI commands.
	 */
	public function register_commands() {
		if ( ! class_exists( 'WP_CLI' ) ) {
			return;
		}

		$this->registrar->add_command(
			'wc migrate products',
			[ $this, 'products_command_handler' ],
			[
				'shortdesc' => 'Migrates products from a source platform to WooCommerce.',
				'longdesc'  => '## EXAMPLES' . "\n\n" . 'wp wc migrate products --platform=shopify',
			]
		);
	}

	/**
	 * The handler for the `wc migrate products` command.
	 *
	 * This is a placeholder and will be replaced by the controller logic.
	 *
	 * @param array $args       Positional arguments.
	 * @param array $assoc_args Associative arguments.
	 */
	public function products_command_handler( $args, $assoc_args ) {
		// The logic will be handled by the Products_Controller.
		// For now, we just show a success message.
		WP_CLI::success( 'Product migration command registered successfully!' );
	}
} 