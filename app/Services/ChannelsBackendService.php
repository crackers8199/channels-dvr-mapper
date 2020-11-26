<?php

namespace App\Services;

use GuzzleHttp\Client;

class ChannelsBackendService
{
    protected $baseUrl;
    protected $httpClient;

    public function __construct()
    {
        if(env('CHANNELS_BACKEND_IP') === null) {
            die('CHANNELS_BACKEND_IP .env variable must be set. Cannot continue.');
        }

        if(env('CHANNELS_BACKEND_PORT') === null) {
            die('CHANNELS_BACKEND_PORT .env variable must be set. Cannot continue.');
        }

        $this->baseUrl =
            sprintf("http://%s:%s",
                env('CHANNELS_BACKEND_IP'), env('CHANNELS_BACKEND_PORT')
            );

        $this->httpClient = new Client(['base_uri' => $this->baseUrl]);

    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    public function getScannedChannels($source)
    {
        $stream = $this->httpClient->get(sprintf('/devices/%s/channels?ScanResult=true', $source));
        $json = $stream->getBody()->getContents();
        return json_decode($json);
    }

    public function getGuideData($device, $startTimestamp, $duration)
    {
        $stream = $this->httpClient->get(
            sprintf('/devices/%s/guide?time=%d&duration=%d', $device, $startTimestamp, $duration));
        $json = $stream->getBody()->getContents();
        return json_decode($json);
    }

    public function isValidDevice($device)
    {
        return ($this->getDevices(true)->search($device) !== false);
    }

    public function getDevices($allowAny = false)
    {
        $stream = $this->httpClient->get(sprintf('/devices'));
        $json = $stream->getBody()->getContents();

        $devices = collect(json_decode($json))->pluck('DeviceID');
        if($allowAny) {
            $devices->push('ANY');
        }

        return $devices;

    }


}
