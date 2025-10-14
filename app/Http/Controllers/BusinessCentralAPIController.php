<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Exception;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use App\Traits\GeneralTrait;
use Inertia\Inertia;

class BusinessCentralAPIController extends Controller
{
    protected $client;
    protected $savedAccessTokenFile;
    use GeneralTrait;

    public function __construct()
    {
        $this->client = new Client();
        $this->savedAccessTokenFile = $this->getAccessTokenFile();

    }

    private function getAccessTokenFile(){
        $directory = base_path('config\\');
        $filename = 'tokenfile.txt';
        $tokenFile = $directory . $filename;

        if(file_exists($tokenFile)){
            return $tokenFile;
        }else {
            fopen($tokenFile, "w");
            fclose($tokenFile);
            return $tokenFile;

        }

    }

    public function getAccessToken(){
        try {

            $response = $this->client->post(config('app.token_url') , [
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

                $accessTokenFile = fopen($this->savedAccessTokenFile, 'wb');
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
                return $data;
            }

            // return $statusCode;
        }catch(ClientException | ServerException $e){

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
 
        };

    }

    public function refreshToken(){
        try{
            
            $validAccessToken = $this->getAccessToken();
            if($validAccessToken){
                // error
                if($validAccessToken['statusCode'] == 401 ){
                    $validAccessToken['previousURL'] = session()->all()['_previous']['url'];
                    return $validAccessToken;
                }
                if($validAccessToken['statusCode'] == 0 ){
                    $validAccessToken['previousURL'] = session()->all()['_previous']['url'];
                    return $validAccessToken;
                }

                // Success
                if($validAccessToken['statusCode'] == 200){
                    $accessToken = $validAccessToken['accessToken'];
                    return $accessToken;

                }
            }
        }catch (ClientException | ServerException $e) {
            return $e;
        }
    }

    public function getOdata($url){
        try{
            $accessToken = '';
            if (file_exists($this->savedAccessTokenFile) && filesize($this->savedAccessTokenFile) > 0) {

                $authToken = fopen($this->savedAccessTokenFile, 'r');
                $savedAccessToken = fread($authToken, filesize($this->savedAccessTokenFile));
                fclose($authToken);
                $accessToken = $savedAccessToken;

            } 
            else {
                $validAccessToken = $this->getAccessToken();

                if($validAccessToken){
                    // error
                    if($validAccessToken['statusCode'] == 401 ){
                        $validAccessToken['previousURL'] = session()->all()['_previous']['url'];
                        return $validAccessToken;
                    }
                    if($validAccessToken['statusCode'] == 0 ){
                        $validAccessToken['previousURL'] = session()->all()['_previous']['url'];
                        return $validAccessToken;
                    }

                    // Success
                    if($validAccessToken['statusCode'] == 200){
                        $accessToken = $validAccessToken['accessToken'];

                    }
                }
            }
            
            $response =  $this->client->get($url, [
                'verify' => false,
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json'
                ]
            ]);
            $data = [
                    'statusCode' => $response->getStatusCode(),
                    'data' => json_decode($response->getBody(), true),
                ];

            

            return $data;
        }catch (ClientException | ServerException $e) {  
            $currentTime = date("Y-m-d H:i:s");
            if($e->getCode() == 401 ) {
                
                $trials = 1;
                // Refresh Token
                            

                // do {
                   
                //     $trials -= 1;
                // }while($trials != 0);
                 $this->refreshToken();
                $this->getOdata($url);


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
            }else{
                  $response = $e->getResponse();
                $logMessage = $currentTime . '_' . 'internet_connection'. $response->getBody()->getContents();
                Log::channel('internet_connection')->error($logMessage);
                
                $data = [
                    'statusCode' => $e->getCode(),
                    'message' => 'We’re experiencing an issue with one of our internal services. This may affect some features temporarily. We’re working to resolve it.',
                ];

                return $data;
            }
            // return $e->getMessage();
            
            // return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
