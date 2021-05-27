<?php
trait PayPalPlusEngine
{
    /*
    * Current version of extension.
    */
    private static $paypal_plus_version = '1.1.1';

    /*
    * Github repository URL.
    */
    private static $repository = 'https://api.github.com/repos/br-paypaldev/opencart-module/tags';

    /*
    * Returns current version of extension.
    */
    public function getPayPalPlusVersion()
    {
        return self::$paypal_plus_version;
    }

    /*
    * Returns array with requirements errors.
    */
    public function getPayPalPlusRequirements()
    {
        return $this->checkPayPalPlusRequirements();
    }

    /*
    * Check if extension upgrade.
    * Returns false or the latest version.
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
    * Performs requirements analysis.
    * Returns empty or error array.
    */
    private function checkPayPalPlusRequirements()
    {
        $alerts = [];

        if (phpversion() < '7.3') {
            $alerts[] = 'At least PHP 7.3 must be used.';
        }

        if (!extension_loaded('curl')) {
            $alerts[] = 'PHP cURL extension needs to be enabled.';
        }

        if (!extension_loaded('json')) {
            $alerts[] = 'PHP json extension needs to be enabled.';
        }

        if (!extension_loaded('mbstring')) {
            $alerts[] = 'PHP mbstring extension needs to be enabled.';
        }

        if (!class_exists('DateTime')) {
            $alerts[] = 'PHP DateTime class needs to be enabled.';
        }

        if (!function_exists('json_encode')) {
            $alerts[] = 'PHP json_encode function needs to be enabled.';
        }

        if (!function_exists('json_decode')) {
            $alerts[] = 'PHP json_decode function needs to be enabled.';
        }

        return $alerts;
    }

    /*
    * Connect to Github repository.
    * Returns false or the version number.
    */
    private function checkPayPalPlusLatestVersion() {
        if (!self::$repository) {
            return false;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::$repository);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, "curl");
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 6);

        ob_start();
        curl_exec($ch);
        curl_close($ch);

        $lines = ob_get_contents();
        ob_end_clean();
        $json = json_decode($lines, true);

        if (!$json || !isset($json[0]['name'])) {
            return false;
        }

        return $json[0]['name'];
    }
}
