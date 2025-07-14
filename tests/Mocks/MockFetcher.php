<?php
/**
 * Mock Fetcher class for testing.
 *
 * @package WooCommerce\Migrator\Tests\Mocks
 */

namespace WooCommerce\Migrator\Tests\Mocks;

use WooCommerce\Migrator\ImporterCore\Interfaces\PlatformFetcherInterface;

/**
 * A mock fetcher class for testing purposes.
 */
class MockFetcher implements PlatformFetcherInterface {

	/**
	 * {@inheritdoc}
	 *
	 * @param array $args Arguments for fetching.
	 */
	public function fetch_batch( array $args ): array {
		return array(
			'items'       => array(),
			'cursor'      => null,
			'hasNextPage' => false,
		);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @param array $args Arguments for fetching.
	 */
	public function fetch_total_count( array $args ): int {
		return 0;
	}
}
