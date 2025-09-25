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
        return Inertia::render('Payments/AmountPayable');

    }

    public function getPaymentInstructions(){
        return Inertia::render('Payments/PaymentInstructions');

    }

    public function getUpdatePaymentForm(){
        return Inertia::render('Payments/UpdatePayment');

    }

    public function postPayment(Request $request){
        $validated = $request->validate([
            'amountPaid' => 'required|numeric',
            'datePaid' => 'required|date',
            'modeOfPayment' => 'required|string',
            'paymentReference' => 'required|string',
        ]);

        try{
            $applicationID =session('user_data')['application_no'];
            $studentNo = session('user_data')['student_no'];
            $applicant = Applicant::where('id', $applicationID)
                ->where('application_status', 'submitted')
                ->first();
            $applicantCourse = ApplicantCourse::where('applicant_id', $applicant->id)->first();

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
