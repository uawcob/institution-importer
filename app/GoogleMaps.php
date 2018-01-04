<?php

namespace App;

use Zttp\PendingZttpRequest;
use Zttp\ZttpResponse;

class GoogleMaps
{
    protected $zttp;

    public function __construct(PendingZttpRequest $zttp)
    {
        $this->zttp = $zttp;
    }

    public function queryLatLng(string $name) : array
    {
        $response = $this->get(['address' => $name]);

        return $this->extractLatLng($response);
    }

    public function get(array $options = []) : ZttpResponse
    {
        return $this->zttp->get('https://maps.googleapis.com/maps/api/geocode/json',
            array_merge($options, [
                'key' => config('google-maps.server-key')
            ])
        );
    }

    protected function extractLatLng(ZttpResponse $response) : array
    {
        return $response->json()['results'][0]['geometry']['location'] ?? [
            'lat' => null,
            'lng' => null,
        ];
    }
}
