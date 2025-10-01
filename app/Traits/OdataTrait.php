<?php
namespace App\Traits;

use GuzzleHttp\Client;
use Microsoft\Graph\Generated\Models\SignIn;
use App\Http\Controllers\Auth\OAuth2Controller;
use function PHPUnit\Framework\fileExists;
use function PHPUnit\Framework\isEmpty;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Exception;

trait OdataTrait {
    private $auth;


    public function __construct(){
        $auth = new OAuth2Controller;
    }


    public function getOdata($url){
       
        try {
            $auth = new OAuth2Controller;
            $client = new Client();
            $directory = base_path('config\\');
            $filename = 'tokenfile.txt';
            $tokenFile = $directory . $filename;
            
            
            if (file_exists($tokenFile) && filesize($tokenFile) > 0) {
                $authToken = fopen($tokenFile, 'r');
                $accessToken = fread($authToken, filesize($tokenFile));
                fclose($authToken);
            } else {
                
                $accessToken = $auth->getAccessToken(); 
            }
            // $accessToken = $auth->getAccessToken(); 
           
       
            $response = $client->get($url, [
                'verify' => false,
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json'
                ]
            ]);
            
            
            $jdata = json_decode($response->getBody(), true);
            return $jdata;
            
            
        }  catch (  ClientException | ServerException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $message = $e->getMessage();

            if($statusCode == 401){
                
                $auth = new OAuth2Controller;
                $accessToken = $auth->getAccessToken();
                $response = $client->get($url, [
                    'verify' => false,
                    'headers' => [
                        'Authorization' => 'Bearer ' . $accessToken,
                        'Content-Type' => 'application/json'
                    ]
                ]);
                
    
                $jdata = json_decode($response->getBody(), true);
                return $jdata;
            }

            return redirect()->back()->with('error', "Error $statusCode: $message");
            
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    
}