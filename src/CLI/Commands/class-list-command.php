<?php
/**
 * Platforms List Command
 *
 * @package WooCommerce\Migrator\CLI\Commands
 */

declare( strict_types=1 );

namespace WooCommerce\Migrator\CLI\Commands;

use WooCommerce\Migrator\Core\PlatformRegistry;
use WP_CLI;

/**
 * Lists all registered migration platforms.
 */
class List_Command {

	/**
	 * Lists all registered migration platforms.
	 *
	 * ## EXAMPLES
	 *
	 *     $ wp wc migrate list
	 */
	public function __invoke() {

		$registry  = PlatformRegistry::get_instance();
		$platforms = $registry->get_platforms();

		if ( empty( $platforms ) ) {
			WP_CLI::line( 'No migration platforms are registered.' );
			return;
		}

		$formatted_items = array();
		foreach ( $platforms as $id => $details ) {
			$formatted_items[] = array(
				'id'      => $id,
				'name'    => $details['name'] ?? '',
				'fetcher' => $details['fetcher'] ?? '',
				'mapper'  => $details['mapper'] ?? '',
			);
		}

		WP_CLI\Utils\format_items(
			'table',
			$formatted_items,
			array( 'id', 'name', 'fetcher', 'mapper' )
		);
	}
}
