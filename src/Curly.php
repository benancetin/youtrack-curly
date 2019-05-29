<?php
namespace benancetin\Curly;

class Curly
{
    /**
     * Curl handle - curl init
     * @var string
     */
    protected $ch = '';

    /**
     * Settings to use in the api
     * @var array
     */
    protected $setting = array();

    /**
     * Json string to post
     * @var string
     */
    protected $json = '';

    /**
     * All errors
     * @var array
     */
    protected $warnings = array();

    /**
     * Status codes to use for responses
     * @var array
     */
    protected $statusCodes = array();

    public function __construct()
    {
        $this->register();
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Authorization: Bearer '.$this->setting['apiToken'],
            'Content-Type: application/json'
        ));
        curl_setopt($this->ch, CURLOPT_HEADER, $this->setting['responseHeaders']);
        curl_setopt($this->ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, 30);
    }

    /**
     *  Register files to use
     */
    protected function register()
    {
        $this->setting = include(__DIR__."/../config/curly.php");
        $jsonString = file_get_contents(__DIR__."/../data/http-status-codes.json");
        $this->statusCodes = json_decode($jsonString, true);
    }

    /**
     * Set array data
     * @param string $json json data to execute
     */
    public function setArray($array)
    {
        $json = json_encode($array, true);
        $this->json = $json;
    }

    /**
     * Set Json data
     * @param string $json json data to execute
     */
    public function setJson($json)
    {
        $this->json = $json;
    }

    /**
     * Set warning messages
     * @param string $code        Response code
     * @param string $description Response code description
     */
    protected function setWarning($code, $description)
    {
        array_push($this->warnings, array($code => $description));
    }

    /**
     * Execute given command
     * @param  string $link the part after the base url
     * @return array result of the query
     */
    public function execute($method, $link)
    {
        // echo $this->statusCodes[200];
        switch (strtolower($method)) {
            // no extra parameters needed for get
            case 'update':
                $this->setWarning('xxx', 'You requested -update- method, for update you need to use -post-');
                break;
            case 'post':
                curl_setopt($this->ch, CURLOPT_POST, true);
                curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->json);
                break;
            case 'delete':
                curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
        }
        $link = $this->setting['baseUrl'].$link;
        curl_setopt($this->ch, CURLOPT_URL, $link);
        $result = curl_exec($this->ch);
        if (empty($result)) {
            $this->setWarning('xxx', curl_error($this->ch));
        } else {
            $info = curl_getinfo($this->ch);
            if (empty($info['http_code'])) {
                $this->setWarning('xxx', "Result: No HTTP code was returned (OK if it is delete action)");
            } else {
                $this->setWarning($info['http_code'], $this->statusCodes[$info['http_code']]);
            }
        }
        return $result;
    }

    /**
     * Notices that script gives
     * @return array json array
     */
    public function getWarnings()
    {
        return $this->warnings;
    }

    /**
     * Print warning messages
     * @return void
     */
    public function printWarnings() : void
    {
        echo "<pre>";
            print_r($this->warnings);
        echo "</pre>";
    }
}
