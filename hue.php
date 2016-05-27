<?php

/**
 * 
 */
class Hue
{
    protected $url;
    protected $curl;

    public function __construct($bridgeIp, $userName, $ligthNum)
    {
        $this->url = sprintf('http://%s/api/%s/lights/%d/state', $bridgeIp, $userName, $ligthNum);
        $this->curl = curl_init($this->url);
    }

    public function sendRequest($params, $method = 'GET')
    {
        if (strtolower($method) === 'post') {
            curl_setopt($this->curl, CURLOPT_POST, true);
            //curl_setopt($this->curl, CURLOPT_POSTFIELDS, $params);
        }

        if (strtolower($method) === 'put') {
            curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($this->curl, CURLOPT_HEADER, false);
            // curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($params));
        }

        curl_setopt($this->curl, CURLOPT_POSTFIELDS, json_encode($params));

        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
        return curl_exec($this->curl);
    }

    public function turnOn($hue, $bri = 255, $sat = 255)
    {
        return $this->sendRequest(['on' => true, 'hue' => $hue, 'bri' => $bri, 'sat' => $sat], 'put');
    }

    public function setSate($hue, $bri = 255, $sat = 255)
    {
        return $this->sendRequest(['hue' => $hue, 'bri' => $bri, 'sat' => $sat], 'put');
    }

    public function turnOff()
    {
        return $this->sendRequest(['on' => false]);
    }

}

$colors = [
    'red' => 0,
    'yellow' => 12750,
    'green' => 25500,
    'blue' => 46920,
    'pink' => 56100,
    'red' => 65279,
];

$userName = "r47ty-ubSNwD10DwmbaF-lxLx2xnzeQU42xRsA2u";
$ligthNum = 4;

$hueApi = new Hue('172.21.28.114', $userName, $ligthNum);
$hueApi->turnOn(0);
foreach ($colors as $color => $hue) {

    for ($sat = 0; $sat < 255; $sat += 30) {
        for ($bri = 0; $bri < 255; $bri += 30) {
            $result = $hueApi->turnOn($hue, $bri, $sat);
            $result = json_decode($result);
            if(!empty($result->error)) {
                var_dump($result);
                break 3;
            }
            sleep(0.5);
        }
        sleep(1);
    }
    sleep(1);
}
$hueApi->turnOff(0);