<?php
/**
 * Class CLITest
 *
 * @package WooCommerce\Migrator
 */

declare( strict_types=1 );

namespace WooCommerce\Migrator\Tests;

use WooCommerce\Migrator\CLI\CLI;
use WooCommerce\Migrator\CLI\CommandRegistrar;
use WooCommerce\Migrator\CLI\Commands\ProductsCommand;

/**
 * Test cases for CLI command registration.
 */
class CLITest extends TestCase {

	/**
	 * Test that the register_commands method registers the products command.
	 */
	public function test_register_commands_adds_products_command() {
		// Create a mock of the CommandRegistrar.
		$registrar = $this->createMock( CommandRegistrar::class );

		// Expect the add_command method to be called once with the correct parameters.
		$registrar->expects( $this->once() )
			->method( 'add_command' )
			->with(
				'wc migrate products',
				ProductsCommand::class
			);

		// Instantiate the CLI class with the mock registrar and call the method.
		$cli = new CLI( $registrar );
		$cli->register_commands();
	}
}
