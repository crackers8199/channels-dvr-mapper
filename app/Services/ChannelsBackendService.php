<?php

namespace App\Services;

class ChannelsBackendService
{
    protected $baseUrl;

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

    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    public function getScannedChannels($lineupStr)
    {
        $url = sprintf('%s/devices/%s/channels?ScanResult=true', $this->baseUrl, $lineupStr);
        $json = file_get_contents($url);
        return json_decode($json);
    }

    public function getGuideData($lineupStr, $startTimestamp, $duration)
    {
        $url = sprintf(
            'http://192.168.88.165:8089/devices/%s/guide?time=%d&duration=%d',
            $lineupStr, $startTimestamp, $duration);
        $json = file_get_contents($url);
        return json_decode($json);
    }

    public function isValidDevice($device)
    {
        return ($this->getDevices(true)->search($device) !== false);
    }

    public function getDevices($allowAny = false)
    {
        $url = sprintf('%s/devices', $this->baseUrl);
        $json = file_get_contents($url);

        $devices = collect(json_decode($json))->pluck('DeviceID');
        if($allowAny) {
            $devices->push('ANY');
        }

        return $devices;

    }


}
