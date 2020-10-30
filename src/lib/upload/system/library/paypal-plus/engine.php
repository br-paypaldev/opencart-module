<?php
trait PayPalPlusEngine
{
    /*
    * Versão atual da extensão.
    */
    private static $version = '1.0.0';

    /*
    * URL do repositório no Github.
    */
    private static $repository = 'https://api.github.com/repos/br-paypaldev/opencart-module/releases/latest';

    /*
    * Retorna a versão atual da extensão.
    */
    public function getPayPalPlusVersion()
    {
        return self::$version;
    }

    /*
    * Retorna um array com erros dos requisitos.
    */
    public function getPayPalPlusRequirements()
    {
        return $this->checkPayPalPlusRequirements();
    }

    /*
    * Verifica se há upgrade para a extensão.
    * Retorna false ou a última versão.
    */
    public function getPayPalPlusUpgrade()
    {
        $latest_version = $this->checkPayPalPlusLatestVersion();

        if ($latest_version > $this->getPayPalPlusVersion()) {
            return $latest_version;
        }

        return false;
    }

    /*
    * Executa a análise de requisitos.
    * Retorna um array vazio ou com erros.
    */
    private function checkPayPalPlusRequirements()
    {
        $alerts = [];

        if (phpversion() < '7.3') {
            $alerts[] = 'Deve ser utilizado no mínimo PHP 7.3.';
        }

        if (!extension_loaded('curl')) {
            $alerts[] = 'A extensão cURL do PHP precisa ser habilitada.';
        }

        if (!extension_loaded('json')) {
            $alerts[] = 'A extensão json do PHP precisa ser habilitada.';
        }

        if (!extension_loaded('mbstring')) {
            $alerts[] = 'A extensão mbstring do PHP precisa ser habilitada.';
        }

        if (!class_exists('DateTime')) {
            $alerts[] = 'A classe DateTime do PHP precisa ser habilitada.';
        }

        if (!function_exists('json_encode')) {
            $alerts[] = 'A função json_encode do PHP precisa ser habilitada.';
        }

        if (!function_exists('json_decode')) {
            $alerts[] = 'A função json_decode do PHP precisa ser habilitada.';
        }

        return $alerts;
    }

    /*
    * Conecta no repositório do Github.
    * Retorna false ou o número da versão.
    */
    private function checkPayPalPlusLatestVersion() {
        if (!self::$repository)
            return false;

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, self::$repository);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_USERAGENT, "curl");

            ob_start();
            curl_exec($ch);
            curl_close($ch);
            $lines = ob_get_contents();
            ob_end_clean();
            $json = json_decode($lines, true);

            if (!$json || !isset($json['tag_name'])) {
                return false;
            }

            $version = $json['tag_name'];

            return (substr($version, 0, 1) == 'v') ? substr($version, 1) : false;
        } catch (Error $e) {
            if ($this->logger === null) {
                $this->logger = new Log('paypal_plus_' . date('Y-m-d') . '.log');
            }

            $this->logger->write($e->getMessage());
        }
    }
}
