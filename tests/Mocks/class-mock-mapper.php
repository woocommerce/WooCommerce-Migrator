<?php
/**
 * Mock Mapper class for testing.
 *
 * @package WooCommerce\Migrator\Tests\Mocks
 */

namespace WooCommerce\Migrator\Tests\Mocks;

use WooCommerce\Migrator\ImporterCore\Interfaces\PlatformMapperInterface;

/**
 * A mock mapper class for testing purposes.
 */
class MockMapper implements PlatformMapperInterface {

	/**
	 * {@inheritdoc}
	 *
	 * @param object $platform_data The platform data.
	 */
	public function map_product_data( object $platform_data ): array {
		return array();
	}
}
