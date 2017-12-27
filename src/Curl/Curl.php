<?php namespace NamespacePath\Curl;

/**
 *
 * Sets up basic Curl requests in PHP.
 *
 */

class Curl
{
    protected $curl = 0;

     /**
     * Send GET cURL request
     *
     * @param string $url
     * @return mixed response from cURL
     */
    public function get($url)
    {
        return $this->sendReq($url, array (
                CURLOPT_AUTOREFERER => 1,
                CURLOPT_FOLLOWLOCATION => 1,
                CURLOPT_RETURNTRANSFER => 1, // Return result on success
                CURLOPT_HEADER =>0
        ));
    }

    /**
     * Send POST cURL request
     *
     * @param string $url
     * @return mixed response from cURL
     */
    public function post($url, $params)
    {
        $headers = array("Content-Type:multipart/form-data"); // cURL headers for file uploading
        return $this->sendReq($url, array (
                CURLOPT_HEADER => 1,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_POST => 1,
                CURLOPT_AUTOREFERER => 1,
                CURLOPT_FOLLOWLOCATION => 1,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_HEADER => 0,
                CURLOPT_SAFE_UPLOAD => 0,
                CURLOPT_POSTFIELDS => $params
        ));
    }

    /**
     * Send Custom cURL request
     *
     * @param string $url
     * @return mixed response from cURL
     */
    public function custom($url, $cmd)
    {
        return $this->sendReq($url, array (
                CURLOPT_AUTOREFERER => 1,
                CURLOPT_FOLLOWLOCATION => 1,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_CUSTOMREQUEST => $cmd
        ));
    }

    public function close()
    {
        if ($this->curl !== 0) {
            curl_close($this->curl);
            $this->curl = 0;
        }
    }

    /**
     * Setup cURL with options, and execute request
     *
     * @param string $url
     * @param string $opt - options for cURL request
     * @return mixed response from cURL
     */
    private function sendReq($url, $opt)
    {
        $this->initCurl();
        $this->setCurlOptions($opt);
        $this->setCurlTargetUrl($url);
        return $this->execCurl();
    }

    /**
     * Initialize a cURL session
     */
    private function initCurl()
    {
        if ($this->curl === 0) {
            $this->curl = curl_init();
        }
    }

    /**
     * Set up cURL session with options
     *
     * @param string $opt - options for cURL request
     */
    private function setCurlOptions($opts)
    {
        foreach ($opts as $opt => $value) {
            curl_setopt($this->curl, $opt, $value);
        }
    }

    /**
     * Set up target URL for cURL session
     *
     * @param string $url - Target URL
     */
    private function setCurlTargetUrl($url)
    {
        curl_setopt($this->curl, CURLOPT_URL, $url);
    }

    /**
     * Set up target URL for cURL session
     *
     * @return mixed response from cURL session
     */
    private function execCurl()
    {
        return curl_exec($this->curl);
    }
}
