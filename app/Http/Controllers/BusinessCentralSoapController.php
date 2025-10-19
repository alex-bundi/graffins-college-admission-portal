<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Exception;
use SoapFault;
use SoapClient;
use App\Models\Applicant;
use App\Models\ApplicantCourse;
use App\Traits\OdataTrait;
use App\Models\GeneralQueries;
use App\Traits\GeneralTrait;
use App\Models\EmergencyContact;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BusinessCentralAPIController;

class BusinessCentralSoapController extends Controller
{
    use OdataTrait;
    use GeneralTrait;
    protected $generalQueries;
    protected $user;
    protected $businessCentralAccess;
    protected $start;

    public function __construct()
    {
        $this->generalQueries = new GeneralQueries();
        $this->user = Auth::user();
        $this->businessCentralAccess = new BusinessCentralAPIController;
        $this->start = date("Y-m-d H:i:s");  
    }

    public function validateApplication(){
        try {
            $applicationID = $this->retrieveOrUpdateSessionData('get', 'application_no');
            $applicant = Applicant::where('id', $applicationID)
                ->where('application_status', 'submitted')
                ->first();

            if (!$applicant) {
               
                return 'Application not Submitted';

            } 

            if($applicant->application_no == null){
                $data = [
                    'hasApplication' => null,
                    'applicant' => $applicant,
                ];
                return $data;
            } else {
                $data = [
                    'hasApplication' => $applicant->application_no,
                    'applicant' => $applicant,
                ];
                return $data;
            }

        } catch(Exception $e){
            return response()->json([
                'error' => false,
                'message' => $e->getMessage()
            ], 404);
            
        }
    }

    public function createApplicationInBC(){
        try {
            // 
            $applicationExists = $this->validateApplication();
            $applicantNoBC = '';

            if($applicationExists == 'Application not Submitted'){
                
            }

           
            if($applicationExists['hasApplication'] == null){
                
            } else if($applicationExists['hasApplication'] != null){
                $applicantNoBC = $applicationExists['applicant']['application_no'];
               
            }

            $context = $this->businessCentralAccess->initializeSoapProcess();
           
            $soapClient = new SoapClient(
                config('app.webService'), 
                [
                    'stream_context' => $context,
                    'trace' => 1,
                    'exceptions' => 1
                    
                ]
            );

          

            $params = new \stdClass();
            $params->applicationNo = $applicantNoBC;
            $params->firstName = trim(ucfirst($applicationExists['applicant']['first_name']));
            $params->secondName = trim(ucfirst($applicationExists['applicant']['second_name']));
            $params->lastName = trim(ucfirst($applicationExists['applicant']['last_name']));
            $params->email = strtolower(trim($applicationExists['applicant']['email']));
            $params->portalPassword = strtolower(trim('1234'));
            $params->nationality = trim($applicationExists['applicant']['nationality']);
            $params->residence = (trim($applicationExists['applicant']['residence']));
            $params->marketing = trim($applicationExists['applicant']['marketing']);
            $params->allergies = (int)($applicationExists['applicant']['allergies']);
            $params->allergyDescription = trim($applicationExists['applicant']['allergy_description']);
            $params->phoneNo = trim($applicationExists['applicant']['phone_no']);
            $params->passportID = trim($applicationExists['applicant']['id_passport_No']);
            $params->dateOfBirth = trim($applicationExists['applicant']['dob']);
            $params->gender = trim($applicationExists['applicant']['gender']);

            $imageFilePath = $applicationExists['applicant']['student_image_file_path'];
            if($imageFilePath && file_exists($imageFilePath)){
                $fileContent = file_get_contents($imageFilePath);
                $base64 = base64_encode($fileContent);

                
            }
            $params->studentImageBase64 = $base64;

            $passportFilePath = $applicationExists['applicant']['passport_file_path'];
            if($passportFilePath && file_exists($passportFilePath)){
                $fileContent = file_get_contents($passportFilePath);
                $passportFileBase64 = base64_encode($passportFilePath);

                
            }
            $params->documentBase64 = $passportFileBase64;
            $params->documentFileName = 'student_id';
        
            $result = $soapClient->CreateApplicantAccount($params);

            if($result){

                // Insert Application No
                $applicant = Applicant::where('id', $applicationExists['applicant']['id'])->first();
                $applicant->application_no = $result->return_value;
                $applicant->save();
                $this->testPerformance($this->start, 'performance', 'Creating Application in Business central took ');
                return response()->json([
                    'success' => true,
                    'data' => $result,
                ], 200);
        
            }else {
                return response()->json([
                    'error' => false,
                    'message' => 'We encountered a problem while creating your application. Please try again, and if the issue continues, contact our support team for assistance.'
                ], 404);
            }

        } catch(SoapFault | Exception $e){
            if($e->getCode() == 0){
                $trials = 1;
                $this->businessCentralAccess->initializeSoapProcess(true);
                $this->createApplicationInBC();

                
            }
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'statusCode' => $e->getCode(),
            ], 404);
            
        }
    }

    public function insertEmergencyContacts($applicationID){
        try {
            $applicationID = $this->retrieveOrUpdateSessionData('get', 'application_no');
            $emergencyContact = EmergencyContact::where('applicant_id', $applicationID)->first();
            $context = $this->businessCentralAccess->initializeSoapProcess();
            $soapClient = new SoapClient(
                config('app.webService'), 
                [
                    'stream_context' => $context,
                    'trace' => 1,
                    'exceptions' => 1
                    
                ]
            );
            $params = new \stdClass();
            $params->applicationNo = trim($applicationID);
            $params->fullName = trim(ucfirst($emergencyContact->full_name));
            $params->phoneNo = trim(($emergencyContact->phone_no));
            $params->relationship = ($emergencyContact->relationship);
            
            
            $result = $soapClient->UpsertEmergencyContacts($params);
            if($result){
                $this->testPerformance($this->start, 'performance', 'Inserting Emergency Contacts in Business central took ');

                return response()->json([
                    'success' => true,
                    'data' => $result,
                ], 200);
        
            } else {
                return response()->json([
                'error' => false,
                'message' => 'We encountered a problem while creating your application. Please try again, and if the issue continues, contact our support team for assistance.'
                ], 404);
            }

        }catch(SoapFault | Exception $e){
            if($e->getCode() == 0){
                $trials = 1;
                $this->businessCentralAccess->initializeSoapProcess(true);
                    // $this->createApplicationInBC();

                
            }
            return response()->json([
                'error' => false,
                'message' => $e->getMessage(),
                'statusCode' => $e->getCode(),
            ], 404);
            
        }
    }
}
