<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WC_PPP_Brasil_API_Exception extends Exception {
	protected $error_code;
	protected $data;

	public function __construct( $error_code = '', $error_message = '', $http_status_code = null, $data = null ) {
		$this->error_code = $error_code;
		$this->data       = $data;
		parent::__construct( $error_message, $http_status_code );
	}

	public function getErrorCode() {
		return $this->error_code;
	}

	public function getData() {
		return $this->data;
	}
}