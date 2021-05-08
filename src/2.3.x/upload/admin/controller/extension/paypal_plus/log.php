<?php
class ControllerExtensionPayPalPlusLog extends Controller {
    private $error = array();

    const NAME = 'paypal_plus';
    const LOG = 'extension/paypal_plus/log';

    public function index() {
        $data = $this->load->language(self::LOG);

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addScript('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.3/locales.min.js');

        $config_admin_language = $this->config->get('config_admin_language');

        $data['calendar_language'] = 'en-gb';

        if ($config_admin_language == 'pt-br') {
            $data['calendar_language'] = 'pt-br';
        } elseif ($config_admin_language == 'es-mx' || $config_admin_language == 'es-es') {
            $data['calendar_language'] = 'es';
        }

        if (isset($this->session->data['error'])) {
            $data['error_warning'] = $this->session->data['error'];

            unset($this->session->data['error']);
        } elseif (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['empty'])) {
            $data['error_empty'] = $this->session->data['empty'];

            unset($this->session->data['empty']);
        } else {
            $data['error_empty'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

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
            'href' => $this->url->link(self::LOG, 'token=' . $this->session->data['token'], true)
        );

        $data['log'] = '';

        $files = $this->getLogFiles();

        $filter_date = $this->request->get['filter_date'] ?? date('Y-m-d');

        $file = $files[$filter_date] ?? null;

        if (file_exists($file)) {
            $size = filesize($file);

            if ($size >= 5242880) {
                $suffix = array(
                    'B',
                    'KB',
                    'MB',
                    'GB',
                    'TB',
                    'PB',
                    'EB',
                    'ZB',
                    'YB'
                );

                $i = 0;

                while (($size / 1024) > 1) {
                    $size = $size / 1024;
                    $i++;
                }

                $data['error_warning'] = sprintf($this->language->get('error_warning'), basename($file), round(substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i]);
            } else {
                $data['log'] = file_get_contents($file, FILE_USE_INCLUDE_PATH, null);
            }
        }

        $data['dates'] = !empty($files) ? json_encode(array_keys($files)) : json_encode(array(0));

        $data['action'] = $this->url->link(self::LOG, 'token=' . $this->session->data['token'], true);

        $data['filter_date'] = $filter_date;

        $data['download'] = $this->url->link(self::LOG . '/download', 'token=' . $this->session->data['token'] . '&filter_date=' . $filter_date, true);
        $data['clear'] = $this->url->link(self::LOG . '/clear', 'token=' . $this->session->data['token'], true);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view(self::LOG, $data));
    }

    public function download() {
        $this->load->language(self::LOG);

        if (!$this->user->hasPermission('modify', self::LOG)) {
            $this->session->data['error'] = $this->language->get('error_permission');
        } else {
            $filter_date = $this->request->get['filter_date'] ?? '';
            $file = DIR_LOGS . self::NAME . '_' . $filter_date . '.log';

            if (file_exists($file) && filesize($file) > 0) {
                $this->response->addheader('Pragma: public');
                $this->response->addheader('Expires: 0');
                $this->response->addheader('Content-Description: File Transfer');
                $this->response->addheader('Content-Type: application/octet-stream');
                $this->response->addheader('Content-Disposition: attachment; filename="' . self::NAME . '_' . $filter_date . '.log"');
                $this->response->addheader('Content-Transfer-Encoding: binary');

                $this->response->setOutput(file_get_contents($file, FILE_USE_INCLUDE_PATH, null));
            } else {
                $this->session->data['empty'] = $this->language->get('error_empty');

                $this->response->redirect($this->url->link(self::LOG, 'token=' . $this->session->data['token'], true));
            }
        }
    }

    public function clear() {
        $this->load->language(self::LOG);

        if (!$this->user->hasPermission('modify', self::LOG)) {
            $this->session->data['error'] = $this->language->get('error_permission');
        } else {
            foreach($this->getLogFiles() as $file) {
                unlink($file);
            }

            $this->session->data['success'] = $this->language->get('text_success');
        }

        $this->response->redirect($this->url->link(self::LOG, 'token=' . $this->session->data['token'], true));
    }

    private function getLogFiles() {
        $files = array();

        $fsIterator = new FilesystemIterator(DIR_LOGS);

        while ($fsIterator->valid()) {
            $filename = $fsIterator->getFilename();

            if ($fsIterator->isFile() && strpos($filename, self::NAME . '_') !== false) {
                preg_match('/.+(\d{4}-\d{2}-\d{2})/', $filename, $date);
                $files[$date[1]] = $fsIterator->getPathname();
            }

            $fsIterator->next();
        }

        return $files;
    }
}
