<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_PPP_Brasil_API {

	/**
	 * @var WC_PPP_Brasil_Gateway
	 */
	private $gateway;

	private $base_url = 'https://api.paypal.com/v1';
	private $base_url_sandbox = 'https://api.sandbox.paypal.com/v1';
	private $access_token_transient_key = 'wc_ppp_brasil_api_access_token';

	private $timeout = 10;

	public function __construct( $gateway ) {
		$this->gateway = $gateway;
	}

	private function get_endpoint_url( $endpoint ) {
		return ( $this->gateway->mode === 'sandbox' ? $this->base_url_sandbox : $this->base_url ) . $endpoint;
	}

	/**
	 * Get access token.
	 *
	 * @return array|WP_Error
	 * @throws WC_PPP_Brasil_API_Exception
	 */
	public function get_access_token( $force = false ) {
		$endpoint   = $this->get_endpoint_url( '/oauth2/token' );
		$basic_auth = 'Basic ' . base64_encode( $this->gateway->client_id . ':' . $this->gateway->client_secret );

		$transient = get_transient( $this->access_token_transient_key );

		// If there's any token in transients, return it.
		if ( ! $force && $transient && $transient['basic_auth'] === $basic_auth ) {
			return $transient['access_token'];
		}

		$headers = array(
			'Accept'                        => 'application/json',
			'Accept-Language'               => 'pt_BR',
			'Authorization'                 => $basic_auth,
			'Content-Type'                  => 'application/x-www-form-urlencoded',
			'PayPal-Partner-Attribution-Id' => 'WooCommerceBR_Ecom_PPPlus',
		);

		// Get response.
		$response      = wp_remote_post( $endpoint, array(
			'headers' => $headers,
			'body'    => 'grant_type=client_credentials',
			'timeout' => $this->timeout,
		) );
		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );

		// Check if is WP_Error
		if ( is_wp_error( $response ) ) {
			$this->gateway->add_notice( __( 'Houve um erro de conexão ao obter o access token.', 'paypal-plus-brasil' ) );
			throw new WC_PPP_Brasil_API_Exception( $response->get_error_code(), $response->get_error_message(), null, $response_body );
		}

		$code = wp_remote_retrieve_response_code( $response );

		// Check if response was ok.
		if ( $code === 200 ) {
			$transient_value = array(
				'basic_auth'   => $basic_auth,
				'access_token' => $response_body['access_token'],
			);
			set_transient( $this->access_token_transient_key, $transient_value, $response_body['expires_in'] );

			return $response_body['access_token'];
		}

		$this->gateway->add_notice( __( 'Houve um erro ao obter o access token.', 'paypal-plus-brasil' ) );
		throw new WC_PPP_Brasil_API_Exception( $response_body['name'], $response_body['message'], $code, $response_body );
	}

	/**
	 * Create a payment.
	 *
	 * @param $body
	 *
	 * @return mixed
	 * @throws WC_PPP_Brasil_API_Exception
	 */
	public function create_payment( $body ) {
		$endpoint      = $this->get_endpoint_url( '/payments/payment' );
		$authorization = $this->get_access_token();
		$headers       = array(
			'Authorization' => 'Bearer ' . $authorization,
			'Content-Type'  => 'application/json',
		);

		// Get response.
		$response      = wp_remote_post( $endpoint, array(
			'headers' => $headers,
			'body'    => json_encode( $body, JSON_UNESCAPED_SLASHES ),
			'timeout' => $this->timeout,
		) );
		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );

		// Check if is WP_Error
		if ( is_wp_error( $response ) ) {
			throw new WC_PPP_Brasil_API_Exception( $response->get_error_code(), $response->get_error_message(), null, $response_body );
		}

		$code = wp_remote_retrieve_response_code( $response );

		// Check if response was created.
		if ( $code === 201 ) {
			return $response_body;
		}

		throw new WC_PPP_Brasil_API_Exception( $response_body['name'], $response_body['message'], $code, $response_body );
	}

	/**
	 * Execute a payment.
	 *
	 * @param $payment_id
	 * @param $payer_id
	 *
	 * @return array|mixed|object
	 * @throws WC_PPP_Brasil_API_Exception
	 */
	public function execute_payment( $payment_id, $payer_id ) {
		$endpoint      = $this->get_endpoint_url( '/payments/payment/' . $payment_id . '/execute' );
		$authorization = $this->get_access_token();
		$headers       = array(
			'Authorization' => 'Bearer ' . $authorization,
			'Content-Type'  => 'application/json',
		);
		$body          = array(
			'payer_id' => $payer_id,
		);

		// Get response.
		$response      = wp_remote_post( $endpoint, array(
			'headers' => $headers,
			'body'    => json_encode( $body,JSON_UNESCAPED_SLASHES ),
			'timeout' => 10,
		) );
		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );

		// Check if is WP_Error
		if ( is_wp_error( $response ) ) {
			throw new WC_PPP_Brasil_API_Exception( $response->get_error_code(), $response->get_error_message(), null, $response_body );
		}

		$code = wp_remote_retrieve_response_code( $response );

		// Check if response was created.
		if ( $code === 200 ) {
			return $response_body;
		}

		throw new WC_PPP_Brasil_API_Exception( $response_body['name'], $response_body['message'], $code, $response_body );
	}

	/**
	 * @param $payment_id
	 * @param $body
	 *
	 * @return array|mixed|object
	 * @throws WC_PPP_Brasil_API_Exception
	 */
	public function patch_payment( $payment_id, $body ) {
		$endpoint      = $this->get_endpoint_url( '/payments/payment/' . $payment_id );
		$authorization = $this->get_access_token();
		$headers       = array(
			'Authorization' => 'Bearer ' . $authorization,
			'Content-Type'  => 'application/json',
		);

		// Get response.
		$response      = wp_remote_request( $endpoint, array(
			'method'  => 'PATCH',
			'headers' => $headers,
			'body'    => json_encode( $body,JSON_UNESCAPED_SLASHES )
		) );
		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );

		// Check if is WP_Error
		if ( is_wp_error( $response ) ) {
			throw new WC_PPP_Brasil_API_Exception( $response->get_error_code(), $response->get_error_message(), null, $response_body );
		}

		$code = wp_remote_retrieve_response_code( $response );

		// Check if response was created.
		if ( $code === 200 ) {
			return $response_body;
		}

		throw new WC_PPP_Brasil_API_Exception( $response_body['name'], $response_body['message'], $code, $response_body );
	}

	/**
	 * Refund a payment.
	 *
	 * @param $payment_id
	 *
	 * @param null $total
	 * @param null $currency
	 *
	 * @return array|mixed|object
	 * @throws WC_PPP_Brasil_API_Exception
	 */
	public function refund_payment( $payment_id, $total = null, $currency = null ) {
		$endpoint      = $this->get_endpoint_url( '/payments/sale/' . $payment_id . '/refund' );
		$authorization = $this->get_access_token();
		$headers       = array(
			'Authorization' => 'Bearer ' . $authorization,
			'Content-Type'  => 'application/json',
		);

		// Body is default empty for full refund.
		$body = array();

		// If is set total, it's a partial refund.
		if ( $total !== null ) {
			$body = array(
				'amount' => array(
					'total'    => $total,
					'currency' => $currency,
				),
			);
		}

		// Get response.
		$response      = wp_remote_post( $endpoint, array(
			'headers' => $headers,
			'body'    => json_encode( $body,JSON_UNESCAPED_SLASHES ),
			'timeout' => $this->timeout,
		) );
		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );

		// Check if is WP_Error
		if ( is_wp_error( $response ) ) {
			throw new WC_PPP_Brasil_API_Exception( $response->get_error_code(), $response->get_error_message(), null, $response_body );
		}

		$code = wp_remote_retrieve_response_code( $response );

		// Check if response was ok.
		if ( $code === 201 ) {
			return $response_body;
		}

		throw new WC_PPP_Brasil_API_Exception( $response_body['name'], $response_body['message'], $code, $response_body );
	}

	/**
	 * Get webhook list.
	 *
	 * @return array
	 * @throws WC_PPP_Brasil_API_Exception
	 */
	public function get_webhooks() {
		$endpoint = $this->get_endpoint_url( '/notifications/webhooks' );

		$authorization = $this->get_access_token();
		$headers       = array(
			'Authorization' => 'Bearer ' . $authorization,
			'Content-Type'  => 'application/json',
		);

		// Get response.
		/** @var WP_Error|array $response */
		$response      = wp_remote_get( $endpoint, array(
			'headers' => $headers,
			'timeout' => $this->timeout,
		) );
		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );

		// Check if is WP_Error
		if ( is_wp_error( $response ) ) {
			throw new WC_PPP_Brasil_API_Exception( $response->get_error_code(), $response->get_error_message(), null, $response_body );
		}

		$code = wp_remote_retrieve_response_code( $response );

		// Check if response was ok.
		if ( $code === 200 ) {
			return $response_body;
		}

		throw new WC_PPP_Brasil_API_Exception( $response_body['name'], $response_body['message'], $code, $response_body );
	}

	/**
	 * Create a webhook events.
	 *
	 * @param $url
	 * @param $events
	 *
	 * @return array|mixed|object
	 * @throws WC_PPP_Brasil_API_Exception
	 */
	public function create_webhook( $url, $events ) {
		$endpoint      = $this->get_endpoint_url( '/notifications/webhooks' );
		$authorization = $this->get_access_token();
		$headers       = array(
			'Authorization' => 'Bearer ' . $authorization,
			'Content-Type'  => 'application/json',
		);

		// Set body.
		$body = array(
			'url'         => $url,
			'event_types' => array(),
		);

		// Add events.
		foreach ( $events as $event ) {
			$body['event_types'][] = array(
				'name' => $event,
			);
		}

		// Get response.
		$response      = wp_remote_post( $endpoint, array(
			'headers' => $headers,
			'body'    => json_encode( $body,JSON_UNESCAPED_SLASHES ),
			'timeout' => $this->timeout,
		) );
		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );

		// Check if is WP_Error
		if ( is_wp_error( $response ) ) {
			throw new WC_PPP_Brasil_API_Exception( $response->get_error_code(), $response->get_error_message(), null, $response_body );
		}

		$code = wp_remote_retrieve_response_code( $response );

		// Check if response was ok.
		if ( $code === 201 ) {
			return $response_body;
		}

		throw new WC_PPP_Brasil_API_Exception( $response_body['name'], $response_body['message'], $code, $response_body );
	}

	/**
	 * @param $body
	 *
	 * @return array|mixed|object
	 * @throws WC_PPP_Brasil_API_Exception
	 */
	public function verify_signature( $body ) {
		$endpoint      = $this->get_endpoint_url( '/notifications/verify-webhook-signature' );
		$authorization = $this->get_access_token();
		$headers       = array(
			'Authorization' => 'Bearer ' . $authorization,
			'Content-Type'  => 'application/json',
		);

		// Get response.
		$response      = wp_remote_post( $endpoint, array(
			'headers' => $headers,
			'body'    => $body,
			'timeout' => $this->timeout,
		) );
		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );

		// Check if is WP_Error
		if ( is_wp_error( $response ) ) {
			throw new WC_PPP_Brasil_API_Exception( $response->get_error_code(), $response->get_error_message(), null, $response_body );
		}

		$code = wp_remote_retrieve_response_code( $response );

		// Check if response was ok.
		if ( $code === 200 ) {
			return $response_body;
		}

		throw new WC_PPP_Brasil_API_Exception( $response_body['name'], $response_body['message'], $code, $response_body );
	}

}