<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TokenStore\TokenCache;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use GuzzleHttp\Client;

class OAuth2Controller extends Controller
{
    public function getAccessToken(){
        try{
            $client = new Client();
            $tenantId= env('BUSINESS_CENTRAL_TENANT_ID');
            $clientId= env('BUSINESS_CENTRAL_CLIENT_ID');
            $clientSecret= env('BUSINESS_CENTRAL_CLIENT_SECRET');
        
            $response = $client->post("https://login.microsoftonline.com/$tenantId/oauth2/v2.0/token", [
                'verify' => false,
                'form_params' => [
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                    'scope' => 'https://api.businesscentral.dynamics.com/.default',
                    'grant_type' => 'client_credentials'
                ]
            ]);

            $body = json_decode($response->getBody(), true);
            $accessToken = $body['access_token'];

            // write access token
            $directory = base_path('config\\');
            $filename = 'tokenfile.txt';
            $fullPath = $directory . $filename;
            $accessTokenFile = fopen($fullPath, 'wb');
            if ($accessTokenFile){
                $token = $accessToken;
                fwrite($accessTokenFile, $token);
                fclose($accessTokenFile);
            }


            return $accessToken;
        }catch (\GuzzleHttp\Exception\ClientException $e) {
            
            throw $e;
        }
           
    }
}
