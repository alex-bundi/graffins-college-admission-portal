<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Exception;
use App\Models\Applicant;
use App\Models\ApplicantCourse;
use App\Traits\OdataTrait;
use App\Models\GeneralQueries;
use App\Traits\GeneralTrait;


class ApplicationController extends Controller
{
    use OdataTrait;
    use GeneralTrait;
    protected $generalQueries;

    public function __construct()
    {
        $this->generalQueries = new GeneralQueries();
    }

    public function getDepartmentPage(){
        try {
            $departmentsQuery = $this->generalQueries->departmentsQuery();
            $departmentsURL = config('app.odata') . "{$departmentsQuery}";
            $departmentsData = $this->getOdata($departmentsURL);
            $departments = $departmentsData['value'];

            return Inertia::render('Application/Department', [
                'departments' => $departments,
            ]);

        }catch(Exception $e){

        }
        
    }
    public function postDepartment(Request $request){
        try{
            $validated = $request->validate([
                'departmentCode' => 'required|string',
            ]);
            
            $applicationID =session('user_data')['applicationCourseID'];
            
            $applicantCourse = ApplicantCourse::where('id', $applicationID)->first();
            $applicantCourse->department_code = trim($validated['departmentCode']);
            $applicantCourse->save();
            
            return redirect()->route('pick.course');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getModeOfStudyPage(){
        return Inertia::render('Application/ModeOfStudy');
    }

    public function postModeOfStudy(Request $request){
        $validated = $request->validate([
            'inclass' => 'nullable|string',
            'online' => 'nullable|string',
        ]);
        try{
            $applicationNo = session('user_data')['application_no'];
            
            // Create the application
            $application = [
                'application_no' => $applicationNo,
            ];

            $newApplication = Applicant::create($application);
            if($newApplication->exists){
                

                $applicantCourse = [
                    'mode_of_study' =>$validated['inclass'] != null ? 1 : 2,
                    'applicant_id' => $newApplication->id,
                ];
                session()->put('user_data.applicant_id', $newApplication->id);


                $newApplicantCourse = ApplicantCourse::create($applicantCourse);

                if($newApplicantCourse->exists){
                    session()->put('user_data.applicationCourseID', $newApplicantCourse->id);
                    return redirect()->route('department')->with('success', 'Mode of study created successfully');;
                }
            }

            
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }


    public function getPickCoursePage(){
        return Inertia::render('Application/PickCourse');
    }
    public function postPickCourse(Request $request){
        try{

            return redirect()->route('course-type');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

    public function getCourseTypePage(){
        return Inertia::render('Application/CourseType');
    }
    public function postCourseType(Request $request){
        try{

            return redirect()->route('class.start.date');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }


    public function getClassStartDatePage(){
        return Inertia::render('Application/ClassStartDate');
    }
    public function postClassStartDate(Request $request){
        try{

            return redirect()->route('class.start.time');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }


    public function getClassStartTimePage(){
        return Inertia::render('Application/ClassTime');
    }
    public function postClassStartTime(Request $request){
        try{

            return redirect()->route('course.summary');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

    public function getCourseSummmary(){
        return Inertia::render('Application/CourseSummary');
    }
    public function postCourseSummmary(Request $request){
        try{

            return redirect()->route('department');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

    public function getStartBioData(){
        return Inertia::render('Application/StartBioData');
    }
}
