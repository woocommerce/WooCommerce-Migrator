<?php
/**
 * Credential Flow Test
 *
 * @package WooCommerce\Migrator\Tests
 */

declare( strict_types=1 );

namespace WooCommerce\Migrator\Tests;

use WooCommerce\Migrator\CLI\Commands\ProductsCommand;
use WooCommerce\Migrator\CLI\Commands\ResetCommand;
use WooCommerce\Migrator\CLI\Commands\SetupCommand;
use WP_CLI;

/**
 * Test cases for the credential management flow.
 */
class CredentialFlowTest extends TestCase {

	/**
	 * The name of the option used to store Shopify credentials.
	 */
	const SHOPIFY_OPTION_NAME = 'wc_migrator_credentials_shopify';

	/**
	 * Clean up options after each test.
	 */
	public function tearDown(): void {
		parent::tearDown();
		delete_option( self::SHOPIFY_OPTION_NAME );
		WP_CLI::reset();
	}

	/**
	 * Test that the reset command deletes the credentials.
	 */
	public function test_reset_command_deletes_credentials() {
		// Arrange: Manually set the credential option.
		update_option( self::SHOPIFY_OPTION_NAME, wp_json_encode( array( 'api_key' => 'test' ) ) );
		$this->assertTrue( get_option( self::SHOPIFY_OPTION_NAME, false ) !== false );

		// Act: Execute the reset command.
		$command = new ResetCommand();
		$command( array(), array() );

		// Assert: Retrieve the option and assert that it no longer exists.
		$this->assertFalse( get_option( self::SHOPIFY_OPTION_NAME, false ) );
	}

	/**
	 * Test that the products command proceeds when credentials exist.
	 */
	public function test_products_command_proceeds_when_credentials_exist() {
		// Arrange: Manually set the credential option.
		update_option( self::SHOPIFY_OPTION_NAME, wp_json_encode( array( 'api_key' => 'test' ) ) );

		// Act & Assert: Execute the command and expect a success message.
		// We can't easily capture output, but we can confirm it doesn't error out.
		$command = new ProductsCommand();
		try {
			$command( array(), array() );
			// If we get here, no exception was thrown, which is what we want.
			$this->assertTrue( true, 'ProductsCommand should succeed when credentials exist.' );
		} catch ( \Exception $e ) {
			$this->fail( 'ProductsCommand threw an exception even though credentials exist.' );
		}
	}

	/**
	 * Test that the products command prompts and saves credentials if they do not exist.
	 */
	public function test_products_command_prompts_for_credentials_if_not_set() {
		// Arrange: Ensure the option is deleted.
		delete_option( self::SHOPIFY_OPTION_NAME );
		$this->assertFalse( get_option( self::SHOPIFY_OPTION_NAME, false ) );

		// Arrange: Set the mock return values for the readline prompt.
		WP_CLI::set_readline_returns(
			array(
				'test_api_key',
				'test-store.myshopify.com',
			)
		);

		// Act: Run the products command.
		$command = new ProductsCommand();
		$command( array(), array() );

		// Assert: Check that credentials have been saved.
		$saved_credentials_json = get_option( self::SHOPIFY_OPTION_NAME, false );
		$this->assertNotFalse( $saved_credentials_json );

		$expected_credentials = array(
			'api_key'  => 'test_api_key',
			'shop_url' => 'test-store.myshopify.com',
		);
		$this->assertEquals( $expected_credentials, json_decode( $saved_credentials_json, true ) );
	}

	/**
	 * Test that the setup command prompts for and saves credentials.
	 */
	public function test_setup_command_saves_credentials() {
		// Arrange: Ensure the option is deleted.
		delete_option( self::SHOPIFY_OPTION_NAME );
		$this->assertFalse( get_option( self::SHOPIFY_OPTION_NAME, false ) );

		// Arrange: Set the mock return values for the readline prompt.
		WP_CLI::set_readline_returns(
			array(
				'setup_api_key',
				'setup-store.myshopify.com',
			)
		);

		// Act: Run the setup command.
		$command = new SetupCommand();
		$command( array(), array() );

		// Assert: Check that credentials have been saved.
		$saved_credentials_json = get_option( self::SHOPIFY_OPTION_NAME, false );
		$this->assertNotFalse( $saved_credentials_json );

		$expected_credentials = array(
			'api_key'  => 'setup_api_key',
			'shop_url' => 'setup-store.myshopify.com',
		);
		$this->assertEquals( $expected_credentials, json_decode( $saved_credentials_json, true ) );
	}
}
