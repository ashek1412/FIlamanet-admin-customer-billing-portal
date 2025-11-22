<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DynamicsService
{
    protected $tenantId;
    protected $clientId;
    protected $clientSecret;
    protected $resource;
    protected $tokenUrl;

    public function __construct()
    {

        $this->tokenUrl=config('app.dynamics_token_url');
        $this->clientId=config('app.dynamics_client_id');
        $this->clientSecret=config('app.dynamics_client_secret');
        $this->resource=config('app.dynamics_client_scope');
    }

    public function getAccessToken()
    {

        return Cache::remember('dynamics_access_token', 3500, function () {
            $response = Http::asForm()->post($this->tokenUrl, [
                'grant_type' => 'client_credentials',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'scope' => $this->resource,
            ]);

            if ($response->failed()) {
                //throw new \Exception('Failed to get Dynamics access token: ' . $response->body());\
                Log::info('Failed to get Dynamics access token: ' . $response->body());
                return false;
            }

             return $response->json('access_token');
        });
    }

}

