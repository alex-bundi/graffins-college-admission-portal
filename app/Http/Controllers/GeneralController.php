<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Exception;
use App\Traits\OdataTrait;
use App\Models\GeneralQueries;
use App\Traits\GeneralTrait;


class GeneralController extends Controller
{
    use OdataTrait;
    use GeneralTrait;
    protected $generalQueries;

    public function __construct()
    {
        $this->generalQueries = new GeneralQueries();
    }

    public function getHomePage(){
        return Inertia::render('Home');
    }

    public function getWelcomePage(){
        return Inertia::render('Welcome');
    }

    public function getRegistrationStatus(){
        return Inertia::render('RegistrationStatus');
    }

    public function getRegisterPage(){
        return Inertia::render('Auth/Register');
    }

    public function getLoginPage(){
           return Inertia::render('Auth/Login');
    }


    public function getDashboard(){
        return Inertia::render('Dashboard');
    }

    public function postLogin(Request $request){
        $validated = $request->validate([
            'email' => 'required|email:rfc,dns',
            'password' => 'required|string',

        ]);
        try{
            return redirect()->route('dashboard');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function postRegistration(Request $request){
        $validated = $request->validate([
            'firstName' => 'required|string',
            'secondName' => 'required|string',
            'email' => 'required|email:rfc,dns',
            'lastName' => 'required|string',
        ]);
        try{
            $emailExists = $this->ValidateRegistration(strtolower($validated['email']));
            

            return redirect()->route('dashboard');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }

    private function ValidateRegistration(string $email){
        $applicantsQuery = $this->generalQueries->applicantsQuery();
        $applicantsURL = config('app.odata') . "{$applicantsQuery}?". '$filter=' . rawurlencode("Email eq '{$email}'");
        $applicantsData = $this->getOdata($applicantsURL);
        dd($applicantsData);

         if (count($applicantsData['value']) === 0){ 
            // Email does not exist
            return false;
        } else if(count($applicantsData['value']) > 0) {
            // Email exists
            return true;
        }
    }
}
