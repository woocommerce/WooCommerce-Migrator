<?php
/**
 * Class CLITest
 *
 * @package WooCommerce\Migrator
 */

use WooCommerce\Migrator\CLI\CLI;

/**
 * Test cases for CLI command registration.
 */
class CLITest extends \PHPUnit\Framework\TestCase {

	/**
	 * Test that the handler method for the products command exists.
	 */
	public function test_products_command_handler_exists() {
		$this->assertTrue(
			method_exists( CLI::class, 'products_command_handler' ),
			'The products_command_handler method should exist on the CLI class.'
		);
	}
} 