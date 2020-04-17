<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'getallheaders' ) ) {
	function getallheaders() {
		if ( ! is_array( $_SERVER ) ) {
			return array();
		}

		$headers = array();
		foreach ( $_SERVER as $name => $value ) {
			if ( substr( $name, 0, 5 ) == 'HTTP_' ) {
				$headers[ str_replace( ' ', '-', ucwords( strtolower( str_replace( '_', ' ', substr( $name, 5 ) ) ) ) ) ] = $value;
			}
		}

		return $headers;
	}
}

if ( ! function_exists( 'pppbr_needs_cpf' ) ) {
	function pppbr_needs_cpf() {
		return function_exists( 'get_woocommerce_currency' ) ? get_woocommerce_currency() === 'BRL' : false;
	}
}