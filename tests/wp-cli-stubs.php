<?php
/**
 * Stubs for WP-CLI classes and functions for testing purposes.
 *
 * @package WooCommerce\Migrator\Tests
 */

// phpcs:disable Squiz.Commenting.ClassComment.Missing, Squiz.Commenting.FunctionComment.Missing, Generic.CodeAnalysis.UnusedFunctionParameter.Found, WordPress.Security.EscapeOutput.ExceptionNotEscaped

if ( ! class_exists( 'WP_CLI' ) ) {
	class WP_CLI {
		private static $readline_returns = array();

		public static function set_readline_returns( array $values ) {
			self::$readline_returns = $values;
		}

		public static function reset() {
			self::$readline_returns = array();
		}

		public static function success( $message ) {}
		public static function warning( $message ) {}
		public static function error( $message ) {
			throw new WP_CLI_ExitException( $message );
		}
		public static function log( $message ) {}
		public static function readline( $prompt ) {
			return array_shift( self::$readline_returns );
		}
	}
}

// phpcs:enable
