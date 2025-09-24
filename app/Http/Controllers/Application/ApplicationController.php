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

            $applicationID =session('user_data')['applicationCourseID'];
            $applicantCourse = ApplicantCourse::where('id', $applicationID)->first();

            return Inertia::render('Application/Department', [
                'departments' => $departments,
                'applicantCourse' => $applicantCourse,
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

            if($validated['departmentCode'] != null){
                $departmentCodePtrn = '/^([^.]+)\.\./';
                $departmentDescriPtrn = '/\.\.(.+)$/';
                if (preg_match($departmentCodePtrn, $validated['departmentCode'], $matches)) {
                    $departmentCode = trim($matches[1]);
                }

                if (preg_match($departmentDescriPtrn, $validated['departmentCode'] , $matches)) {
                    $departmentDescription = $matches[1];
                }
                
            }
            
            $applicationID =session('user_data')['applicationCourseID'];
            
            $applicantCourse = ApplicantCourse::where('id', $applicationID)->first();
            $applicantCourse->department_code = trim($departmentCode);
            $applicantCourse->department_description = trim($departmentDescription);
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
            $pendingApplications = $this->ValidateApplications();

            if ($pendingApplications == false){
                return Inertia::render('Application/ModeOfStudy');

            }else if ($pendingApplications == true){
                $email = session('user_data')['email'];
                $applications = Applicant::where('email', $email)
                    ->where('application_status' , 'new')
                    ->first();
                $applicantCourse = ApplicantCourse::where('applicant_id', $applications->id)->first();

                return Inertia::render('Application/ModeOfStudy', [
                    'applicantCourse' => $applicantCourse,
                ]);

            }
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }

    private function ValidateApplications(){
        try{
            $email = session('user_data')['email'];
            $applications = Applicant::where('email', $email)
                ->where('application_status' , 'new')
                ->first();
            
            if ($applications === null) {
                return false;
            } else {
                return true;
            }

        }catch(Exception $e){
            // return false;
        }
    }

    public function postModeOfStudy(Request $request){
        $validated = $request->validate([
            'mode_of_study' => 'required|string',
        ]);
        try{
            $pendingApplications = $this->ValidateApplications();

            if ($pendingApplications == false){
                $application = [
                    'first_name' => session('user_data')['first_name'],
                    'second_name' => session('user_data')['second_name'],
                    'last_name' => session('user_data')['last_name'],
                    'email' => strtolower(session('user_data')['email']),
                ];
                $newApplication = Applicant::create($application);
                if($newApplication->exists){
                    session()->put('user_data.application_no', $newApplication->id);
                    
                    $applicantCourse = [
                        'mode_of_study' =>$validated['mode_of_study'] == 'inclass' ? 1 : 2,
                        'applicant_id' => session('user_data')['application_no'],
                    ];

                    $newApplicantCourse = ApplicantCourse::create($applicantCourse);
                                // $applicationNo = session('user_data')['application_no'];


                    if($newApplicantCourse->exists){
                        session()->put('user_data.applicationCourseID', $newApplicantCourse->id);
                        return redirect()->route('department');
                    }   
                }
            } else if ($pendingApplications == true){
                $email = session('user_data')['email'];
                $applications = Applicant::where('email', $email)
                    ->where('application_status' , 'new')
                    ->first();
                $applicantCourse = ApplicantCourse::where('applicant_id', $applications->id)->first();
                
                if($applicantCourse == null){
                     $applicantCourse = [
                        'mode_of_study' =>$validated['mode_of_study'] == 'inclass' ? 1 : 2,
                        'applicant_id' => $applications->id,
                    ];

                    $newApplicantCourse = ApplicantCourse::create($applicantCourse);
                    session()->put('user_data.applicationCourseID', $newApplicantCourse->id);
                } else {
                    $applicantCourse->mode_of_study = $validated['mode_of_study'] == 'inclass' ? 1 : 2;
                    if (!$applicantCourse->save()) {
                        return redirect()->back()->withErrors([
                            'error' => 'Failed to save the mode of study. Please try again.'
                        ]);
                    }
                    session()->put('user_data.applicationCourseID', $applicantCourse->id);
                }
                
                
                
                return redirect()->route('department');
                

            }

            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }


    public function getPickCoursePage(){
        try{
            $applicationID =session('user_data')['applicationCourseID'];
             $applicantCourse = ApplicantCourse::where('id', $applicationID)->first();

            if(!$applicantCourse){
                return redirect()->route('mode.of.study')->with('success', 'Could not process application. Please contact support');;
            }

            $courseQuery = $this->generalQueries->coursesQuery();
            $coursesURL = config('app.odata') . "{$courseQuery}";
            $courseData = $this->getOdata($coursesURL);
            
            return Inertia::render('Application/PickCourse', [
                'courses' => $courseData['value'],
                'department' => $applicantCourse->department_code,
                'applicantCourse' => $applicantCourse,
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
            if($validated['courseCode'] != null){
                $courseCodePtrn = '/^([^.]+)\.\./';
                $courseDescriPtrn = '/\.\.(.+)$/';
                if (preg_match($courseCodePtrn, $validated['courseCode'], $matches)) {
                     $courseCode = trim($matches[1]);
                }

                if (preg_match($courseDescriPtrn, $validated['courseCode'] , $matches)) {
                    $courseDescription = $matches[1];
                }
                
            }
            $applicationID =session('user_data')['applicationCourseID'];
            
            $applicantCourse = ApplicantCourse::where('id', $applicationID)->first();
            $applicantCourse->course_code = trim($courseCode);
            $applicantCourse->course_description = trim($courseDescription);
            $applicantCourse->save();

            return redirect()->route('course-type');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

    // public function getCourseLevels(){
    //    try{
    //          $unitFeesQuery = $this->generalQueries->unitFeesQuery();
    //     $unitFeesURL = config('app.odata') . "{$unitFeesQuery}?". '$filter=' . rawurlencode("CourseCode eq '{$applicanCourseCode}'");
    //     $unitFeesData = $this->getOdata($unitFeesURL);
    //     $unitFees = $unitFeesData['value'];
    //    }
            
    // }

    public function getCourseTypePage(){
        try{
            $applicationID =session('user_data')['applicationCourseID'];
            $applicantCourse = ApplicantCourse::where('id', $applicationID)->first();
            $applicanCourseCode = $applicantCourse->course_code;
            
            if ($applicantCourse->department_code == 'WCAPS'){
                $unitFeesQuery = $this->generalQueries->unitFeesQuery();
                $unitFeesURL = config('app.odata') . "{$unitFeesQuery}?". '$filter=' . rawurlencode("CourseCode eq '{$applicanCourseCode}'");
                $unitFeesData = $this->getOdata($unitFeesURL);
                $unitFees = $unitFeesData['value'];
                return Inertia::render('Application/CourseType', [
                    'units' => $unitFees,
                ]);
            } else if ($applicantCourse->department_code == 'WENG'){
                $courseLevelsQuery = $this->generalQueries->courseLevelsQuery();
                $courseLevelsURL = config('app.odata') . "{$courseLevelsQuery}?". '$filter=' . rawurlencode("CourseCode eq '{$applicanCourseCode}'");
                $courseLevelsData = $this->getOdata($courseLevelsURL);
                $courseLevels = $courseLevelsData['value'];
                

                return Inertia::render('Application/ENGLevels', [
                    'courseLevels' => $courseLevels,
                ]);
            }else if ($applicantCourse->department_code == 'WBM'){
                $courseLevelsQuery = $this->generalQueries->courseLevelsQuery();
                $courseLevelsURL = config('app.odata') . "{$courseLevelsQuery}?". '$filter=' . rawurlencode("CourseCode eq '{$applicanCourseCode}'");
                $courseLevelsData = $this->getOdata($courseLevelsURL);
                $courseLevels = $courseLevelsData['value'];

                return Inertia::render('Application/WBMCourseLevels', [
                    'courseLevels' => $courseLevels,
                ]);
            }


            
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
        
    }

    public function postCourseLevel(Request $request){
        $validated = $request->validate([
            'courseLevel' => 'required|string',
        ]);

        try{
            $applicationID =session('user_data')['applicationCourseID'];
            
            $applicantCourse = ApplicantCourse::where('id', $applicationID)->first();
            $applicantCourse->course_level = trim($validated['courseLevel']);
            $applicantCourse->unit_code = '';
            $applicantCourse->unit_status = 'Full Course';
            if (!$applicantCourse->save()) {
                return redirect()->back()->withErrors([
                    'error' => 'Failed to save the mode of study. Please try again.'
                ]);
            }


            return redirect()->route('class.start.date');
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }
    public function postCourseType(Request $request){
        $validated = $request->validate([
            'courseLevel' => 'nullable|string',
            'singleSubject' => 'nullable|string',
        ]);
        try{
            $courseLevel = '';
            $courseUnit = '';
            if ($validated['singleSubject'] != null){
                $pattern = '/^([^.]+)\.\./';
                if (preg_match($pattern, $validated['singleSubject'], $matches)) {
                    $courseLevel = trim($matches[1]);
                }

                $unitPattern = '/\.\.(.+)$/';
                if (preg_match($pattern, $validated['singleSubject'] , $matches)) {
                    $courseUnit = $matches[1];
                }
            } else {
                $courseLevel = $validated['courseLevel'];
            }

            $applicationID =session('user_data')['applicationCourseID'];
            
            $applicantCourse = ApplicantCourse::where('id', $applicationID)->first();
            $applicantCourse->course_level = $courseLevel;
            $applicantCourse->unit_code = $courseUnit;
            $applicantCourse->unit_status = ($validated['singleSubject'] != null) ? 'Single Subject' : 'Full Course';
            $applicantCourse->save();


            return redirect()->route('class.start.date');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }


    public function getClassStartDatePage(){
        try{
            $applicationID =session('user_data')['applicationCourseID'];
            $applicantCourse = ApplicantCourse::where('id', $applicationID)->first();
            return Inertia::render('Application/ClassStartDate',[
                'applicantCourse' => $applicantCourse,
            ]);  
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
        
    }
    public function postClassStartDate(Request $request){
        $validated = $request->validate([
            'startDate' => 'nullable|date',
        ]);    
        try{
            $applicationID =session('user_data')['applicationCourseID'];
            
            $applicantCourse = ApplicantCourse::where('id', $applicationID)->first();
            $applicantCourse->start_date = $validated['startDate'];
            if (!$applicantCourse->save()) {
                return redirect()->back()->withErrors([
                    'error' => 'Failed to save the start date. Please try again.'
                ]);
            }

            return redirect()->route('class.start.time');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }


    public function getClassStartTimePage(){
        try{
            $classTimeQuery = $this->generalQueries->classTimeQuery();
            $classTimeURL = config('app.odata') . "{$classTimeQuery}";
            $classTimeData = $this->getOdata($classTimeURL);
            $classTimes = $classTimeData['value'];
           
            $applicationID =session('user_data')['applicationCourseID'];
            $applicantCourse = ApplicantCourse::where('id', $applicationID)->first();
            return Inertia::render('Application/ClassTime', [
                'classTimes' => $classTimes,
                'applicantCourse' => $applicantCourse,
            ]);
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }
    public function postClassStartTime(Request $request){
        $validated = $request->validate([
            'time' => 'nullable|string',
        ]);
        try{
             $applicationID =session('user_data')['applicationCourseID'];
            
            $applicantCourse = ApplicantCourse::where('id', $applicationID)->first();
            $applicantCourse->class_time = trim($validated['time']);
            $applicantCourse->application_date = date('Y-m-d');
            if (!$applicantCourse->save()) {
                return redirect()->back()->withErrors([
                    'error' => 'Failed to save the class time. Please try again.'
                ]);
            }

            return redirect()->route('course.summary');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

    public function getCourseSummmary(){
        try{
            $applicationID =session('user_data')['applicationCourseID'];
            
            $applicantCourse = ApplicantCourse::where('id', $applicationID)->first();

            return Inertia::render('Application/CourseSummary', [
                'applicantCourse' => $applicantCourse,
            ]);

            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }
    public function postCourseSummmary(Request $request){
         $validated = $request->validate([
            'courseSummary' => 'nullable|string',
        ]);
        try{
            $applicationID =session('user_data')['applicationCourseID'];
            
            $applicantCourse = ApplicantCourse::where('id', $applicationID)->first();
            $applicantCourse->application_status = $validated['courseSummary'];
            if (!$applicantCourse->save()) {
                return redirect()->back()->withErrors([
                    'error' => 'Failed to save the start date. Please try again.'
                ]);
            }


            return redirect()->route('start.bio.data');
            
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
