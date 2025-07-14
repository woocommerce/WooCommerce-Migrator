<?php
/**
 * Shopify Platform Registration
 *
 * @package WooCommerce\Migrator\Platforms\Shopify
 */

declare( strict_types=1 );

namespace WooCommerce\Migrator\Platforms\Shopify;

defined( 'ABSPATH' ) || exit;

/**
 * ShopifyPlatform class.
 *
 * This class handles the registration of the Shopify platform with the
 * WooCommerce Migrator's platform registry system.
 */
class ShopifyPlatform {

	/**
	 * Initializes the Shopify platform registration.
	 */
	public static function init(): void {
		self::load_dependencies();
		add_filter( 'wc_migrator_register_platform', array( self::class, 'register_platform' ) );
	}

	/**
	 * Loads the required Shopify platform dependencies.
	 */
	private static function load_dependencies(): void {
		$base_dir = __DIR__;

		if ( file_exists( $base_dir . '/class-shopify-fetcher.php' ) ) {
			require_once $base_dir . '/class-shopify-fetcher.php';
		}

		if ( file_exists( $base_dir . '/class-shopify-mapper.php' ) ) {
			require_once $base_dir . '/class-shopify-mapper.php';
		}
	}

	/**
	 * Registers the Shopify platform with the migrator system.
	 *
	 * @param array $platforms Array of registered platforms.
	 *
	 * @return array Updated array of platforms including Shopify.
	 */
	public static function register_platform( array $platforms ): array {
		$platforms['shopify'] = array(
			'name'    => 'Shopify',
			'fetcher' => ShopifyFetcher::class,
			'mapper'  => ShopifyMapper::class,
		);

		return $platforms;
	}
}

// Auto-initialize when this file is loaded (external plugin pattern).
ShopifyPlatform::init();
