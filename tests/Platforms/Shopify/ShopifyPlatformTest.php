<?php
/**
 * Shopify Platform Test
 *
 * @package WooCommerce\Migrator\Tests\Platforms\Shopify
 */

declare( strict_types=1 );

namespace WooCommerce\Migrator\Tests\Platforms\Shopify;

use WooCommerce\Migrator\Core\PlatformRegistry;
use WooCommerce\Migrator\Platforms\Shopify\ShopifyFetcher;
use WooCommerce\Migrator\Platforms\Shopify\ShopifyMapper;
use WooCommerce\Migrator\Platforms\Shopify\ShopifyPlatform;
use WooCommerce\Migrator\Tests\TestCase;

/**
 * Test cases for Shopify platform registration and integration.
 */
class ShopifyPlatformTest extends TestCase {

	/**
	 * Set up each test.
	 *
	 * Simulates how an external Shopify platform plugin would register itself.
	 */
	protected function setUp(): void {
		parent::setUp();
		// Simulate external Shopify plugin initialization
		// In real-world usage, this would be done by the external Shopify plugin.
		ShopifyPlatform::init();
	}



	/**
	 * Clean up after each test.
	 */
	protected function tearDown(): void {
		parent::tearDown();
		// Remove all filters to ensure clean state.
		remove_all_filters( 'wc_migrator_register_platform' );
		// Reset the singleton to ensure a clean state for the next test.
		$this->reset_singleton( PlatformRegistry::class, 'instance' );
	}

	/**
	 * Test that the Shopify platform is registered correctly.
	 */
	public function test_shopify_platform_is_registered() {
		// Reset the singleton to force re-loading of platforms with our filter.
		$this->reset_singleton( PlatformRegistry::class, 'instance' );
		$registry = PlatformRegistry::get_instance();

		$platforms = $registry->get_platforms();

		// Assert that Shopify platform exists.
		$this->assertArrayHasKey( 'shopify', $platforms, 'Shopify platform should be registered.' );

		// Assert platform configuration is correct.
		$shopify_config = $platforms['shopify'];
		$this->assertEquals( 'Shopify', $shopify_config['name'], 'Shopify platform name should be "Shopify".' );
		$this->assertEquals( ShopifyFetcher::class, $shopify_config['fetcher'], 'Shopify fetcher class should be ShopifyFetcher.' );
		$this->assertEquals( ShopifyMapper::class, $shopify_config['mapper'], 'Shopify mapper class should be ShopifyMapper.' );
	}

	/**
	 * Test that the registry can successfully instantiate the Shopify fetcher.
	 */
	public function test_registry_can_instantiate_shopify_fetcher() {
		// Reset the singleton to force re-loading of platforms with our filter.
		$this->reset_singleton( PlatformRegistry::class, 'instance' );
		$registry = PlatformRegistry::get_instance();

		$fetcher = $registry->get_fetcher( 'shopify' );

		$this->assertInstanceOf( ShopifyFetcher::class, $fetcher, 'Registry should return a ShopifyFetcher instance.' );
	}

	/**
	 * Test that the registry can successfully instantiate the Shopify mapper.
	 */
	public function test_registry_can_instantiate_shopify_mapper() {
		// Reset the singleton to force re-loading of platforms with our filter.
		$this->reset_singleton( PlatformRegistry::class, 'instance' );
		$registry = PlatformRegistry::get_instance();

		$mapper = $registry->get_mapper( 'shopify' );

		$this->assertInstanceOf( ShopifyMapper::class, $mapper, 'Registry should return a ShopifyMapper instance.' );
	}

	/**
	 * Test that ShopifyFetcher implements the correct interface and returns expected stub data.
	 */
	public function test_shopify_fetcher_returns_stub_data() {
		$fetcher = new ShopifyFetcher();

		// Test fetch_batch method returns expected structure.
		$batch_result   = $fetcher->fetch_batch( array() );
		$expected_batch = array(
			'items'       => array(),
			'cursor'      => null,
			'hasNextPage' => false,
		);
		$this->assertEquals( $expected_batch, $batch_result, 'ShopifyFetcher should return stub batch data.' );

		// Test fetch_total_count method returns 0.
		$count_result = $fetcher->fetch_total_count( array() );
		$this->assertEquals( 0, $count_result, 'ShopifyFetcher should return 0 for total count.' );
	}

	/**
	 * Test that ShopifyMapper implements the correct interface and returns expected stub data.
	 */
	public function test_shopify_mapper_returns_stub_data() {
		$mapper = new ShopifyMapper();

		// Create a mock platform data object.
		$platform_data = (object) array( 'id' => 'test_product_id' );

		// Test map_product_data method returns empty array.
		$mapped_result = $mapper->map_product_data( $platform_data );
		$this->assertEquals( array(), $mapped_result, 'ShopifyMapper should return empty array for stub implementation.' );
	}

	/**
	 * Test that the list command shows the registered Shopify platform.
	 */
	public function test_list_command_shows_shopify_platform() {
		// Reset the singleton to force re-loading of platforms with our filter.
		$this->reset_singleton( PlatformRegistry::class, 'instance' );
		$registry = PlatformRegistry::get_instance();

		// Verify the platform data that the list command would display.
		$platforms = $registry->get_platforms();
		$this->assertArrayHasKey( 'shopify', $platforms, 'Shopify platform should be available for listing.' );

		// Verify the platform data structure matches what List_Command expects.
		$shopify_config = $platforms['shopify'];
		$expected_list_item = array(
			'id'      => 'shopify',
			'name'    => 'Shopify',
			'fetcher' => ShopifyFetcher::class,
			'mapper'  => ShopifyMapper::class,
		);

		$actual_list_item = array(
			'id'      => 'shopify',
			'name'    => $shopify_config['name'] ?? '',
			'fetcher' => $shopify_config['fetcher'] ?? '',
			'mapper'  => $shopify_config['mapper'] ?? '',
		);

		$this->assertEquals( $expected_list_item, $actual_list_item, 'Platform data should be correctly formatted for list command.' );

		// Test that the List_Command can access platform data (the core functionality).
		// Note: We don't test the actual WP_CLI output formatting as that's external to our code.
		$list_command = new List_Command();
		$this->assertInstanceOf( List_Command::class, $list_command, 'List_Command should be instantiable.' );

		// Verify that platforms are not empty (which would trigger the "No platforms" message).
		$this->assertNotEmpty( $platforms, 'Platform registry should contain registered platforms for list command.' );
	}
}
