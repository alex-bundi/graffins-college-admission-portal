<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Exception;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;



class BusinessCentralAPIController extends Controller
{
    protected $client;
    public function __construct()
    {
        $this->client = new Client();
    }
    public function getAccessToken(){
        try {

            $bcTokenURL = config('app.token_url');

            $response = $this->client->post($bcTokenURL , [
                'connect_timeout' => 2,
                'form_params' => [
                    'client_id' => config('app.client_id'),
                    'client_secret' => config('app.client_secret'),
                    'scope' => config('app.scope'),
                    'grant_type' => config('app.grant_type'),
                ]
            ]);

            $statusCode = $response->getStatusCode();

            if($statusCode == 200){
                $body = json_decode($response->getBody(), true);
                $accessToken = $body['access_token'];

                $directory = base_path('config\\');
                $filename = 'tokenfile.txt';
                $fullPath = $directory . $filename;
                $accessTokenFile = fopen($fullPath, 'wb');
                if ($accessTokenFile){
                    $token = $accessToken;
                    fwrite($accessTokenFile, $token);
                    fclose($accessTokenFile);
                }

                $data = [
                    'statusCode' => $statusCode,
                    'accessToken' => $accessToken,
                    'message' => 'success',

                ];

                return $accessToken;
            }

            

            return $statusCode;
        }catch(ClientException $e){
            $currentTime = date("Y-m-d H:i:s");
            
            if($e->getCode() == 401 ) {
                 $response = $e->getResponse();
                $logMessage = $currentTime . '_' . 'access_token_'. $response->getBody()->getContents();
                Log::channel('access_token')->error($logMessage);
                
                $data = [
                    'statusCode' => $e->getCode(),
                    'message' => 'We’re experiencing an issue with one of our internal services. This may affect some features temporarily. We’re working to resolve it.',
                ];

                return $data;
            }
            if($e->getCode() == 0 ) {
                 $response = $e->getResponse();
                $logMessage = $currentTime . '_' . 'internet_connection'. $response->getBody()->getContents();
                Log::channel('internet_connection')->error($logMessage);
                
                $data = [
                    'statusCode' => $e->getCode(),
                    'message' => 'No internet connection. Please check your connection and try again.',
                ];

                return $data;
            }
            
            
            return $e->getCode();


            
        };

    }

    public function getOdata($url){
        try{
            $directory = base_path('config\\');
            $filename = 'tokenfile.txt';
            $tokenFile = $directory . $filename;

            $trials = 3;

            if (file_exists($tokenFile) && filesize($tokenFile) > 0) {
                $authToken = fopen($tokenFile, 'r');
                $accessToken = fread($authToken, filesize($tokenFile));
                fclose($authToken);
            } else {

            }

            $response =  $this->client->get($url, [
                'verify' => false,
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json'
                ]
            ]);
            return $response;
            
            $jdata = json_decode($response->getBody(), true);

            
        }catch (ClientException | ServerException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            
            // return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
