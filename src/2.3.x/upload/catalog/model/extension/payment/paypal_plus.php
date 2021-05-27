<?php
class ModelExtensionPaymentPayPalPlus extends Model {
    const TYPE = '';
    const NAME = 'paypal_plus';
    const CODE = self::TYPE . self::NAME;

    public function getMethod($address, $total) {
        $query = $this->db->query("
            SELECT * FROM `" . DB_PREFIX . "zone_to_geo_zone`
            WHERE geo_zone_id = '" . (int) $this->config->get(self::CODE . '_geo_zone_id') . "'
              AND country_id = '" . (int) $address['country_id'] . "'
              AND (zone_id = '" . (int) $address['zone_id'] . "' OR zone_id = '0')
        ");

        if ($total <= 0) {
            $status = false;
        } elseif ($this->config->get(self::CODE . '_total') > 0 && $this->config->get(self::CODE . '_total') > $total) {
            $status = false;
        } elseif (!$this->config->get(self::CODE . '_geo_zone_id')) {
            $status = true;
        } elseif ($query->num_rows) {
            $status = true;
        } else {
            $status = false;
        }

        $currencies = array('BRL', 'MXN', 'USD');
        $currency_code = $this->session->data['currency'];
        if (!in_array(strtoupper($currency_code), $currencies)) {
            $status = false;
        }

        if (!in_array($this->config->get('config_store_id'), $this->config->get(self::CODE . '_stores'))) {
            $status = false;
        }

        if ($this->customer->isLogged()) {
            $customer_group_id = $this->customer->getGroupId();
        } elseif (isset($this->session->data['guest']['customer_group_id'])) {
            $customer_group_id = $this->session->data['guest']['customer_group_id'];
        } else {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }
        if (!in_array($customer_group_id, $this->config->get(self::CODE . '_customer_groups'))) {
            $status = false;
        }

        $method_data = array();

        if ($status) {
            $method_data = array(
                'code' => self::NAME,
                'title' => $this->config->get(self::CODE . '_extension_title'),
                'terms' => '',
                'sort_order' => $this->config->get(self::CODE . '_sort_order')
            );
        }

        return $method_data;
    }

    public function getOrder($data, $order_id) {
        $columns = implode(", ", array_values($data));

        $query = $this->db->query("
            SELECT " . $columns . "
            FROM `" . DB_PREFIX . "order`
            WHERE order_id = '" . (int) $order_id . "'
        ");

        if ($query->num_rows) {
            return $query->row;
        }

        return array();
    }

    public function getOrderProducts($order_id) {
        $query = $this->db->query("
            SELECT p.product_id, p.tax_class_id, op.name, op.price, op.quantity
            FROM `" . DB_PREFIX . "order_product` op
            INNER JOIN `" . DB_PREFIX . "product` p ON (op.product_id = p.product_id)
            WHERE op.order_id = '" . (int) $order_id . "'
        ");

        return $query->rows;
    }

    public function getOrderHitoryStatusId($order_id) {
        $query = $this->db->query("
            SELECT h.order_status_id, o.order_status_id AS current_order_status_id
            FROM `" . DB_PREFIX . "order_history` h
            inner join `" . DB_PREFIX . "order` o ON o.order_id = h.order_id
            WHERE h.order_id = '" . (int) $order_id . "'
        ");

        return $query->rows;
    }

    public function getOrderTotals($order_id) {
        $query = $this->db->query("
            SELECT * FROM `" . DB_PREFIX . "order_total`
            WHERE order_id = '" . (int) $order_id . "'
        ");

        return $query->rows;
    }

    public function getOrderShippingValue($order_id) {
        $value = 0;

        $query = $this->db->query("
            SELECT *
            FROM `" . DB_PREFIX . "order_total`
            WHERE order_id = '" . (int) $order_id . "'
            ORDER BY sort_order ASC
        ");

        $totals = $query->rows;
        foreach ($totals as $total) {
            if ($total['value'] > 0) {
                if ($total['code'] == "shipping") {
                    $value += $total['value'];
                }
            }
        }

        return $value;
    }

    public function editOrder($data, $order_id) {
        if (is_array($data) && (count($data) > 0) && ($order_id > '0')) {
            $this->db->query("
                UPDATE `" . DB_PREFIX . "order`
                SET custom_field = '" . $this->db->escape(json_encode($data['custom_field'])) . "',
                    payment_custom_field = '" . $this->db->escape(json_encode($data['payment_custom_field'])) . "',
                    shipping_custom_field = '" . $this->db->escape(json_encode($data['shipping_custom_field'])) . "'
                WHERE order_id = '" . (int) $order_id . "'
            ");
        }
    }

    public function addTransaction($data) {
        if (is_array($data) && (count($data) > 0)) {
            $columns = implode(", ", array_keys($data));
            $values = "'" . implode("', '", array_values($data)) . "'";

            $this->db->query("
                INSERT INTO `" . DB_PREFIX . "order_paypal_plus`
                ($columns) VALUES ($values)
            ");
        }
    }

    public function updateTransaction($data) {
        if (is_array($data) && (count($data) > 0)) {
            $this->db->query("
                UPDATE `" . DB_PREFIX . "order_paypal_plus`
                SET `payment_id` = '" . $this->db->escape($data['payment_id']) . "',
                    `invoice_number` = '". $this->db->escape($data['invoice_number']) ."',
                    `sale_id` = '" . $this->db->escape($data['sale_id']) . "',
                    `total_value` = '" . $this->db->escape($data['total_value']) . "',
                    `installments` = '" . (int) $data['installments'] . "',
                    `status` = '" . $this->db->escape($data['status']) . "',
                    `json` = '" . $this->db->escape($data['json']) . "'
                WHERE `order_id` = " . (int) $data['order_id']
            );
        }
    }

    public function getTransactionByWebhooks($sale_id) {
        $query = $this->db->query("
            SELECT order_id, total_value
            FROM `" . DB_PREFIX . "order_paypal_plus`
            WHERE `sale_id` = '" . $this->db->escape($sale_id) . "'
        ");

        if ($query->num_rows) {
            return $query->row;
        }

        return false;
    }

    public function updateTransactionByWebhooks($sale_id, $status) {
        $this->db->query("
            UPDATE `" . DB_PREFIX . "order_paypal_plus`
            SET `status` = '" . $this->db->escape($status) . "'
            WHERE `sale_id` = '" . $this->db->escape($sale_id) . "'
        ");
    }

    public function getCardId($customer_id) {
        $query = $this->db->query("
            SELECT card_id
            FROM `" . DB_PREFIX . "customer_paypal_plus`
            WHERE `customer_id` = '" . $this->db->escape($customer_id) . "' ORDER BY date_added DESC LIMIT 0,1
        ");

        if ($query->num_rows) {
            return $query->row['card_id'];
        }

        return false;
    }

    public function saveCardId($customer_id, $card_id) {
        $this->db->query("
            INSERT INTO `" . DB_PREFIX . "customer_paypal_plus`
            (customer_id, card_id)
            VALUES ('" . $this->db->escape($customer_id) . "', '" . $this->db->escape($card_id) . "')
        ");
    }
}
