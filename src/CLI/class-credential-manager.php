<?php
/**
 * Credential Manager class
 *
 * @package WooCommerce\Migrator\CLI
 */

declare( strict_types=1 );

namespace WooCommerce\Migrator\CLI;

use WP_CLI;

/**
 * Manages platform credentials.
 */
class CredentialManager {

	/**
	 * The slug of the platform (e.g., 'shopify').
	 *
	 * @var string
	 */
	private string $platform_slug;

	/**
	 * The WordPress option name for storing credentials.
	 *
	 * @var string
	 */
	private string $option_name;

	/**
	 * Constructor.
	 *
	 * @param string $platform_slug The slug for the platform.
	 */
	public function __construct( string $platform_slug ) {
		$this->platform_slug = $platform_slug;
		$this->option_name   = "wc_migrator_credentials_{$platform_slug}";
	}

	/**
	 * Retrieves the stored credentials.
	 *
	 * @return array|null An associative array of credentials, or null if not found.
	 */
	public function get_credentials(): ?array {
		$credentials_json = get_option( $this->option_name, false );
		if ( ! $credentials_json ) {
			return null;
		}

		$credentials = json_decode( $credentials_json, true );

		return is_array( $credentials ) ? $credentials : null;
	}

	/**
	 * Checks if credentials exist.
	 *
	 * @return bool True if credentials exist, false otherwise.
	 */
	public function has_credentials(): bool {
		$credentials = $this->get_credentials();

		return ! empty( $credentials );
	}

	/**
	 * Prompts the user for credentials via the command line.
	 *
	 * @param array $fields An associative array of fields to prompt for.
	 *
	 * @return array The collected credentials.
	 */
	public function prompt_for_credentials( array $fields ): array {
		$credentials = array();
		foreach ( $fields as $key => $prompt ) {
			$credentials[ $key ] = $this->readline( $prompt . ' ' );
		}

		return $credentials;
	}

	/**
	 * Saves credentials to the database.
	 *
	 * @param array $credentials An associative array of credentials.
	 */
	public function save_credentials( array $credentials ): void {
		update_option( $this->option_name, wp_json_encode( $credentials ) );
	}

	/**
	 * Deletes credentials from the database.
	 */
	public function delete_credentials(): void {
		delete_option( $this->option_name );
	}

	/**
	 * Reads a line from STDIN.
	 *
	 * A backward-compatible wrapper for WP_CLI::readline().
	 *
	 * @param string $prompt The prompt to show to the user.
	 *
	 * @return string
	 */
	private function readline( string $prompt ): string {
		if ( method_exists( 'WP_CLI', 'readline' ) ) {
			return WP_CLI::readline( $prompt );
		}

		WP_CLI::line( $prompt );
		return trim( fgets( STDIN ) );
	}
}
