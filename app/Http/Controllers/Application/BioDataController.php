<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Exception;
use App\Models\User;
use App\Models\Applicant;
use App\Models\EmergencyContact;
use App\Models\ApplicantCourse;
use App\Traits\OdataTrait;
use App\Models\GeneralQueries;
use App\Traits\GeneralTrait;
use Illuminate\Support\Str;
use App\Http\Controllers\BusinessCentralAPIController;
use Illuminate\Support\Facades\Auth;





class BioDataController extends Controller
{
      use OdataTrait;
    use GeneralTrait;
    protected $generalQueries;
    protected $businessCentralAccess;
    protected $user;
    protected $start;


    public function __construct()
    {
        $this->generalQueries = new GeneralQueries();
        $this->businessCentralAccess = new BusinessCentralAPIController;
        $this->user = Auth::user();
        $this->start = microtime(true);

    }

    protected function getCompletedSteps($applicantCourse = null, $applicant = null){
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


     public function getNamesPage(){
        try{
            // $email = trim(session('user_data')['email']);
            // $user = User::where('email', $email)->first();
            $applicantCourse = null;
            $applicant = null;
            $completedSteps = $this->getCompletedSteps($applicantCourse, $applicant);
            return Inertia::render('Application/BioData/FullName', [
                'user' => $this->user,
                'completedSteps' => $completedSteps,
            ]);
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
        
    }

    public function postNames(Request $request){
        $validated = $request->validate([
            'firstName' => 'required|string',
            'secondName' => 'required|string',
            'lastName' => 'required|string',
        ]);
        try{
            
            $applicationID = $this->retrieveOrUpdateSessionData('get','application_no' );
            $applicant = Applicant::where('id', $applicationID)->first();
            $applicant->first_name = $validated['firstName'];
            $applicant->second_name = $validated['secondName'];
            $applicant->last_name = $validated['lastName'];
            if (!$applicant->save()) {
                return redirect()->back()->withErrors([
                    'error' => 'Failed to save. Please try again.'
                ]);

            }

            $user = User::where('email', $this->user->email)->first();
            $user->first_name = $validated['firstName'];
            $user->second_name = $validated['secondName'];
            $user->last_name = $validated['lastName'];
            if (!$user->save()) {
                return redirect()->back()->withErrors([
                    'error' => 'Failed to save. Please try again.'
                ]);

            }


            return redirect()->route('contacts');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

    public function getContactPage(){
        try{
            $applicationID =$this->retrieveOrUpdateSessionData('get','application_no' );
            $applicant = Applicant::where('id', $applicationID)->first();
            $applicantCourse = null;

            $completedSteps = $this->getCompletedSteps($applicantCourse, $applicant);
            
            return Inertia::render('Application/BioData/Contacts', [
                'applicant' => $applicant,
                'completedSteps' => $completedSteps,
            ]);

        } catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function postContact(Request $request){
        $validated = $request->validate([
            'phoneNo' => 'required|string',
        ]);
        try{
            $applicationID =$this->retrieveOrUpdateSessionData('get','application_no' );
            $applicant = Applicant::where('id', $applicationID)->first();
            $applicant->phone_no = $validated['phoneNo'];
            if (!$applicant->save()) {
                return redirect()->back()->withErrors([
                    'error' => 'Failed to save. Please try again.'
                ]);
            }
            $this->testPerformance($this->start, 'performance', 'inserting contacts');


            return redirect()->route('nationality');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

    public function getNationalityPage(){
        try{
            $applicationID =$this->retrieveOrUpdateSessionData('get','application_no' );
            $applicant = Applicant::where('id', $applicationID)->first();
            
            $countriesQuery = $this->generalQueries->countriesQuery();
            $countriesURL = config('app.odata') . "{$countriesQuery}";
            $countriesData = $this->businessCentralAccess->getOdata($countriesURL);
            $response = $this->validateAPIResponse($countriesData, url()->previous());
           
            if ($response) {
                return $response;
            }
            $countries = $countriesData['data']['value'];

            $applicantCourse = null;

            $completedSteps = $this->getCompletedSteps($applicantCourse, $applicant);
            $this->testPerformance($this->start, 'performance', 'getting Nationalities');
            
            
            return Inertia::render('Application/BioData/Nationality', [
                'countries' => $countries,
                'applicant' => $applicant,
                'completedSteps' => $completedSteps,
            ]);

            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function postNationality(Request $request){
         $validated = $request->validate([
            'country' => 'required|string',
            'countryName' => 'required|string',
        ]);
        try{
             

            $applicationID =$this->retrieveOrUpdateSessionData('get','application_no');
            $applicant = Applicant::where('id', $applicationID)->first();
            $applicant->nationality = $validated['country'];
            $applicant->country_name = $validated['countryName'];
            if (!$applicant->save()) {
                return redirect()->back()->withErrors([
                    'error' => 'Failed to save. Please try again.'
                ]);
            }
            $this->testPerformance($this->start, 'performance', 'inserting Nationality to db');

            return redirect()->route('email.address');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

     public function getEmailAddressPage(){
        try{
            $applicant = null;
            $applicantCourse = null;

            $completedSteps = $this->getCompletedSteps($applicantCourse, $applicant);
            
            return Inertia::render('Application/BioData/EmailAddress', [
                'user' => $this->user,
                'completedSteps' => $completedSteps,

            ]);

            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function postEmailAddress(Request $request){
        $validated = $request->validate([
            'email' => 'required|string',
        ]);
        try{
            $applicationID =$this->retrieveOrUpdateSessionData('get','application_no');
            $applicant = Applicant::where('id', $applicationID)->first();
            $applicant->email = trim($validated['email']);
            if (!$applicant->save()) {
                return redirect()->back()->withErrors([
                    'error' => 'Failed to save. Please try again.'
                ]);
            }

            $user = User::where('email', $this->user->email)->first();
            $user->email = trim($validated['email']);
            if (!$user->save()) {
                return redirect()->back()->withErrors([
                    'error' => 'Failed to save. Please try again.'
                ]);

            }

            return redirect()->route('date.of.birth');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

    public function getResidencePage(){
        try{
            $applicationID =$this->retrieveOrUpdateSessionData('get','application_no');
            $applicant = Applicant::where('id', $applicationID)->first();

            $residenceQuery = $this->generalQueries->residenceQuery();
            $residenceURL = config('app.odata') . "{$residenceQuery}?" . '$filter=' . rawurlencode("Type eq 'Location'");
            $residenceData =  $this->businessCentralAccess->getOdata($residenceURL);
            $response = $this->validateAPIResponse($residenceData, url()->previous());
           
            if ($response) {
                return $response;
            }
            $residence = $residenceData['data']['value'];
            $applicantCourse = null;

            $completedSteps = $this->getCompletedSteps($applicantCourse, $applicant);
            $this->testPerformance($this->start, 'performance', 'getting residences');
            
            // dd($residence);
            
            return Inertia::render('Application/BioData/Residence', [
                'residences' => $residence,
                'applicant' => $applicant,
                'completedSteps' => $completedSteps,
            ]);

            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function postResidence(Request $request){
        $validated = $request->validate([
            'residence' => 'required|string',
            'residenceDescription' => 'required|string',
        ]);
        try{
            $applicationID = $this->retrieveOrUpdateSessionData('get', 'application_no');
            $applicant = Applicant::where('id', $applicationID)->first();
            $applicant->residence = $validated['residence'];
            $applicant->residence_description = $validated['residenceDescription'];
            if (!$applicant->save()) {
                return redirect()->back()->withErrors([
                    'error' => 'Failed to save. Please try again.'
                ]);
            }
            $this->testPerformance($this->start, 'performance', 'inserting residence into db');

            return redirect()->route('marketing');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

    public function getMarketingPage(){
         try{
             $applicationID =$this->retrieveOrUpdateSessionData('get', 'application_no');
            $applicant = Applicant::where('id', $applicationID)->first();

            $marketingQuery = $this->generalQueries->marketingQuery();
            $marketingURL = config('app.odata') . "{$marketingQuery}";
            $marketingData =  $this->businessCentralAccess->getOdata($marketingURL);
            $response = $this->validateAPIResponse($marketingData, url()->previous());
           
            if ($response) {
                return $response;
            }
            $marketing = $marketingData['data']['value'];


            $applicantCourse = null;

            $completedSteps = $this->getCompletedSteps($applicantCourse, $applicant);
            $this->testPerformance($this->start, 'performance', 'getting marketing data');

            
            return Inertia::render('Application/BioData/Marketing', [
                'marketingAreas' => $marketing,
                'applicant' => $applicant,
                'completedSteps' => $completedSteps,
            ]);
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
        
    }

    public function postMarketing(Request $request){
        $validated = $request->validate([
            'aboutUs' => 'required|string',
            'marketingDescription' => 'required|string',
        ]);
        try{
            $applicationID =$this->retrieveOrUpdateSessionData('get', 'application_no');
            $applicant = Applicant::where('id', $applicationID)->first();
            $applicant->marketing = $validated['aboutUs'];
            $applicant->marketing_description = $validated['marketingDescription'];
            if (!$applicant->save()) {
                return redirect()->back()->withErrors([
                    'error' => 'Failed to save. Please try again.'
                ]);
            }
            $this->testPerformance($this->start, 'performance', 'inserting about us into db');


            return redirect()->route('allergies');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

    public function getAllergiesPage(){
        try{
             $applicationID =$this->retrieveOrUpdateSessionData('get', 'application_no');
            $applicant = Applicant::where('id', $applicationID)->first();

             $applicantCourse = null;

            $completedSteps = $this->getCompletedSteps($applicantCourse, $applicant);
            
            return Inertia::render('Application/BioData/Allergies', [
                'applicant' => $applicant,
                'completedSteps' => $completedSteps,
            ]);

            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function postAllergies(Request $request){
        $validated = $request->validate([
            'allergy' => 'required|string',
        ]);
        try{

            $applicationID =$this->retrieveOrUpdateSessionData('get', 'application_no');
            $applicant = Applicant::where('id', $applicationID)->first();
            $applicant->allergies = $validated['allergy'] == 'yes' ? 1 : 2;
            if (!$applicant->save()) {
                return redirect()->back()->withErrors([
                    'error' => 'Failed to save. Please try again.'
                ]);
            }
            if ($validated['allergy'] == 'yes'){
                return redirect()->route('allergy.description');

            } else {
                return redirect()->route('emergency.contact');
            }
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

     public function getAllergyDescriptionPage(){
        try{
             $applicationID =$this->retrieveOrUpdateSessionData('get', 'application_no');
            $applicant = Applicant::where('id', $applicationID)->first();

            $applicantCourse = null;

            $completedSteps = $this->getCompletedSteps($applicantCourse, $applicant);
            
            return Inertia::render('Application/BioData/AllergyDescription', [
                'applicant' => $applicant,
                'completedSteps' => $completedSteps,
            ]);

        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function postAllergyDescription(Request $request){
        $validated = $request->validate([
            'allergyDescription' => 'required|string',
        ]);
        try{
            $applicationID =$this->retrieveOrUpdateSessionData('get', 'application_no');
            $applicant = Applicant::where('id', $applicationID)->first();
            $applicant->allergy_description = $validated['allergyDescription'];
            if (!$applicant->save()) {
                return redirect()->back()->withErrors([
                    'error' => 'Failed to save. Please try again.'
                ]);
            }
            return redirect()->route('emergency.contact');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

    public function getEmergencyContactPage(){
        try{
             $applicationID =$this->retrieveOrUpdateSessionData('get', 'application_no');
            $emergencyContact = EmergencyContact::where('applicant_id', $applicationID)->first();
            
            $applicantCourse = null;
            $applicant = null;

            $completedSteps = $this->getCompletedSteps($applicantCourse, $applicant);
            
            return Inertia::render('Application/BioData/EmergencyContact', [
                'emergencyContact' => $emergencyContact,
                'completedSteps' => $completedSteps,
            ]);

        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

    public function postEmergencyContact(Request $request){
        $validated = $request->validate([
            'fullName' => 'required|string',
            'relationship' => 'required|string',
            'phoneNo' => 'required|string',
        ]);
        try{
             
            if($validated['relationship'] == 'parent' ){
                $relationStatus = 1;
            } else if($validated['relationship'] == 'sibling'){
                $relationStatus = 3;
            }else if($validated['relationship'] == 'relative'){
                $relationStatus = 4;
            }else if($validated['relationship'] == 'spouse'){
                $relationStatus = 5;
            }else if($validated['relationship'] == 'child'){
                $relationStatus = 8;
            }

           
            $applicationID =$this->retrieveOrUpdateSessionData('get', 'application_no');
           
            $emergencyContact = [
                'full_name' => trim($validated['fullName']),
                'phone_no' => trim($validated['phoneNo']),
                'relationship' => $relationStatus,
                'applicant_id' => $applicationID,
            ];

            $newEmergencyContact = EmergencyContact::create($emergencyContact);
          
            if($newEmergencyContact->exists){
                return redirect()->route('upload.id');
            }
            
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

     public function getUploadIDPage(){
        try{
            $applicant = null;
            $applicantCourse = null;

            $completedSteps = $this->getCompletedSteps($applicantCourse, $applicant);
            return Inertia::render('Application/BioData/IDPassport', [
                'completedSteps' => $completedSteps,
            ]);
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
        
    }

    public function postUploadID(Request $request){
         $validated = $request->validate([
            
            'passport_id_file' => 'required|file|max:2048',

        ], [
            'passport_id_file.required' => 'test',

            'passport_id_file.max' => 'The uploaded file exceeds the allowed size of 2MB.',
        ]);
        try{
            if($request->hasFile('passport_id_file')){
                if ($request->file('passport_id_file')->isValid()) {
                 
                    $applicationID =$this->retrieveOrUpdateSessionData('get', 'application_no');
                    $safeEmail = Str::slug(Str::before($this->user->email, '@'));
                    $file = $request->file('passport_id_file');
                    $filename = $applicationID . '_' . $safeEmail.'.'. $file->getClientOriginalExtension();

                    $destination = storage_path('app\passport_files');
                    if (!is_dir($destination)) {
                        mkdir($destination, 0755, true);
                    }
                    $fullPath = $destination . DIRECTORY_SEPARATOR . $filename;

                    
                    if(file_exists($fullPath)){
                        unlink($fullPath);
                    } 
                    $file->move($destination, $filename);
                    
                    $applicationID =$this->retrieveOrUpdateSessionData('get', 'application_no');
                    $applicant = Applicant::where('id', $applicationID)->first();
                    $applicant-> passport_file_path = $fullPath;

                    $this->testPerformance($this->start, 'performance', 'processing passport file');
                    if (!$applicant->save()) {
                        return redirect()->back()->withErrors([
                            'error' => 'Failed to save. Please try again.'
                        ]);
                    }
                }
            }

            return redirect()->route('upload.photo');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

    public function getUploadPhotoPage(){
        try{
             $applicant = null;
            $applicantCourse = null;

            $completedSteps = $this->getCompletedSteps($applicantCourse, $applicant);
            return Inertia::render('Application/BioData/UploadPassportPhoto', [
                'completedSteps' => $completedSteps,
            ]);
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function postUploadPhoto(Request $request){
         $validated = $request->validate([  
            'passportImage' => 'required|image|mimes:jpeg,png||max:2048',
        ], [
            'passportImage.max' => 'The uploaded file exceeds the allowed size of 2MB.',
        ]);
        try{
            if($request->hasFile('passportImage')){
                if ($request->file('passportImage')->isValid()) {
                 
                    $applicationID =$this->retrieveOrUpdateSessionData('get', 'application_no');
                    $safeEmail = Str::slug(Str::before($this->user->email, '@'));
                    $file = $request->file('passportImage');
                    $filename = $applicationID . '_' . $safeEmail.'.'. $file->getClientOriginalExtension();

                    $destination = storage_path('app\student_images');
                    if (!is_dir($destination)) {
                        mkdir($destination, 0755, true);
                    }
                    $fullPath = $destination . DIRECTORY_SEPARATOR . $filename;

                    if(file_exists($fullPath)){
                        unlink($fullPath);
                    } 

                    $file->move($destination, $filename);
                    $applicationID =$this->retrieveOrUpdateSessionData('get', 'application_no');
                    $applicant = Applicant::where('id', $applicationID)->first();
                    $applicant->student_image_file_path = $fullPath;
                    if (!$applicant->save()) {
                        return redirect()->back()->withErrors([
                            'error' => 'Failed to save. Please try again.'
                        ]);
                    }

                }
            }
            return redirect()->route('rules.regulations');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

    public function getBiodataSummary(){
        try{
            $applicationID =$this->retrieveOrUpdateSessionData('get', 'application_no');
            $applicant = Applicant::where('id', $applicationID)->first();
            $emergencyContact = EmergencyContact::where('applicant_id', $applicationID)->first();
            $applicantCourse = null;

            $completedSteps = $this->getCompletedSteps($applicantCourse, $applicant);
            return Inertia::render('Application/BioData/BioDataSummary', [
                'applicantData' => $applicant,
                'emergencyContact'=> $emergencyContact,
                'completedSteps' => $completedSteps,
            ]);
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
        
    }

    public function postBiodataSummary(Request $request){
        $validated = $request->validate([  
            'personalDataSummary' => 'nullable|string',
        ]);
        try{
            $applicationID =$this->retrieveOrUpdateSessionData('get', 'application_no');
            $applicant = Applicant::where('id', $applicationID)->first();
            $applicant->application_status = 'submitted';
            if (!$applicant->save()) {
                return redirect()->back()->withErrors([
                    'error' => 'Failed to save. Please try again.'
                ]);
            }
            //return redirect()->route('amount.payable');
            return redirect()->route('application.processing');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    
    }

    public function getCountries(){
        try {
            $start = microtime(true);

            $countriesQuery = $this->generalQueries->countriesQuery();
            $countriesURL = config('app.odata') . "{$countriesQuery}";
            $countriesData = $this->businessCentralAccess->getOdata($countriesURL);
            
            $response = $this->validateAPIResponse($countriesData, url()->previous());
           
            if ($response) {
                return $response;
            }
            $countries = $countriesData['data']['value'];
            $this->testPerformance($start, 'business_central_api', 'Getting data ');

            dd($countries);



            // if($validAccessToken){
            //     // error
            //     if($validAccessToken['statusCode'] == 401){
            //         return redirect()->back()->withErrors([
            //             'error' => $validAccessToken['message']
            //         ]);
            //     }
            //     if($validAccessToken['statusCode'] == 0 ){
            //         return redirect()->back()->withErrors([
            //             'error' => $validAccessToken['message']
            //         ]);
            //     }
            // }

            
          
            // return $countries;
        }catch(Exception $e){
            
            return redirect()->back()->withErrors([
                'error' => $this->validateError($e->getCode(), $e->getMessage()),
            ]);
        }
    }

  

    public function validateError($statusCode, $errorMessage){
        if($statusCode == 0){
            return 'No internet connection. Please check your connection and try again.';
        }else {
            return $errorMessage;
        }
    }

    public function getDOBPage(){
        try{
           

            return Inertia::render('Application/BioData/DOB', [
            ]);
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function postDOB(Request $request){
        $validated = $request->validate([  
            'dob' => 'required|date',
        ]);
        try{
            $applicationID =$this->retrieveOrUpdateSessionData('get', 'application_no');
            $applicant = Applicant::where('id', $applicationID)->first();
            $applicant->dob = $validated['dob'];
            if (!$applicant->save()) {
                return redirect()->back()->withErrors([
                    'error' => 'Failed to save. Please try again.'
                ]);
            }
           return redirect()->route('gender');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

    public function getGenderPage(){
        try{
           

            return Inertia::render('Application/BioData/Gender', [
            ]);
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function postGender(Request $request){
        $validated = $request->validate([  
            'gender' => 'required|string',
        ]);
        try{
            $applicationID =$this->retrieveOrUpdateSessionData('get', 'application_no');
            $applicant = Applicant::where('id', $applicationID)->first();
            $applicant->gender = (int) $validated['gender'];
            if (!$applicant->save()) {
                return redirect()->back()->withErrors([
                    'error' => 'Failed to save. Please try again.'
                ]);
            }
           return redirect()->route('passport');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

    public function getPassportPage(){
        try{
           

            return Inertia::render('Application/BioData/Passport', [
            ]);
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function postPassportID(Request $request){
        $validated = $request->validate([  
            'passportID' => 'required|string',
        ]);
        try{
            $applicationID =$this->retrieveOrUpdateSessionData('get', 'application_no');
            $applicant = Applicant::where('id', $applicationID)->first();
            $applicant->id_passport_No = strtoupper(trim($validated['passportID']));
            if (!$applicant->save()) {
                return redirect()->back()->withErrors([
                    'error' => 'Failed to save. Please try again.'
                ]);
            }
           return redirect()->route('residence');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

}
