<?php

// Exit if not in WordPress.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Check if class already exists before create.
if ( ! class_exists( 'WC_PPP_Brasil_Gateway' ) ) {

	/**
	 * Class WC_PPP_Brasil_Gateway.
	 * @property string client_id
	 * @property string client_secret
	 * @property string $mode
	 * @property string webhook_id
	 * @property string debug
	 * @property WC_Logger log
	 * @property string wrong_credentials
	 * @property string form_height
	 * @property string invoice_id_prefix
	 * @property WC_PPP_Brasil_API api
	 */
	class WC_PPP_Brasil_Gateway extends WC_Payment_Gateway {

		/**
		 * WC_PPP_Brasil_Gateway constructor.
		 */
		public function __construct() {
			// Set default settings.
			$this->id                 = 'wc-ppp-brasil-gateway';
			$this->has_fields         = true;
			$this->method_title       = __( 'PayPal Plus Brasil', 'paypal-plus-brasil' );
			$this->method_description = __( 'Solução PayPal para pagamentos transparentes aonde utiliza-se apenas o Cartão de Crédito.', 'paypal-plus-brasil' );
			$this->supports           = array( 'products', 'refunds' );

			// Load settings fields.
			$this->init_form_fields();
			$this->init_settings();

			// Get options in variable.
			$this->title             = $this->get_option( 'title' );
			$this->client_id         = $this->get_option( 'client_id' );
			$this->client_secret     = $this->get_option( 'client_secret' );
			$this->webhook_id        = defined( 'WC_PPP_BRASIL_WEBHOOK_ID' ) && WC_PPP_BRASIL_WEBHOOK_ID ? WC_PPP_BRASIL_WEBHOOK_ID : $this->get_option( 'webhook_id' );
			$this->mode              = $this->get_option( 'mode' );
			$this->debug             = $this->get_option( 'debug' );
			$this->wrong_credentials = $this->get_option( 'wrong_credentials' );
			$this->form_height       = $this->get_option( 'form_height' );
			$this->invoice_id_prefix = $this->get_option( 'invoice_id_prefix', '' );

			// Start API.
			$this->api = new WC_PPP_Brasil_API( $this );

			// Active logs.
			if ( 'yes' == $this->debug ) {
				$this->log = new WC_Logger();
			}

			// Handler for IPN.
			add_action( 'woocommerce_api_' . $this->id, array( $this, 'webhook_handler' ) );

			// Update web experience profile id before actually saving.
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array(
				$this,
				'before_process_admin_options'
			), 1 );

			// Now save with the save hook.
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array(
				$this,
				'process_admin_options'
			), 10 );

			// Filter the save data to add a custom experience profile id.
			add_filter( 'woocommerce_settings_api_sanitized_fields_' . $this->id, array( $this, 'filter_save_data' ) );

			// Enqueue scripts.
			add_action( 'wp_enqueue_scripts', array( $this, 'checkout_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		}

		/**
		 * Check if the gateway is available for use.
		 *
		 * @return bool
		 */
		public function is_available() {
			$is_available = ( 'yes' === $this->enabled );

			if ( WC()->cart && 0 < $this->get_order_total() && 0 < $this->max_amount && $this->max_amount < $this->get_order_total() ) {
				$is_available = false;
			}

			if ( ! $this->client_id || ! $this->client_secret || ! $this->webhook_id || $this->wrong_credentials === 'yes' ) {
				$is_available = false;
			}

			return $is_available;
		}

		/**
		 * Set some settings before save the options.
		 */
		public function before_process_admin_options() {
			$client_id_key     = $this->get_field_key( 'client_id' );
			$client_secret_key = $this->get_field_key( 'client_secret' );
			$mode_key          = $this->get_field_key( 'mode' );

			// Update the client_id and client_secret with the posted data.
			$this->client_id     = isset( $_POST[ $client_id_key ] ) ? sanitize_text_field( trim( $_POST[ $client_id_key ] ) ) : '';
			$this->client_secret = isset( $_POST[ $client_secret_key ] ) ? sanitize_text_field( trim( $_POST[ $client_secret_key ] ) ) : '';
			$this->mode          = isset( $_POST[ $mode_key ] ) ? sanitize_text_field( $_POST[ $mode_key ] ) : '';

			// Validate credentials.
			$this->validate_credentials();

			// Update things.
			$this->update_webhooks();
		}

		/**
		 * Validate credentials when saving options page.s
		 */
		public function validate_credentials() {
			try {
				$this->api->get_access_token( true );
				$this->add_notice( __( 'Suas credenciais estão corretas, um novo token foi gerado.', 'paypal-plus-brasil' ), 'updated' );
			} catch ( WC_PPP_Brasil_API_Exception $ex ) {
				$this->log( '[backend] Error generating access token: ' . $this->print_r( $ex->getData(), true ) );
				// If is invalid credentials
				if ( $ex->getCode() == 401 ) {
					$this->wrong_credentials = 'yes';
					$this->add_notice( __( 'Suas credenciais estão inválidas. Verifique os dados informados e salve as configurações novamente.', 'paypal-plus-brasi' ) );
				} else {
					$this->log( __( 'Houve um erro ao gerar um novo access token. Verifique os logs para mais informações.', 'paypal-plus-brasil' ) );
				}
			}
		}

		/**
		 * Update the webhooks.
		 */
		public function update_webhooks() {
			// Set by default as not found.
			$webhook = null;
			// Check if has client_id and client_secret to connect and get webhooks.
			$this->log( 'Updating the webhooks' );

			try {
				$webhook_url = $this->get_webhook_url();

				// Get a list of webhooks
				$registered_webhooks = $this->api->get_webhooks();

				$this->log( 'Webhooks list: ' . $this->print_r( $registered_webhooks, true ) );

				foreach ( $registered_webhooks['webhooks'] as $registered_webhook ) {
					if ( $registered_webhook['url'] === $webhook_url ) {
						$this->log( 'Matched webhook: ' . $this->print_r( $registered_webhook, true ) );
						$webhook = $registered_webhook;
						break;
					}
				}

				// If no webhook matched, create a new one.
				if ( ! $webhook ) {
					$this->log( 'No webhook matched. Creating one.' );

					$webhook_url = $this->get_webhook_url();

					$events_types = array(
						'PAYMENT.SALE.COMPLETED',
						'PAYMENT.SALE.DENIED',
						'PAYMENT.SALE.PENDING',
						'PAYMENT.SALE.REFUNDED',
						'PAYMENT.SALE.REVERSED',
					);

					// Create webhook.
					$webhook_result = $this->api->create_webhook( $webhook_url, $events_types );

					// Set the webhook ID
					$this->log( 'Set webhook ID to: ' . $webhook_result['id'] );
					$this->webhook_id        = $webhook_result['id'];
					$this->wrong_credentials = 'no';

					return;
				}

				// Set the webhook ID
				$this->log( 'Set webhook ID to: ' . $webhook['id'] );
				$this->webhook_id        = $webhook['id'];
				$this->wrong_credentials = 'no';
			} catch ( WC_PPP_Brasil_API_Exception $ex ) {
				$uid_error = $this->unique_id();
				$this->log( 'Error #' . $uid_error );
				$this->log( 'Code: ' . $ex->getCode() );
				$this->log( $ex->getMessage() );
				$this->log( 'WC_PPP_Brasil_API_Exception: ' . $this->print_r( $ex->getData(), true ) );

				$this->add_notice( __( 'Houve um erro ao definir o webhook.', 'paypal-plus-brasil' ) );
			}

			// If we don't have a webhook, set as empty.ˆ
			if ( ! $webhook ) {
				$this->webhook_id = '';
			} else {
				$this->add_notice( __( 'O webhook foi definido com sucesso.', 'paypal-plus-brasil' ), 'updated' );
			}
		}

		/**
		 * Add the experience profile ID to save data.
		 *
		 * @param $settings
		 *
		 * @return mixed
		 */
		public function filter_save_data( $settings ) {
			if ( $this->wrong_credentials === 'yes' ) {
				$this->client_id           = '';
				$settings['client_id']     = $this->client_id;
				$this->client_secret       = '';
				$settings['client_secret'] = $this->client_secret;
				$this->webhook_id          = '';
				$settings['webhook_id']    = $this->webhook_id;
			}
			$settings['webhook_id']        = $this->webhook_id ? $this->webhook_id : '';
			$settings['wrong_credentials'] = $this->wrong_credentials ? $this->wrong_credentials : 'no';

			return $settings;
		}

		/**
		 * Get the store URL for gateway.
		 * @return string
		 */
		private function get_webhook_url() {
			$base_url = site_url();

			if ( defined( 'WC_PPP_BRASIL_WEBHOOK_URL' ) && WC_PPP_BRASIL_WEBHOOK_URL ) {
				$base_url = WC_PPP_BRASIL_WEBHOOK_URL;
			} else if ( $_SERVER['HTTP_HOST'] === 'localhost' ) {
				$base_url = 'https://example.com/';
			}

			return str_replace( 'http:', 'https:', add_query_arg( 'wc-api', $this->id, $base_url ) );
		}

		/**
		 * Init the admin form fields.
		 */
		public function init_form_fields() {
			$this->form_fields = array(
				'enabled'           => array(
					'title'   => __( 'Habilitar/Desabilitar', 'paypal-plus-brasil' ),
					'type'    => 'checkbox',
					'label'   => __( 'Habilitar', 'paypal-plus-brasil' ),
					'default' => 'no',
				),
				'title'             => array(
					'title'       => __( 'Nome de exibição', 'paypal-plus-brasil' ),
					'type'        => 'text',
					'default'     => '',
					'placeholder' => __( 'Exemplo: (Parcelado em até 12x)', 'paypal-plus-brasil' ),
					'description' => __( 'Será exibido no checkout: Cartão de Crédito (Parcelado em até 12x)', 'paypal-plus-brasil' ),
					'desc_tip'    => __( 'Por padrão a solução do PayPal Plus é exibida como “Cartão de Crédito”, utilize esta opção para definir um texto adicional como parcelamento ou descontos.', 'paypal-plus-brasil' ),
				),
				'mode'              => array(
					'title'       => __( 'Modo', 'paypal-plus-brasil' ),
					'type'        => 'select',
					'options'     => array(
						'live'    => __( 'Produção', 'paypal-plus-brasil' ),
						'sandbox' => __( 'Sandbox', 'paypal-plus-brasil' ),
					),
					'description' => __( 'Utilize esta opção para alternar entre os modos Sandbox e Produção. Sandbox é utilizado para testes e Produção para compras reais.', 'paypal-plus-brasil' ),
				),
				'client_id'         => array(
					'title'       => __( 'Client ID', 'paypal-plus-brasil' ),
					'type'        => 'text',
					'default'     => '',
					'description' => sprintf( __( 'Para gerar o Client ID acesse <a href="%s" target="_blank">aqui</a> e procure pela seção “REST API apps”.', 'paypal-plus-brasil' ), 'https://developer.paypal.com/docs/classic/lifecycle/sb_credentials/' ),

				),
				'client_secret'     => array(
					'title'       => __( 'Secret ID', 'paypal-plus-brasil' ),
					'type'        => 'text',
					'default'     => '',
					'description' => sprintf( __( 'Para gerar o Secret ID acesse <a href="%s" target="_blank">aqui</a> e procure pela seção “REST API apps”.', 'paypal-plus-brasil' ), 'https://developer.paypal.com/docs/classic/lifecycle/sb_credentials/' ),
				),
				'debug'             => array(
					'title'       => __( 'Modo depuração', 'paypal-plus-brasil' ),
					'type'        => 'checkbox',
					'label'       => __( 'Habilitar', 'paypal-plus-brasil' ),
					'desc_tip'    => __( 'Habilite este modo para depurar a aplicação em caso de homologação ou erros.', 'paypal-plus-brasil' ),
					'description' => sprintf( __( 'Os logs serão salvos no caminho: %s.', 'paypal-plus-brasil' ), $this->get_log_view() ),
				),
				'advanced_settings' => array(
					'title'       => __( 'Configurações avançadas', 'paypal-plus-brasil' ),
					'type'        => 'title',
					'description' => __( 'Utilize estas opções para customizar a experiência da solução.', 'paypal-plus-brasil' ),
				),
				'form_height'       => array(
					'title'       => __( 'Altura do formulário', 'paypal-plus-brasil' ),
					'type'        => 'text',
					'default'     => '',
					'placeholder' => __( 'px', 'paypal-plus-brasil' ),
					'description' => __( 'Utilize esta opção para definir uma altura máxima do formulário de cartão de crédito (será considerado um valor em pixels). Será aceito um valor em pixels entre 400 - 550.', 'paypal-plus-brasil' ),
				),
				'invoice_id_prefix' => array(
					'title'       => __( 'Prefixo de Invoice ID', 'paypal-plus-brasil' ),
					'type'        => 'text',
					'default'     => '',
					'description' => __( 'Adicione um prefixo as transações feitas com PayPal Plus na sua loja. Isso pode auxiliar caso trabalhe com a mesma conta PayPal em mais de um site.', 'paypal-plus-brasil' ),
				),
			);
		}

		/**
		 * Get log.
		 *
		 * @return string
		 */
		protected function get_log_view() {
			return '<a target="_blank" href="' . esc_url( admin_url( 'admin.php?page=wc-status&tab=logs&log_file=' . esc_attr( $this->id ) . '-' . sanitize_file_name( wp_hash( $this->id ) ) . '.log' ) ) . '">' . __( 'Status do Sistema &gt; Logs', 'paypal-plus-brasil' ) . '</a>';
		}

		/**
		 * Process the payment.
		 *
		 * @param int $order_id
		 *
		 * @param bool $force
		 *
		 * @return null|array
		 */
		public function process_payment( $order_id, $force = false ) {
			$this->log( 'Processing payment for order #' . $order_id );
			$order      = wc_get_order( $order_id );
			$session    = WC()->session->get( 'wc-ppp-brasil-payment-id' );
			$payment_id = $session['payment_id'];

			// Check if is a iframe error
			if ( isset( $_POST['wc-ppp-brasil-error'] ) && ! empty( $_POST['wc-ppp-brasil-error'] ) ) {
				switch ( $_POST['wc-ppp-brasil-error'] ) {
					case 'CARD_ATTEMPT_INVALID':
						wc_add_notice( __( 'Número de tentativas excedidas, por favor tente novamente. Se o erro persistir entre em contato.', 'paypal-plus-brasil' ), 'error' );
						break;
					case 'INTERNAL_SERVICE_ERROR':
					case 'SOCKET_HANG_UP':
					case 'socket hang up':
					case 'connect ECONNREFUSED':
					case 'connect ETIMEDOUT':
					case 'UNKNOWN_INTERNAL_ERROR':
					case 'fiWalletLifecycle_unknown_error':
					case 'Failed to decrypt term info':
						wc_add_notice( __( 'Ocorreu um erro inesperado, por favor tente novamente. Se o erro persistir entre em contato.', 'paypal-plus-brasil' ), 'error' );
						break;
					case 'RISK_N_DECLINE':
					case 'NO_VALID_FUNDING_SOURCE_OR_RISK_REFUSED':
					case 'TRY_ANOTHER_CARD':
					case 'NO_VALID_FUNDING_INSTRUMENT':
						wc_add_notice( __( 'Não foi possível processar o seu pagamento, tente novamente ou entre em contato contato com o PayPal (0800-047-4482).', 'paypal-plus-brasil' ), 'error' );
						break;
					case 'INVALID_OR_EXPIRED_TOKEN':
						wc_add_notice( __( 'Ocorreu um erro temporário. Por favor, preencha os dados novamente. Se o erro persistir, entre em contato.', 'paypal-plus-brasil' ), 'error' );
						break;
					default:
						wc_add_notice( __( 'Por favor revise as informações inseridas do cartão de crédito.', 'paypal-plus-brasil' ), 'error' );
						break;
				}

				// Set refresh totals to trigger update_checkout on frontend.
				WC()->session->set( 'refresh_totals', true );

				$uid_error = $this->unique_id();
				$this->log( 'Error #' . $uid_error );
				$this->log( 'Payment failed because an iframe error: ' . sanitize_text_field( $_POST['wc-ppp-brasil-error'] ) );

				do_action( 'wc_ppp_brasil_process_payment_error', 'IFRAME_ERROR', $order_id, $_POST['wc-ppp-brasil-error'] );

				return null;
			}

			// Prevent submit any dummy data.
			if ( WC()->session->get( 'wc-ppp-brasil-dummy-data' ) === true ) {
				wc_add_notice( __( 'You are not allowed to do that.', 'paypal-plus-brasil' ), 'error' );
				$this->log( 'Payment failed because was trying to pay with dummy data.' );

				// Set refresh totals to trigger update_checkout on frontend.
				WC()->session->set( 'refresh_totals', true );

				do_action( 'wc_ppp_brasil_process_payment_error', 'DUMMY_DATA', $order_id, null );

				return null;
			}

			// Check the payment id
			/**
			 * This error is caused by multiple requests that
			 */
			if ( ! $payment_id ) {
				wc_add_notice( __( 'Houve um erro interno ao processar o pagamento. Por favor, tente novamente. Se o erro persistir, entre em contato.', 'paypal-plus-brasil' ), 'error' );
				$this->log( 'Payment failed because was trying to pay with invalid payment ID.' );

				// Set refresh totals to trigger update_checkout on frontend.
				WC()->session->set( 'refresh_totals', true );

				do_action( 'wc_ppp_brasil_process_payment_error', 'SESSION_ERROR', $order_id, null );

				return null;
			}

			try {
				$iframe_data = isset( $_POST['wc-ppp-brasil-data'] ) ? json_decode( wp_unslash( $_POST['wc-ppp-brasil-data'] ), true ) : null;
				$this->log( 'Iframe init data: ' . $this->print_r( $iframe_data, true ) );
				$response_data = isset( $_POST['wc-ppp-brasil-response'] ) ? json_decode( wp_unslash( $_POST['wc-ppp-brasil-response'] ), true ) : null;
				$this->log( 'Iframe response data: ' . $this->print_r( $response_data, true ) );
				$payer_id       = $response_data['payer_id'];
				$remember_cards = $response_data['remembered_cards_token'];

				// Check if the payment id
				if ( empty( $payer_id ) ) {
					wc_add_notice( __( 'Ocorreu um erro inesperado, por favor tente novamente. Se o erro persistir, entre em contato.', 'paypal-plus-brasil' ), 'error' );
					$this->log( 'Empty payer ID' );

					// Set refresh totals to trigger update_checkout on frontend.
					WC()->session->set( 'refresh_totals', true );

					do_action( 'wc_ppp_brasil_process_payment_error', 'PAYER_ID', $order_id, null );

					return null;
				}

				// Check if the payment id equal to stored
				if ( $payment_id !== $iframe_data['payment_id'] ) {
					wc_add_notice( __( 'Houve um erro com a sessão do usuário. Por favor, tente novamente. Se o erro persistir, entre em contato.', 'paypal-plus-brasil' ), 'error' );
					$this->log( 'Payment failed because was trying to change the iframe response data with a new payment ID' );

					// Set refresh totals to trigger update_checkout on frontend.
					WC()->session->set( 'refresh_totals', true );

					do_action( 'wc_ppp_brasil_process_payment_error', 'PAYMENT_ID', $order_id, array(
						'stored_payment_id' => $payment_id,
						'iframe_payment_id' => $iframe_data['payment_id']
					) );

					return null;
				}

				// execute the order here.
				$execution = $this->execute_payment( $order, $payment_id, $payer_id );
				$this->log( 'Execute payment response: ' . $this->print_r( $execution, true ) );
				$sale = $execution["transactions"][0]["related_resources"][0]["sale"];
				update_post_meta( $order_id, 'wc_ppp_brasil_sale_id', $sale['id'] );
				update_post_meta( $order_id, 'wc_ppp_brasil_sale', $sale );
				$installments = 1;
				if ( $response_data && $response_data['term'] && $response_data['term']['term'] ) {
					$installments = $response_data['term']['term'];
				}
				update_post_meta( $order_id, 'wc_ppp_brasil_installments', $installments );
				update_post_meta( $order_id, 'wc_ppp_brasil_sandbox', $this->mode );
				$result_success = false;
				switch ( $sale['state'] ) {
					case 'completed';
						$order->payment_complete();
						$result_success = true;
						break;
					case 'pending':
						wc_reduce_stock_levels( $order_id );
						$order->update_status( 'on-hold', __( 'O pagamento está em revisão pelo PayPal.', 'paypal-plus-brasil' ) );
						$result_success = true;
						break;
				}

				if ( $result_success ) {
					// Remember user cards
					if ( is_user_logged_in() ) {
						update_user_meta( get_current_user_id(), 'wc_ppp_brasil_remembered_cards', $remember_cards );
					}

					do_action( 'wc_ppp_brasil_process_payment_success', $order_id );

					// Return the success URL.s
					return array(
						'result'   => 'success',
						'redirect' => $this->get_return_url( $order ),
					);
				}
			} catch ( WC_PPP_Brasil_API_Exception $ex ) {
				$data      = $ex->getData();
				$uid_error = $this->unique_id();

				switch ( $data['name'] ) {
					// Repeat the execution
					case 'INTERNAL_SERVICE_ERROR':
						if ( $force ) {
							$this->log( 'Error #' . $uid_error );
							wc_add_notice( sprintf( __( 'Ocorreu um erro inesperado, por favor tente novamente. Se o erro persistir entre em contato. Código: %s.', 'paypal-plus-brasil' ), $uid_error ), 'error' );
						} else {
							$this->process_payment( $order_id, true );
						}
						break;
					case 'VALIDATION_ERROR':
						wc_add_notice( sprintf( __( 'Ocorreu um erro inesperado, por favor tente novamente. Se o erro persistir entre em contato. Código: %s.', 'paypal-plus-brasil' ), $uid_error ), 'error' );
						$this->log( 'Error #' . $uid_error );
						break;
					case 'PAYMENT_ALREADY_DONE':
						wc_add_notice( __( 'Já existe um pagamento para este pedido.', 'paypal-plus-brasil' ), 'error' );
						break;
					default:
						wc_add_notice( __( 'O seu pagamento não foi aprovado, por favor tente novamente.', 'paypal-plus-brasil' ), 'error' );
						break;
				}

				// Log anyway
				$this->log( 'WC_PPP_Brasil_API_Exception: ' . $this->print_r( $ex->getMessage(), true ) );
				$this->log( "Data: " . $this->print_r( $data, true ) );

				// Set refresh totals to trigger update_checkout on frontend.
				WC()->session->set( 'refresh_totals', true );

				do_action( 'wc_ppp_brasil_process_payment_error', 'API_EXCEPTION', $order_id, $data['name'] );

				return null;
			}

			return null;
		}

		/**
		 * Process the refund for an order.
		 *
		 * @param int $order_id
		 * @param null $amount
		 * @param string $reason
		 *
		 * @return WP_Error|bool
		 */
		public function process_refund( $order_id, $amount = null, $reason = '' ) {

			$amount  = floatval( $amount );
			$sale_id = get_post_meta( $order_id, 'wc_ppp_brasil_sale_id', true );

			// Check if the amount is bigger than zero
			if ( $amount <= 0 ) {
				return new WP_Error( 'error', sprintf( __( 'O reembolso não pode ser menor que %s.', 'paypal-plus-brasil' ), wc_price( 0 ) ) );
			}

			// Check if we got the sale ID
			if ( $sale_id ) {
				try {

					$refund_sale = $this->api->refund_payment( $sale_id, $amount, get_woocommerce_currency() );

					$this->log( 'Refund response: ' . $this->print_r( $refund_sale, true ) );

					// Check the result success.
					if ( $refund_sale['state'] === 'completed' ) {
						return true;
					} else {
						return new WP_Error( 'error', $refund_sale->getReason() );
					}

				} catch ( WC_PPP_Brasil_API_Exception $ex ) { // Catch any PayPal error.
					$uid_error = $this->unique_id();
					$data      = $ex->getData();
					$this->log( 'Error #' . $uid_error );
					$this->log( 'Code: ' . $ex->getCode() );
					$this->log( $ex->getMessage() );
					$this->log( 'PayPalConnectionException: ' . $this->print_r( $data, true ) );

					return new WP_Error( 'error', $data['message'] . ' -  Code: #' . $uid_error );
				}
			} else { // If we don't have the PayPal sale ID.
				$uid_error = $this->unique_id();
				$this->log( 'Error #' . $uid_error );
				$this->log( 'Trying to refund a PayPal payment without the sale ID' );

				return new WP_Error( 'error', sprintf( __( 'Parece que você não tem um pedido para realizar o reembolso. Código: #%s', 'paypal-plus-brasil' ), $uid_error ) );
			}

		}

		/**
		 * Execute a payment.
		 * @throws WC_PPP_Brasil_API_Exception
		 */
		public function execute_payment( $order, $payment_id, $payer_id ) {

			$patch_data = array(
				array(
					'op'    => 'add',
					'path'  => '/transactions/0/invoice_number',
					'value' => $this->invoice_id_prefix . $order->get_id(),
				),
				array(
					'op'    => 'add',
					'path'  => '/transactions/0/description',
					'value' => sprintf( __( 'Pedido #%s realizado na loja %s', 'paypal-plus-brasil' ), $order->get_id(), get_bloginfo( 'name' ) ),
				),
				array(
					'op'    => 'add',
					'path'  => '/transactions/0/custom',
					'value' => sprintf( __( 'Pedido #%s realizado na loja %s', 'paypal-plus-brasil' ), $order->get_id(), get_bloginfo( 'name' ) ),
				),
			);

			$this->log( sprintf( 'Executing patch request for payment id %s: %s', $payment_id, $this->print_r( $patch_data, true ) ) );
			$this->api->patch_payment( $payment_id, $patch_data );

			$this->log( sprintf( 'Executing execute request for payment id %s', $payment_id ) );
			$execution_response = $this->api->execute_payment( $payment_id, $payer_id );

			return $execution_response;
		}

		/**
		 * Render the payment fields in checkout.
		 */
		public function payment_fields() {
			include dirname( __FILE__ ) . '/views/html-payment-fields.php';
		}

		/**
		 * Render HTML in admin options.
		 */
		public function admin_options() {
			include dirname( __FILE__ ) . '/views/html-admin-options.php';
		}

		/**
		 * Get the posted data in the checkout.
		 *
		 * @return array
		 * @throws Exception
		 */
		public function get_posted_data() {
			$execution_time = microtime( true );

			$order_id = get_query_var( 'order-pay' );
			$order    = $order_id ? new WC_Order( $order_id ) : null;
			$data     = array();
			$defaults = array(
				'first_name'       => '',
				'last_name'        => '',
				'person_type'      => '',
				'cpf'              => '',
				'cnpj'             => '',
				'phone'            => '',
				'email'            => '',
				'postcode'         => '',
				'address'          => '',
				'number'           => '',
				'address_2'        => '',
				'neighborhood'     => '',
				'city'             => '',
				'state'            => '',
				'country'          => '',
				'approval_url'     => '',
				'payment_id'       => '',
				'dummy'            => false,
				'invalid'          => array(),
				'remembered_cards' => '',
			);

			if ( $order ) {
				$billing_cellphone = get_post_meta( $order->get_id(), '_billing_cellphone', true );
				$data['postcode']  = $order->get_shipping_postcode();
				$data['address']   = $order->get_shipping_address_1();
				$data['address_2'] = $order->get_shipping_address_2();
				$data['city']      = $order->get_shipping_city();
				$data['state']     = $order->get_shipping_state();
				$data['country']   = $order->get_shipping_country();

				$data['neighborhood'] = get_post_meta( $order->get_id(), '_billing_neighborhood', true );
				$data['number']       = get_post_meta( $order->get_id(), '_billing_number', true );
				$data['first_name']   = $order->get_billing_first_name();
				$data['last_name']    = $order->get_billing_last_name();
				$data['person_type']  = get_post_meta( $order->get_id(), '_billing_persontype', true );
				$data['cpf']          = get_post_meta( $order->get_id(), '_billing_cpf', true );
				$data['cnpj']         = get_post_meta( $order->get_id(), '_billing_cnpj', true );
				$data['phone']        = $billing_cellphone ? $billing_cellphone : $order->get_billing_phone();
				$data['email']        = $order->get_billing_email();
			} else if ( $_POST ) {
				$this->log( 'Preparing posted data: ' . $this->print_r( $_POST, true ) );
				$data['postcode']  = isset( $_POST['s_postcode'] ) ? preg_replace( '/[^0-9]/', '', $_POST['s_postcode'] ) : '';
				$data['address']   = isset( $_POST['s_address'] ) ? sanitize_text_field( $_POST['s_address'] ) : '';
				$data['address_2'] = isset( $_POST['s_address_2'] ) ? sanitize_text_field( $_POST['s_address_2'] ) : '';
				$data['city']      = isset( $_POST['s_city'] ) ? sanitize_text_field( $_POST['s_city'] ) : '';
				$data['state']     = isset( $_POST['s_state'] ) ? sanitize_text_field( $_POST['s_state'] ) : '';
				$data['country']   = isset( $_POST['s_country'] ) ? sanitize_text_field( $_POST['s_country'] ) : '';
				// Now get other post data that other fields can send.
				$post_data = array();
				if ( isset( $_POST['post_data'] ) ) {
					parse_str( $_POST['post_data'], $post_data );
				}
				$billing_cellphone    = isset( $post_data['billing_cellphone'] ) ? sanitize_text_field( $post_data['billing_cellphone'] ) : '';
				$data['neighborhood'] = isset( $post_data['billing_neighborhood'] ) ? sanitize_text_field( $post_data['billing_neighborhood'] ) : '';
				$data['number']       = isset( $post_data['billing_number'] ) ? sanitize_text_field( $post_data['billing_number'] ) : '';
				$data['first_name']   = isset( $post_data['billing_first_name'] ) ? sanitize_text_field( $post_data['billing_first_name'] ) : '';
				$data['last_name']    = isset( $post_data['billing_last_name'] ) ? sanitize_text_field( $post_data['billing_last_name'] ) : '';
				$data['person_type']  = isset( $post_data['billing_persontype'] ) ? sanitize_text_field( $post_data['billing_persontype'] ) : '';
				$data['cpf']          = isset( $post_data['billing_cpf'] ) ? sanitize_text_field( $post_data['billing_cpf'] ) : '';
				$data['cnpj']         = isset( $post_data['billing_cnpj'] ) ? sanitize_text_field( $post_data['billing_cnpj'] ) : '';
				$data['phone']        = $billing_cellphone ? $billing_cellphone : ( isset( $post_data['billing_phone'] ) ? sanitize_text_field( $post_data['billing_phone'] ) : '' );
				$data['email']        = isset( $post_data['billing_email'] ) ? sanitize_text_field( $post_data['billing_email'] ) : '';
			}

			if ( pppbr_needs_cpf() ) {
				// Get wcbcf settings
				$wcbcf_settings = get_option( 'wcbcf_settings' );
				// Set the person type default if we don't have any person type defined
				if ( $wcbcf_settings && ! $data['person_type'] && ( $wcbcf_settings['person_type'] == '2' || $wcbcf_settings['person_type'] == '3' ) ) {
					// The value 2 from person_type in settings is CPF (1) and 3 is CNPJ (2), and 1 is both, that won't reach here.
					$data['person_type']         = $wcbcf_settings['person_type'] == '2' ? '1' : '2';
					$data['person_type_default'] = true;
				}
			}

			// Now set the invalid.
			$data    = wp_parse_args( $data, $defaults );
			$data    = apply_filters( 'wc_ppp_brasil_user_data', $data );
			$invalid = $this->validate_data( $data );

			$this->log( 'Captured data: ' . $this->print_r( $data, true ) );

			// if its invalid, return demo data.
			if ( $invalid ) {
				$data = array(
					'first_name'   => 'PayPal',
					'last_name'    => 'Brasil',
					'person_type'  => '2',
					'cpf'          => '',
					'cnpj'         => '10.878.448/0001-66',
					'phone'        => '(21) 99999-99999',
					'email'        => 'contato@paypal.com.br',
					'postcode'     => '01310-100',
					'address'      => 'Av. Paulista',
					'number'       => '1048',
					'address_2'    => '',
					'neighborhood' => 'Bela Vista',
					'city'         => 'São Paulo',
					'state'        => 'SP',
					'country'      => 'BR',
					'dummy'        => true,
					'invalid'      => $invalid,
				);
			}

			// Add session if is dummy data to check it later.
			WC()->session->set( 'wc-ppp-brasil-dummy-data', $data['dummy'] );

			// Return the data if is dummy. We don't need to process this.
			if ( $invalid ) {
				return $data;
			}

			// Create the payment.
			$payment = $this->create_payment( $data, $data['dummy'] );

			// Get old session.
			$old_session = WC()->session->get( 'wc-ppp-brasil-payment-id' );

			$this->log( 'Execution time: ' . $execution_time );

			// Check if old session exists and it's an array.
			if ( $old_session && is_array( $old_session ) ) {
				// Log old data.
				$this->log( 'Old session data: ' . $this->print_r( $old_session, true ) );

				// If this execution time is later than old session time, we can ignore this request.
				if ( $execution_time < $old_session['execution_time'] ) {
					return $data;
				}
			}

			// Add session with payment ID to check it later.
			WC()->session->set( 'wc-ppp-brasil-payment-id', array(
				'payment_id'     => $payment['id'],
				'execution_time' => $execution_time,
			) );

			// Add the saved remember card, approval link and the payment URL.
			$data['remembered_cards'] = is_user_logged_in() ? get_user_meta( get_current_user_id(), 'wc_ppp_brasil_remembered_cards', true ) : '';
			$data['approval_url']     = $payment['links'][1]['href'];
			$data['payment_id']       = $payment['id'];

			return $data;
		}

		/**
		 * Create the PayPal payment.
		 *
		 * @param $data
		 * @param bool $dummy
		 *
		 * @return mixed
		 * @throws Exception
		 */
		public function create_payment( $data, $dummy = false ) {
			// Don' log if is dummy data.
			if ( $dummy ) {
				$this->debug = false;
			}
			// Check if is order pay
			$order_id = get_query_var( 'order-pay' );
			$order    = $order_id ? wc_get_order( $order_id ) : false;
			$cart     = WC()->cart;
			$this->log( 'Creating payment' );
			$exception_data = array();

			$payment_data = array(
				'intent'        => 'sale',
				'payer'         => array(
					'payment_method' => 'paypal',
				),
				'transactions'  => array(
					array(
						'payment_options' => array(
							'allowed_payment_method' => 'IMMEDIATE_PAY',
						),
						'item_list'       => array(
							'items' => array(),
						),
						'amount'          => array(
							'currency' => get_woocommerce_currency(),
						),
					),
				),
				'redirect_urls' => array(
					'return_url' => home_url(),
					'cancel_url' => home_url(),
				),
			);

			// Add the items.
			$items_total  = 0;
			$only_digital = true;
			$items        = array();
			$cart_items   = $order ? $order->get_items() : $cart->get_cart();
			foreach ( $cart_items as $item_data ) {
				/** @var WC_Product $product */
				$product       = wc_get_product( $item_data['variation_id'] ? $item_data['variation_id'] : $item_data['product_id'] );
				$product_price = $order ? $item_data['line_subtotal'] / $item_data['qty'] : $item_data['line_subtotal'] / $item_data['quantity'];
				$product_title = isset( $item_data['variation_id'] ) && $item_data['variation_id'] ? $product->get_title() . ' - ' . implode( ', ', $item_data['variation'] ) : $product->get_title();

				$items_total += $this->money_format( ( $order ? $item_data['qty'] : $item_data['quantity'] ) * $product_price );

				$items[] = array(
					'name'     => $product_title,
					'currency' => get_woocommerce_currency(),
					'quantity' => (string) ( $order ? $item_data['qty'] : $item_data['quantity'] ),
					'price'    => $this->money_format( $product_price ),
					'sku'      => (string) ( $product->get_sku() ? $product->get_sku() : $product->get_id() ),
					'url'      => $product->get_permalink(),
				);

				// Check if product is not digital.
				if ( ! ( $product->is_downloadable() || $product->is_virtual() ) ) {
					$only_digital = false;
				}
			}

			// Add taxes
			foreach ( $cart->get_tax_totals() as $tax ) {
				$items_total += $this->money_format( $tax->amount );

				$items[] = array(
					'name'     => $tax->label,
					'currency' => get_woocommerce_currency(),
					'quantity' => (string) 1,
					'sku'      => sanitize_title( $tax->label ),
					'price'    => $this->money_format( $tax->amount ),
				);
			}

			// Add discounts
			if ( $discount = - $cart->get_cart_discount_total() ) {
				$items_total = $this->money_format( $items_total + $discount );

				$items[] = array(
					'name'     => __( 'Desconto', 'paypal-plus-brasil' ),
					'currency' => get_woocommerce_currency(),
					'quantity' => (string) 1,
					'sku'      => 'discount',
					'price'    => $this->money_format( $discount ),
				);
			}

			// Add fees
			if ( $fees = $cart->get_fees() ) {
				foreach ( $fees as $fee ) {
					$items_total = $this->money_format( $items_total + $fee->total );

					$items[] = array(
						'name'     => $fee->name,
						'currency' => get_woocommerce_currency(),
						'quantity' => (string) 1,
						'sku'      => (string) $fee->id,
						'price'    => $this->money_format( $fee->total ),
					);
				}
			}

			// Set details
			$payment_data['transactions'][0]['amount']['details'] = array(
				'shipping' => $this->money_format( $order ? $order->get_shipping_total() : floatval( $cart->shipping_total ) ),
				'subtotal' => $this->money_format( $order ? $order->get_subtotal() - $order->get_discount_total() : $items_total ),
			);

			// Set total Total
			$payment_data['transactions'][0]['amount']['total'] = $this->money_format( $order ? $order->get_total() : floatval( $cart->total ) );

			// Set the items in payment data.
			$payment_data['transactions'][0]['item_list']['items'] = $items;

			// Create the address.
			if ( ! $dummy ) {

				// Set shipping only when isn't digital
				if ( ! $only_digital ) {

					// Prepare empty address_line_1
					$address_line_1 = array();

					// Add the address
					if ( $data['address'] ) {
						$address_line_1[] = $data['address'];
					}

					// Add the number
					if ( $data['number'] ) {
						$address_line_1[] = $data['number'];
					}

					// Prepare empty line 2.
					$address_line_2 = array();

					// Add neighborhood to line 2
					if ( $data['neighborhood'] ) {
						$address_line_2[] = $data['neighborhood'];
					}

					// Add shipping address line 2
					if ( $data['address_2'] ) {
						$address_line_2[] = $data['address_2'];
					}

					$shipping_address = array(
						'recipient_name' => $data['first_name'] . ' ' . $data['last_name'],
						'country_code'   => $data['country'],
						'postal_code'    => $data['postcode'],
						'line1'          => mb_substr( implode( ', ', $address_line_1 ), 0, 100 ),
						'city'           => $data['city'],
						'state'          => $data['state'],
						'phone'          => $data['phone'],
					);

					// If is anything on address line 2, add to shipping address.
					if ( $address_line_2 ) {
						$shipping_address['line2'] = mb_substr( implode( ', ', $address_line_2 ), 0, 100 );
					}

					$payment_data['transactions'][0]['item_list']['shipping_address'] = $shipping_address;
				}
			}

			// Set the application context
			$payment_data['application_context'] = array(
				'brand_name'          => get_bloginfo( 'name' ),
				'shipping_preference' => $only_digital ? 'NO_SHIPPING' : 'SET_PROVIDED_ADDRESS',
			);

			$this->log( 'Sending create payment request: ' . $this->print_r( $payment_data, true ) );

			try {
				// Create the payment.
				$result = $this->api->create_payment( $payment_data );

				$this->log( 'Payment created: ' . $this->print_r( $result, true ) );

				return $result;
			} catch ( WC_PPP_Brasil_API_Exception $ex ) { // Catch any PayPal error.
				$this->log( 'Code: ' . $ex->getCode() );
				$this->log( $ex->getMessage() );
				$error_data = $ex->getData();
				$this->log( 'WC_PPP_Brasil_API_Exception: ' . $this->print_r( $error_data, true ) );
				if ( $error_data['name'] === 'VALIDATION_ERROR' ) {
					$exception_data = $error_data['details'];
				}
			}

			$exception       = new Exception( __( 'Ocorreu um erro inesperado, por favor tente novamente. Se o erro persistir entre em contato.', 'paypal-plus-brasil' ) );
			$exception->data = $exception_data;

			throw $exception;
		}

		/**
		 * Validate data if contain any invalid field.
		 *
		 * @param $data
		 *
		 * @return array
		 */
		private function validate_data( $data ) {
			$errors = array();

			// Check first name.
			if ( empty( $data['first_name'] ) ) {
				$errors['first_name'] = __( 'Nome inválido', 'paypal-plus-brasil' );
			}

			// Check last name.
			if ( empty( $data['last_name'] ) ) {
				$errors['last_name'] = __( 'Sobrenome inválido', 'paypal-plus-brasil' );
			}

			// Check phone.
			if ( empty( $data['phone'] ) ) {
				$errors['phone'] = __( 'Telefone inválido', 'paypal-plus-brasil' );
			}

			if ( empty( $data['address'] ) ) {
				$errors['address'] = __( 'Endereço inválido', 'paypal-plus-brasil' );
			}

			if ( empty( $data['city'] ) ) {
				$errors['city'] = __( 'Cidade inválida', 'paypal-plus-brasil' );
			}

			if ( empty( $data['state'] ) ) {
				$errors['state'] = __( 'Estado inválido', 'paypal-plus-brasil' );
			}

			if ( empty( $data['country'] ) ) {
				$errors['country'] = __( 'País inválido', 'paypal-plus-brasil' );
			}

			if ( empty( $data['postcode'] ) ) {
				$errors['postcode'] = __( 'CEP inválido', 'paypal-plus-brasil' );
			}

			// Check email.
			if ( ! is_email( $data['email'] ) ) {
				$errors['email'] = __( 'Email inválido', 'paypal-plus-brasil' );
			}

			// Only if require CPF/CNPJ
			if ( pppbr_needs_cpf() ) {

				// Check address number (only with CPF/CPNJ)
				if ( empty( $data['number'] ) ) {
					$errors['number'] = __( 'Número inválido', 'paypal-plus-brasil' );
				}

				// Check person type.
				if ( $data['person_type'] !== '1' && $data['person_type'] !== '2' ) {
					$errors['person_type'] = __( 'Tipo de pessoa inválido', 'paypal-plus-brasil' );
				}

				// Check the CPF
				if ( $data['person_type'] == '1' && ! $this->is_cpf( $data['cpf'] ) ) {
					$errors['cpf'] = __( 'CPF inválido', 'paypal-plus-brasil' );
				}

				// Check the CNPJ
				if ( $data['person_type'] == '2' && ! $this->is_cnpj( $data['cnpj'] ) ) {
					$errors['cnpj'] = __( 'CNPJ inválido', 'paypal-plus-brasil' );
				}

			}

			return $errors;
		}

		/**
		 * Enqueue scripts in checkout.
		 */
		public function checkout_scripts() {
			// Just load this script in checkout and if isn't in order-receive.
			if ( is_checkout() && ! get_query_var( 'order-received' ) ) {
				if ( 'yes' === $this->debug ) {
					wp_enqueue_script( 'pretty-web-console', plugins_url( 'assets/js/pretty-web-console.lib.js', __DIR__ ), array(), '0.10.1', true );
				}
				wp_enqueue_script( 'ppp-script', '//www.paypalobjects.com/webstatic/ppplusdcc/ppplusdcc.min.js', array(), WC_PPP_Brasil::$VERSION, true );
				wp_localize_script( 'ppp-script', 'wc_ppp_brasil_data', array(
					'id'                => $this->id,
					'order_pay'         => ! ! get_query_var( 'order-pay' ),
					'mode'              => $this->mode === 'sandbox' ? 'sandbox' : 'live',
					'form_height'       => $this->get_form_height(),
					'show_payer_tax_id' => pppbr_needs_cpf(),
					'language'          => get_woocommerce_currency() === 'BRL' ? 'pt_BR' : 'en_US',
					'country'           => $this->get_woocommerce_country(),
					'messages'          => array(
						'check_entry' => __( 'Verifique os dados informados e tente novamente.', 'paypal-plus-brasil' ),
					),
					'debug_mode'        => 'yes' === $this->debug,
				) );
				wp_enqueue_script( 'wc-ppp-brasil-script', plugins_url( 'assets/js/frontend.js', __DIR__ ), array( 'jquery' ), WC_PPP_Brasil::$VERSION, true );
				wp_enqueue_style( 'wc-ppp-brasil-style', plugins_url( 'assets/css/frontend.css', __DIR__ ), array(), WC_PPP_Brasil::$VERSION, 'all' );
			}
		}

		/**
		 * Get the WooCommerce country.
		 *
		 * @return string
		 */
		private function get_woocommerce_country() {
			return get_woocommerce_currency() === 'BRL' ? 'BR' : 'US';
		}

		/**
		 * Get form height.
		 */
		private function get_form_height() {
			$height    = trim( $this->form_height );
			$min_value = 400;
			$max_value = 550;
			$test      = preg_match( '/[0-9]+/', $height, $matches );
			if ( $test && $matches[0] === $height && $height >= $min_value && $height <= $max_value ) {
				return $height;
			}

			return null;
		}

		/**
		 * Enqueue admin scripts.
		 */
		public function admin_scripts() {
			$screen         = get_current_screen();
			$screen_id      = $screen ? $screen->id : '';
			$wc_screen_id   = sanitize_title( __( 'WooCommerce', 'paypal-plus-brasil' ) );
			$wc_settings_id = $wc_screen_id . '_page_wc-settings';
			if ( $wc_settings_id === $screen_id && isset( $_GET['section'] ) && $_GET['section'] === $this->id ) {
				wp_enqueue_style( 'wc-ppp-brasil-admin-style', plugins_url( 'assets/css/backend.css', __DIR__ ), array(), WC_PPP_Brasil::$VERSION, 'all' );
			}
		}

		/**
		 * Handle webhooks events.
		 */
		public function webhook_handler() {
			// Include the handler.
			include_once dirname( __FILE__ ) . '/class-wc-ppp-brasil-webhooks-handler.php';

			try {
				$this->log( 'Checking webhook' );

				// Instance the handler.
				$handler = new WC_PPP_Brasil_Webhooks_Handler();

				// Get the data.
				$headers = array_change_key_case( getallheaders(), CASE_UPPER );
				$body    = $this->get_raw_data();

				$webhook_event = json_decode( $body, true );

				// Prepare the signature verification.
				$signature_verification = array(
					'auth_algo'         => $headers['PAYPAL-AUTH-ALGO'],
					'cert_url'          => $headers['PAYPAL-CERT-URL'],
					'transmission_id'   => $headers['PAYPAL-TRANSMISSION-ID'],
					'transmission_sig'  => $headers['PAYPAL-TRANSMISSION-SIG'],
					'transmission_time' => $headers['PAYPAL-TRANSMISSION-TIME'],
					'webhook_id'        => $this->webhook_id,
				);

				$payload = "{";
				foreach ( $signature_verification as $field => $value ) {
					$payload .= "\"$field\": \"$value\",";
				}
				$payload .= "\"webhook_event\": $body";
				$payload .= "}";

				$signature_response = $this->api->verify_signature( $payload );

				$this->log( 'Signature response: ' . $this->print_r( $signature_response, true ) );

				if ( $signature_response['verification_status'] === 'SUCCESS' ) {
					$this->log( 'Handling event...' );
					$handler->handle( $webhook_event );
				}

			} catch ( WC_PPP_Brasil_API_Exception $ex ) { // Catch any PayPal error.
				$this->log( 'Code: ' . $ex->getCode() );
				$this->log( $ex->getMessage() );
				$this->log( 'WC_PPP_Brasil_API_Exception: ' . $this->print_r( $ex->getData(), true ) );
			} catch ( Exception $ex ) { // Catch any other error.
				$this->log( 'PHP Error: ' . $this->print_r( $ex->getMessage(), true ) );
			}

		}

		/**
		 * Retrieve the raw request entity (body).
		 *
		 * @return string
		 */
		public function get_raw_data() {
			// $HTTP_RAW_POST_DATA is deprecated on PHP 5.6
			if ( function_exists( 'phpversion' ) && version_compare( phpversion(), '5.6', '>=' ) ) {
				return file_get_contents( 'php://input' );
			}
			global $HTTP_RAW_POST_DATA;
			// A bug in PHP < 5.2.2 makes $HTTP_RAW_POST_DATA not set by default,
			// but we can do it ourself.
			if ( ! isset( $HTTP_RAW_POST_DATA ) ) {
				$HTTP_RAW_POST_DATA = file_get_contents( 'php://input' );
			}

			return $HTTP_RAW_POST_DATA;
		}

		/**
		 * Format money.
		 *
		 * @param $value
		 *
		 * @return string
		 */
		protected function money_format( $value ) {
			return number_format( $value, 2, '.', '' );
		}

		/**
		 * Add log to the system.
		 *
		 * @param $log
		 */
		private function log( $log ) {
			static $prefix;

			// If we don't exists any prefix, create new one.
			if ( ! $prefix ) {
				$prefix = 'SESSION_' . $this->unique_id() . ' - ';
			}

			// Check if is in debug mode.
			if ( 'yes' == $this->debug ) {
				$this->log->add( $this->id, $log );
			}
		}

		/**
		 * Gerneate ID for logs.
		 *
		 * @return int
		 */
		private function unique_id() {
			return rand( 1, 10000 );
		}

		/**
		 * Checks if the CPF is valid.
		 *
		 * @param string $cpf CPF to validate.
		 *
		 * @return bool
		 */
		public function is_cpf( $cpf ) {
			$cpf = preg_replace( '/[^0-9]/', '', $cpf );

			if ( 11 !== strlen( $cpf ) || preg_match( '/^([0-9])\1+$/', $cpf ) ) {
				return false;
			}

			$digit = substr( $cpf, 0, 9 );

			for ( $j = 10; $j <= 11; $j ++ ) {
				$sum = 0;

				for ( $i = 0; $i < $j - 1; $i ++ ) {
					$sum += ( $j - $i ) * intval( $digit[ $i ] );
				}

				$summod11        = $sum % 11;
				$digit[ $j - 1 ] = $summod11 < 2 ? 0 : 11 - $summod11;
			}

			return intval( $digit[9] ) === intval( $cpf[9] ) && intval( $digit[10] ) === intval( $cpf[10] );
		}

		/**
		 * Checks if the CNPJ is valid.
		 *
		 * @param string $cnpj CNPJ to validate.
		 *
		 * @return bool
		 */
		public function is_cnpj( $cnpj ) {
			$cnpj = sprintf( '%014s', preg_replace( '{\D}', '', $cnpj ) );

			if ( 14 !== strlen( $cnpj ) || 0 === intval( substr( $cnpj, - 4 ) ) ) {
				return false;
			}

			for ( $t = 11; $t < 13; ) {
				for ( $d = 0, $p = 2, $c = $t; $c >= 0; $c --, ( $p < 9 ) ? $p ++ : $p = 2 ) {
					$d += $cnpj[ $c ] * $p;
				}

				if ( intval( $cnpj[ ++ $t ] ) !== ( $d = ( ( 10 * $d ) % 11 ) % 10 ) ) {
					return false;
				}
			}

			return true;
		}

		/**
		 * Get assets file in the plugin.
		 */
		public function plugin_url( $file ) {
			return plugins_url( $file, __DIR__ );
		}

		/**
		 * Return the gateway's title.
		 *
		 * @return string
		 */
		public function get_title() {
			$title = get_woocommerce_currency() === "BRL" ? __( 'Cartão de Crédito', 'paypal-plus-brasil' ) : __( 'Credit Card', 'paypal-plus-brasil' );
			if ( ! empty( $this->title ) ) {
				$title .= ' ' . $this->title;
			}

			return apply_filters( 'woocommerce_gateway_title', $title, $this->id );
		}

		protected function print_r( $expression, $return = false ) {
			if ( ! function_exists( 'wc_print_r' ) ) {
				return print_r( $expression, $return );
			} else {
				return wc_print_r( $expression, $return );
			}
		}

		public function add_notice( $text, $type = 'error' ) {
			$notices   = get_option( 'wc-ppp-brasil-notices', array() );
			$notices[] = array(
				'text' => $text,
				'type' => $type,
			);
			update_option( 'wc-ppp-brasil-notices', $notices );
		}

		public function get_notices( $clear = true ) {
			$notices = get_option( 'wc-ppp-brasil-notices', array() );

			if ( $clear ) {
				update_option( 'wc-ppp-brasil-notices', array() );
			}

			return $notices;
		}

	}

}