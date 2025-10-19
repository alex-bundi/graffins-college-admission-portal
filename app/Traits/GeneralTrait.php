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
use Illuminate\Support\Facades\Log;


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
            $accessToken = $auth->getAccessToken();  // use only when you encounter the error SOAP-ERROR: Parsing WSDL: Couldn't load from 'https://api.businesscentral.dynamics.com/v2.0/9db19b6a-d4cf-4625-b29e-0f5028f72d99/Graffins-
            // It is getting the access token

            return $accessToken;
            
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


    public function logError($header, $logMessage, $logFile){
        $currentTime = date("Y-m-d H:i:s");
        $logMessage = $currentTime . '_' .$header. $logMessage;
        Log::channel($logFile)->error($logMessage);
    }

    public function testPerformance($startTime, $logfile, $performanceMsg){
        // $start = microtime(true);

        $elapsed = round((microtime(true) - $startTime) * 1000, 2); // in milliseconds
        $currentTime = date("Y-m-d H:i:s");

        $logMessage = "[{$currentTime}] {$performanceMsg} took {$elapsed} ms";
        Log::channel($logfile)->error($logMessage);
    }

    public function validateAPIResponse($apiResponse){
        
        if ($apiResponse['statusCode'] == 401 || $apiResponse['statusCode'] == 0) {
            return redirect()->route('api.errors')->with([
                'data' => $apiResponse,
                'previousURL' => url()->previous(),
            ]);
        } 
    }

    public function retrieveOrUpdateSessionData($action, $key, $value = null){
        $sessionKey = 'applicant_data';
        if($action == 'put'){
            
            $data = $sessionKey . $key;
            session()->put($data, $value);
            return;
        }
        if ($action == 'get'){
            return session($sessionKey)[$key];
        }

    }
    
}