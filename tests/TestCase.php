<?php
/**
 * Base test case
 *
 * @package WooCommerce\Migrator\Tests
 */

declare( strict_types=1 );

namespace WooCommerce\Migrator\Tests;

use ReflectionClass;
use ReflectionException;

/**
 * Our main test case class.
 */
abstract class TestCase extends \PHPUnit\Framework\TestCase {

	/**
	 * Resets a singleton instance.
	 *
	 * @param string $class_name      The class name of the singleton.
	 * @param string $property_name The name of the static property holding the instance.
	 *
	 * @throws ReflectionException If the class or property does not exist.
	 */
	protected function reset_singleton( string $class_name, string $property_name ): void {
		$reflection = new ReflectionClass( $class_name );
		$property   = $reflection->getProperty( $property_name );
		$property->setAccessible( true );
		$property->setValue( null, null );
		$property->setAccessible( false );
	}
}
