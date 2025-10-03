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
use App\Models\EmergencyContact;
use Illuminate\Support\Facades\Auth;



class ApplicationController extends Controller
{
    use OdataTrait;
    use GeneralTrait;
    protected $generalQueries;
    protected $user;

    public function __construct()
    {
        $this->generalQueries = new GeneralQueries();
        $this->user = Auth::user();
    }

    // Add this method to your ApplicationController or create a BaseController
    protected function getCompletedSteps($applicantCourse = null, $applicant = null)
    {
        $completedSteps = [];
        
        // Check ApplicantCourse completion
        if ($applicantCourse) {
            // Application flow steps
            if (!empty($applicantCourse->mode_of_study)) {
                $completedSteps[] = 'mode.of.study';
            }
            if (!empty($applicantCourse->department_code)) {
                $completedSteps[] = 'department';
            }
            if (!empty($applicantCourse->course_code)) {
                $completedSteps[] = 'pick.course';
            }
            if (!empty($applicantCourse->course_level)) {
                $completedSteps[] = 'course.levels';
            }
            if (!empty($applicantCourse->unit_code)) {
                $completedSteps[] = 'course-type';
            }
            if (!empty($applicantCourse->start_date)) {
                $completedSteps[] = 'class.start.date';
            }
            if (!empty($applicantCourse->class_time)) {
                $completedSteps[] = 'class.start.time';
            }
            
            // If all course info is filled, mark course summary as complete
            if (count(array_intersect(['mode.of.study', 'department', 'pick.course', 'course.levels', 'course-type', 'class.start.date', 'class.start.time'], $completedSteps)) >= 6) {
                $completedSteps[] = 'course.summary';
            }
        }
        
        // Check Applicant (Bio Data) completion
        if ($applicant) {
            // Mark bio data start if we have basic course info
            if (count($completedSteps) >= 3) {
                $completedSteps[] = 'start.bio.data';
            }
            
            // Bio data flow steps
            if (!empty($applicant->first_name) && !empty($applicant->last_name)) {
                $completedSteps[] = 'full.name';
            }
            if (!empty($applicant->phone_no)) {
                $completedSteps[] = 'contacts';
            }
            if (!empty($applicant->nationality)) {
                $completedSteps[] = 'nationality';
            }
            if (!empty($applicant->email)) {
                $completedSteps[] = 'email.address';
            }
            if (!empty($applicant->residence)) {
                $completedSteps[] = 'residence';
            }
            if (!empty($applicant->marketing)) {
                $completedSteps[] = 'marketing';
            }
            if (!empty($applicant->allergies)) {
                $completedSteps[] = 'allergies';
                
                // If they have allergies and description is filled
                if ($applicant->allergies === 'Yes' && !empty($applicant->allergy_description)) {
                    $completedSteps[] = 'allergy.description';
                } elseif ($applicant->allergies === 'No') {
                    // If no allergies, skip the description step
                    $completedSteps[] = 'allergy.description';
                }
            }
            
            // Check emergency contacts from separate table
            $emergencyContact = EmergencyContact::where('applicant_id', $applicant->id)->first();
            if ($emergencyContact && !empty($emergencyContact->full_name) && !empty($emergencyContact->phone_no)) {
                $completedSteps[] = 'emergency.contact';
            }
            
            if (!empty($applicant->passport_file_path)) {
                $completedSteps[] = 'upload.id';
            }
            if (!empty($applicant->student_image_file_path)) {
                $completedSteps[] = 'upload.photo';
            }
            
            // If most bio data is complete, mark bio summary
            $bioSteps = ['full.name', 'contacts', 'nationality', 'email.address', 'residence'];
            if (count(array_intersect($bioSteps, $completedSteps)) >= 4) {
                $completedSteps[] = 'bio.data.summary';
            }
            
            // Student ID - you might need to add this field or check application_status
            if (!empty($applicant->application_no)) {
                $completedSteps[] = 'student.id';
            }
        }
        
        // Check session data for regulations and payments
        $sessionData = session('user_data', []);
        
        // Regulations completion - track these in session when user completes each page
        $regulationSteps = ['page.one', 'page.two', 'page.three', 'page.four', 'page.five', 'page.six', 'page.seven', 'page.eight'];
        foreach ($regulationSteps as $step) {
            if (!empty($sessionData['regulations'][$step])) {
                $completedSteps[] = $step;
            }
        }
        
        if (!empty($sessionData['declaration_accepted'])) {
            $completedSteps[] = 'declaration';
        }
        
        // Payment completion
        if (!empty($sessionData['payment_calculated'])) {
            $completedSteps[] = 'amount.payable';
        }
        if (!empty($sessionData['payment_instructions_viewed'])) {
            $completedSteps[] = 'payment.instructions';
        }
        if (!empty($sessionData['payment_updated'])) {
            $completedSteps[] = 'update.payment';
        }
        
        // Final steps - check application_status
        if ($applicant && $applicant->application_status === 'Approved') {
            $completedSteps[] = 'admission.letter';
        }
        if ($applicant && $applicant->application_status === 'Completed') {
            $completedSteps[] = 'final';
        }
        
        return $completedSteps;
    }

    

    public function getDepartmentPage(){
        try {

            $departmentsQuery = $this->generalQueries->departmentsQuery();
            $departmentsURL = config('app.odata') . "{$departmentsQuery}?". '$filter=' . rawurlencode("Dimension_Code eq 'DEPARTMENT'");
            $departmentsData = $this->getOdata($departmentsURL);
            $departments = $departmentsData['value'];

            $applicationID =session('applicant_data')['applicationCourseID'];
            
            $applicantCourse = ApplicantCourse::where('id', $applicationID)->first();
            session()->put('applicant_data.application_no', $applicantCourse->applicant_id);
            $applicant = null;
            $completedSteps = $this->getCompletedSteps($applicantCourse, $applicant);

            return Inertia::render('Application/Department', [
                'departments' => $departments,
                'applicantCourse' => $applicantCourse,
                'completedSteps' => $completedSteps,
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

    public function editApplication($applicantID){
        try {
            dd($applicantID);
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getModeOfStudyPage(){
        try {
            $pendingApplications = $this->ValidateApplications();
            $applicant = null;
            $applicantCourse = null;


            if ($pendingApplications == false){

                return Inertia::render('Application/ModeOfStudy', [
                    'applicantCourse' => $applicantCourse,


                ]);

            }else if ($pendingApplications == true){
                $applications = Applicant::where('email', $this->user->email)
                    ->where('application_status' , 'new')
                    ->first();

               
                $applicantCourse = ApplicantCourse::where('applicant_id', $applications->id)->first();
                $completedSteps = $this->getCompletedSteps($applicantCourse, $applicant);

                return Inertia::render('Application/ModeOfStudy', [
                    'applicantCourse' => $applicantCourse,
                    'completedSteps' => $completedSteps,


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
          
            $email = Auth::user()->email; 
            
            $applications = Applicant::where('email', $email)
                ->where('application_status' , 'new')
                ->first();
            
            if ($applications == null) {
                return false;
            } else {
                return true;
            }

        }catch(Exception $e){
            return false;
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
                    'first_name' => $this->user->first_name,
                    'second_name' => $this->user->second_name,
                    'last_name' => $this->user->last_name,
                    'email' => strtolower($this->user->email),
                    'application_date' => date('Y-m-d'),
                ];
                $newApplication = Applicant::create($application);
                if($newApplication->exists){
                
                    session()->put('applicant_data.application_no', $newApplication->id);
                     
                    $applicantCourse = [
                        'mode_of_study' =>$validated['mode_of_study'] == 'inclass' ? 1 : 2,
                        'applicant_id' => session('applicant_data')['application_no'],
                    ];

                    $newApplicantCourse = ApplicantCourse::create($applicantCourse);
                                // $applicationNo = session('user_data')['application_no'];
                  

                    if($newApplicantCourse->exists){
                        session()->put('applicant_data.applicationCourseID', $newApplicantCourse->id);
                        return redirect()->route('department');
                    }   
                }
            } else if ($pendingApplications == true){
                $applications = Applicant::where('email', $this->user->email)
                    ->where('application_status' , 'new')
                    ->first();
                $applicantCourse = ApplicantCourse::where('applicant_id', $applications->id)->first();
                
                if($applicantCourse == null){
                     $applicantCourse = [
                        'mode_of_study' =>$validated['mode_of_study'] == 'inclass' ? 1 : 2,
                        'applicant_id' => $applications->id,
                    ];

                    $newApplicantCourse = ApplicantCourse::create($applicantCourse);
                    session()->put('applicant_data.applicationCourseID', $newApplicantCourse->id);
                    session()->put('applicant_data.application_no', $applications->id);
                } else {
                    $applicantCourse->mode_of_study = $validated['mode_of_study'] == 'inclass' ? 1 : 2;
                    if (!$applicantCourse->save()) {
                        return redirect()->back()->withErrors([
                            'error' => 'Failed to save the mode of study. Please try again.'
                        ]);
                    }
                    session()->put('applicant_data.applicationCourseID', $applicantCourse->id);
                    session()->put('applicant_data.application_no', $applications->id);
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
            $applicant = null;
            $completedSteps = $this->getCompletedSteps($applicantCourse, $applicant);

            
            return Inertia::render('Application/PickCourse', [
                'courses' => $courseData['value'],
                'department' => $applicantCourse->department_code,
                'applicantCourse' => $applicantCourse,
                'completedSteps' => $completedSteps,
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
             $applicant = null;
            $applicationID =session('user_data')['applicationCourseID'];
            $applicantCourse = ApplicantCourse::where('id', $applicationID)->first();
            $applicanCourseCode = $applicantCourse->course_code;
                $completedSteps = $this->getCompletedSteps($applicantCourse, $applicant);
            
            if ($applicantCourse->department_code == 'WCAPS'){
                $unitFeesQuery = $this->generalQueries->unitFeesQuery();
                $unitFeesURL = config('app.odata') . "{$unitFeesQuery}?". '$filter=' . rawurlencode("CourseCode eq '{$applicanCourseCode}'");
                $unitFeesData = $this->getOdata($unitFeesURL);
                $unitFees = $unitFeesData['value'];
                return Inertia::render('Application/CourseType', [
                    'units' => $unitFees,
                    'completedSteps' => $completedSteps,
                ]);
            } else if ($applicantCourse->department_code == 'WENG'){
                $courseLevelsQuery = $this->generalQueries->courseLevelsQuery();
                $courseLevelsURL = config('app.odata') . "{$courseLevelsQuery}?". '$filter=' . rawurlencode("CourseCode eq '{$applicanCourseCode}'");
                $courseLevelsData = $this->getOdata($courseLevelsURL);
                $courseLevels = $courseLevelsData['value'];
                

                return Inertia::render('Application/ENGLevels', [
                    'courseLevels' => $courseLevels,
                    'completedSteps' => $completedSteps,
                    
                ]);
            }else if ($applicantCourse->department_code == 'WBM'){
                $courseLevelsQuery = $this->generalQueries->courseLevelsQuery();
                $courseLevelsURL = config('app.odata') . "{$courseLevelsQuery}?". '$filter=' . rawurlencode("CourseCode eq '{$applicanCourseCode}'");
                $courseLevelsData = $this->getOdata($courseLevelsURL);
                $courseLevels = $courseLevelsData['value'];

                return Inertia::render('Application/WBMCourseLevels', [
                    'courseLevels' => $courseLevels,
                    'completedSteps' => $completedSteps,
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
            $courseLevel = '';
            $courseDescri = '';
            if ($validated['courseLevel'] != null){
                $courseCodePtrn = '/^([^.]+)\.\./';
                $courseDescriPattern = '/\.\.(.+)$/';

                if (preg_match($courseCodePtrn, $validated['courseLevel'], $matches)) {
                    $courseLevel = trim($matches[1]);
                }

                if (preg_match($courseDescriPattern, $validated['courseLevel'] , $matches)) {
                    $courseDescri = $matches[1];
                }
            } 
            $applicationID =session('user_data')['applicationCourseID'];
            
            $applicantCourse = ApplicantCourse::where('id', $applicationID)->first();
            $applicantCourse->course_level = trim($courseLevel);
            $applicantCourse->course_description = $courseDescri;
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
            $applicant = null;
            $applicationID =session('user_data')['applicationCourseID'];
            $applicantCourse = ApplicantCourse::where('id', $applicationID)->first();
            $completedSteps = $this->getCompletedSteps($applicantCourse, $applicant);
            return Inertia::render('Application/ClassStartDate',[
                'applicantCourse' => $applicantCourse,
                'completedSteps' => $completedSteps,
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
            
            $applicant = null;
            $completedSteps = $this->getCompletedSteps($applicantCourse, $applicant);
            return Inertia::render('Application/ClassTime', [
                'classTimes' => $classTimes,
                'applicantCourse' => $applicantCourse,
                'completedSteps' => $completedSteps,
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
            $applicant = null;

            $applicationID =session('user_data')['applicationCourseID'];
            
            $applicantCourse = ApplicantCourse::where('id', $applicationID)->first();
            $completedSteps = $this->getCompletedSteps($applicantCourse, $applicant);
            return Inertia::render('Application/CourseSummary', [
                'applicantCourse' => $applicantCourse,
                'completedSteps' => $completedSteps,
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
        try{
            $studentNo = session('user_data')['student_no'];
            $studentQuery = $this->generalQueries->studentsQuery();
            $studentURL = config('app.odata') . "{$studentQuery}?". '$filter=' . rawurlencode("No eq '{$studentNo}'");
            $students= $this->getOdata($studentURL);
            $studentsData = $students['value'][0];
            return Inertia::render('Application/AccessStudentID', [
                'studentsData' => $studentsData,
            ]);

        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

    public function postStudentID(Request $request){
        $validated = $request->validate([
            'studentPortalPWD' => 'required|string',
            'idVerificationURL' => 'required|string',
        ]);
        try{
            $studentNo = session('user_data')['student_no'];
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
            $params->studentPortalPWD = trim($validated['studentPortalPWD']);
            $params->iDVerificationUrl = trim(($validated['idVerificationURL']));
            $result = $soapClient->InsertStudentIDDetails($params);

            if($result){
                if($result->return_value){
                    return redirect()->route('admission.letter')->with('success', 'Student ID details captured successfully');

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

    public function getAdmissionLetterPage(){
        try{
            $studentNo = session('user_data')['student_no'];
            return Inertia::render('Application/AdmissionLetter', [
                'studentNo' => $studentNo,
            ]);

        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

    public function downloadAdmissionLetter($studentNo){
        try{
            $context = $this->initializeSoapProcess();
            $soapClient = new \SoapClient(
                config('app.webService'), 
                [
                    'stream_context' => $context,
                    'trace' => 1,
                    'exceptions' => 1
                    
                ]
            );
            $studentNo = session('user_data')['student_no'];
            $params = new \stdClass();
            $params->studentNo = $studentNo;
            
            $result = $soapClient->GetAdmissionLetter($params);
            if($result){
                if($result->return_value === 'Could Not find student'){
                    return redirect()->back()->with('error', 'Could Not find student');
                }
                if($this->is_base64($result->return_value)){
                    $base64Data = $result->return_value;
                    $pdf_decoded = base64_decode ($base64Data);

                    $fileName = 'Admission_letter' . '_' . $studentNo . '_' . '.pdf';
                    file_put_contents($fileName, $pdf_decoded);

                    return response($pdf_decoded)
                        ->header('Content-Type', 'application/pdf')
                        ->header('Content-Disposition', "attachment; filename=\"$fileName\"");
                } else {
                    return redirect()->back()->with('error', 'The result is not a valid base64 string.');

                }
            }
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }
     public function getFinalPage(){
        return Inertia::render('Application/Final');

    }

    public function getApplicationProcessingPage(){
        try{
             $applicant = null;
            $applicantCourse = null;

            $completedSteps = $this->getCompletedSteps($applicantCourse, $applicant);
            return Inertia::render('Application/ApplicationProcessing', [
                'completedSteps' => $completedSteps,
            ]);

            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }

    // public function getSingleSubjectUnits($course){
    //     $unitsQuery = $this->generalQueries->unitsQuery();
    //     $applicantsURL = config('app.odata') . "{$unitsQuery}?". '$filter=' . rawurlencode("Email eq '{$email}'");
    //     $applicantsData = $this->getOdata($applicantsURL);
    // }


    public function CreateApplicantInERP(){
        try{
            
            // Get Applicant data
            $applicationID =session('user_data')['application_no'];
            $applicant = Applicant::where('id', $applicationID)
                ->where('application_status', 'submitted')
                ->first();

            if (!$applicant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Applicant not found'
                ], 404);
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
                $params->firstName = trim(ucfirst($applicant->first_name));
                $params->secondName = trim(ucfirst($applicant->second_name));
                $params->lastName = trim(ucfirst($applicant->last_name));
                $params->email = strtolower(trim($applicant->email));
                $params->portalPassword = strtolower(trim('1234'));
                $params->nationality = trim($applicant->nationality);
                $params->residence = (trim($applicant->residence));
                $params->marketing = trim($applicant->marketing);
                $params->allergies = trim($applicant->allergies);
                $params->allergyDescription = trim($applicant->allergy_description);
                $params->phoneNo = trim($applicant->phone_no);

                $imageFilePath = $applicant->passport_file_path;
                if($imageFilePath && file_exists($imageFilePath)){
                    $fileContent = file_get_contents($imageFilePath);
                    $base64 = base64_encode($fileContent);

                    
                }
                $params->studentImageBase64 = $base64;
            
                $result = $soapClient->CreateApplicantAccount($params);

                if($result){
                    return response()->json([
                        'success' => true,
                        'data' => $result,
                    ], 200);
            
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'BC insert error'
                    ], 404);
                }

          
        }catch(Exception $e){
            return response()->json([
                        'success' => false,
                        'message' => $e->getMessage()
                    ], 404);
            // return redirect()->back()->withErrors([
            //     'error' => $e->getMessage()
            // ]);
        }
    }

    public function InsertEmergencyContacts($applicantNo){
        try{
            
            // Get Applicant data
            $applicationID =session('user_data')['application_no'];
            $applicant = Applicant::where('id', $applicationID)
                ->where('application_status', 'submitted')
                ->first();

            $emergencyContact = EmergencyContact::where('applicant_id', $applicant->id)->first();

            if (!$emergencyContact) {
                return response()->json([
                    'success' => false,
                    'message' => 'Applicant not found'
                ], 404);
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
                $params->applicationNo = trim($applicantNo);
                $params->fullName = trim(ucfirst($emergencyContact->full_name));
                $params->phoneNo = trim(($emergencyContact->phone_no));
                $params->relationship = ($emergencyContact->relationship);
               
                
                $result = $soapClient->InsertEmergencyContacts($params);

                if($result){
                    return response()->json([
                        'success' => true,
                        'data' => $result,
                    ], 200);
            
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'BC insert error'
                    ], 404);
                }

          
        }catch(Exception $e){
            return response()->json([
                        'success' => false,
                        'message' => $e->getMessage()
                    ], 404);
            // return redirect()->back()->withErrors([
            //     'error' => $e->getMessage()
            // ]);
        }
    }

    public function InsertApplicantCourse($applicantNo){
        try{
            
            // Get Applicant data
            $applicationID =session('user_data')['application_no'];
            $applicant = Applicant::where('id', $applicationID)
                ->where('application_status', 'submitted')
                ->first();

            $applicantCourse = ApplicantCourse::where('applicant_id', $applicant->id)->first();

            if (!$applicantCourse) {
                return response()->json([
                    'success' => false,
                    'message' => 'Applicant not found'
                ], 404);
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
                $params->applicationNo = trim($applicantNo);
                $params->department = ($applicantCourse->department_code);
                $params->modeOfStudy = (int) ($applicantCourse->mode_of_study);
                $params->courseCode = $applicantCourse->course_code;
                $params->courseLevel = $applicantCourse->course_level;
                $params->startDate = $applicantCourse->start_date;
                $params->classTime = $applicantCourse->class_time;
                $params->courseType = $applicantCourse->unit_status;
                $params->unitCode = $applicantCourse->unit_code;

               
                
                $result = $soapClient->InsertApplicantCourse($params);

                if($result){
                    return response()->json([
                        'success' => true,
                        'data' => $result,
                    ], 200);
            
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'BC insert error'
                    ], 404);
                }

          
        }catch(Exception $e){
            return response()->json([
                        'success' => false,
                        'message' => $e->getMessage()
                    ], 404);
        }
    }

    public function ConvertApplicationToCustomer($applicantNo){
        try{
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
            $params->applicationNo = trim($applicantNo);

            $result = $soapClient->ConvertApplicantToStudent($params);

                if($result){
                    session()->put('user_data.student_no', $result->return_value);
                    return response()->json([
                        'success' => true,
                        'data' => $result,
                    ], 200);
            
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'BC insert error'
                    ], 404);
                }

        }catch(Exception $e){
            return response()->json([
                        'success' => false,
                        'message' => $e->getMessage()
                    ], 404);
        }
    }

    function is_base64($s){
        // Check if there are valid base64 characters
        if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $s)){ 
            return false;
        }
    
        // Decode the string in strict mode and check the results
        $decoded = base64_decode($s, true);
        if(false === $decoded) {
            return false;
        }
    
        // Encode the string again
        if(base64_encode($decoded) != $s) {
            return false;
        }
    
        return true;
    }
}
