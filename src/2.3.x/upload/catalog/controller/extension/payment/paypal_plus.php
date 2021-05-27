<?php
class ControllerExtensionPaymentPayPalPlus extends Controller {
    const TYPE = '';
    const NAME = 'paypal_plus';
    const CODE = self::TYPE . self::NAME;
    const EXTENSION = 'extension/payment/' . self::NAME;
    const MODEL = 'model_extension_payment_paypal_plus';

    public function index() {
        $data = $this->load->language(self::EXTENSION);

        $data['error'] = '';

        $errors_register = $this->validate_register();

        if (empty($errors_register)) {
            $payment = $this->payment();

            if ($payment) {
                $mode = ($this->config->get(self::CODE . '_sandbox')) ? 'sandbox' : 'live';
                $country_code = $this->config->get(self::CODE . '_country');

                $data['mode'] = $mode;
                $data['approval_url'] = $payment['href'];
                $data['payment_id'] = $payment['id'];
                $data['payerFirstName'] = $payment['firstname'];
                $data['payerLastName'] = $payment['lastName'];
                $data['payerEmail'] = $payment['email'];
                $data['payerPhone'] = $payment['telephone'];
                $data['card_id'] = $this->config->get(self::CODE . '_save_card') ? $payment['card_id'] : '';
                $data['disallowRememberedCards'] = $this->config->get(self::CODE . '_save_card') ? 'false' : 'true';

                if ($country_code == 'BR') {
                    $data['payerTaxId'] = $payment['document'];
                    $data['payerTaxIdType'] = 'BR_CPF';
                    $data['language'] = 'pt_BR';
                    $data['country'] = 'BR';
                } elseif ($country_code == 'MX') {
                    $data['payerTaxId'] = '';
                    $data['payerTaxIdType'] = '';
                    $data['language'] = 'es_MX';
                    $data['country'] = 'MX';
                } elseif ($country_code == 'US') {
                    $data['payerTaxId'] = '';
                    $data['payerTaxIdType'] = '';
                    $data['language'] = 'en_US';
                    $data['country'] = 'US';
                }

                $data['information'] = '';
                if ($this->config->get(self::CODE . '_information_id')) {
                    $this->load->model('catalog/information');
                    $information_info = $this->model_catalog_information->getInformation($this->config->get(self::CODE . '_information_id'));

                    if ($information_info) {
                        $data['information'] = html_entity_decode($information_info['description'], ENT_QUOTES, 'UTF-8');
                    }
                }

                $data['button_text'] = $this->config->get(self::CODE . '_button_text');
                $data['button_style'] = $this->config->get(self::CODE . '_button_style');
            } else {
                $data['error'] = $this->language->get('error_configuration');
            }
        } else {
            $data['error'] = sprintf($this->language->get('error_register'), $errors_register);
        }

        return $this->load->view(self::EXTENSION, $data);
    }

    private function payment() {
        $this->language->load(self::EXTENSION);

        $order_id = $this->session->data['order_id'];

        $this->load->model('checkout/order');
        $order_info = $this->model_checkout_order->getOrder($order_id);

        $country_code = $this->config->get(self::CODE . '_country');
        $currency_code = strtoupper($order_info['currency_code']);
        $currency_value = $order_info['currency_value'];
        $store_name = $order_info['store_name'];

        $this->load->model(self::EXTENSION);

        $subtotal = 0;
        $parameters = array();
        $parameters['items'] = array();

        $products = $this->{self::MODEL}->getOrderProducts($order_id);

        foreach ($products as $product) {
            $quantity = $product['quantity'];
            $price = $this->currency->format($product['price'], $currency_code, $currency_value, false);
            $price = number_format($price, 2, '.', '');

            $subtotal += $price * $quantity;

            $parameters['items'][] = array(
                'sku' => $product['product_id'],
                'name' => $product['name'],
                'description' => $product['name'],
                'quantity' => $quantity,
                'price' => $price,
                'url' => $this->url->link('product/product', 'product_id=' . $product['product_id'], true)
            );
        }

        if (!empty($this->session->data['vouchers'])) {
            foreach ($this->session->data['vouchers'] as $voucher) {
                $price = $this->currency->format($voucher['amount'], $currency_code, $currency_value, false);
                $price = number_format($price, 2, '.', '');

                $subtotal += $price;

                $parameters['items'][] = array(
                    'sku' => 'voucher',
                    'name' => $voucher['description'],
                    'description' => $voucher['description'],
                    'quantity' => '1',
                    'price' => $price,
                    'url' => ''
                );
            }
        }

        $discount_description = '';
        $discount_value = 0;

        $taxes_fees = array('tax', 'low_order_fee', 'handling');

        $taxes_fees_description = '';
        $taxes_fees_value = 0;

        $order_totals = $this->{self::MODEL}->getOrderTotals($order_id);
        foreach ($order_totals as $order_total) {
            if ($order_total['value'] < 0) {
                $discount_description .= $order_total['title'] . '/';
                $discount_value += abs($order_total['value']);
            }

            if (in_array($order_total['code'], $taxes_fees)) {
                $taxes_fees_description .= $order_total['title'] . '/';
                $taxes_fees_value += $order_total['value'];
            }
        }

        if ($discount_value > 0) {
            $discount_description = rtrim($discount_description, '/');

            $price = $this->currency->format($discount_value, $currency_code, $currency_value, false);
            $price = number_format($price, 2, '.', '');

            $subtotal -= $price;

            $parameters['items'][] = array(
                'sku' => 'discount',
                'name' => $discount_description,
                'description' => $discount_description,
                'quantity' => '1',
                'price' => '-' . $price,
                'url' => ''
            );
        }

        if ($taxes_fees_value > 0) {
            $taxes_fees_description = rtrim($taxes_fees_description, '/');

            $price = $this->currency->format($taxes_fees_value, $currency_code, $currency_value, false);
            $price = number_format($price, 2, '.', '');

            $subtotal += $price;

            $parameters['items'][] = array(
                'sku' => 'taxes',
                'name' => $taxes_fees_description,
                'description' => $taxes_fees_description,
                'quantity' => '1',
                'price' => $price,
                'url' => ''
            );
        }

        $shipping = $this->{self::MODEL}->getOrderShippingValue($order_id);

        if ($shipping > 0) {
            $shipping = $this->currency->format($shipping, $currency_code, $currency_value, false);
        }

        $fields = $this->fields();

        $columns = array();

        if ($country_code == 'BR') {
            $custom_document_id = $this->config->get(self::CODE . '_custom_document_id');
            $document_column = $this->config->get(self::CODE . '_document_column');

            if (in_array($custom_document_id, $fields) && $custom_document_id == 'C') {
                array_push($columns, $document_column);
            }
        }

        $custom_number_id = $this->config->get(self::CODE . '_custom_number_id');
        $number_payment_column = $this->config->get(self::CODE . '_number_payment_column');

        if ($custom_number_id == 'C') {
            array_push($columns, $number_payment_column);
        }

        $custom_complement_id = $this->config->get(self::CODE . '_custom_complement_id');
        $complement_payment_column = $this->config->get(self::CODE . '_complement_payment_column');

        if ($custom_complement_id == 'C') {
            array_push($columns, $complement_payment_column);
        }

        $has_shipping = $this->cart->hasShipping();

        if ($has_shipping) {
            $number_shipping_column = $this->config->get(self::CODE . '_number_shipping_column');

            if ($custom_number_id == 'C') {
                array_push($columns, $number_shipping_column);
            }

            $complement_shipping_column = $this->config->get(self::CODE . '_complement_shipping_column');

            if ($custom_complement_id == 'C') {
                array_push($columns, $complement_shipping_column);
            }
        }

        $columns_info = array();
        if (count($columns)) {
            $columns_info = $this->{self::MODEL}->getOrder($columns, $order_id);
        }

        $document_number = '';
        if ($country_code == 'BR') {
            if (in_array($custom_document_id, $fields)) {
                $document_number = $this->value_field(
                    $order_info['custom_field'],
                    $custom_document_id,
                    $columns_info,
                    $document_column
                );
            }
        }

        $payment_number = $this->value_field(
            $order_info['payment_custom_field'],
            $custom_number_id,
            $columns_info,
            $number_payment_column
        );

        $payment_complement = $this->value_field(
            $order_info['payment_custom_field'],
            $custom_complement_id,
            $columns_info,
            $complement_payment_column
        );

        $address_parts = array($order_info['payment_address_1']);

        if (!empty($payment_number)) {
            array_push($address_parts, $payment_number);
        }

        if (!empty($payment_complement)) {
            array_push($address_parts, $payment_complement);
        }

        if (!empty($order_info['payment_address_2']) && $order_info['payment_address_2'] != $order_info['payment_address_1']) {
            array_push($address_parts, $order_info['payment_address_2']);
        }

        $address = implode(" ", $address_parts);
        $postcode = $order_info['payment_postcode'];
        $city = $order_info['payment_city'];
        $zone = $order_info['payment_zone_code'];
        $country = $order_info['payment_iso_code_2'];
        $prefix = $this->config->get(self::CODE . '_prefix')[$order_info['store_id']] ? $this->config->get(self::CODE . '_prefix')[$order_info['store_id']] : '';

        if (!empty($prefix)) {
            $invoice_number = $prefix . '-' . str_pad($order_id, 6, '0', STR_PAD_LEFT);
        } else {
            $invoice_number = str_pad($order_id, 6, '0', STR_PAD_LEFT);
        }

        if ($has_shipping) {
            $shipping_number = $this->value_field(
                $order_info['shipping_custom_field'],
                $custom_number_id,
                $columns_info,
                $number_shipping_column
            );

            $shipping_complement = $this->value_field(
                $order_info['shipping_custom_field'],
                $custom_complement_id,
                $columns_info,
                $complement_shipping_column
            );

            $address_parts = array($order_info['shipping_address_1']);

            if (!empty($shipping_number)) {
                array_push($address_parts, $shipping_number);
            }

            if (!empty($shipping_complement)) {
                array_push($address_parts, $shipping_complement);
            }

            if (!empty($order_info['shipping_address_2']) && $order_info['shipping_address_2'] != $order_info['shipping_address_1']) {
                array_push($address_parts, $order_info['shipping_address_2']);
            }

            $address = implode(" ", $address_parts);
            $postcode = $order_info['shipping_postcode'];
            $city = $order_info['shipping_city'];
            $zone = $order_info['shipping_zone_code'];
            $country = $order_info['shipping_iso_code_2'];
        }

        $parameters['sandbox'] = $this->config->get(self::CODE . '_sandbox');
        $parameters['client_id'] = $this->config->get(self::CODE . '_client_id');
        $parameters['client_secret'] = $this->config->get(self::CODE . '_client_secret');

        $parameters['description'] = $this->config->get(self::CODE . '_description');
        $parameters['invoice_number'] = $invoice_number;
        $parameters['country_code'] = $country_code;
        $parameters['currency_code'] = $currency_code;

        $parameters['subtotal'] = number_format($subtotal, 2, '.', '');
        $parameters['shipping'] = number_format($shipping, 2, '.', '');
        $parameters['total'] = number_format($parameters['subtotal'] + $parameters['shipping'], 2, '.', '');

        $parameters['store_name'] = $store_name;
        $parameters['postcode'] = $postcode;
        $parameters['address'] = $address;
        $parameters['city'] = $city;
        $parameters['zone'] = $zone;
        $parameters['country'] = $country;

        $parameters['return_url'] = $this->url->link(self::EXTENSION . '/return', '', true);
        $parameters['cancel_url'] = $this->url->link(self::EXTENSION . '/return', '', true);

        $error = array();

        try {
            require_once(DIR_SYSTEM . 'library/paypal-plus/paypal_plus.php');
            $paypal = new PayPalPlus();
            $paypal->setParameters($parameters);
            $response = $paypal->setPayment();

        } catch (Error | Exception $e) {
            $this->debug($parameters);

            $error = array(
                $e->getCode(),
                $e->getMessage()
            );

            $this->debug(implode(PHP_EOL, $error));
        }

        if (!$error) {
            $card_id = $this->{self::MODEL}->getCardId($order_info['customer_id']);

            if ($response) {
                $this->debug(json_encode(json_decode($paypal->getResponse()), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

                if (isset($response->links)) {
                    return array(
                        'href' => $response->links[1]->href,
                        'id' => $response->id,
                        'firstname' => trim($order_info['firstname']),
                        'lastName' => trim($order_info['lastname']),
                        'email' => strtolower($order_info['email']),
                        'document' => preg_replace("/[^0-9]/", '', $document_number),
                        'telephone' => preg_replace("/[^0-9]/", '', $order_info['telephone']),
                        'card_id' => trim($card_id)
                    );
                }
            }
        }

        return array();
    }

    public function saveCardId($_customer_id, $card_id) {
        $this->load->model(self::EXTENSION);
        $this->{self::MODEL}->saveCardId($_customer_id, $card_id);
    }

    private function addTransaction($order_id, $response, $installments) {
        $inputs = $this->dataTransaction($order_id, $response, $installments);

        $this->load->model(self::EXTENSION);
        $this->{self::MODEL}->addTransaction($inputs);
    }

    private function updateTransaction($order_id, $response, $installments) {
        $inputs = $this->dataTransaction($order_id, $response, $installments);

        $this->load->model(self::EXTENSION);
        $this->{self::MODEL}->updateTransaction($inputs);
    }

    private function dataTransaction($order_id, $response, $installmentsValue = 1) {
        if (isset($response->credit_financing_offered->term)) {
            $installments = $response->credit_financing_offered->term;
        } else {
            $installments = $installmentsValue;
        }

        $inputs = array(
            'order_id' => $order_id,
            'payment_id' => $response->id,
            'invoice_number' => $response->transactions[0]->invoice_number,
            'sale_id' => $response->transactions[0]->related_resources[0]->sale->id,
            'total_value' => $response->transactions[0]->related_resources[0]->sale->amount->total,
            'installments' => $installments,
            'status' => $response->transactions[0]->related_resources[0]->sale->state,
            'json' => json_encode($response, JSON_HEX_QUOT|JSON_HEX_APOS)
        );

        return $inputs;
    }

    public function execute() {
        $json = array();

        if (isset($this->request->post['pp-payment-id']) && isset($this->request->post['pp-payer-id'])) {
            $payment_id = filter_var($this->request->post['pp-payment-id'], FILTER_SANITIZE_STRING);
            $payer_id = filter_var($this->request->post['pp-payer-id'], FILTER_SANITIZE_STRING);
            $installments = filter_var($this->request->post['pp-installments'], FILTER_SANITIZE_STRING);
            $card_id = filter_var($this->request->post['pp-rememberedcards'], FILTER_SANITIZE_STRING);

            $parameters['sandbox'] = $this->config->get(self::CODE . '_sandbox');
            $parameters['client_id'] = $this->config->get(self::CODE . '_client_id');
            $parameters['client_secret'] = $this->config->get(self::CODE . '_client_secret');
            $parameters['country_code'] = $this->config->get(self::CODE . '_country');
            $parameters['payment_id'] = $payment_id;
            $parameters['payer_id'] = $payer_id;

            $error = array();

            try {
                require_once(DIR_SYSTEM . 'library/paypal-plus/paypal_plus.php');
                $paypal = new PayPalPlus();
                $paypal->setParameters($parameters);
                $response = $paypal->setExecute();

            } catch (Error | Exception $e) {
                $this->debug($parameters);

                $error = array(
                    $e->getCode(),
                    $e->getMessage()
                );

                $this->debug(implode(PHP_EOL, $error));
            }

            if (!$error) {
                if ($response) {
                    $this->debug(json_encode(json_decode($paypal->getResponse()), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

                    $this->language->load(self::EXTENSION);

                    if (isset($response->state)) {
                        $order_id = $this->session->data['order_id'];

                        $this->load->model('checkout/order');
                        $order_info = $this->model_checkout_order->getOrder($order_id);

                        if ($order_info) {
                            $state = $response->state;

                            $this->addTransaction($order_id, $response, $installments);
                            if ($this->config->get(self::CODE . '_save_card') == 1) {
                                $this->saveCardId($order_info['customer_id'], $card_id);
                            }

                            switch ($state) {
                                case 'created':
                                    $order_status_id = $this->config->get(self::CODE . '_order_status_pending_id');
                                    $comment = $this->language->get('text_pending');

                                    break;
                                case 'failed':
                                    $order_status_id = $this->config->get(self::CODE . '_order_status_denied_id');
                                    $comment = $this->language->get('text_denied');

                                    break;
                                case 'approved':
                                    $sale_state = $response->transactions[0]->related_resources[0]->sale->state;

                                    if ($sale_state = 'completed') {
                                        $order_status_id = $this->config->get(self::CODE . '_order_status_completed_id');
                                        $comment = $this->language->get('text_completed');
                                    } else {
                                        $order_status_id = $this->config->get(self::CODE . '_order_status_analyze_id');
                                        $comment = $this->language->get('text_analyze');
                                    }

                                    break;
                            }

                            if ($order_info['order_status_id'] != $order_status_id) {
                                try {
                                    $this->model_checkout_order->addOrderHistory($order_id, $order_status_id, $comment, true);
                                } catch (Error | Exception $e) {
                                    $error = array(
                                        $e->getMessage()
                                    );

                                    $this->debug(implode(PHP_EOL, $error));
                                }
                            }

                            if ($state == 'approved') {
                                $json['redirect'] = $this->url->link('checkout/success', '', true);
                            }
                        }
                    } elseif (isset($response->name)) {
                        $error_name = $response->name;

                        if ($error_name == 'INTERNAL_SERVICE_ERROR') {
                            $error_message = $this->language->get('error_api');
                        } elseif ($error_name == 'VALIDATION_ERROR') {
                            $error_message = $this->language->get('error_order');
                        } elseif ($error_name == 'INSTRUMENT_DECLINED') {
                            $error_message = $this->language->get('error_card_issuer');
                        } else {
                            $error_message = $this->language->get('error_paypal');
                        }

                        $json['error'] = $error_message;
                    }
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function search() {
        $json = array();

        if (isset($this->request->post['pp-payment-id']) && isset($this->request->post['pp-payer-id'])) {
            $payment_id = filter_var($this->request->post['pp-payment-id'], FILTER_SANITIZE_STRING);
            $payer_id = filter_var($this->request->post['pp-payer-id'], FILTER_SANITIZE_STRING);
            $installments = filter_var($this->request->post['pp-installments'], FILTER_SANITIZE_STRING);

            $parameters['sandbox'] = $this->config->get(self::CODE . '_sandbox');
            $parameters['client_id'] = $this->config->get(self::CODE . '_client_id');
            $parameters['client_secret'] = $this->config->get(self::CODE . '_client_secret');
            $parameters['country_code'] = $this->config->get(self::CODE . '_country');
            $parameters['payment_id'] = $payment_id;
            $parameters['payer_id'] = $payer_id;

            $error = array();

            try {
                require_once(DIR_SYSTEM . 'library/paypal-plus/paypal_plus.php');
                $paypal = new PayPalPlus();
                $paypal->setParameters($parameters);
                $response = $paypal->getSearch();

            } catch (Error | Exception $e) {
                $this->debug($parameters);

                $error = array(
                    $e->getCode(),
                    $e->getMessage()
                );

                $this->debug(implode(PHP_EOL, $error));
            }

            if (!$error) {
                if ($response) {
                    $this->debug(json_encode(json_decode($paypal->getResponse()), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

                    $this->language->load(self::EXTENSION);

                    if (isset($response->state)) {
                        $order_id = $this->session->data['order_id'];

                        $this->load->model('checkout/order');
                        $order_info = $this->model_checkout_order->getOrder($order_id);

                        if ($order_info) {

                            $state = $response->state;

                            $this->updateTransaction($order_id, $response, $installments);

                            switch ($state) {
                                case 'created':
                                    $order_status_id = $this->config->get(self::CODE . '_order_status_pending_id');
                                    $comment = $this->language->get('text_pending');

                                    break;
                                case 'failed':
                                    $order_status_id = $this->config->get(self::CODE . '_order_status_denied_id');
                                    $comment = $this->language->get('text_denied');

                                    break;
                                case 'approved':
                                    $sale_state = $response->transactions[0]->related_resources[0]->sale->state;

                                    if ($sale_state = 'completed') {
                                        $order_status_id = $this->config->get(self::CODE . '_order_status_completed_id');
                                        $comment = $this->language->get('text_completed');
                                    } else {
                                        $order_status_id = $this->config->get(self::CODE . '_order_status_analyze_id');
                                        $comment = $this->language->get('text_analyze');
                                    }

                                    break;
                            }

                            if ($order_info['order_status_id'] != $order_status_id) {
                                try {
                                    $this->model_checkout_order->addOrderHistory($order_id, $order_status_id, $comment, true);
                                } catch (Error | Exception $e) {
                                    $error = array(
                                        $e->getMessage()
                                    );

                                    $this->debug(implode(PHP_EOL, $error));
                                }
                            }

                            if ($state == 'approved') {
                                $json['redirect'] = $this->url->link('checkout/success', '', true);
                            }
                        }
                    } elseif (isset($response->name)) {
                        $error_name = $response->name;

                        if ($error_name == 'INTERNAL_SERVICE_ERROR') {
                            $error_message = $this->language->get('error_api');
                        } elseif ($error_name == 'VALIDATION_ERROR') {
                            $error_message = $this->language->get('error_order');
                        } elseif ($error_name == 'INSTRUMENT_DECLINED') {
                            $error_message = $this->language->get('error_card_issuer');
                        } else {
                            $error_message = $this->language->get('error_paypal');
                        }

                        $json['error'] = $error_message;
                    }
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function return() {
        $this->debug(file_get_contents('php://input'));
        $this->debug($this->request->get);
        $this->debug($this->request->post);

        $this->response->redirect($this->url->link('common/home', '', true));
    }

    public function webhooks() {
        $headers = $this->getWebhookHeaders();
        $body = $this->getWebhookBody();

        if (empty($headers) || empty($body)) {
            return;
        }

        $webhook_id = $this->config->get(self::CODE . '_webhook_id');

        if (empty($webhook_id)) {
            $this->debug($this->language->get('log_webhook_id_invalid'));

            return;
        }

        $json = '{
            "transmission_id": "'. $headers['PAYPAL-TRANSMISSION-ID'] .'",
            "transmission_time": "'. $headers['PAYPAL-TRANSMISSION-TIME'] .'",
            "cert_url": "'. $headers['PAYPAL-CERT-URL'] .'",
            "auth_algo": "'. $headers['PAYPAL-AUTH-ALGO'] .'",
            "transmission_sig": "'. $headers['PAYPAL-TRANSMISSION-SIG'] .'",
            "webhook_id": "' . $webhook_id . '",
            "webhook_event": ' . $body . '
        }';

        $signature = $this->verifyWebhookSignature($json);

        if (!$signature) {
            return;
        }

        $status = '';
        $sale_id = '';
        $force_update = false;

        $body_data = json_decode($body, true);

        if (isset($body_data['resource']['id'])) {
            $sale_id = $body_data['resource']['id'];
        }

        switch ($body_data['event_type']) {
            case 'PAYMENT.SALE.COMPLETED':
                $status = 'completed';

                break;
            case 'PAYMENT.SALE.DENIED':
                $status = 'denied';
                $force_update = true;

                break;
            case 'PAYMENT.SALE.REFUNDED':
                $status = 'refunded';
                $force_update = true;
                $sale_id = $body_data['resource']['sale_id'];

                break;
            case 'PAYMENT.SALE.REVERSED':
                $status = 'reversed';
                $force_update = true;

                break;
            case 'PAYMENT.SALE.PENDING':
                $status = 'pending';

                break;
            case 'CUSTOMER.DISPUTE.CREATED':
                $status = 'dispute';
                $force_update = true;
                $sale_id = $body_data['resource']['disputed_transactions'][0]['seller_transaction_id'];

                break;
        }

        if (empty($status)) {
            $this->debug($this->language->get('log_status_empty'));

            return;
        }

        if (empty($sale_id)) {
            $this->debug($this->language->get('log_sale_id_empty'));

            return;
        }

        $this->load->model(self::EXTENSION);
        $transaction = $this->{self::MODEL}->getTransactionByWebhooks($sale_id);

        if (!isset($transaction['order_id'])) {
            $this->debug($this->language->get('log_order_id_empty'));

            return;
        }

        $order_id = (int) $transaction['order_id'];

        if ($body_data['event_type'] == 'PAYMENT.SALE.REFUNDED') {
            $total_value = (float) $transaction['total_value'];
            $total_refunded = (float) $body_data['resource']['total_refunded_amount']['value'];

            if ($total_value > $total_refunded) {
                $status = 'partially_refund';
            }
        }

        $this->{self::MODEL}->updateTransactionByWebhooks($sale_id, $status);

        $update_order_history = true;

        $order_history = $this->{self::MODEL}->getOrderHitoryStatusId($order_id);
        $new_order_status_id = $this->config->get(self::CODE . '_order_status_' . $status . '_id');

        foreach ($order_history as $history) {
            if ($history['current_order_status_id'] == $new_order_status_id) {
                $update_order_history = false;

                break;
            }

            if (
                $history['order_status_id'] == $new_order_status_id &&
                $force_update == false
            ) {
                $update_order_history = false;

                break;
            }
        }

        if ($update_order_history == true) {
            $this->load->model('checkout/order');

            try {
                $this->model_checkout_order->addOrderHistory($order_id, $new_order_status_id, '', true);
            } catch (Error | Exception $e) {
                $error = array(
                    $e->getMessage()
                );

                $this->debug(implode(PHP_EOL, $error));
            }
        }

        $this->debug($this->language->get('log_success'));
    }

    private function getWebhookHeaders() {
        $all_headers = array();
        $headers = array();

        if (function_exists('getallheaders')) {
            $all_headers = getallheaders();

            if (count($all_headers)) {
                $headers = array_change_key_case($all_headers, CASE_UPPER);
            }
        } else {
            if (is_array($_SERVER)) {
                $all_headers = $_SERVER;

                $server = array_change_key_case($all_headers, CASE_UPPER);

                foreach ($server as $name => $value) {
                    if (substr($name, 0, 11) == 'HTTP_PAYPAL') {
                        $key = str_replace('_', '-', substr($name, 5));
                        $headers[$key] = $value;
                    }
                }
            }
        }

        if (
            isset($headers['PAYPAL-TRANSMISSION-ID']) &&
            isset($headers['PAYPAL-TRANSMISSION-TIME']) &&
            isset($headers['PAYPAL-CERT-URL']) &&
            isset($headers['PAYPAL-AUTH-ALGO']) &&
            isset($headers['PAYPAL-TRANSMISSION-SIG'])
        ) {
            return $headers;
        } else {
            if (count($all_headers)) {
                $this->debug(implode(PHP_EOL, array('Webhook Header:', print_r($all_headers, true), )));
            }

            $this->debug($this->language->get('log_header_invalid'));

            return;
        }
    }

    private function getWebhookBody() {
        $body = file_get_contents('php://input');

        $this->debug(implode(PHP_EOL, array('Webhook Body:', $body)));

        if (empty($body)) {
            $this->debug($this->language->get('log_body_empty'));

            return;
        }

        $json = json_decode($body);

        if (
            isset($json->id) &&
            isset($json->resource) &&
            isset($json->event_type)
        ) {
            return $body;
        } else {
            $this->debug($this->language->get('log_body_invalid'));

            return;
        }
    }

    private function verifyWebhookSignature($json) {
        $parameters['sandbox'] = $this->config->get(self::CODE . '_sandbox');
        $parameters['client_id'] = $this->config->get(self::CODE . '_client_id');
        $parameters['client_secret'] = $this->config->get(self::CODE . '_client_secret');
        $parameters['country_code'] = $this->config->get(self::CODE . '_country');
        $parameters['method'] = 'POST';
        $parameters['endpoint'] = 'verify-webhook-signature';
        $parameters['json'] = $json;

        try {
            require_once(DIR_SYSTEM . 'library/paypal-plus/paypal_plus.php');
            $paypal = new PayPalPlus();
            $paypal->setParameters($parameters);
            $response = $paypal->changeWebhook();

            if (
                isset($response->verification_status) &&
                $response->verification_status == 'SUCCESS'
            ) {
                $this->debug($this->language->get('log_signature_success'));

                return true;
            } else {
                $this->debug('Webhook Signature:' . PHP_EOL . $json);

                $this->debug('Webhook Verify:' . PHP_EOL . json_encode($response, JSON_PRETTY_PRINT));

                $this->debug($this->language->get('log_signature_fail'));
            }
        } catch (Error | Exception $e) {
            $this->debug($parameters);

            $error = array(
                $e->getCode(),
                $e->getMessage()
            );

            $this->debug(implode(PHP_EOL, $error));
        }

        return false;
    }

    private function update_order() {
        $order_data['custom_field'] = array();

        if ($this->customer->isLogged()) {
            $this->load->model('account/customer');
            $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
            $order_data['custom_field'] = json_decode($customer_info['custom_field'], true);
        } elseif (isset($this->session->data['guest'])) {
            $order_data['custom_field'] = $this->session->data['guest']['custom_field'];
        }

        $order_data['payment_custom_field'] = (isset($this->session->data['payment_address']['custom_field']) ? $this->session->data['payment_address']['custom_field'] : array());

        if ($this->cart->hasShipping()) {
            $order_data['shipping_custom_field'] = (isset($this->session->data['shipping_address']['custom_field']) ? $this->session->data['shipping_address']['custom_field'] : array());
        } else {
            $order_data['shipping_custom_field'] = array();
        }

        $this->load->model(self::EXTENSION);
        $this->{self::MODEL}->editOrder($order_data, $this->session->data['order_id']);
    }

    private function fields() {
        if ($this->customer->isLogged()) {
            $customer_group_id = $this->customer->getGroupId();
        } elseif (isset($this->session->data['guest']['customer_group_id'])) {
            $customer_group_id = $this->session->data['guest']['customer_group_id'];
        } else {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }

        $this->load->model('account/custom_field');
        $custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

        $fields = array();
        foreach ($custom_fields as $custom_field) {
            array_push($fields, $custom_field['custom_field_id']);
        }

        return $fields;
    }

    private function value_field($custom_data, $field_key, $collumn_data, $field_collumn) {
        $field_value = '';

        if ($field_key == 'C') {
            if (isset($collumn_data[$field_collumn]) && !empty($collumn_data[$field_collumn])) {
                $field_value = $collumn_data[$field_collumn];
            }
        } elseif (!empty($field_key) && is_array($custom_data)) {
            foreach ($custom_data as $key => $value) {
                if ($field_key == $key) { $field_value = $value; }
            }
        }

        return $field_value;
    }

    private function validate_register() {
        $this->load->language('payment/paypal_plus_validation');

        $this->update_order();

        $country_code = $this->config->get(self::CODE . '_country');

        if ($country_code == 'BR') {
            $custom_document_id = $this->config->get(self::CODE . '_custom_document_id');
            $document_column = $this->config->get(self::CODE . '_document_column');

            $custom_number_id = $this->config->get(self::CODE . '_custom_number_id');
            $number_column = $this->config->get(self::CODE . '_number_payment_column');
        }

        $columns = array();
        $columns_info = array();

        $fields = $this->fields();

        if ($country_code == 'BR') {
            if (in_array($custom_document_id, $fields) && $custom_document_id == 'C') { array_push($columns, $document_column); }
            if ($custom_number_id == 'C') { array_push($columns, $number_column); }
        }

        $order_id = $this->session->data['order_id'];

        if (count($columns)) {
            $this->load->model(self::EXTENSION);
            $columns_info = $this->{self::MODEL}->getOrder($columns, $order_id);
        }

        $this->load->model('checkout/order');
        $order_info = $this->model_checkout_order->getOrder($order_id);

        $errors = array();

        $customer = trim($order_info['firstname'] . ' ' . $order_info['lastname']);
        if (empty($customer)) {
            $errors[] = $this->language->get('error_customer');
        }

        if ($country_code == 'BR') {
            $document = $this->value_field($order_info['custom_field'], $custom_document_id, $columns_info, $document_column);

            $document = preg_replace("/[^0-9]/", '', $document);
            $document = strlen($document);
            if ($document == 14 || $document == 11) {
            } else {
                $errors[] = $this->language->get('error_document');
            }

            $telephone = strlen(preg_replace("/[^0-9]/", '', trim($order_info['telephone'])));
            if ($telephone < 10 || $telephone > 11) {
                $errors[] = $this->language->get('error_telephone');
            }

            $number = $this->value_field($order_info['payment_custom_field'], $custom_number_id, $columns_info, $number_column);
            $number = preg_replace("/[^0-9]/", '', $number);
            if (strlen($number) < 1) {
                $errors[] = $this->language->get('error_payment_number');
            }

            $payment_address_2 = trim($order_info['payment_address_2']);
            if (empty($payment_address_2)) {
                $errors[] = $this->language->get('error_payment_address_2');
            }

            $payment_postcode = preg_replace("/[^0-9]/", '', trim($order_info['payment_postcode']));
            if (strlen($payment_postcode) != 8) {
                $errors[] = $this->language->get('error_payment_postcode');
            }
        } else {
            $telephone = preg_replace("/[^0-9]/", '', trim($order_info['telephone']));
            if (empty($telephone)) {
                $errors[] = $this->language->get('error_telephone');
            }

            $payment_postcode = preg_replace("/[^0-9]/", '', trim($order_info['payment_postcode']));
            if (empty($payment_postcode)) {
                $errors[] = $this->language->get('error_payment_postcode');
            }
        }

        $payment_address_1 = trim($order_info['payment_address_1']);
        if (empty($payment_address_1)) {
            $errors[] = $this->language->get('error_payment_address_1');
        }

        $payment_city = trim($order_info['payment_city']);
        if (empty($payment_city)) {
            $errors[] = $this->language->get('error_payment_city');
        }

        $payment_zone_code = trim($order_info['payment_zone_code']);
        if (empty($payment_zone_code)) {
            $errors[] = $this->language->get('error_payment_zone_code');
        }

        if (count($errors) > 0) {
            $result = '';

            foreach ($errors as $key => $value) {
                $result .= $value;
            }

            return $result;
        } else {
            return '';
        }
    }

    private function debug($data) {
        if (!$this->config->get(self::CODE . '_debug')) return;

        if ($this->logger === null) {
            $this->logger = new Log(self::NAME . '_' . date('Y-m-d') . '.log');
        }

        $this->logger->write($data);
    }
}
