<?php
class ControllerExtensionPayPalPlusTransaction extends Controller {
    const TYPE = '';
    const NAME = 'paypal_plus';
    const CODE = self::TYPE . self::NAME;
    const EXTENSION = 'extension/payment/' . self::NAME;
    const TRANSACTION = 'extension/paypal_plus/transaction';
    const MODEL = 'model_extension_payment_paypal_plus';

    private $error = array();

    public function index() {
        $data = $this->load->language(self::TRANSACTION);

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addScript('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.3/locales.min.js');

        $config_admin_language = $this->config->get('config_admin_language');

        $data['calendar_language'] = 'en-gb';
        $data['datatables_language'] = '//cdn.datatables.net/plug-ins/1.10.21/i18n/English.json';

        if ($config_admin_language == 'pt-br') {
            $data['calendar_language'] = 'pt-br';
            $data['datatables_language'] = '//cdn.datatables.net/plug-ins/1.10.21/i18n/Portuguese-Brasil.json';
        } elseif ($config_admin_language == 'es-mx' || $config_admin_language == 'es-es') {
            $data['calendar_language'] = 'es';
            $data['datatables_language'] = '//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json';
        }

        $this->document->addStyle('//cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css');
        $this->document->addStyle('//cdn.datatables.net/buttons/1.6.2/css/buttons.bootstrap.min.css');
        $this->document->addScript('//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js');
        $this->document->addScript('//cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js');
        $this->document->addScript('//cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js');
        $this->document->addScript('//cdn.datatables.net/buttons/1.6.2/js/buttons.bootstrap.min.js');
        $this->document->addScript('//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js');
        $this->document->addScript('//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js');
        $this->document->addScript('//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js');
        $this->document->addScript('//cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js');
        $this->document->addScript('//cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js');
        $this->document->addScript('//cdn.datatables.net/buttons/1.6.2/js/buttons.colVis.min.js');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_paypal_plus'),
            'href' => ''
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link(self::TRANSACTION, 'token=' . $this->session->data['token'], true)
        );

        $sandbox = $this->config->get(self::CODE . '_sandbox');

        $filters = array();

        if (isset($this->request->get['filter_initial_date']) && $this->validateDate($this->request->get['filter_initial_date'])) {
            $filters['filter_initial_date'] = $this->request->get['filter_initial_date'];
        } else {
            $filters['filter_initial_date'] = (new DateTime('-3 days'))->format('Y-m-d');
        }

        if (isset($this->request->get['filter_final_date']) && $this->validateDate($this->request->get['filter_final_date'])) {
            $filters['filter_final_date'] = $this->request->get['filter_final_date'];
        } else {
            $filters['filter_final_date'] = (new DateTime())->format('Y-m-d');
        }

        if (isset($this->request->get['filter_status'])) {
            $filters['filter_status'] = $this->request->get['filter_status'];
        } else {
            $filters['filter_status'] = '';
        }

        $data = array_merge($data, $filters);

        $this->load->model(self::EXTENSION);
        $transactions = $this->{self::MODEL}->getTransactions($filters);

        $data['transactions'] = array();

        foreach ($transactions as $transaction) {
            switch ($transaction['status']) {
                case 'pending':
                    $status = $this->language->get('text_pending');

                    break;
                case 'analyze':
                    $status = $this->language->get('text_analyze');

                    break;
                case 'approved':
                    $status = $this->language->get('text_approved');

                    break;
                case 'completed':
                    $status = $this->language->get('text_completed');

                    break;
                case 'refunded':
                    $status = $this->language->get('text_refunded');

                    break;
                case 'partially_refund':
                    $status = $this->language->get('text_partially_refund');

                    break;
            }

            $action = array();

            $action[] = array(
                'name' => 'button-info',
                'title' => $this->language->get('button_info'),
                'icon' => 'fa fa-info-circle',
                'class' => 'btn btn-info',
                'id' => $transaction['order_paypal_plus_id'],
                'sale_id' => $transaction['sale_id']
            );

            if ($transaction['status'] == 'completed' ||
                $transaction['status'] == 'approved' ||
                $transaction['status'] == 'partially_refund'
            ) {
                $action[] = array(
                    'name' => 'button-refund',
                    'title' => $this->language->get('button_refund'),
                    'icon' => 'fa fa-paypal',
                    'class' => 'btn btn-warning',
                    'id' => $transaction['order_paypal_plus_id'],
                    'sale_id' => $transaction['sale_id']
                );
            }

            if ($sandbox) {
                $action[] = array(
                    'name' => 'button-delete',
                    'title' => $this->language->get('button_delete'),
                    'icon' => 'fa fa-trash-o',
                    'class' => 'btn btn-danger',
                    'id' => $transaction['order_paypal_plus_id'],
                    'sale_id' => $transaction['sale_id']
                );
            }

            $data['transactions'][] = array(
                'id' => $transaction['order_paypal_plus_id'],
                'order_id' => $transaction['order_id'],
                'date_added' => date('d/m/Y H:i:s', strtotime($transaction['date_added'])),
                'customer' => $transaction['customer'],
                'payment_id' => $transaction['payment_id'],
                'total_value' =>  $this->currency->format((float) $transaction['total_value'], $transaction['currency_code'], '1.00'),
                'status' => $status,
                'view_order' => $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $transaction['order_id'], true),
                'action' => $action
            );
        }

        $data['sandbox'] = $sandbox;

        $data['statuses'] = array(
            '' => $this->language->get('text_all'),
            'pending' => $this->language->get('text_pending'),
            'analyze' => $this->language->get('text_analyze'),
            'approved' => $this->language->get('text_approved'),
            'completed' => $this->language->get('text_completed'),
            'refunded' => $this->language->get('text_refunded'),
            'canceled' => $this->language->get('text_canceled'),
            'partially_refund' => $this->language->get('text_partially_refund')
        );

        $data['token'] = $this->session->data['token'];

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view(self::TRANSACTION, $data));
    }

    public function dataTransaction() {
        $json = array();

        $this->load->language(self::TRANSACTION);

        if ($this->user->hasPermission('modify', self::TRANSACTION)) {
            if (isset($this->request->get['paypal_plus_id'])) {
                $paypal_plus_id = (int) $this->request->get['paypal_plus_id'];

                $this->load->model(self::EXTENSION);
                $transaction = $this->{self::MODEL}->getTransaction($paypal_plus_id);

                if ($transaction) {
                    switch ($transaction['status']) {
                        case 'pending':
                            $status = $this->language->get('text_analyze');

                            break;
                        case 'completed':
                            $status = $this->language->get('text_completed');

                            break;
                        case 'approved':
                            $status = $this->language->get('text_approved');

                            break;
                        case 'refunded':
                            $status = $this->language->get('text_refunded');

                            break;
                        case 'partially_refund':
                            $status = $this->language->get('text_partially_refund');

                            break;
                    }

                    $json = array(
                        'payer_name' => $transaction['payer_name'],
                        'payer_email' => $transaction['payer_email'],
                        'invoice_number' => $transaction['invoice_number'],
                        'total_value' => $transaction['total_value'],
                        'total_value_txt'=> $this->currency->format((float) $transaction['total_value'], $transaction['currency_code'], '1.00'),
                        'refunded_value' => (float) $transaction['refunded_value'],
                        'installments' => (int) $transaction['installments'],
                        'refunded_value_txt' => $this->currency->format((float) $transaction['refunded_value'], $transaction['currency_code'], '1.00'),
                        'status' => $status,
                        'json' => json_encode(json_decode($transaction['json']), JSON_PRETTY_PRINT),
                        'paypal_plus_id' => $transaction['order_paypal_plus_id']
                    );

                } else {
                    $json['error'] = $this->language->get('error_search');
                }
            } else {
                $json['error'] = $this->language->get('error_warning');
            }
        } else {
            $json['error'] = $this->language->get('error_permission');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function search() {
        $json = array();

        $this->load->language(self::TRANSACTION);

        if ($this->user->hasPermission('modify', self::TRANSACTION)) {
            if (isset($this->request->get['paypal_plus_id'])) {
                $paypal_plus_id = (int) $this->request->get['paypal_plus_id'];

                $this->load->model(self::EXTENSION);
                $transaction = $this->{self::MODEL}->getTransaction($paypal_plus_id);

                if ($transaction) {
                    $parameters['sandbox'] = $this->config->get(self::CODE . '_sandbox');
                    $parameters['client_id'] = $this->config->get(self::CODE . '_client_id');
                    $parameters['client_secret'] = $this->config->get(self::CODE . '_client_secret');
                    $parameters['country_code'] = $this->config->get(self::CODE . '_country');
                    $parameters['order_id'] = $transaction['order_id'];
                    $parameters['sale_id'] = $transaction['sale_id'];

                    try {
                        require_once(DIR_SYSTEM . 'library/paypal-plus/paypal_plus.php');
                        $paypal = new PayPalPlus();
                        $paypal->setParameters($parameters);
                        $response = $paypal->getSale();

                        $this->debug($response);

                        if (isset($response->id)) {
                            $status = $response->state;

                            $fields = array(
                                'paypal_plus_id' => $transaction['order_paypal_plus_id'],
                                'payment_id' => $response->parent_payment,
                                'total_value' => $response->amount->total,
                                'invoice_number' => isset($response->invoice_number) ? $response->invoice_number : '',
                                'installments' => isset($response->credit_financing_offered->term) ? $response->credit_financing_offered->term : '1',
                                'status' => $status
                            );

                            $this->{self::MODEL}->editTransaction($fields);

                            switch ($status) {
                                case 'pending':
                                    $message = $this->language->get('text_analyze');

                                    break;
                                case 'completed':
                                    $message = $this->language->get('text_completed');

                                    break;
                                case 'approved':
                                    $message = $this->language->get('text_approved');

                                    break;
                            }

                            if (isset($message)) {
                                $json['success'] = $message;
                            } else {
                                $json['success'] = $this->language->get('text_consulted');
                            }
                        } else {
                            $json['error'] = $this->language->get('error_search');
                        }
                    } catch (Error | Exception $e) {
                        $this->debug($parameters);

                        $error = array(
                            $e->getCode(),
                            $e->getMessage()
                        );

                        $this->debug(implode(PHP_EOL, $error));
                    }
                } else {
                    $json['error'] = $this->language->get('error_search');
                }
            } else {
                $json['error'] = $this->language->get('error_warning');
            }
        } else {
            $json['error'] = $this->language->get('error_permission');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function refund() {
        $json = array();

        $this->load->language(self::TRANSACTION);

        if ($this->user->hasPermission('modify', self::TRANSACTION)) {
            if (
                isset($this->request->get['paypal_plus_id']) &&
                isset($this->request->post['refund_value'])
            ) {
                $paypal_plus_id = (int) $this->request->get['paypal_plus_id'];

                $this->load->model(self::EXTENSION);
                $transaction = $this->{self::MODEL}->getTransaction($paypal_plus_id);

                if ($transaction) {
                    $amount = (float) $this->request->post['refund_value'];

                    if ($amount > 0) {
                        $parameters['sandbox'] = $this->config->get(self::CODE . '_sandbox');
                        $parameters['client_id'] = $this->config->get(self::CODE . '_client_id');
                        $parameters['client_secret'] = $this->config->get(self::CODE . '_client_secret');
                        $parameters['country_code'] = $this->config->get(self::CODE . '_country');
                        $parameters['order_id'] = $transaction['order_id'];
                        $parameters['sale_id'] = $transaction['sale_id'];
                        $parameters['amount'] = $amount;
                        $parameters['currency'] = json_decode($transaction['json'])->transactions[0]->related_resources[0]->sale->amount->currency;
                        $parameters['invoice_number'] = $transaction['invoice_number'];

                        try {
                            require_once(DIR_SYSTEM . 'library/paypal-plus/paypal_plus.php');
                            $paypal = new PayPalPlus();
                            $paypal->setParameters($parameters);

                            $response = $paypal->setRefund();

                            $this->debug($response);

                            if (
                                isset($response->state) &&
                                $response->state == 'completed'
                            ) {
                                $total_value = (float) $transaction['total_value'];
                                $total_refunded = (float) $response->total_refunded_amount->value;

                                if ($total_value == $total_refunded) {
                                    $status = 'refunded';
                                } elseif ($total_value > $total_refunded) {
                                    $status = 'partially_refund';
                                } else {
                                    $status = $transaction['status'];
                                }

                                $fields = array(
                                    'paypal_plus_id' => $transaction['order_paypal_plus_id'],
                                    'payment_id' => $response->parent_payment,
                                    'refunded_value' => $total_refunded,
                                    'status' => $status,
                                    'json' => json_encode($response, JSON_HEX_QUOT|JSON_HEX_APOS)
                                );

                                $this->{self::MODEL}->refundTransaction($fields);

                                $json['success'] = $this->language->get('text_solicited_refund');
                            } else {
                                if ($response->name == 'REFUND_EXCEEDED_TRANSACTION_AMOUNT') {
                                    $json['error'] = $this->language->get('error_value');
                                } elseif ($response->name == 'TRANSACTION_ALREADY_REFUNDED') {
                                    $json['error'] = $this->language->get('error_refunded');
                                } else {
                                    $json['error'] = $this->language->get('error_refund');
                                }
                            }
                        } catch (Error | Exception $e) {
                            $this->debug($parameters);

                            $error = array(
                                $e->getCode(),
                                $e->getMessage()
                            );

                            $this->debug(implode(PHP_EOL, $error));
                        }
                    } else {
                        $json['error'] = $this->language->get('error_value_zero');
                    }
                } else {
                    $json['error'] = $this->language->get('error_warning');
                }
            } else {
                $json['error'] = $this->language->get('error_warning');
            }
        } else {
            $json['error'] = $this->language->get('error_permission');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function delete() {
        $json = array();

        $this->load->language(self::TRANSACTION);

        if ($this->user->hasPermission('modify', self::TRANSACTION)) {
            if (isset($this->request->get['paypal_plus_id'])) {
                $paypal_plus_id = (int) $this->request->get['paypal_plus_id'];

                $this->load->model(self::EXTENSION);
                $this->{self::MODEL}->deleteTransaction($paypal_plus_id);

                $json['success'] = $this->language->get('text_excluded');
            } else {
                $json['error'] = $this->language->get('error_warning');
            }
        } else {
            $json['error'] = $this->language->get('error_permission');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    private function validateDate($date, $format = 'Y-m-d'){
        $dt = DateTime::createFromFormat($format, $date);

        return $dt && $dt->format($format) === $date;
    }

    private function debug($data) {
        if (!$this->config->get(self::CODE . '_debug')) return;

        if ($this->logger === null) {
            $this->logger = new Log(self::NAME . '_' . date('Y-m-d') . '.log');
        }

        $this->logger->write($data);
    }
}
