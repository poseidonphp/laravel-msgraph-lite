<?php

namespace Poseidonphp\MsGraphLite;

use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Poseidonphp\MsGraphLite\Exceptions\CouldNotGetToken;
use Poseidonphp\MsGraphLite\Exceptions\CouldNotReachService;
use Illuminate\Support\Facades\Http;

class MsGraphApi
{

//    private static string $secret;
//    private static string $tenant_id;
//    private static ?string $client_id;


    /**
     * @var string
     */
    protected static string $tokenEndpoint = 'https://login.microsoftonline.com/{tenant}/oauth2/v2.0/token';

    /**
     * @var string
     */
    protected static string $apiBaseEndpoint = 'https://graph.microsoft.com/v1.0/users/';

//    public function __construct() {
////        self::$secret = config('mail.mailers.microsoft-graph.secret');
////        self::$tenant_id = config('mail.mailers.microsoft-graph.tenant');
////        self::$client_id = config('mail.mailers.microsoft-graph.client');
////        $this->apiBaseEndpoint = $this->apiBaseEndpoint . $appendToEndpoint;
//    }


    /**
     * Returns header collection for API request
     * @return string[]
     * @throws CouldNotGetToken
     * @throws CouldNotReachService
     */
    protected static function getHeaders(): array {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . self::getAccessToken(),
        ];
    }

    /**
     * Returns API access token
     * @return string
     * @throws CouldNotReachService
     * @throws CouldNotGetToken
     */
    protected static function getAccessToken(): string {
        try {
            return Cache::remember('mail-msgraph-accesstoken', 45, function () {
                $url = str_replace('{tenant}', config('mail.mailers.microsoft-graph.tenant') ?? 'common', static::$tokenEndpoint);
                $response = Http::asForm()->post($url, [

                        'client_id' => config('mail.mailers.microsoft-graph.client'),
                        'client_secret' => config('mail.mailers.microsoft-graph.secret'),
                        'scope' => 'https://graph.microsoft.com/.default',
                        'grant_type' => 'client_credentials',

                ]);
                $response->throw();
                return $response['access_token'];
            });
        } catch (BadResponseException $e) {
            // The endpoint responded with 4XX or 5XX error
            $response = json_decode((string)$e->getResponse()->getBody());
            throw CouldNotGetToken::serviceRespondedWithError($response->error, $response->error_description);
        } catch (ConnectException $e) {
            // A connection error (DNS, timeout, ...) occurred
            throw CouldNotReachService::networkError();
        } catch (Throwable $e) {
            // An unknown error occurred
            throw CouldNotReachService::unknownError();
        }
    }

    public static function doPatchApi($endpoint = '/', $data = []) {

        $response = Http::withHeaders(static::getHeaders())->patch(static::$apiBaseEndpoint . $endpoint, $data);
        $response->throw();
        Log::debug('Response from patch api:');
        Log::debug($response->json());
        return $response;
    }

    public static function doPostApi($endpoint = '/', $data = []) {

        $response = Http::withHeaders(static::getHeaders())->post(static::$apiBaseEndpoint . $endpoint, $data);
        $response->throw();
        Log::debug('Response from post api:');
        Log::debug($response->json());
        return $response;
    }

    public static function doGetApi($endpoint = '/') {
        $response = Http::withHeaders(static::getHeaders())->get(static::$apiBaseEndpoint . $endpoint);
        $response->throw();
        return $response;
    }

    public static function doDeleteApi($endpoint = '/') {
        $response = Http::withHeaders(static::getHeaders())->delete(static::$apiBaseEndpoint . $endpoint);
        $response->throw();
        return $response;
    }



}
