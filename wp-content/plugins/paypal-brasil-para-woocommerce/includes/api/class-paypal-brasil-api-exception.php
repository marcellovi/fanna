<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class paypal_brasil_Api_Exception.
 */
class paypal_brasil_Api_Exception extends Exception {
	protected $error_code;
	protected $data;

	/**
	 * paypal_brasil_Api_Exception constructor.
	 *
	 * @param mixed $error_code
	 * @param string $error_message
	 * @param mixed $data
	 */
	public function __construct( $error_code = '', $error_message = '', $data = null ) {
		$this->error_code = $error_code;
		$this->data       = $data;
		parent::__construct( $error_message, $error_code );
	}

	/**
	 * Get the error code.
	 *
	 * @return mixed
	 */
	public function getErrorCode() {
		return $this->error_code;
	}

	/**
	 * Get error data.
	 * @return mixed
	 */
	public function getData() {
		return $this->data;
	}
}