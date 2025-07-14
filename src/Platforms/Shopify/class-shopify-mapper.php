<?php
/**
 * Shopify Mapper
 *
 * @package WooCommerce\Migrator\Platforms\Shopify
 */

declare( strict_types=1 );

namespace WooCommerce\Migrator\Platforms\Shopify;

use WooCommerce\Migrator\ImporterCore\Interfaces\PlatformMapperInterface;

defined( 'ABSPATH' ) || exit;

/**
 * ShopifyMapper class.
 *
 * This class is responsible for transforming raw Shopify product data
 * into a standardized format suitable for the WooCommerce Importer.
 * Currently contains stub implementations that will be replaced with actual
 * data mapping logic in future PRs.
 */
class ShopifyMapper implements PlatformMapperInterface {

	/**
	 * Maps raw Shopify product data to a standardized array format.
	 *
	 * @param object $platform_data The raw product data object from Shopify (e.g., Shopify product node).
	 *
	 * @return array A standardized array representing the product, understandable by the WooCommerce_Product_Importer.
	 */
	public function map_product_data( object $platform_data ): array {
		// Stub implementation - will be replaced with actual Shopify to WooCommerce data mapping.
		return array();
	}
}
