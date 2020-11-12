<?php
class ModelExtensionPaymentPayPalPlus extends Model {
    public function install() {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "order_paypal_plus` (
                `order_paypal_plus_id` INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
                `order_id` INT(11) NULL,
                `payment_id` VARCHAR(35) NULL,
                `sale_id` VARCHAR(20) NULL,
                `total_value` double(10,2),
                `invoice_number` VARCHAR(35) NULL,
                `installments` INT(11) NOT NULL DEFAULT '1',
                `refunded_value` double(10,2) NOT NULL DEFAULT '0.00',
                `status` VARCHAR(16) NULL,
                `json` TEXT NULL,
                `date_added` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
        ");

        $this->db->query("
            CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "customer_paypal_plus` (
                `customer_paypal_plus_id` INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
                `customer_id` INT(11) NULL,
                `card_id` text,
                `date_added` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
        ");
    }

    public function update() {
        $this->install();

        $fields = array();
        $table = array();

        $fields[] = array(
            'order_paypal_plus_id' => 'int(11)',
            'order_id' => 'int(11)',
            'payment_id' => 'varchar(35)',
            'sale_id' => 'varchar(20)',
            'total_value' => 'double(10,2)',
            'installments'=> 'int(11) NOT NULL DEFAULT \'1\'',
            'invoice_number' => 'varchar(35)',
            'refunded_value' => 'double(10,2) NOT NULL DEFAULT \'0.00\'',
            'status' => 'varchar(16)',
            'json' => 'text',
            'date_added' => 'DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP'
        );
        $fields[] = array(
            'customer_paypal_plus_id' => 'int(11)',
            'customer_id' => 'int(11)',
            'card_id' => 'text',
            'date_added' => 'DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP'
        );

        $table[] = "order_paypal_plus";
        $table[] = "customer_paypal_plus";


        for ($i=0; $i < count($fields); $i++) {
            $field_data = array();

            $field_query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . $table[$i] . "`");
            foreach ($field_query->rows as $field) {
                $field_data[$field['Field']] = $field['Type'];
            }

            foreach ($field_data as $key => $value) {
                if (!array_key_exists($key, $fields[$i])) {
                    $this->db->query("ALTER TABLE `" . DB_PREFIX  . $table[$i] . "` DROP COLUMN `" . $key . "`");
                }
            }

            $this->session->data['after_column'] = $table[$i] . '_id';
            foreach ($fields[$i] as $key => $value) {
                if (!array_key_exists($key, $field_data)) {
                    $this->db->query("ALTER TABLE `" . DB_PREFIX . $table[$i] . "` ADD `" . $key . "` " . $value . " AFTER `" . $this->session->data['after_column'] . "`");
                }
                $this->session->data['after_column'] = $key;
            }
            unset($this->session->data['after_column']);

            foreach ($fields[$i] as $key => $value) {
                if ($key == $table[$i] . '_id') {
                    $this->db->query("ALTER TABLE `" . DB_PREFIX . $table[$i] . "` CHANGE COLUMN `" . $key . "` `" . $key . "` " . $value . " NOT NULL AUTO_INCREMENT");
                } else {
                    $this->db->query("ALTER TABLE `" . DB_PREFIX . $table[$i] . "` CHANGE COLUMN `" . $key . "` `" . $key . "` " . $value);
                }
            }
        }
    }

    public function uninstall() {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "order_paypal_plus`;");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "customer_paypal_plus`;");
    }

    public function getColumns() {
        $query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "order`");

        if ($query->num_rows) {
            return $query->rows;
        }

        return array();
    }

    public function getTransactions($filter = array()) {
        $sql = "
            SELECT opp.order_id, opp.order_paypal_plus_id, o.date_added, opp.sale_id, opp.total_value, opp.refunded_value, CONCAT(o.firstname, ' ', o.lastname) as customer, opp.payment_id, opp.status
            FROM `" . DB_PREFIX . "order_paypal_plus` opp
            INNER JOIN `" . DB_PREFIX . "order` o ON (o.order_id = opp.order_id)
            WHERE opp.order_id > '0'
        ";

        if (!empty($filter['filter_initial_date'])) {
            $sql .= " AND DATE(o.date_added) >= DATE('" . $this->db->escape($filter['filter_initial_date']) . "')";
        }

        if (!empty($filter['filter_final_date'])) {
            $sql .= " AND DATE(o.date_added) <= DATE('" . $this->db->escape($filter['filter_final_date']) . "')";
        }

        if (!empty($filter['filter_status'])) {
            $sql .= " AND opp.status = '" . $this->db->escape($filter['filter_status']) . "'";
        }

        $sql .= " ORDER BY opp.order_id DESC;";

        $query = $this->db->query($sql);

        if ($query->num_rows) {
            return $query->rows;
        }

        return array();
    }

    public function getTransaction($paypal_plus_id) {
        $query = $this->db->query("
            SELECT o.store_id, opp.*, CONCAT(o.firstname, ' ', o.lastname) as payer_name, o.email payer_email
            FROM `" . DB_PREFIX . "order_paypal_plus` opp
            INNER JOIN `" . DB_PREFIX . "order` o ON (o.order_id = opp.order_id)
            WHERE opp.order_paypal_plus_id = '" . (int) $paypal_plus_id . "'
        ");

        if ($query->num_rows) {
            return $query->row;
        }

        return array();
    }

    public function editTransaction($data) {
        $this->db->query("
            UPDATE `" . DB_PREFIX . "order_paypal_plus`
            SET payment_id = '" . $this->db->escape($data['payment_id']) . "',
                total_value = " . (float) $data['total_value'] . ",
                invoice_number = '" . $this->db->escape($data['invoice_number']) . "',
                installments = '" . $this->db->escape($data['installments']) . "',
                status = '" . $this->db->escape($data['status']) . "'
            WHERE order_paypal_plus_id = '" . (int) $data['paypal_plus_id'] . "'
        ");
    }

    public function refundTransaction($data) {
        $this->db->query("
            UPDATE `" . DB_PREFIX . "order_paypal_plus`
            SET payment_id = '" . $this->db->escape($data['payment_id']) . "',
                refunded_value = '" . (float) $data['refunded_value'] . "',
                status = '" . $this->db->escape($data['status']) . "'
            WHERE order_paypal_plus_id = '" . (int) $data['paypal_plus_id'] . "'
        ");
    }

    public function deleteTransaction($paypal_plus_id) {
        $this->db->query("
            DELETE FROM `" . DB_PREFIX . "order_paypal_plus`
            WHERE order_paypal_plus_id = '" . (int) $paypal_plus_id . "'"
        );
    }
}
