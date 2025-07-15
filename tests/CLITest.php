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
use WooCommerce\Migrator\CLI\Commands\ResetCommand;
use WooCommerce\Migrator\CLI\Commands\SetupCommand;

/**
 * Test cases for CLI command registration.
 */
class CLITest extends TestCase {

	/**
	 * Test that the register_commands method registers all commands.
	 */
	public function test_register_commands_adds_all_commands() {
		// Create a mock of the CommandRegistrar.
		$registrar = $this->createMock( CommandRegistrar::class );

		// Expect the add_command method to be called three times with the correct parameters.
		$registrar->expects( $this->exactly( 3 ) )
			->method( 'add_command' )
			->withConsecutive(
				array( 'wc migrate setup', SetupCommand::class, $this->anything() ),
				array( 'wc migrate reset', ResetCommand::class, $this->anything() ),
				array( 'wc migrate products', ProductsCommand::class, $this->anything() )
			);

		// Instantiate the CLI class with the mock registrar and call the method.
		$cli = new CLI( $registrar );
		$cli->register_commands();
	}
}
