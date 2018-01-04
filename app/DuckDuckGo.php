<?php

namespace App;

use Zttp\PendingZttpRequest;

class DuckDuckGo
{
    protected $zttp;

    public function __construct(PendingZttpRequest $zttp)
    {
        $this->zttp = $zttp;
    }

    public function queryFirstUrl(string $search)
    {
        $search = rawurlencode($search);

        $response = $this->zttp->get("https://api.duckduckgo.com/?format=json&q=$search");

        return $response->json()['Results'][0]['FirstURL'] ?? null;
    }
}
