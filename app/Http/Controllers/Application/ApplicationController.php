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
            $departmentsURL = config('app.odata') . "{$departmentsQuery}?". '$filter=' . rawurlencode("Dimension_Code eq 'DEPARTMENT'");
            $departmentsData = $this->getOdata($departmentsURL);
            $departments = $departmentsData['value'];

            return Inertia::render('Application/Department', [
                'departments' => $departments,
            ]);

        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
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
        try {

            return Inertia::render('Application/ModeOfStudy');
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function postModeOfStudy(Request $request){
        $validated = $request->validate([
            'inclass' => 'nullable|string',
            'online' => 'nullable|string',
        ]);
        try{
            $applicationNo = session('user_data')['application_no'];
            
          
                

                $applicantCourse = [
                    'mode_of_study' =>$validated['inclass'] != null ? 1 : 2,
                    'applicant_id' => session('user_data')['applicant_id'],
                ];

                $newApplicantCourse = ApplicantCourse::create($applicantCourse);

                if($newApplicantCourse->exists){
                    session()->put('user_data.applicationCourseID', $newApplicantCourse->id);
                    return redirect()->route('department')->with('success', 'Mode of study created successfully');;
                }
           

            
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }


    public function getPickCoursePage(){
        try{
            $courseQuery = $this->generalQueries->coursesQuery();
            $coursesURL = config('app.odata') . "{$courseQuery}";
            $courseData = $this->getOdata($coursesURL);
            
            return Inertia::render('Application/PickCourse', [
                'courses' => $courseData['value'],
            ]);
        }catch(Exception $e){
            return redirect()->route('department')->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }
    public function postPickCourse(Request $request){
         $validated = $request->validate([
            'courseCode' => 'required|string',
        ]);
        try{
            $applicationID =session('user_data')['applicationCourseID'];
            
            $applicantCourse = ApplicantCourse::where('id', $applicationID)->first();
            $applicantCourse->course_code = trim($validated['courseCode']);
            $applicantCourse->save();

            return redirect()->route('course-type');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

    public function getCourseTypePage(){
        try{
            
            $applicationID =session('user_data')['applicationCourseID'];
            
            $applicantCourse = ApplicantCourse::where('id', $applicationID)->first();
            if ($applicantCourse->department_code == 'WCAPS'){
                return Inertia::render('Application/CourseType');
            } else if ($applicantCourse->department_code == 'WENG'){
                return Inertia::render('Application/ENGLevels');
            }

            
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
        
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

    public function getStudentIDPage(){
        return Inertia::render('Application/AccessStudentID');

    }

    public function postStudentID(Request $request){
        try{

            return redirect()->route('admission.letter');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getAdmissionLetterPage(){
        return Inertia::render('Application/AdmissionLetter');

    }
     public function getFinalPage(){
        return Inertia::render('Application/Final');

    }

    // public function getSingleSubjectUnits($course){
    //     $unitsQuery = $this->generalQueries->unitsQuery();
    //     $applicantsURL = config('app.odata') . "{$unitsQuery}?". '$filter=' . rawurlencode("Email eq '{$email}'");
    //     $applicantsData = $this->getOdata($applicantsURL);
    // }
}
