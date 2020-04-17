=== Checkout Transparente do PayPal ===
Contributors: apuhlmann
Tags: paypal, paypal plus, woocommerce, woo commerce, checkout transparente, transparente, pagamento, gateway, paypal brasil, ecommerce, e-commerce
Requires at least: 4.4
Tested up to: 5.2
Stable tag: 1.6
Requires PHP: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
WC requires at least: 3.0
WC tested up to: 3.6

Adicione facilmente opções de pagamento do PayPal Plus ao seu site do WordPress/WooCommerce.

== Description ==

A experiência de um Checkout Transparente processado com a segurança do PayPal. Seu cliente efetua o pagamento diretamente no seu site, sem redirecionamento e sem a necessidade de abertura de uma conta PayPal, utilizando os dados cartão de crédito, que podem ser salvos para agilizar o pagamento em futuras compras.

= Conheça mais vantagens do PayPal =

* **Segurança:** nível máximo de certificação de segurança PCI Compliance e criptografia em todas as transações.
* **Programa de Proteção ao Vendedor¹:** protege suas vendas em casos de “chargebacks”, reclamações ou cancelamentos solicitados pelo comprador.
* **Facilidade no recebimento das vendas:** parcele suas vendas em até 12 vezes e receba em 24 horas², sem tarifa incremental de antecipação.
* **Atendimento especializado:** atendimento comercial e técnico para tirar suas dúvidas e te ajudar com integrações. Seu cliente também conta com um atendimento bilíngue 24x7.
* **Venda para novos clientes no exterior:** receba pagamentos de compradores de mais de 200 mercados³ diferentes e para 250 milhões de compradores ao redor do mundo.
* **Soluções responsivas:** seu cliente compra pelo celular com melhor experiência e estabilidade no mobile.

¹ Sujeito ao cumprimento dos requisitos do Programa de Proteção ao Vendedor e Comprador.
² Pagamentos recebidos na conta do PayPal e sujeitos a análise de risco e crédito pelo PayPal.
³ Este módulo só permite recebimento nas moedas Real Brasileiro (BRL) e Dólar Americano (USD).

= Para quem o produto está disponível =

O produto está disponível para contas PayPal cadastradas com CNPJ (Conta Empresa). Caso a sua conta seja de pessoa física, você deve abrir uma conta PayPal de pessoa jurídica por [este link](https://www.paypal.com/bizsignup/).

Caso já tenha uma conta Empresa, você pode solicitar o Checkout Transparente do PayPal [clicando aqui](https://www.paypal.com/br/webapps/mpp/paypal-payments-pro/woocomerce#woocommerce).

= Para desenvolvedores =

A ação do plugin poderá ser extendida quando há um sucesso ou rejeição no pagamento. Os hooks disponíveis são: `wc_ppp_brasil_process_payment_error` e `wc_ppp_brasil_process_payment_success`.

Para o `wc_ppp_brasil_process_payment_success` receberá o parâmetro de `$order_id`, com o número do pedido processado pelo PayPal.

Para o `wc_ppp_brasil_process_payment_error` receberá os parâmetros `$type`, `$order_id` e `$data`.

* **$type**

IFRAME_ERROR: quando um erro é causado dentro do iframe.

DUMMY_DATA: quando há uma tentativa de processar o pagamento sem preencher todos os campos.

SESSION_ERROR: Caso tenha algum conflito com sessão do usuário (recomendado tentar novamente).

PAYER_ID: Caso por algum motivo o payer id venha em branco (provavelmente por um erro da instalação).

PAYMENT_ID: Houve um problema com a sessão do usuário que tentou realizar um pagamento para um ID inválido (recomendado tentar novamente).

* **$data**

Poderá receber qualquer tipo de dado (array, string, null, etc). É recomendado buscar no código do `class-wc-ppp-brasil-gateway.php` pela action `wc_ppp_brasil_process_payment_error` e verificar as possibilidades de dado a ser recebido.

= Compatibilidade =

Compatível à partir da versão 3.0.x até a 3.5.x do WooCommerce.

= Pré-requisitos =

Por padrão o WooCommerce não pede no cadastro do cliente as informações de CPF/CNPJ. Estas informações são importantes para o PayPal oferecer uma análise de risco eficiente, portanto este campo é obrigatório para utilizar este plugin.
Você deve adicionar esta funcionalidade ao seu WooCommerce via plugin, por exemplo: [WooCommerce Extra Checkout Fields for Brazil](http://wordpress.org/plugins/woocommerce-extra-checkout-fields-for-brazil/).

= Instalação =

O PayPal disponibiliza um tutorial completo com o passo a passo de integração do Chekout Transparente utilizando o WooCommerce para facilitar a integração do meio de pagamento no seu site.

Clique no [link](https://www.paypal.com/br/webapps/mpp/paypal-payments-pro/woocomerce) e siga as instruções.

= Dúvidas/Suporte =

Caso tenha alguma dúvida ou dificuldade na utilização do plugin acesse a seção de Suporte por [este link](https://wordpress.org/support/plugin/paypal-plus-brasil).

== Installation ==

= Instalação do plugin: =

* Envie os arquivos do plugin para a pasta "wp-content/plugins", ou instale usando o instalador de plugins do WordPress.
* Ative o plugin.

== Frequently Asked Questions ==

= Instalação do plugin: =

* Envie os arquivos do plugin para a pasta "wp-content/plugins", ou instale usando o instalador de plugins do WordPress.
* Ative o plugin.

== Screenshots ==

1. Exemplo de dados não preenchidos no tema Storefront.
2. Exemplo de checkout com cartão de crédito salvo no tema Storefront.

== Changelog ==

= 1.0.0 - 2017/07/25 =

= 1.3 - 2018/09/25 =

= 1.4 - 2018/10/28 =

= 1.4.1 - 2018/10/29 =

= 1.6.0 - 2019/03/11 =

= 1.6.1 - 2019/03/12 =

= 1.6.2 - 2019/03/13 =

= 1.6.3 - 2019/03/17 =

= 1.6.4 - 2019/03/29 =

= 1.6.5 - 2019/04/21 =

= 1.6.6 - 2019/07/18 =

== Upgrade Notice ==

= 1.0.0 =

* Versão inicial do plugin.

= 1.3 =

* Configurações agora possuem somente um checkbox para debug.
* Modificado a descrição do campo de "Prefixo de Invoice ID".
* Título do gateway baseado na moeda configurada no WooCommerce.
* Modificado os detalhes do pagamento na página administrativa do pedido.
* Alterado o plugin locale para paypal-plus-brasil

= 1.4 =

* Melhoria nos logs para análise de erros.
* Validação de compatibilidade com WooCommerce 3.5.

= 1.4.1 =

* Melhoria nos logs para análise de erros.

= 1.5.0 =

* Adicionado suporte a Digital Goods.
* Melhoria nos logs para análise de erros.

= 1.5.1 =

* Atualizado título do plugin.

= 1.6.0 =

* Removido o PayPal PHP SDK e adicionado funções nativas do WordPress.

= 1.6.1 =

* Adicionado fees no cálculo total para suportar alguns plugins de descontos.
* Corrigido erro de PHP que atrapalhava o tratamento de erros.
* Adicionado versão de suporte para PHP.

= 1.6.2 =

* Alterado forma que os valores são enviados à API.

= 1.6.3 =

* Corrigido erro com produtos digitais.

= 1.6.4 =

* Corrigido problema que causava falha no pagamento sempre que a primeira tentativa desse errado.

= 1.6.5 =

* Adicionado melhorias nos logs.
* Modificado Javascript para utilizar funções nativas do PayPal Plus.
* Adicionado suporte ao WooCommerce 3.6 e WordPress 5.2.
* Adicionado hooks para desenvolvedores.

= 1.6.6 =

* Corrigido falha no cálculo de parcelamento dentro do pedido.