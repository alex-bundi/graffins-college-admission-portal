<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Exception;
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
        $this->start = microtime(true);  
    }

    public function validateApplication(){
        try {
            $applicationID = $this->retrieveOrUpdateSessionData('get', 'application_no');
            $applicant = Applicant::where('id', $applicationID)
                ->where('application_status', 'submitted')
                ->first();

            if (!$applicant) {
                $data = [
                    'hasSubmittedApplication' => false,
                ];
                return;

            } 
            $data = [
                'hasApplication' => true,
                'applicantNo' => $applicant->application_No
            ];
            return $data;

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
            return response()->json([
                'success' => true,
                'data' => $applicationExists,
            ], 200);



            // return response()->json([
            //     'success' => true,
            //     'data' => 'APP01',
            // ], 200);


            // Error
            return response()->json([
                'error' => true,
                'message' => 'BC insert error',
            ], 200);

        } catch(Exception $e){
            return response()->json([
                'error' => false,
                'message' => $e->getMessage()
            ], 404);
            
        }
    }
}
