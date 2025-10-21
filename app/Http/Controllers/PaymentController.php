<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Exception;
use App\Models\Applicant;
use App\Models\ApplicantCourse;
use App\Traits\OdataTrait;
use App\Models\GeneralQueries;
use App\Traits\GeneralTrait;
use App\Models\EmergencyContact;


class PaymentController extends Controller
{
    use OdataTrait;
    use GeneralTrait;
    protected $generalQueries;

    public function __construct()
    {
        $this->generalQueries = new GeneralQueries();
    }

    public function getAmountPayable(){
        try{
            $applicationID =$this->retrieveOrUpdateSessionData('get','application_no' );
            $applicant = Applicant::where('id', $applicationID)
                ->where('application_status', 'submitted')
                ->first();

            if($applicant){
                $applicantCourse = ApplicantCourse::where('applicant_id', $applicant->id)->first();
                $studentNo = $applicant->student_no;
            }

            

            $studentUnitsQuery = $this->generalQueries->studentUnitsQuery();
            $studentUnitsURL = config('app.odata') . "{$studentUnitsQuery}?". '$filter=' . rawurlencode("Admission_No eq '{$studentNo}' and Course_Code eq '{$applicantCourse->course_code}' and Course_Level eq '{$applicantCourse->course_level}'");
            $studentUnits = $this->getOdata($studentUnitsURL);
            $studentUnitsData = $studentUnits['value'];

            $totalFees = 0;

            foreach ($studentUnitsData as $unit) {
                if ($unit['Is_Applicable']) {
                    $totalFees += $unit['Unit_Fees'];
                }
            }
            $this->retrieveOrUpdateSessionData('put','fee_amount', $totalFees);
            // session()->put('applicant_data.', $totalFees);

            return Inertia::render('Payments/AmountPayable',[
                'applicantCourse' => $applicantCourse,
                'totalFees' => $totalFees,
                'studentUnits'=> $studentUnitsData,
            ]);

        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

    public function getPaymentInstructions(){
        try{
            $totalFees =$this->retrieveOrUpdateSessionData('get','fee_amount');
            return Inertia::render('Payments/PaymentInstructions', [
                'totalFees' => $totalFees,
            ]);
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
        

    }

    public function getUpdatePaymentForm(){
        try{
            $applicationID = $this->retrieveOrUpdateSessionData('get','application_no' );
            $applicant = Applicant::where('id', $applicationID)
                ->where('application_status', 'submitted')
                ->first();

            if($applicant){
                $applicantCourse = ApplicantCourse::where('applicant_id', $applicant->id)->first();
                $studentNo = $applicant->student_no;
            }
      


            $studentPaymentsQuery = $this->generalQueries->studentPaymentsQuery();
            $studentPaymentsURL = config('app.odata') . "{$studentPaymentsQuery}?". '$filter=' . rawurlencode("Student_No eq '{$studentNo}' and CourseCode eq '{$applicantCourse->course_code}' and CourseLevel eq '{$applicantCourse->course_level}'");
            $studentPayments = $this->getOdata($studentPaymentsURL);
            // $studentPaymentsData = $studentPayments['value'][0];

             if (!empty($studentPayments['value']) && count($studentPayments['value']) > 0) {
                    $studentPaymentsData = $studentPayments['value'][0];
            }else {
                $studentPaymentsData = null;
            }
            return Inertia::render('Payments/UpdatePayment', [
                'studentPayments' => $studentPaymentsData,
            ]);

        }catch(Exception $e){
            // dd($e->getMessage());
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

    public function postPayment(Request $request){
        $validated = $request->validate([
            'amountPaid' => 'required|numeric',
            'datePaid' => 'required|date',
            'modeOfPayment' => 'required|string',
            'paymentReference' => 'required|string',
        ]);

        try{
            $applicationID= $this->retrieveOrUpdateSessionData('get','application_no' );
            $applicant = Applicant::where('id', $applicationID)
                ->where('application_status', 'submitted')
                ->first();
            if($applicant){
                $applicantCourse = ApplicantCourse::where('applicant_id', $applicant->id)->first();
                $studentNo = $applicant->student_no;
            }

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
            $params->studentNo = $studentNo;
            $params->courseCode = $applicantCourse->course_code;
            $params->courseLevel = $applicantCourse->course_level;
            $params->amountPaid = $validated['amountPaid'];
            $params->datePaid = $validated['datePaid'];
            $params->paymentRef = $validated['paymentReference'];
            $params->paymentMode = (int) $validated['modeOfPayment'];

            $result = $soapClient->InsertCapturePayment($params);
            if($result){
                if($result->return_value){
                    return redirect()->route('student.id')->with('success', 'Payment details captured successfully');

                } else{
                    return redirect()->back()->withErrors([
                        'error' => 'There was an error capturing the payment reference',
                    ]);
                }
            }
            

            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }
}
