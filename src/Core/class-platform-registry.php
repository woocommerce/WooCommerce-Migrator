<?php
/**
 * Platform Registry
 *
 * @package WooCommerce\Migrator\Core
 */

namespace WooCommerce\Migrator\Core;

use InvalidArgumentException;
use WooCommerce\Migrator\ImporterCore\Interfaces\PlatformFetcherInterface;
use WooCommerce\Migrator\ImporterCore\Interfaces\PlatformMapperInterface;

defined( 'ABSPATH' ) || exit;

/**
 * PlatformRegistry class.
 *
 * This class is a Singleton responsible for loading and providing access to registered migration platforms.
 */
class PlatformRegistry {

	/**
	 * The single instance of the class.
	 *
	 * @var PlatformRegistry|null
	 */
	private static $instance = null;

	/**
	 * An array to hold the configuration for all registered platforms.
	 *
	 * @var array
	 */
	private $platforms = array();

	/**
	 * Private constructor to prevent direct instantiation.
	 */
	private function __construct() {
		$this->load_platforms();
	}

	/**
	 * Get the single instance of the class.
	 *
	 * @return PlatformRegistry
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Loads platforms discovered via a filter.
	 *
	 * It also validates that each registered platform provides both a fetcher and a mapper class.
	 */
	private function load_platforms() {
		$platforms = apply_filters( 'wc_migrator_register_platform', array() );

		if ( ! is_array( $platforms ) ) {
			return;
		}

		foreach ( $platforms as $platform_id => $config ) {
			if ( isset( $config['fetcher'], $config['mapper'] ) ) {
				$this->platforms[ $platform_id ] = $config;
			}
		}
	}

	/**
	 * Returns the entire array of registered platform configurations.
	 *
	 * @return array
	 */
	public function get_platforms() {
		return $this->platforms;
	}

	/**
	 * Returns the configuration array for a single, specified platform ID.
	 *
	 * @param string $platform_id The ID of the platform (e.g., 'shopify').
	 *
	 * @return array|null The platform configuration or null if not found.
	 */
	public function get_platform( $platform_id ) {
		return $this->platforms[ $platform_id ] ?? null;
	}

	/**
	 * Retrieves and instantiates the fetcher class for a given platform.
	 *
	 * @param string $platform_id The ID of the platform.
	 *
	 * @return PlatformFetcherInterface An instance of the platform's fetcher class.
	 *
	 * @throws InvalidArgumentException If the platform is not found or the fetcher class is invalid.
	 */
	public function get_fetcher( $platform_id ) {
		$platform = $this->get_platform( $platform_id );

		if ( ! $platform ) {
			throw new InvalidArgumentException( "Platform '$platform_id' not found." );
		}

		$fetcher_class = $platform['fetcher'];

		if ( ! class_exists( $fetcher_class ) || ! in_array( PlatformFetcherInterface::class, class_implements( $fetcher_class ), true ) ) {
			throw new InvalidArgumentException( "Invalid fetcher class for platform '$platform_id'." );
		}

		return new $fetcher_class();
	}

	/**
	 * Retrieves and instantiates the mapper class for a given platform.
	 *
	 * @param string $platform_id The ID of the platform.
	 *
	 * @return PlatformMapperInterface An instance of the platform's mapper class.
	 *
	 * @throws InvalidArgumentException If the platform is not found or the mapper class is invalid.
	 */
	public function get_mapper( $platform_id ) {
		$platform = $this->get_platform( $platform_id );

		if ( ! $platform ) {
			throw new InvalidArgumentException( "Platform '$platform_id' not found." );
		}

		$mapper_class = $platform['mapper'];

		if ( ! class_exists( $mapper_class ) || ! in_array( PlatformMapperInterface::class, class_implements( $mapper_class ), true ) ) {
			throw new InvalidArgumentException( "Invalid mapper class for platform '$platform_id'." );
		}

		return new $mapper_class();
	}
}
