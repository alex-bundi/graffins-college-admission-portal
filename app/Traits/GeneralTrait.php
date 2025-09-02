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

trait GeneralTrait {
   
    public function initializeSoapProcess(){
        try{
            $auth = new OAuth2Controller;
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
            // It is getting the access token
            
            //-----------------------------------------------------------------------------------
            // SOAP client options with OAuth token in the headers
            $opts = [
                'http' => [
                    'header' => "Authorization: Bearer " . $accessToken . "\r\n"
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ]
            ];
            $context = stream_context_create($opts);

            return $context;
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    
}