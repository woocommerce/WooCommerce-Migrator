<?php
/**
 * Class InterfaceTest
 *
 * @package WooCommerce\Migrator
 */

declare( strict_types=1 );

namespace WooCommerce\Migrator\Tests;

use WooCommerce\Migrator\ImporterCore\Interfaces\PlatformFetcherInterface;
use WooCommerce\Migrator\ImporterCore\Interfaces\PlatformMapperInterface;

/**
 * Test cases for the core interfaces.
 */
class InterfaceTest extends TestCase {

	/**
	 * Test that the PlatformFetcherInterface exists.
	 */
	public function test_platform_fetcher_interface_exists() {
		$this->assertTrue( interface_exists( PlatformFetcherInterface::class ) );
	}

	/**
	 * Test that the PlatformMapperInterface exists.
	 */
	public function test_platform_mapper_interface_exists() {
		$this->assertTrue( interface_exists( PlatformMapperInterface::class ) );
	}
}
