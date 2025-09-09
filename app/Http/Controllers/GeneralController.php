<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Exception;
use App\Traits\OdataTrait;
use App\Models\GeneralQueries;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Hash;
use App\Models\Applicant;




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
            $email = trim($validated['email']);
            $applicantsQuery = $this->generalQueries->applicantsQuery();
            $applicantsURL = config('app.odata') . "{$applicantsQuery}?". '$filter=' . rawurlencode("Email eq '{$email}'");
            $applicantsData = $this->getOdata($applicantsURL);
            
            if (count($applicantsData['value']) === 0){
                
                return redirect()->back()->withInput()->withErrors([
                    'error' => 'The email you provided does not exist'
                ]);

            } else if (count($applicantsData['value']) > 0){
                if(Hash::check($validated['password'], $applicantsData['value'][0]['Admission_Portal_Password'])){
                    // find applicant
                    $applicant = Applicant::where('email', $email)->first();


                    $user = [
                        'application_no' => $applicantsData['value'][0]['Application_No'],
                        'first_name' => $applicantsData['value'][0]['First_Name'],
                        'second_name' => $applicantsData['value'][0]['Second_Name'],
                        'last_name' => $applicantsData['value'][0]['Last_Name'],
                        'email' => $applicantsData['value'][0]['Email'],
                        'application_status' => $applicantsData['value'][0]['Application_Status'],
                        'applicant_id' => $applicant->id,
                    ];
                    session()->put('user_data', $user);

                    return redirect()->route('dashboard');
                    
                } else {
                    return back()->withInput()->withErrors([
                        'error' => 'The Password you provided does not match our records'
                    ]);

                    }
            }

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
            'password' => 'required|string',
        ]);
        try{
            $emailExists = $this->ValidateRegistration(strtolower($validated['email']));

            if($emailExists == true){
                return redirect()->back()->withInput()->withErrors([
                    'error' => 'The email you are using to sign up is already in use. Please sign in or use another email.'
                ]);
            }else if ($emailExists == false) {
                $context = $this->initializeSoapProcess();
          
                $soapClient = new \SoapClient(
                    config('app.webService'), 
                    [
                        'stream_context' => $context,
                        'trace' => 1,
                        'exceptions' => 1
                    ]
                );

                $params = new \stdClass();
                $params->firstName = trim($validated['firstName']);
                $params->secondName = trim($validated['secondName']);
                $params->lastName = trim($validated['lastName']);
                $params->email = strtolower(trim($validated['email']));
                $params->portalPassword = Hash::make($validated['password']);
            
                $result = $soapClient->CreateApplicantAccount($params);


                if($result){
                    
                    $application = [
                        'application_no' => $result->return_value,
                        'first_name' => trim($validated['firstName']),
                        'second_name' => trim($validated['secondName']),
                        'last_name' => trim($validated['lastName']),
                        'email' => strtolower(trim($validated['email'])),
                    ];
                    $newApplication = Applicant::create($application);
                // Rember to cater for the other $result conditions such as 'Reset UnSuccessful' etc...
                    if($result->return_value){
                        return redirect()->route('login')->with('success', 'Your account has been created successfully and is currently under review');
                    } else if($result->return_value === 'INSERT UNSUCCESSFUL'){
                        return redirect()->route('sign.up')->with('error', 'An error occurred while creating your account. Please try again or contact support if the issue persists.');
                    } 
                } else {
                    return redirect()->route('sign.up')->with('error', 'Something went wrong. Try again or reach out to our support team for help.');

                }
            }
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
        

         if (count($applicantsData['value']) === 0){ 
            // Email does not exist
            return false;
        } else if(count($applicantsData['value']) > 0) {
            // Email exists
            return true;
        }
    }
}
