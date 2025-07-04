<?php
/**
 * Custom exception for the migrator.
 *
 * @package WooCommerce\Migrator\Exceptions
 */

namespace WooCommerce\Migrator\Exceptions;

/**
 * Represents an error that occurs during the data migration process.
 */
class MigratorException extends \Exception {

	/**
	 * A string-based error code.
	 *
	 * @var string
	 */
	public $code_str;

	/**
	 * Constructor.
	 *
	 * @param string          $code_str A string-based error code.
	 * @param string          $message  The exception message.
	 * @param int             $code     The exception code.
	 * @param \Throwable|null $previous The previous throwable used for the exception chaining.
	 */
	public function __construct( $code_str = '', $message = '', $code = 0, $previous = null ) {
		parent::__construct( $message, $code, $previous );
		$this->code_str = $code_str;
	}
}