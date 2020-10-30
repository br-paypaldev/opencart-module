<?php
require_once DIR_SYSTEM . 'library/paypal-plus/engine.php';

class ControllerExtensionPaymentPayPalPlus extends Controller {
    use PayPalPlusEngine;

    const NAME = 'paypal_plus';
    const CODE = self::NAME;
    const EXTENSION = 'extension/payment/' . self::NAME;
    const EXTENSIONS = 'extension/extension';
    const PERMISSION = 'extension/paypal_plus';
    const MODEL = 'model_extension_payment_paypal_plus';

    private $error = array();

    public function index() {
        $data = $this->load->language(self::EXTENSION);

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addStyle('//cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css');
        $this->document->addScript('//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js');
        $this->document->addScript('//cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js');

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting(self::CODE, $this->prepareInputs());

            $this->update();

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link(self::EXTENSIONS, 'token=' . $this->session->data['token'] . '&type=payment', true));
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $data['requirements'] = $this->getPayPalPlusRequirements();

        $data['version'] = $this->getPayPalPlusVersion();

        $data['token'] = $this->session->data['token'];

        $errors = array(
            'warning',
            'customer_groups',
            'stores',
            'prefix',
            'client_id',
            'client_secret',
            'document',
            'number_payment',
            'number_shipping',
            'complement_payment',
            'complement_shipping',
            'extension_title',
            'button_text'
        );

        foreach ($errors as $error) {
            if (isset($this->error[$error])) {
                $data['error_' . $error] = $this->error[$error];
            } else {
                $data['error_' . $error] = '';
            }
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link(self::EXTENSIONS, 'token=' . $this->session->data['token'] . '&type=payment', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link(self::EXTENSION, 'token=' . $this->session->data['token'], true)
        );

        $data['action'] = $this->url->link(self::EXTENSION, 'token=' . $this->session->data['token'], true);

        $data['cancel'] = $this->url->link(self::EXTENSIONS, 'token=' . $this->session->data['token'] . '&type=payment', true);

        $lib = DIR_SYSTEM . 'library/paypal-plus/paypal_plus.php';
        if (is_file($lib)) {
            if (!is_readable($lib)) {
                chmod($lib, 0644);
            }
        }

        $fields = array(
            'customer_groups' => array(0),
            'total' => '',
            'geo_zone_id' => '',
            'status' => '',
            'sort_order' => '',
            'stores' => array(0),
            'prefix' => '',
            'client_id' => '',
            'client_secret' => '',
            'sandbox' => '',
            'debug' => '',
            'description' => '',
            'extension_discount' => '',
            'order_status_pending_id' => '',
            'order_status_denied_id' => '',
            'order_status_analyze_id' => '',
            'order_status_completed_id' => '',
            'order_status_refunded_id' => '',
            'order_status_dispute_id' => '',
            'order_status_reversed_id' => '',
            'custom_document_id' => '',
            'document_column' => '',
            'custom_number_id' => '',
            'number_payment_column' => '',
            'number_shipping_column' => '',
            'custom_complement_id' => '',
            'complement_payment_column' => '',
            'complement_shipping_column' => '',
            'extension_title' => '',
            'information_id' => '',
            'button_style' => 'primary',
            'button_text' => 'Confirmar pagamento'
        );

        foreach ($fields as $key => $value) {
            if (isset($this->request->post[$key])) {
                $data[$key] = $this->request->post[$key];
            } else {
                $value = ($this->config->get(self::CODE . '_' . $key)) ? $this->config->get(self::CODE . '_' . $key) : $value;
                $data[$key] = $value;
            }
        }

        if (strlen($data['client_id']) < 6 || strlen($data['client_secret']) < 6) {
            $data['info_webhook'] = $this->language->get('info_webhook_credencials');
            $data['hide_webhook'] = true;
        }

        $this->load->model('customer/customer_group');
        $data['customer_groups_data'] = $this->model_customer_customer_group->getCustomerGroups();

        $this->load->model('localisation/geo_zone');
        $data['geo_zones_data'] = $this->model_localisation_geo_zone->getGeoZones();

        $data['default_store'] = $this->config->get('config_name');
        $this->load->model('setting/store');
        $data['stores_data'] = $this->model_setting_store->getStores();

        if ($data['prefix'] != '') {
            foreach ($data['prefix'] as $key => $value) {
                $data['new_prefix'][$key] = $value;
            }
        } else {
            $data['new_prefix'][0] = '';
            foreach ($data['stores_data'] as $store) {
                $data['new_prefix'][$store['store_id']] = '';
            }
        }

        $this->load->model('localisation/order_status');
        $data['order_statuses_data'] = $this->model_localisation_order_status->getOrderStatuses();

        $data['custom_fields_data'] = array();
        $this->load->model('customer/custom_field');
        $custom_fields = $this->model_customer_custom_field->getCustomFields();
        foreach ($custom_fields as $custom_field) {
            $data['custom_fields_data'][] = array(
                'custom_field_id' => $custom_field['custom_field_id'],
                'name' => $custom_field['name'],
                'type' => $custom_field['type'],
                'location' => $custom_field['location']
            );
        }

        $this->load->model(self::EXTENSION);
        $data['columns_data'] = $this->{self::MODEL}->getColumns();

        $this->load->model('catalog/information');
        $data['informations'] = $this->model_catalog_information->getInformations();

        $data['styles'] = array(
            'default' => $this->language->get('text_btn_default'),
            'primary' => $this->language->get('text_btn_primary'),
            'success' => $this->language->get('text_btn_success'),
            'info' => $this->language->get('text_btn_info'),
            'warning' => $this->language->get('text_btn_warning'),
            'danger' => $this->language->get('text_btn_danger')
        );

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view(self::EXTENSION, $data));
    }

    public function listWebhooksEventTypes() {
        $this->load->language(self::EXTENSION);

        $method = 'GET';
        $endpoint = 'webhooks-event-types';
        $json = '';

        $webhooks = $this->webhooks($method, $endpoint, $json);

        if (isset($webhooks->event_types)) {
            $list = array();

            foreach($webhooks->event_types as $event_type) {
                $code = array("PAYMENT", "CUSTOMER", ".");
                $language = array("text", "text", "_");

                if (
                    preg_match('/^PAYMENT.SALE./', $event_type->name) ||
                    preg_match('/CUSTOMER.DISPUTE.CREATED/', $event_type->name)
                ) {
                    $event = array();
                    $event[] = '<input type="checkbox" class="editor-active" value="' . $event_type->name . '">';
                    $event[] = $event_type->name;
                    $event[] = $this->language->get(strtolower(str_replace($code, $language , $event_type->name)));
                    $event[] = $this->language->get('text_' . strtolower($event_type->status));

                    $list[] = $event;
                }
            }

            $output = array("data" => $list);

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($output));
        } else {
            $data['error'] = $this->language->get('error_webhook_event_types');
        }
    }

    public function listWebhooks() {
        $this->load->language(self::EXTENSION);

        $method = 'GET';
        $endpoint = 'webhooks';
        $json = '';

        $webhooks = $this->webhooks($method, $endpoint, $json);
        if (isset($webhooks->webhooks)) {
            $list = array();

            foreach($webhooks->webhooks as $webhook) {
                if ($webhook->url == HTTPS_CATALOG . 'index.php?route=' . self::EXTENSION .'/webhooks') {
                    $webhook_id = $webhook->id;
                }

                $sub_array = array();
                $sub_array[] = $webhook->id;
                $sub_array[] = $webhook->url;
                $sub_array[] = '<div class="text-center"><button type="button" class="btn btn-danger" id="del-' . $webhook->id . '" onclick="deleteWebhook(\'' . $webhook->id . '\');"><i class="fa fa-trash-o"></i></button></div>';
                $list[] = $sub_array;
            }

            if (!empty($list)) {
                $output = array("data" => $list);

                if (isset($webhook_id)) {
                    $output['webhook_id'] = $webhook_id;
                }
            } else {
                $output['empty'] = true;
            }
        } else {
            $data['error'] = $this->language->get('error_webhook_indisponible');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($output));
    }

    public function addWebhooks() {
        $this->load->language(self::EXTENSION);

        if ($_SERVER['HTTP_HOST'] != 'localhost' && $_SERVER['HTTP_HOST'] != '127.0.0.1') {
            $url = HTTPS_CATALOG . 'index.php?route=' . self::EXTENSION .'/webhooks';
            $method = 'POST';
            $endpoint = 'webhooks';
            $event_types = $this->request->post['event_types'];
            $json = str_replace('\\/', '/',json_encode(array(
                'url' => $url,
                'event_types' => $event_types
            )));

            $webhook = $this->webhooks($method, $endpoint, $json);
            if (isset($webhook->id)) {
                $data['success'] = $this->language->get('text_webhook_created');
            } else {
                $data['error'] = $webhook->message;
            }
        } else {
            $data['error'] = $this->language->get('error_webhook_localhost');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function deleteWebhook() {
        $this->load->language(self::EXTENSION);

        if (isset($this->request->post['webhook_id'])) {
            $method = "DELETE";
            $endpoint = 'webhooks/' . $this->request->post['webhook_id'];
            $json = '';

            $this->webhooks($method, $endpoint, $json);

            $data['success'] = $this->language->get('text_webhook_deleted');
        } else {
            $data['error'] = $this->language->get('error_webhook_remove');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    private function webhooks($method, $endpoint, $json) {
        $parameters['sandbox'] = $this->config->get(self::CODE . '_sandbox');
        $parameters['client_id'] = $this->config->get(self::CODE . '_client_id');
        $parameters['client_secret'] = $this->config->get(self::CODE . '_client_secret');
        $parameters['method'] = $method;
        $parameters['endpoint'] = $endpoint;
        $parameters['json'] = $json;

        $error = array();

        try {
            require_once(DIR_SYSTEM . 'library/paypal-plus/paypal_plus.php');
            $paypal = new PayPalPlus();
            $paypal->setParameters($parameters);
            $response = $paypal->changeWebhook();
        } catch (Exception $e) {
            $error = array(
                $e->getMessage()
            );
        }

        if (!$error) {
            if ($response) {
                return $response;
            }
        }
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', self::EXTENSION)) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ($this->getPayPalPlusRequirements()) {
            $this->error['warning'] = $this->language->get('error_requirements');
        }

        if (empty($this->request->post['customer_groups'])) {
            $this->error['customer_groups'] = $this->language->get('error_customer_groups');
        }

        if (empty($this->request->post['stores'])) {
            $this->error['stores'] = $this->language->get('error_stores');
        }

        if (!empty($this->request->post['stores']) && count($this->request->post['stores']) > 1) {
            foreach ($this->request->post['stores'] as $store) {
                if (empty($this->request->post['prefix'][$store])) {
                    $this->error['prefix'][$store] = $this->language->get('error_prefix');
                }
            }
        }

        $errors = array(
            'client_id',
            'client_secret',
            'extension_title',
            'button_text'
        );

        foreach ($errors as $error) {
            if (!(trim($this->request->post[$error]))) {
                $this->error[$error] = $this->language->get('error_required');
            }
        }

        $errors_field = array(
            'document'
        );

        foreach ($errors_field as $error) {
            if ($this->request->post['custom_' . $error . '_id'] == 'C') {
                if (!(trim($this->request->post[$error . '_column']))) {
                    $this->error[$error] = $this->language->get('error_field_column');
                }
            }
        }

        $errors_field_number = array(
            'number_payment',
            'number_shipping'
        );

        if ($this->request->post['custom_number_id'] == 'C') {
            foreach ($errors_field_number as $error) {
                if (!(trim($this->request->post[$error . '_column']))) {
                    $this->error[$error] = $this->language->get('error_field_column');
                }
            }
        }

        $errors_field_complement = array(
            'complement_payment',
            'complement_shipping'
        );

        if ($this->request->post['custom_complement_id'] == 'C') {
            foreach ($errors_field_complement as $error) {
                if (!(trim($this->request->post[$error . '_column']))) {
                    $this->error[$error] = $this->language->get('error_field_column');
                }
            }
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    private function prepareInputs() {
        $values = array_values($this->request->post);
        $keys = array_map(
            function($input) {
                return self::CODE . '_' . $input;
            }, array_keys($this->request->post)
        );

        return array_combine($keys, $values);
    }

    public function install() {
        $this->load->model(self::EXTENSION);
        $this->{self::MODEL}->update();
    }

    public function uninstall() {
        $this->load->model('user/user_group');
        $this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', self::PERMISSION . '/transaction');
        $this->model_user_user_group->removePermission($this->user->getGroupId(), 'modify', self::PERMISSION . '/transaction');
        $this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', self::PERMISSION . '/log');
        $this->model_user_user_group->removePermission($this->user->getGroupId(), 'modify', self::PERMISSION . '/log');
    }

    public function update() {
        $this->load->model(self::EXTENSION);
        $this->{self::MODEL}->update();

        if (!$this->user->hasPermission('modify', self::PERMISSION . '/transaction')) {
            $this->load->model('user/user_group');
            $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', self::PERMISSION . '/transaction');
            $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', self::PERMISSION . '/transaction');
            $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', self::PERMISSION . '/log');
            $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', self::PERMISSION . '/log');
        }
    }
}
