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
    protected $soaUrl;

    public function __construct()
    {

        $this->tokenUrl = config('app.dynamics_token_url');
        $this->clientId = config('app.dynamics_client_id');
        $this->clientSecret = config('app.dynamics_client_secret');
        $this->resource = config('app.dynamics_client_scope');
        $this->soaUrl = config('app.dynamics_soa_url');
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

    const CACHE_TTL = 300;

    public function generateSoa($customerCode, $accessToken)
    {

        $response =  Http::withToken($accessToken)->post($this->soaUrl, [
            'customerCode' => $customerCode,
            'asOfDate' => date('01/01/Y'),
        ]);

        if ($response->failed()) {
            Log::warning('Failed to generate SOA: ' . $response->body(), ['status' => $response->status()]);
            return $response->status();
        }

        return $response->json();
    }

    public function getDynamicsData(?string $customerCode = null, $accessToken = null): array
    {
        if (!$customerCode) {
            return [];
        }

        $cacheKey = "dynamics_data_{$customerCode}_" . date('Y-m-d');

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($customerCode, $accessToken) {
            return $this->fetchDynamicsData($customerCode, $accessToken);
        });
    }

    private function fetchDynamicsData(string $customerCode, $accessToken): array
    {
        try {


            if (!$accessToken) {
                return [];
            }


            $toDate = date('Y-m-d', strtotime('last day of last month'));;

            $baseUrl = config('app.dynamics_api_url');
            $entity = 'SatementOfAccounts';
            $company = urlencode('AAL-LIVE');

            $url = "{$baseUrl}/Company('{$company}')/{$entity}";
            $filter = "\$filter=customerCode eq '{$customerCode}' and invoiceDate le {$toDate}";
            $fullUrl = "{$url}?{$filter}";

            $response = Http::withToken($accessToken)->get($fullUrl);

            if ($response->failed()) {
                Log::warning('Dynamics API service error', ['status' => $response->body()]);
                return [];
            }

            $data = $response->json();
            $records = $data['value'] ?? [];

            return array_values(array_filter($records, fn($item) => $item['customerCode'] === $customerCode));
        } catch (\Exception $e) {
            Log::error('Dynamics API error', [
                'customer' => $customerCode,
                'error' => $e->getMessage(),
            ]);
            return [];
        }
    }

    public function showFileViewer(string $awb)
    {
        $fileUrl = route('file.view', ['awb' => $awb]);
        return view('file_viewer', compact('fileUrl'));
    }
}
