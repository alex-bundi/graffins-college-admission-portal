<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Exception;

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

            }

            

            return $statusCode;
        }catch(Exception $e){
            
            // return $e->getMessage();
            return $e->getMessage();


            
        };
    }
}
