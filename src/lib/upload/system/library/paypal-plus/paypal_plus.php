<?php
/**
 * Class PayPal Plus
 *
 * Class for PayPal Plus API
 * @package PayPal Plus
 * @author PayPal Brazil
 * @version 1.1.1
 * @copyright Copyright (c) PayPal Brazil
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
     * Set parameters for request
     *
     * @param array $data
     * @return void
     */
    public function setParameters($data = []) {
        $this->_parameter = $data;
    }

    /**
     * Set API Endpoint URL
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
     * Check if sandbox enabled
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
     * Get content type
     *
     * @return string
     */
    private function getContentType() {
        return $this->_content_type;
    }

    /**
     * Get authorization header
     *
     * @return string
     */
    private function getAuthorization() {
        return $this->_authorization;
    }

    /**
     * Get API resource
     *
     * @return string
     */
    public function getResource() {
        return $this->_url . $this->_resource;
    }

    /**
     * Get API headers
     *
     * @return array
     */
    public function getHeaders() {
        $paypal_partner_attribution_id = 'OpenCartBrazil_Ecom_PPPlus';

        if (
            isset($this->_parameter['country_code'])
            && $this->_parameter['country_code'] == 'MX'
        ) {
            $paypal_partner_attribution_id = 'OpenCartMexico_Cart_PP';
        }

        $headers = [];
        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-type: ' . $this->getContentType();
        $headers[] = $this->getAuthorization();
        $headers[] = 'PayPal-Partner-Attribution-Id: ' . $paypal_partner_attribution_id;

        return $headers;
    }

    /**
     * Get API method
     *
     * @return string
     */
    public function getMethod() {
        return $this->_method;
    }

    /**
     * Get API request
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
     * Get API response
     *
     * @return string
     */
    public function getResponse() {
        return $this->_response;
    }

    /**
     * Get API HTTP Status Code
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
        $inputs = ['sandbox', 'client_id', 'client_secret', 'country_code', 'method', 'endpoint', 'json'];
        $parameters = $this->validateParameters($inputs);
        if ($parameters == false) {
            throw new \Exception('Erro 412 (Precondition Failed). The parameter ' . $this->_input . ' was not sent to perform the query via webhooks.');
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
        $inputs = ['sandbox', 'client_id', 'client_secret', 'store_name', 'description', 'invoice_number', 'country_code', 'currency_code', 'subtotal', 'shipping', 'total', 'postcode', 'address', 'city', 'zone', 'country', 'return_url', 'cancel_url'];
        $parameters = $this->validateParameters($inputs);
        if ($parameters == false) {
            throw new \Exception('Erro 412 (Precondition Failed). The parameter ' . $this->_input . ' was not sent to initiate payment on PayPal.');
        }

        $store_name = html_entity_decode($this->utf8_substr($this->_parameter['store_name'], 0, 126));
        $description = html_entity_decode($this->utf8_substr($this->_parameter['description'], 0, 127));

        $invoice_number = html_entity_decode($this->utf8_substr($this->_parameter['invoice_number'], 0, 126));
        if ($invoice_number == '') {
            throw new \Exception('Erro 412 (Precondition Failed). The invoice_number has not been set.');
        }

        $country_code = trim($this->_parameter['country_code']);
        $countries = ['BR','MX','US'];
        if (!in_array($country_code, $countries)) {
            throw new \Exception('Erro 412 (Precondition Failed). Country code is not supported.');
        }

        if ($country_code == 'BR') {
            $locale = 'pt_BR';
        } elseif ($country_code == 'MX') {
            $locale = 'es_MX';
        } elseif ($country_code == 'US') {
            $locale = 'en_US';
        }

        $currency_code = trim($this->_parameter['currency_code']);
        $currencies = ['BRL','MXN','USD'];
        if (!in_array($currency_code, $currencies)) {
            throw new \Exception('Erro 412 (Precondition Failed). Currency code  is not supported.');
        }

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
                            'currency' => $currency_code,
                            'sku' => $sku
                        ];
                    } else {
                        $items[] = [
                            'name' => $name,
                            'description' => $description,
                            'quantity' => $quantity,
                            'price' => $price,
                            'currency' => $currency_code,
                            'sku' => $sku,
                            'url' => $url
                        ];
                    }
                }
            } else {
                throw new \Exception('Erro 412 (Precondition Failed). Cart items are not valid. ');
            }
        } else {
            throw new \Exception('Erro 412 (Precondition Failed). Cart items have not been reported.');
        }

        $subtotal = $this->_parameter['subtotal'];
        if ($subtotal <= 0) {
            throw new \Exception('Erro 412 (Precondition Failed). Order subtotal cannot be less than or equal to zero.');
        }

        $shipping = $this->_parameter['shipping'];
        if ($shipping < 0) {
            throw new \Exception('Erro 412 (Precondition Failed). Shipping value cannot be negative.');
        }

        $total = $this->_parameter['total'];
        if ($total <= 0) {
            throw new \Exception('Erro 412 (Precondition Failed). Order total cannot be less than or equal to zero.');
        }

        $postal_code = substr(preg_replace("/[^0-9]/", '', $this->_parameter['postcode']), 0, 8);
        $line1 = html_entity_decode($this->utf8_substr($this->_parameter['address'], 0, 100));
        $city = html_entity_decode($this->utf8_substr($this->_parameter['city'], 0, 64));
        $state = html_entity_decode($this->utf8_substr($this->_parameter['zone'], 0, 40));
        $country = html_entity_decode($this->utf8_substr($this->_parameter['country'], 0, 2));

        $return_url = preg_replace('/[^A-Za-z0-9-:_?=.\/]/', '', $this->_parameter['return_url']);
        if (($return_url == '') || (strlen($return_url) > 600)) {
            throw new \Exception('Erro 412 (Precondition Failed). The payment return URL is not valid.');
        }

        $cancel_url = preg_replace('/[^A-Za-z0-9-:_?=.\/]/', '', $this->_parameter['cancel_url']);
        if (($cancel_url == '') || (strlen($cancel_url) > 600)) {
            throw new \Exception('Erro 412 (Precondition Failed). The URL for canceled payment is not valid.');
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
                'locale' => $locale,
                'shipping_preference' => 'SET_PROVIDED_ADDRESS',
            ],
            'transactions' => [
                [
                    'amount' => [
                        'currency' => $currency_code,
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
                            'country_code' => $country,
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
        $inputs = ['sandbox', 'client_id', 'client_secret', 'country_code', 'payment_id', 'payer_id'];
        $parameters = $this->validateParameters($inputs);
        if ($parameters == false) {
            throw new \Exception('Erro 412 (Precondition Failed). The parameter ' . $this->_input . ' was not sent to execute payment on PayPal.');
        }

        $payment_id = trim($this->_parameter['payment_id']);
        if (empty($payment_id)) {
            throw new \Exception('Erro 412 (Precondition Failed). The payment_id has not been set.');
        }

        $payer_id = trim($this->_parameter['payer_id']);
        if (empty($payer_id)) {
            throw new \Exception('Erro 412 (Precondition Failed). The payer_id has not been set.');
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
        $inputs = ['sandbox', 'client_id', 'client_secret', 'country_code', 'sale_id', 'amount', 'currency', 'invoice_number'];
        $parameters = $this->validateParameters($inputs);
        if ($parameters == false) {
            throw new \Exception('Erro 412 (Precondition Failed). The parameter ' . $this->_input . ' was not sent to request a refund on PayPal.');
        }

        $sale_id = trim($this->_parameter['sale_id']);
        if (empty($sale_id)) {
            throw new \Exception('Erro 412 (Precondition Failed). The sale_id has not been set.');
        }

        $amount = $this->_parameter['amount'];
        if ($amount <= 0) {
            throw new \Exception('Erro 412 (Precondition Failed). The refund amount is not valid.');
        }

        $currency = trim($this->_parameter['currency']);
        if (empty($currency)) {
            throw new \Exception('Erro 412 (Precondition Failed). The currency has not been set.');
        }

        $invoice_number = $this->_parameter['invoice_number'];
        if ($invoice_number == '') {
            throw new \Exception('Erro 412 (Precondition Failed). The invoice_number has not been set.');
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
        $inputs = ['sandbox', 'client_id', 'client_secret', 'country_code', 'sale_id'];
        $parameters = $this->validateParameters($inputs);
        if ($parameters == false) {
            throw new \Exception('Erro 412 (Precondition Failed). The parameter ' . $this->_input . ' was not sent to check order status on PayPal.');
        }

        $sale_id = trim($this->_parameter['sale_id']);
        if (empty($sale_id)) {
            throw new \Exception('Erro 412 (Precondition Failed). The sale_id has not been set.');
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
        $inputs = ['sandbox', 'client_id', 'client_secret', 'country_code', 'payment_id'];
        $parameters = $this->validateParameters($inputs);
        if ($parameters == false) {
            throw new \Exception('Erro 412 (Precondition Failed). The parameter ' . $this->_input . ' was not sent to check payment status on PayPal.');
        }

        $payment_id = trim($this->_parameter['payment_id']);
        if (empty($payment_id)) {
            throw new \Exception('Erro 412 (Precondition Failed). The payment_id has not been set.');
        }

        $this->_authorization = 'Authorization: Bearer ' . $this->getToken();
        $this->_content_type = 'application/json';
        $this->_resource = '/v1/payments/payment/' . $payment_id;
        $this->_method = 'GET';
        $this->_request = '';

        return $this->connect();
    }

    /**
     * Get access_token to authenticate request to API
     *
     * @throws \Exception
     * @return object
     */
    private function getToken() {
        $client_id = trim($this->_parameter['client_id']);
        if (empty($client_id)) {
            throw new \Exception('Erro 412 (Precondition Failed). The Client ID has not been set.');
        }

        $client_secret = trim($this->_parameter['client_secret']);
        if (empty($client_secret)) {
            throw new \Exception('Erro 412 (Precondition Failed). The Secret has not been set.');
        }

        $this->_authorization = 'Authorization: Basic ' . base64_encode($client_id . ':' . $client_secret);
        $this->_content_type = 'application/x-www-form-urlencoded';
        $this->_resource = '/v1/oauth2/token';
        $this->_method = 'POST';
        $this->_request = 'grant_type=client_credentials';

        $response = $this->connect();

        if (!isset($response->access_token)) {
            throw new \Exception('Erro 412 (Precondition Failed). Access_token was not generated by the PayPal API.');
        }

        return $response->access_token;
    }

    /**
     * Connect API
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
                throw new \Exception('Erro 412 (Precondition Failed). It was not possible to decode the json received through the PayPal API.');
            }

            return $json;
        }

        switch ($code) {
            case '401':
                throw new \Exception('Erro 401 (Unauthorized). Check in your PayPal account that your Client ID and Secret are valid.');
            case '403':
                throw new \Exception('Erro 403 (Forbidden). Check in your PayPal account that your Client ID and Secret are valid.');
            case '404':
                throw new \Exception('Erro 404 (Resource Not Found). The requested resource was not found in the PayPal API.');
            case '405':
                throw new \Exception('Erro 405 (Method Not Supported). The method used was not implemented in the PayPal API.');
            case '406':
                throw new \Exception('Erro 406 (Media Type Not Acceptable). The requested response format is not available in the PayPal API.');
            case '408':
                throw new \Exception('Erro 408 (Request Timed Out). PayPal API timeout.');
            case '413':
                throw new \Exception('Erro 413 (Request Entity Too Long). The request exceeds the maximum size allowed by the PayPal API.');
            case '415':
                throw new \Exception('Erro 415 (Unsupported Media Type). The submitted content format was not accepted by the PayPal API.');
            case '422':
                throw new \Exception('Erro 422 (Unprocessable Entity). The request could not be processed in the PayPal API.');
            case '429':
                throw new \Exception('Erro 429 (Rate Limit Reached). You have reached the request limit for the PayPal API.');
            case '500':
                throw new \Exception('Erro 500 (Internal Server Error). The PayPal API did not respond due to technical problems. Contact PayPal for more information.');
            case '501':
                throw new \Exception('Erro 501 (Not Implemented). The PayPal API did not respond due to technical problems. Contact PayPal for more information.');
            case '503':
                throw new \Exception('Erro 503 (Service Unavailable). The PayPal API did not respond due to temporary maintenance. Contact PayPal for more information.');
            case '504':
                throw new \Exception('Erro 504 (Gateway Timeout). Time out for requesting in PayPal API. Contact PayPal for more information.');
            default:
                throw new \Exception('Erro não identificado.');
        }
    }

    /**
     * Checks basic requirements
     *
     * @throws \Exception
     * @return void
     */
    private function validateRequirements() {
        if (phpversion() < '7.3') {
            throw new \Exception('Erro 412 (Precondition Failed). At least PHP 7.3 is required to communicate with a PayPal API.');
        }

        if (extension_loaded('mbstring')) {
            mb_internal_encoding('UTF-8');
        } else {
            throw new \Exception('Erro 412 (Precondition Failed). The mbstring PHP extension is disabled on your hosting. Contact your hosting support, and ask them to enable the extension.');
        }

        if (!extension_loaded('curl')) {
            throw new \Exception('Erro 412 (Precondition Failed). The PHP curl extension is disabled on your hosting. Contact your hosting support, and ask them to enable the extension.');
        }

        if (!extension_loaded('json')) {
            throw new \Exception('Erro 412 (Precondition Failed). The PHP json extension is disabled on your hosting. Contact your hosting support and ask them to enable the extension.');
        }

        if (!function_exists('json_encode')) {
            throw new \Exception('Erro 412 (Precondition Failed). PHP json_encode function is disabled on your hosting. Contact your hosting support and ask them to enable the function.');
        }

        if (!function_exists('json_decode')) {
            throw new \Exception('Erro 412 (Precondition Failed). PHP json_decode function is disabled on your hosting. Contact your hosting support and ask them to enable the function.');
        }
    }

    /**
     * Validates that all parameters expected by request have been sent
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
     * Returns part of UTF-8 string
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
