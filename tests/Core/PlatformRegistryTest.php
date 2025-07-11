<?php
/**
 * Platform Registry Test
 *
 * @package WooCommerce\Migrator\Tests\Core
 */

namespace WooCommerce\Migrator\Tests\Core;

use WooCommerce\Migrator\Core\PlatformRegistry;
use WooCommerce\Migrator\ImporterCore\Interfaces\PlatformFetcherInterface;
use WooCommerce\Migrator\ImporterCore\Interfaces\PlatformMapperInterface;
use WooCommerce\Migrator\Tests\TestCase;
use InvalidArgumentException;

/**
 * A mock fetcher class for testing purposes.
 */
class MockFetcher implements PlatformFetcherInterface {
	public function fetch_batch( array $args ): array {
		return [
			'items'       => [],
			'cursor'      => null,
			'hasNextPage' => false,
		];
	}

	public function fetch_total_count( array $args ): int {
		return 0;
	}
}

/**
 * A mock mapper class for testing purposes.
 */
class MockMapper implements PlatformMapperInterface {
	public function map_product_data( object $platform_data ): array {
		return [];
	}
}

/**
 * PlatformRegistryTest class.
 */
class PlatformRegistryTest extends TestCase {

	/**
	 * Test that the `get_instance` method always returns the same instance.
	 */
	public function test_singleton_instance() {
		$instance1 = PlatformRegistry::get_instance();
		$instance2 = PlatformRegistry::get_instance();
		$this->assertSame( $instance1, $instance2 );
	}

	/**
	 * Test platform registration and retrieval.
	 */
	public function test_platform_registration() {
		add_filter(
			'wc_migrator_register_platform',
			function ( $platforms ) {
				$platforms['test-platform'] = array(
					'name'    => 'Test Platform',
					'fetcher' => MockFetcher::class,
					'mapper'  => MockMapper::class,
				);
				return $platforms;
			}
		);

		// Reset the singleton to force re-loading of platforms.
		$this->reset_singleton( PlatformRegistry::class, 'instance' );
		$registry = PlatformRegistry::get_instance();

		$platforms = $registry->get_platforms();
		$this->assertArrayHasKey( 'test-platform', $platforms );
		$this->assertEquals( 'Test Platform', $platforms['test-platform']['name'] );
	}

	/**
	 * Test fetcher and mapper instantiation.
	 */
	public function test_get_fetcher_and_mapper() {
		add_filter(
			'wc_migrator_register_platform',
			function ( $platforms ) {
				$platforms['test-platform'] = array(
					'fetcher' => MockFetcher::class,
					'mapper'  => MockMapper::class,
				);
				return $platforms;
			}
		);

		$this->reset_singleton( PlatformRegistry::class, 'instance' );
		$registry = PlatformRegistry::get_instance();

		$fetcher = $registry->get_fetcher( 'test-platform' );
		$mapper  = $registry->get_mapper( 'test-platform' );

		$this->assertInstanceOf( MockFetcher::class, $fetcher );
		$this->assertInstanceOf( MockMapper::class, $mapper );
	}

	/**
	 * Test that an exception is thrown for an invalid fetcher platform.
	 */
	public function test_get_fetcher_for_invalid_platform_throws_exception() {
		$this->expectException( InvalidArgumentException::class );
		$registry = PlatformRegistry::get_instance();
		$registry->get_fetcher( 'non-existent-platform' );
	}

	/**
	 * Test that an exception is thrown for an invalid mapper platform.
	 */
	public function test_get_mapper_for_invalid_platform_throws_exception() {
		$this->expectException( InvalidArgumentException::class );
		$registry = PlatformRegistry::get_instance();
		$registry->get_mapper( 'non-existent-platform' );
	}

	/**
	 * Test that a platform with a missing fetcher or mapper is not registered.
	 */
	public function test_incomplete_platform_is_not_registered() {
		add_filter(
			'wc_migrator_register_platform',
			function ( $platforms ) {
				$platforms['incomplete-platform'] = array(
					'name' => 'Incomplete Platform',
				);
				return $platforms;
			}
		);

		$this->reset_singleton( PlatformRegistry::class, 'instance' );
		$registry = PlatformRegistry::get_instance();

		$this->assertNull( $registry->get_platform( 'incomplete-platform' ) );
	}

	/**
	 * Clean up filters after each test.
	 */
	public function tearDown() : void {
		parent::tearDown();
		remove_all_filters( 'wc_migrator_register_platform' );
		// Reset the singleton to ensure a clean state for the next test.
		$this->reset_singleton( PlatformRegistry::class, 'instance' );
	}
} 