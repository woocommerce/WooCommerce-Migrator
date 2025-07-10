<?php
/**
 * Plugin Name:     WooCommerce Migrator
 * Plugin URI:      https://github.com/woocommerce/woocommerce-migrator
 * Description:     CLI commands to migrate data to WooCommerce from other platforms.
 * Version:         1.0.0
 * Author:          WooCommerce
 * Author URI:      https://woocommerce.com
 * License:         GPL-3.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:     woocommerce-migrator
 *
 * @package         WooCommerce\Migrator
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Check if WP-CLI is running.
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	return;
}

// Autoload dependencies.
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

add_action(
	'cli_init',
	function () {
		$registrar = new WooCommerce\Migrator\CLI\CommandRegistrar();
		$cli       = new WooCommerce\Migrator\CLI\CLI( $registrar );
		$cli->register_commands();
	}
);
