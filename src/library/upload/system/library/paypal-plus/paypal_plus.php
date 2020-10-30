<?php
/**
 * Class PayPal Plus
 *
 * Class para comunicação com a API PayPal Plus
 * @package PayPal Plus
 * @author PayPal Brasil
 * @version 1.0.0
 * @copyright Copyright (c) PayPal Brasil
 * @link https://www.paypal.com/br/
 */
final class PayPalPlus
{
    /**
     * @var array
     */
    private $_parameter = [];

    /**
     * @var string
     */
    private $_input = '';

    /**
     * @var string
     */
    private $_url = '';

    /**
     * @var string
     */
    private $_authorization = '';

    /**
     * @var string
     */
    private $_content_type = '';

    /**
     * @var string
     */
    private $_resource = '';

    /**
     * @var string
     */
    private $_method = '';

    /**
     * @var string
     */
    private $_request = '';

    /**
     * @var string
     */
    private $_response = '';

    /**
     * @var string
     */
    private $_http_code = '';

    /**
     * @throws Exception
     * @return void
     */
    public function __construct() {
        $this->validateRequirements();
    }

    /**
     * Parâmetros esperados pelo request
     *
     * @param array $data
     * @return void
     */
    public function setParameters($data = []) {
        $this->_parameter = $data;
    }

    /**
     * Endpoint para comunicação com a API
     *
     * @return void
     */
    private function setEnvironment() {
        if ($this->_parameter['sandbox']) {
            $this->_url = 'https://api.sandbox.paypal.com';
        } else {
            $this->_url = 'https://api.paypal.com';
        }
    }

    /**
     * Verifica se o ambiente é o sandbox
     *
     * @return bool
     */
    private function getIfSandbox() {
        if ($this->_parameter['sandbox']) {
            return true;
        }

        return false;
    }

    /**
     * Captura o tipo de conteúdo utilizado na comunicação com a API
     *
     * @return string
     */
    private function getContentType() {
        return $this->_content_type;
    }

    /**
     * Captura o tipo de autorização utilizada na comunicação com a API
     *
     * @return string
     */
    private function getAuthorization() {
        return $this->_authorization;
    }

    /**
     * Captura a URL para o recurso utilizado na comunicação com a API
     *
     * @return string
     */
    public function getResource() {
        return $this->_url . $this->_resource;
    }

    /**
     * Captura o cabeçalho utilizado na comunicação com a API
     *
     * @return array
     */
    public function getHeaders() {
        $headers = [];
        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-type: ' . $this->getContentType();
        $headers[] = $this->getAuthorization();
        $headers[] = 'PayPal-Partner-Attribution-Id: OpenCartBrazil_Ecom_PPPlus';

        return $headers;
    }

    /**
     * Captura o método utilizado na comunicação com a API
     *
     * @return string
     */
    public function getMethod() {
        return $this->_method;
    }

    /**
     * Captura o request utilizado na comunicação com a API
     *
     * @return string|bool
     */
    public function getRequest() {
        if ($this->_request) {
            return $this->_request;
        }

        return false;
    }

    /**
     * Captura o response retornado na comunicação com a API
     *
     * @return string
     */
    public function getResponse() {
        return $this->_response;
    }

    /**
     * Captura o HTTP Status Code retornado na comunicação com a API
     *
     * @return string
     */
    public function getResponseCode() {
        return $this->_http_code;
    }

    /**
     * @throws \Exception
     * @return object
     */
    public function changeWebhook() {
        $inputs = ['sandbox', 'client_id', 'client_secret', 'method', 'endpoint', 'json'];
        $parameters = $this->validateParameters($inputs);
        if ($parameters == false) {
            throw new \Exception('Erro 412 (Precondition Failed). O parâmetro ' . $this->_input . ' não foi enviado para executar a consulta por webhooks.');
        }

        $method = trim($this->_parameter['method']);
        $endpoint = trim($this->_parameter['endpoint']);
        $json = $this->_parameter['json'];

        $this->_authorization = 'Authorization: Bearer ' . $this->getToken();
        $this->_content_type = 'application/json';
        $this->_resource = '/v1/notifications/' . $endpoint;
        $this->_method = $method;
        $this->_request = $json;

        return $this->connect();
    }

    /**
     * @throws \Exception
     * @return object
     */
    public function setPayment() {
        $inputs = ['sandbox', 'client_id', 'client_secret', 'store_name', 'description', 'subtotal', 'shipping', 'total', 'postcode', 'address', 'city', 'zone', 'country', 'return_url', 'cancel_url'];
        $parameters = $this->validateParameters($inputs);
        if ($parameters == false) {
            throw new \Exception('Erro 412 (Precondition Failed). O parâmetro ' . $this->_input . ' não foi enviado para iniciar o pagamento no PayPal.');
        }

        $store_name = html_entity_decode($this->utf8_substr($this->_parameter['store_name'], 0, 126));
        $description = html_entity_decode($this->utf8_substr($this->_parameter['description'], 0, 127));
        $invoice_number = html_entity_decode($this->utf8_substr($this->_parameter['invoice_number'], 0, 126));

        $items = [];
        if (isset($this->_parameter['items'])) {
            if (is_array($this->_parameter['items'])) {
                foreach ($this->_parameter['items'] as $item) {
                    $name = (isset($item['name'])) ? html_entity_decode($this->utf8_substr($item['name'], 0, 127)) : '';
                    $description = (isset($item['description'])) ? html_entity_decode($this->utf8_substr($item['description'], 0, 127)) : '';
                    $quantity = (isset($item['quantity'])) ? (int) $item['quantity'] : '1';
                    $price = (isset($item['price'])) ? $item['price'] : '0';
                    $sku = (isset($item['sku'])) ? trim($item['sku']) : '';
                    $url = (isset($item['url'])) ? trim($item['url']) : '';

                    if (empty($url)) {
                        $items[] = [
                            'name' => $name,
                            'description' => $description,
                            'quantity' => $quantity,
                            'price' => $price,
                            'currency' => 'BRL',
                            'sku' => $sku
                        ];
                    } else {
                        $items[] = [
                            'name' => $name,
                            'description' => $description,
                            'quantity' => $quantity,
                            'price' => $price,
                            'currency' => 'BRL',
                            'sku' => $sku,
                            'url' => $url
                        ];
                    }
                }
            } else {
                throw new \Exception('Erro 412 (Precondition Failed). Os itens do carrinho não são válidos.');
            }
        } else {
            throw new \Exception('Erro 412 (Precondition Failed). Os itens do carrinho não foram informados.');
        }

        $subtotal = $this->_parameter['subtotal'];
        if ($subtotal <= 0) {
            throw new \Exception('Erro 412 (Precondition Failed). O subtotal do pedido não pode ser menor ou igual a zero.');
        }

        $shipping = $this->_parameter['shipping'];
        if ($shipping < 0) {
            throw new \Exception('Erro 412 (Precondition Failed). O valor do frete não pode ser negativo.');
        }

        $total = $this->_parameter['total'];
        if ($total <= 0) {
            throw new \Exception('Erro 412 (Precondition Failed). O total do pedido não pode ser menor ou igual a zero.');
        }

        $postal_code = substr(preg_replace("/[^0-9]/", '', $this->_parameter['postcode']), 0, 8);
        $line1 = html_entity_decode($this->utf8_substr($this->_parameter['address'], 0, 100));
        $city = html_entity_decode($this->utf8_substr($this->_parameter['city'], 0, 64));
        $state = html_entity_decode($this->utf8_substr($this->_parameter['zone'], 0, 40));
        $country_code = html_entity_decode($this->utf8_substr($this->_parameter['country'], 0, 2));

        $return_url = preg_replace('/[^A-Za-z0-9-:_?=.\/]/', '', $this->_parameter['return_url']);
        if (($return_url == '') || (strlen($return_url) > 600)) {
            throw new \Exception('Erro 412 (Precondition Failed). A URL para retorno do pagamento não é válida.');
        }

        $cancel_url = preg_replace('/[^A-Za-z0-9-:_?=.\/]/', '', $this->_parameter['cancel_url']);
        if (($cancel_url == '') || (strlen($cancel_url) > 600)) {
            throw new \Exception('Erro 412 (Precondition Failed). A URL para pagamento cancelado não é válida.');
        }

        $this->_authorization = 'Authorization: Bearer ' . $this->getToken();
        $this->_content_type = 'application/json';
        $this->_resource = '/v1/payments/payment';
        $this->_method = 'POST';
        $this->_request = str_replace('\\/', '/', json_encode([
            'intent' => 'sale',
            'payer' => [
                'payment_method' => 'paypal'
            ],
            'application_context' => [
                'brand_name' => $store_name,
                'locale' => 'pt_BR',
                'shipping_preference' => 'SET_PROVIDED_ADDRESS',
            ],
            'transactions' => [
                [
                    'amount' => [
                        'currency' => 'BRL',
                        'total' => $total,
                        'details' => [
                            'subtotal' => $subtotal,
                            'shipping' => $shipping
                        ],
                    ],
                    'description' => $description,
                    'invoice_number' => $invoice_number,
                    'payment_options' => [
                        'allowed_payment_method' => 'IMMEDIATE_PAY',
                    ],
                    'item_list' => [
                        'shipping_address' => [
                            'line1' => $line1,
                            'city' => $city,
                            'country_code' => $country_code,
                            'postal_code' => $postal_code,
                            'state' => $state
                        ],
                        'items' => $items,
                    ],
                ],
            ],
            'redirect_urls' => [
                'return_url' => $return_url,
                'cancel_url' => $cancel_url,
            ]
        ]));

        return $this->connect();
    }

    /**
     * @throws \Exception
     * @return object
     */
    public function setExecute() {
        $inputs = ['sandbox', 'client_id', 'client_secret', 'payment_id', 'payer_id'];
        $parameters = $this->validateParameters($inputs);
        if ($parameters == false) {
            throw new \Exception('Erro 412 (Precondition Failed). O parâmetro ' . $this->_input . ' não foi enviado para executar o pagamento no PayPal.');
        }

        $payment_id = trim($this->_parameter['payment_id']);
        if (empty($payment_id)) {
            throw new \Exception('Erro 412 (Precondition Failed). O payment_id não foi informado.');
        }

        $payer_id = trim($this->_parameter['payer_id']);
        if (empty($payer_id)) {
            throw new \Exception('Erro 412 (Precondition Failed). O payer_id não foi informado.');
        }

        $this->_authorization = 'Authorization: Bearer ' . $this->getToken();
        $this->_content_type = 'application/json';
        $this->_resource = '/v1/payments/payment/' . $payment_id . '/execute';
        $this->_method = 'POST';
        $this->_request = str_replace('\\/', '/',json_encode(['payer_id' => $payer_id]));

        return $this->connect();
    }

    /**
     * @throws \Exception
     * @return object
     */
    public function setRefund() {
        $inputs = ['sandbox', 'client_id', 'client_secret', 'sale_id', 'amount', 'currency', 'invoice_number'];
        $parameters = $this->validateParameters($inputs);
        if ($parameters == false) {
            throw new \Exception('Erro 412 (Precondition Failed). O parâmetro ' . $this->_input . ' não foi enviado para solicitar o reembolso no PayPal.');
        }

        $sale_id = trim($this->_parameter['sale_id']);
        if (empty($sale_id)) {
            throw new \Exception('Erro 412 (Precondition Failed). O sale_id não foi informado.');
        }

        $amount = $this->_parameter['amount'];
        if ($amount <= 0) {
            throw new \Exception('Erro 412 (Precondition Failed). O valor para reembolso não é válido.');
        }

        $currency = trim($this->_parameter['currency']);
        if (empty($currency)) {
            throw new \Exception('Erro 412 (Precondition Failed). O código da moeda não foi informado.');
        }

        $invoice_number = $this->_parameter['invoice_number'];
        if ($invoice_number == '') {
            throw new \Exception('Erro 412 (Precondition Failed). O código do pedido não foi informado.');
        }

        $this->_authorization = 'Authorization: Bearer ' . $this->getToken();
        $this->_content_type = 'application/json';
        $this->_resource = '/v1/payments/sale/' . $sale_id . '/refund';
        $this->_method = 'POST';
        $this->_request = str_replace('\\/', '/', json_encode([
            'amount' => [
                'total' => $amount,
                'currency' => $currency
            ],
            'invoice_number' => $invoice_number
        ]));

        return $this->connect();
    }

    /**
     * @throws \Exception
     * @return object
     */
    public function getSale() {
        $inputs = ['sandbox', 'client_id', 'client_secret', 'sale_id'];
        $parameters = $this->validateParameters($inputs);
        if ($parameters == false) {
            throw new \Exception('Erro 412 (Precondition Failed). O parâmetro ' . $this->_input . ' não foi enviado para consultar a situação do pedido no PayPal.');
        }

        $sale_id = trim($this->_parameter['sale_id']);
        if (empty($sale_id)) {
            throw new \Exception('Erro 412 (Precondition Failed). O sale_id não foi informado.');
        }

        $this->_authorization = 'Authorization: Bearer ' . $this->getToken();
        $this->_content_type = 'application/json';
        $this->_resource = '/v1/payments/sale/' . $sale_id;
        $this->_method = 'GET';
        $this->_request = '';

        return $this->connect();
    }

    /**
     * @throws \Exception
     * @return object
     */
    public function getSearch() {
        $inputs = ['sandbox', 'client_id', 'client_secret', 'payment_id'];
        $parameters = $this->validateParameters($inputs);
        if ($parameters == false) {
            throw new \Exception('Erro 412 (Precondition Failed). O parâmetro ' . $this->_input . ' não foi enviado para consultar a situação do pagamento no PayPal.');
        }

        $payment_id = trim($this->_parameter['payment_id']);
        if (empty($payment_id)) {
            throw new \Exception('Erro 412 (Precondition Failed). O payment_id não foi informado.');
        }

        $this->_authorization = 'Authorization: Bearer ' . $this->getToken();
        $this->_content_type = 'application/json';
        $this->_resource = '/v1/payments/payment/' . $payment_id;
        $this->_method = 'GET';
        $this->_request = '';

        return $this->connect();
    }

    /**
     * Obtém o access_token para autenticar o request na API
     *
     * @throws \Exception
     * @return object
     */
    private function getToken() {
        $client_id = trim($this->_parameter['client_id']);
        if (empty($client_id)) {
            throw new \Exception('Erro 412 (Precondition Failed). O Client ID não foi informado.');
        }

        $client_secret = trim($this->_parameter['client_secret']);
        if (empty($client_secret)) {
            throw new \Exception('Erro 412 (Precondition Failed). O Client Secret não foi informado.');
        }

        $this->_authorization = 'Authorization: Basic ' . base64_encode($client_id . ':' . $client_secret);
        $this->_content_type = 'application/x-www-form-urlencoded';
        $this->_resource = '/v1/oauth2/token';
        $this->_method = 'POST';
        $this->_request = 'grant_type=client_credentials';

        $response = $this->connect();

        if (!isset($response->access_token)) {
            throw new \Exception('Erro 412 (Precondition Failed). O access_token não foi gerado pela API do PayPal.');
        }

        return $response->access_token;
    }

    /**
     * Realiza a comunicação com a API
     *
     * @throws \Exception
     * @return object
     */
    private function connect() {
        $this->setEnvironment();

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $this->getResource());
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->getHeaders());
        curl_setopt($curl, CURLOPT_ENCODING, '');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->getMethod());
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->getRequest());
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        if ($this->getIfSandbox()) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        }

        $this->_response = curl_exec($curl);
        $this->_http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            throw new \Exception($error);
        }

        $response = $this->getResponse();
        $code = $this->getResponseCode();

        $codes = ['200', '201', '204', '400'];

        if (in_array($code, $codes)) {
            if (
                version_compare(phpversion(), '7.4.0alpha1', '<')
                && function_exists('get_magic_quotes_gpc')
                && get_magic_quotes_gpc()
            ) {
                $response = stripslashes($response);
            }

            $json = json_decode($response);

            if ($json === false || $json === null) {
                throw new \Exception('Erro 412 (Precondition Failed). Não foi possível decodificar o json recebido através da API do PayPal.');
            }

            return $json;
        }

        switch ($code) {
            case '401':
                throw new \Exception('Erro 401 (Unauthorized). Verifique em sua conta no PayPal se seu Cliente ID e Client Secret são válidos.');
            case '403':
                throw new \Exception('Erro 403 (Forbidden). Verifique em sua conta no PayPal se seu Cliente ID e Client Secret são válidos.');
            case '404':
                throw new \Exception('Erro 404 (Resource Not Found). O recurso solicitado não foi encontrado na API do PayPal.');
            case '405':
                throw new \Exception('Erro 405 (Method Not Supported). O método utilizado não foi implementado na API do PayPal.');
            case '406':
                throw new \Exception('Erro 406 (Media Type Not Acceptable). O formato de resposta solicitado não está disponível na API do PayPal.');
            case '408':
                throw new \Exception('Erro 408 (Request Timed Out). Tempo esgotado para requisição na API do PayPal.');
            case '413':
                throw new \Exception('Erro 413 (Request Entity Too Long). A requisição excede o tamanho máximo permitido pela API do PayPal.');
            case '415':
                throw new \Exception('Erro 415 (Unsupported Media Type). O formado de conteúdo enviado não foi aceito pela API do PayPal.');
            case '422':
                throw new \Exception('Erro 422 (Unprocessable Entity). Não foi possível processar a solicitação na API do PayPal.');
            case '429':
                throw new \Exception('Erro 429 (Rate Limit Reached). Atingiu o limite de solicitações para a API do PayPal.');
            case '500':
                throw new \Exception('Erro 500 (Internal Server Error). A API do PayPal não respondeu devido problemas técnicos. Entre em contato com o PayPal para mais informações.');
            case '501':
                throw new \Exception('Erro 501 (Not Implemented). A API do PayPal não respondeu devido problemas técnicos. Entre em contato com o PayPal para mais informações.');
            case '503':
                throw new \Exception('Erro 503 (Service Unavailable). A API do PayPal não respondeu devido manutenção temporária. Entre em contato com o PayPal para mais informações.');
            case '504':
                throw new \Exception('Erro 504 (Gateway Timeout). Tempo esgotado para requisição na API do PayPal. Entre em contato com o PayPal para mais informações.');
            default:
                throw new \Exception('Erro não identificado.');
        }
    }

    /**
     * Verifica os requisitos básicos para utilização da classe
     *
     * @throws \Exception
     * @return void
     */
    private function validateRequirements() {
        if (phpversion() < '7.3') {
            throw new \Exception('É necessário utilizar no mínimo PHP 7.3 para se comunicar com a API do PayPal.');
        }

        if (extension_loaded('mbstring')) {
            mb_internal_encoding('UTF-8');
        } else {
            throw new \Exception('Erro 412 (Precondition Failed). A extensão mbstring do PHP está desabilitada em sua hospedagem. Entre em contato com o suporte de sua hospedagem, e solicite que habilitem a extensão.');
        }

        if (!extension_loaded('curl')) {
            throw new \Exception('Erro 412 (Precondition Failed). A extensão curl do PHP está desabilitada em sua hospedagem. Entre em contato com o suporte de sua hospedagem, e solicite que habilitem a extensão.');
        }

        if (!extension_loaded('json')) {
            throw new \Exception('Erro 412 (Precondition Failed). A extensão json do PHP está desabilitada em sua hospedagem. Entre em contato com o suporte de sua hospedagem e solicite que habilitem a extensão.');
        }

        if (!function_exists('json_encode')) {
            throw new \Exception('Erro 412 (Precondition Failed). A função json_encode do PHP está desabilitada em sua hospedagem. Entre em contato com o suporte de sua hospedagem e solicite que habilitem a função.');
        }

        if (!function_exists('json_decode')) {
            throw new \Exception('Erro 412 (Precondition Failed). A função json_decode do PHP está desabilitada em sua hospedagem. Entre em contato com o suporte de sua hospedagem e solicite que habilitem a função.');
        }
    }

    /**
     * Valida se todos os parâmetros esperados para a composição do request foram enviados
     *
     * @param array $inputs
     * @return bool
     */
    private function validateParameters($inputs) {
        foreach ($inputs as $input) {
            if (!isset($this->_parameter[$input])) {
                $this->_input = $input;

                return false;
            }
        }

        return true;
    }

    /**
     * Retorna parte de uma string UTF-8
     *
     * @param string $string
     * @param int $offset
     * @param int $length
     * @return string
     */
    private function utf8_substr($string, $offset, $length) {
        $string = trim($string);

        return mb_substr($string, $offset, $length);
    }
}
