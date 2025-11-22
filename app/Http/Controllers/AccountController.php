<?php

namespace App\Http\Controllers;

use App\Services\DynamicsService;
use Exception;
use Filament\Notifications\Notification;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class AccountController extends Controller
{
    private const DMS_TOKEN_KEY = 'dms_tok';
    private const CACHE_TTL = 300; // 5 minutes

    private string $erpUrl;
    private string $dmsUrl;

    public function __construct()
    {
        $this->erpUrl = config('app.erp_url');
        $this->dmsUrl = config('app.dms_url');
    }

    /**
     * Build headers for ERP API requests
     */
    private function getErpHeaders(): array
    {
        return [
            'Accept' => '*/*',
            'ebill-id' => config('app.ebil_id'),
            'ebill-key' => config('app.ebil_key'),
            'client-did' => Auth::user()->customer_id,
        ];
    }

    /**
     * Make an ERP API request
     */
    private function makeErpRequest(string $endpoint, array $data = []): ?array
    {
        try {


            $response = Http::withHeaders($this->getErpHeaders())
                ->post("{$this->erpUrl}/{$endpoint}", $data);



            if ($response->successful()) {
                return $response->json('data');
            }

            $this->showErrorNotification('Failed to retrieve data');
            Log::warning("ERP API failed: {$endpoint}", [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;

        } catch (Exception $e) {
            $this->logAndNotifyError('ERP API Error', $e);
            return null;
        }
    }

    /**
     * Make an ERP API request and return PDF
     */
    private function fetchPdfFromErp(string $endpoint, string $id, string $filename): JsonResponse|Response
    {
        if (!$id || $id <= 0) {
            return $this->jsonError('Invalid ID provided', 400);
        }

        try {
            $response = Http::withHeaders($this->getErpHeaders())
                ->post("{$this->erpUrl}/{$endpoint}", ['invnum' => $id]);

            if (!$response->successful()) {
                return $this->jsonError('Failed to retrieve data', 404);
            }

            $pdfData = base64_decode($response->json('pdf_data', ''));

            if (empty($pdfData)) {
                return $this->jsonError('Document not found', 404);
            }

            return $this->pdfResponse($pdfData, $filename);

        } catch (Exception $e) {
            Log::error('PDF fetch error', [
                'endpoint' => $endpoint,
                'id' => $id,
                'error' => $e->getMessage(),
            ]);
            return $this->jsonError('API Error: Failed to retrieve data', 500);
        }
    }

    /**
     * Create a PDF response
     */
    private function pdfResponse(string $pdfData, string $filename): Response
    {
        return response($pdfData, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"{$filename}\"",
            'Content-Length' => strlen($pdfData),
        ]);
    }

    /**
     * Create a JSON error response
     */
    private function jsonError(string $message, int $status = 404): JsonResponse
    {
        return response()->json(['message' => $message], $status);
    }

    /**
     * Show error notification
     */
    private function showErrorNotification(string $message): void
    {
        Notification::make()
            ->title($message)
            ->danger()
            ->send();
    }

    /**
     * Log error and show notification
     */
    private function logAndNotifyError(string $title, Exception $e): void
    {
        Log::error("{$title}: {$e->getMessage()}", [
            'exception' => get_class($e),
            'trace' => $e->getTraceAsString(),
        ]);
        $this->showErrorNotification($title);
    }

    /**
     * Get customer account details
     */
    public function getAccount(): array
    {
        return $this->makeErpRequest('customer', [
                'cid' => Auth::user()->customer_id,
            ]) ?? [];
    }

    /**
     * Get invoice list
     */
    public function getInvoiceList(): array
    {
        return $this->makeErpRequest('invoices', [
                'cid' => Auth::user()->customer_id,
            ]) ?? [];
    }

    /**
     * Get customer list
     */
    public function getCustomerList(): array
    {
        return $this->makeErpRequest('clist', [
                'cid' => Auth::user()->is_admin,
            ]) ?? [];
    }

    /**
     * Get invoice PDF
     */
    public function getInvoice(?string $id = null): JsonResponse|Response
    {
        return $this->fetchPdfFromErp('invoice', $id, "{$id}.pdf");
    }

    /**
     * Get Musak invoice PDF
     */
    public function getMusak(?string $id = null): JsonResponse|Response
    {
        return $this->fetchPdfFromErp('musak', $id, "{$id}-musak.pdf");
    }

    /**
     * Get CNF PDF
     */
    public function getCnf(?string $id = null): JsonResponse|Response
    {
        return $this->fetchPdfFromErp('cnf', $id, "{$id}-cnf.pdf");
    }

    /**
     * Get ISPS PDF
     */
    public function getIsps(?string $id = null): JsonResponse|Response
    {
        if (!$id || $id <= 0) {
            return $this->jsonError('Invalid ID provided', 400);
        }


        try {
            $response = Http::withHeaders($this->getErpHeaders())
                ->post("{$this->erpUrl}/ispsdet", ['id' => $id]);

            if (!$response->successful()) {
                return $this->jsonError('Failed to retrieve data', 404);
            }

            $pdfData = base64_decode($response->json('pdf_data', ''));


            if (empty($pdfData)) {
                return $this->jsonError('Document not found', 404);
            }

            return $this->pdfResponse($pdfData, "{$id}.pdf");

        } catch (Exception $e) {
            Log::error('ISPS fetch error', ['id' => $id, 'error' => $e->getMessage()]);
            return $this->jsonError('API Error: Failed to retrieve data', 500);
        }
    }

    /**
     * Get tracking information
     */
    public function getTracking(?string $id = null): ?array
    {
        if (!$id || $id <= 0) {
            return null;
        }


        return $this->makeErpRequest('tracking', ['id' => $id]);
    }

    /**
     * Get invoice details
     */
    public function getInvoiceDetails(?string $invoiceNumber = null): array
    {
        if (!$invoiceNumber) {
            return [];
        }

        return $this->makeErpRequest('invoicedet', ['invnum' => $invoiceNumber]) ?? [];
    }

    /**
     * Get DMS document
     */
    public function getDms(?string $id = null): JsonResponse|Response|View
    {

        $trID=$id;

//        $firstChar = strtoupper($trID[0]);
//        if($firstChar == 'E' || $firstChar == 'C')
//            $trID = substr($trID, 1);



        $firstThreeChar = strtoupper(substr($trID, 0,3));
        if(($firstThreeChar == 'EFD' || $firstThreeChar == 'CNF') && $trID[3]=="-")
            $trID = substr($trID, 4);




        $trackingData = $this->getTracking($trID);



        if (empty($trackingData)) {
            return $this->jsonError('Document not found', 404);
        }

        $this->ensureDmsToken();

        if (!Session::has(self::DMS_TOKEN_KEY)) {
            return $this->jsonError('Failed to authenticate with DMS', 500);
        }

        $docId = $this->getAwbScanDocId($id);

        if ($docId === '403') {
            $this->refreshDmsToken();
            $docId = $this->getAwbScanDocId($id);
        }



        if (!$docId || $docId <= 0) {
            return $this->jsonError('Document not found', 404);
        }

        $fileData = $this->downloadAwbScan($docId);


        if (!$fileData) {
            return $this->jsonError('Failed to download document', 500);
        }

        return $this->renderDmsFile($fileData, $trackingData);
    }

    /**
     * Render DMS file (image or PDF)
     */
    private function renderDmsFile(array $fileData, array $trackingData): View|Response
    {
        $contentType = $fileData['content_type'];
        $data = $fileData['data'];

        if (str_contains($contentType, 'image')) {
            return view('file_viewer', [
                'awb' => $trackingData,
                'contentType' => $contentType,
                'fileData' => $data,
            ]);
        }

        if (str_contains($contentType, 'pdf')) {
            $pdfData = base64_decode($data);
            return response($pdfData, 200, [
                'Content-Type' => $contentType,
                'Content-Disposition' => 'inline; filename="scan_' . $trackingData['xpbdocno'] . '.pdf"',
                'Content-Length' => strlen($pdfData),
            ]);
        }

        return $this->jsonError('Unsupported file type', 415);
    }

    /**
     * Ensure DMS token exists
     */
    private function ensureDmsToken(): void
    {
        if (!Session::has(self::DMS_TOKEN_KEY)) {
            $this->refreshDmsToken();
        }
    }

    /**
     * Refresh DMS token
     */
    private function refreshDmsToken(): void
    {
        try {
            $response = Http::post("{$this->dmsUrl}/token/", [
                'username' => config('app.dms_user'),
                'password' => config('app.dms_pass'),
            ]);

            if ($response->successful()) {
                Session::put(self::DMS_TOKEN_KEY, $response->json('access'));
            } else {
                $this->showErrorNotification('Failed to authenticate with DMS');
                Log::warning('DMS token refresh failed', ['status' => $response->status()]);
            }
        } catch (Exception $e) {
            $this->logAndNotifyError('DMS Token Error', $e);
        }
    }

    /**
     * Get DMS document ID
     */
    private function getAwbScanDocId(?string $awbNumber = null): string|int
    {
        if (!$awbNumber) {
            return 0;
        }

        try {

            $client = new Client();
            $response = $client->post("{$this->dmsUrl}/v1/dms/documents/search/", [
                'headers' => [
                    'Authorization' => 'JWT ' . Session::get(self::DMS_TOKEN_KEY),
                ],
                'multipart' => [
                    ['name' => 'search_type', 'contents' => 'standard'],
                    ['name' => 'keyword', 'contents' => $awbNumber],
                ],
            ]);

            $statusCode = $response->getStatusCode();

            if ($statusCode === 403) {
                return '403';
            }

            if ($statusCode === 200) {
                $responseBody = $response->getBody()->getContents();

                if (empty($responseBody)) {
                    return 0;
                }


                $responseData = json_decode($responseBody, true);
                $responseData=$responseData['data'];
                $result = array_filter($responseData, function ($item) use ($awbNumber) {
                    // Check if the 'filename' key exists and matches the target value
                    return isset($item['filename']) && $item['filename'] === $awbNumber;
                });

                $found_file = array_values($result);

                //if(isset($found_file[0]['document_id']))
                return $found_file[0]['document_id'] ?? 0;
            }

            return 0;

        } catch (Exception $e) {
            if ($e->getCode() == 403) {
                return '403';
            }

            Log::error('DMS document search error', [
                'awb' => $awbNumber,
                'error' => $e->getMessage(),
            ]);
            return 0;
        }
    }

    /**
     * Download AWB scan from DMS
     */
    private function downloadAwbScan(?int $documentId = null): ?array
    {
        if (!$documentId || $documentId <= 0) {
            return null;
        }

        try {
            $client = new Client();
            $response = $client->get("{$this->dmsUrl}/v1/dms/documents/external/download/", [
                'headers' => [
                    'Authorization' => 'JWT ' . Session::get(self::DMS_TOKEN_KEY),
                ],
                'multipart' => [
                    ['name' => 'document_id', 'contents' => $documentId],
                ],
            ]);

            if ($response->getStatusCode() !== 200) {
                return null;
            }

            $responseBody = $response->getBody()->getContents();
            $responseData = json_decode($responseBody, true);


            $pdfData = $responseData['data'] ?? null;
            $contentType = $responseData['content_mime_type'] ?? 'application/pdf';

            if (empty($pdfData)) {
                return null;
            }

            return [
                'data' => $pdfData,
                'content_type' => $contentType,
            ];

        } catch (Exception $e) {
            Log::error('DMS download error', [
                'document_id' => $documentId,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Get Dynamics 365 data
     */
    public function getDynamicsData(?string $customerCode = null): array
    {

        if (!$customerCode) {
            return [];
        }

        // Cache key based on customer code and date
        $cacheKey = "dynamics_data_{$customerCode}_" . date('Y-m-d');
        //dd($cacheKey);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($customerCode) {
            return $this->fetchDynamicsData($customerCode);
        });
    }

    /**
     * Fetch data from Dynamics 365
     */
    private function fetchDynamicsData(string $customerCode): array
    {
        try {
            $dynamicsService = new DynamicsService();
            $accessToken = $dynamicsService->getAccessToken();

            if (!$accessToken) {
                return [];
            }

            $fromDate = '2025-01-01';
            $toDate = date('Y-m-t');

            $baseUrl = config('app.dynamics_api_url');
            $entity = 'SatementOfAccounts';
            $company = urlencode('AAL-LIVE');

            $url = "{$baseUrl}/Company('{$company}')/{$entity}";
            $filter = "\$filter=customerCode eq '{$customerCode}' and invoiceDate ge {$fromDate} and invoiceDate le {$toDate}";
            $fullUrl = "{$url}?{$filter}";


            $client = new Client();
            $request = new GuzzleRequest('GET', $fullUrl, [
                'Authorization' => "Bearer {$accessToken}",
            ]);

            $response = $client->sendAsync($request)->wait();

            if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
                Log::warning('Dynamics API error', ['status' => $response->getStatusCode()]);
                return [];
            }

            $data = json_decode($response->getBody()->getContents(), true);
            $records = $data['value'] ?? [];


            // Filter by customer code
            return array_values(array_filter($records, fn($item) => $item['customerCode'] === $customerCode));

        } catch (Exception $e) {
            Log::error('Dynamics API error', [
                'customer' => $customerCode,
                'error' => $e->getMessage(),
            ]);
            return [];
        }
    }

    /**
     * Show file viewer (legacy method)
     */
    public function showFileViewer(string $awb): View
    {
        $fileUrl = route('file.view', ['awb' => $awb]);
        return view('file_viewer', compact('fileUrl'));
    }
}
