<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Exception;
use App\Traits\OdataTrait;
use App\Models\GeneralQueries;
use App\Traits\GeneralTrait;
use App\Models\Applicant;
use Illuminate\Support\Facades\Auth;



class CourseController extends Controller
{
    use OdataTrait;
    use GeneralTrait;
    protected $generalQueries;

    public function __construct()
    {
        $this->generalQueries = new GeneralQueries();
    }

    public function getCourseList(){
        try{
            $email = Auth::user()->email; 

            $applications = Applicant::where('email', $email)->get();
            // dd($applications);
            
            return Inertia::render('CourseList',[
                'applications' => $applications,
            ]);

        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

        // $applicantsQuery = $this->generalQueries->applicantsQuery();
        // $applicantsURL = config('app.odata') . "{$applicantsQuery}?". '$filter=' . rawurlencode("Email eq '{$email}'");
        // $applicantsData = $this->getOdata($applicantsURL);
        
    }
}
