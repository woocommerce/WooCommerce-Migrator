<?php
/**
 * Main class to register WP-CLI commands.
 *
 * @package WooCommerce\Migrator\CLI
 */

declare( strict_types=1 );

namespace WooCommerce\Migrator\CLI;

use WooCommerce\Migrator\CLI\Commands\ProductsCommand;

/**
 * Main class for registering the WP-CLI commands.
 */
class CLI {

	/**
	 * The command registrar.
	 *
	 * @var CommandRegistrar
	 */
	private $registrar;

	/**
	 * Constructor.
	 *
	 * @param CommandRegistrar $registrar The command registrar.
	 */
	public function __construct( CommandRegistrar $registrar ) {
		$this->registrar = $registrar;
	}

	/**
	 * Registers the WP-CLI commands.
	 */
	public function register_commands() {
		$this->registrar->add_command(
			'wc migrate products',
			ProductsCommand::class,
			array(
				'shortdesc' => 'Migrates products from a source platform to WooCommerce.',
				'longdesc'  => '## EXAMPLES' . "\n\n" . 'wp wc migrate products --platform=shopify',
			)
		);
	}
}
