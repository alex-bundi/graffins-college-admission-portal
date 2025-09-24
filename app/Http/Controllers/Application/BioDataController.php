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



class BioDataController extends Controller
{
      use OdataTrait;
    use GeneralTrait;
    protected $generalQueries;

    public function __construct()
    {
        $this->generalQueries = new GeneralQueries();
    }

     public function getNamesPage(){
        try{
            $email = trim(session('user_data')['email']);
            $user = User::where('email', $email)->first();
            return Inertia::render('Application/BioData/FullName', [
                'user' => $user,
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
            
            $applicationID =session('user_data')['application_no'];
            $applicant = Applicant::where('id', $applicationID)->first();
            $applicant->first_name = $validated['firstName'];
            $applicant->second_name = $validated['secondName'];
            $applicant->last_name = $validated['lastName'];
            if (!$applicant->save()) {
                return redirect()->back()->withErrors([
                    'error' => 'Failed to save. Please try again.'
                ]);

            }

            $email = trim(session('user_data')['email']);
            $user = User::where('email', $email)->first();
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
            $applicationID =session('user_data')['application_no'];
            $applicant = Applicant::where('id', $applicationID)->first();
            return Inertia::render('Application/BioData/Contacts', [
                'applicant' => $applicant,
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
            $applicationID =session('user_data')['application_no'];
            $applicant = Applicant::where('id', $applicationID)->first();
            $applicant->phone_no = $validated['phoneNo'];
            if (!$applicant->save()) {
                return redirect()->back()->withErrors([
                    'error' => 'Failed to save. Please try again.'
                ]);
            }

            return redirect()->route('nationality');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

    public function getNationalityPage(){
        try{
            $applicationID =session('user_data')['application_no'];
            $applicant = Applicant::where('id', $applicationID)->first();
            
            $countriesQuery = $this->generalQueries->countriesQuery();
            $countriesURL = config('app.odata') . "{$countriesQuery}";
            $countriesData = $this->getOdata($countriesURL);
            $countries = $countriesData['value'];
            
            return Inertia::render('Application/BioData/Nationality', [
                'countries' => $countries,
                'applicant' => $applicant,
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
        ]);
        try{
             if($validated['country'] != null){
                $countryCodePtrn = '/^([^.]+)\.\./';
                $countryDescriPtrn = '/\.\.(.+)$/';
                if (preg_match($countryCodePtrn, $validated['country'], $matches)) {
                     $countryCode = trim($matches[1]);
                }

                if (preg_match($countryDescriPtrn, $validated['country'] , $matches)) {
                    $countryDescription = $matches[1];
                }

                 $applicationID =session('user_data')['application_no'];
                $applicant = Applicant::where('id', $applicationID)->first();
                $applicant->nationality = $countryCode;
                $applicant->country_name = $countryDescription;
                if (!$applicant->save()) {
                    return redirect()->back()->withErrors([
                        'error' => 'Failed to save. Please try again.'
                    ]);
                }
                
            }
            return redirect()->route('email.address');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

     public function getEmailAddressPage(){
        try{
            $email = trim(session('user_data')['email']);
            $user = User::where('email', $email)->first();
            return Inertia::render('Application/BioData/EmailAddress', [
                'user' => $user,
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
            $applicationID =session('user_data')['application_no'];
            $applicant = Applicant::where('id', $applicationID)->first();
            $applicant->email = trim($validated['email']);
            if (!$applicant->save()) {
                return redirect()->back()->withErrors([
                    'error' => 'Failed to save. Please try again.'
                ]);
            }

            $email = trim(session('user_data')['email']);
            $user = User::where('email', $email)->first();
            $user->email = trim($validated['email']);
            if (!$user->save()) {
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

    public function getResidencePage(){
        try{
            $applicationID =session('user_data')['application_no'];
            $applicant = Applicant::where('id', $applicationID)->first();

            $residenceQuery = $this->generalQueries->residenceQuery();
            $residenceURL = config('app.odata') . "{$residenceQuery}?" . '$filter=' . rawurlencode("Type eq 'Location'");
            $residenceData = $this->getOdata($residenceURL);
            $residence = $residenceData['value'];
            // dd($residence);
            
            return Inertia::render('Application/BioData/Residence', [
                'residences' => $residence,
                'applicant' => $applicant,
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
        ]);
        try{
            if($validated['residence'] != null){
                $residenceCodePtrn = '/^([^.]+)\.\./';
                $residenceDescriPtrn = '/\.\.(.+)$/';
                if (preg_match($residenceCodePtrn, $validated['residence'], $matches)) {
                     $residenceCode = trim($matches[1]);
                }

                if (preg_match($residenceDescriPtrn, $validated['residence'] , $matches)) {
                    $residenceDescription = $matches[1];
                }

                 $applicationID =session('user_data')['application_no'];
                $applicant = Applicant::where('id', $applicationID)->first();
                $applicant->residence = $residenceCode;
                $applicant->residence_description = $residenceDescription;
                if (!$applicant->save()) {
                    return redirect()->back()->withErrors([
                        'error' => 'Failed to save. Please try again.'
                    ]);
                }
                
            }
            return redirect()->route('marketing');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

    public function getMarketingPage(){
         try{
             $applicationID =session('user_data')['application_no'];
            $applicant = Applicant::where('id', $applicationID)->first();

            $marketingQuery = $this->generalQueries->marketingQuery();
            $marketingURL = config('app.odata') . "{$marketingQuery}";
            $marketingData = $this->getOdata($marketingURL);
            $marketing = $marketingData['value'];
            return Inertia::render('Application/BioData/Marketing', [
                'marketingAreas' => $marketing,
                'applicant' => $applicant,
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
        ]);
        try{
            if($validated['aboutUs'] != null){
                $aboutUsCodePtrn = '/^([^.]+)\.\./';
                $aboutUsDescriPtrn = '/\.\.(.+)$/';
                if (preg_match($aboutUsCodePtrn, $validated['aboutUs'], $matches)) {
                     $aboutUsCode = trim($matches[1]);
                }

                if (preg_match($aboutUsDescriPtrn, $validated['aboutUs'] , $matches)) {
                    $aboutUsDescription = $matches[1];
                }

                 $applicationID =session('user_data')['application_no'];
                $applicant = Applicant::where('id', $applicationID)->first();
                $applicant->marketing = $aboutUsCode;
                $applicant->marketing_description = $aboutUsDescription;
                if (!$applicant->save()) {
                    return redirect()->back()->withErrors([
                        'error' => 'Failed to save. Please try again.'
                    ]);
                }
                
            }

            return redirect()->route('allergies');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

    public function getAllergiesPage(){
        try{
             $applicationID =session('user_data')['application_no'];
            $applicant = Applicant::where('id', $applicationID)->first();
            return Inertia::render('Application/BioData/Allergies', [
                'applicant' => $applicant,
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
            $applicationID =session('user_data')['application_no'];
            $applicant = Applicant::where('id', $applicationID)->first();
            $applicant->allergies = $validated['allergy'] == 'yes' ? 1 : 0;
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
             $applicationID =session('user_data')['application_no'];
            $applicant = Applicant::where('id', $applicationID)->first();
            return Inertia::render('Application/BioData/AllergyDescription', [
                'applicant' => $applicant,
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
            $applicationID =session('user_data')['application_no'];
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
             $applicationID =session('user_data')['application_no'];
            $emergencyContact = EmergencyContact::where('applicant_id', $applicationID)->first();
            return Inertia::render('Application/BioData/EmergencyContact', [
                'emergencyContact' => $emergencyContact,
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
            }
           
            $applicationID =session('user_data')['application_no'];
           
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

            return Inertia::render('Application/BioData/IDPassport');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
        
    }

    public function postUploadID(Request $request){
         $validated = $request->validate([
            
            'passport_id_file' => 'required|mimes:pdf',

        ]);
        try{
            if($request->hasFile('passport_id_file')){
                if ($request->file('passport_id_file')->isValid()) {
                 
                    $applicationID =session('user_data')['application_no'];
                    $safeEmail = Str::slug(Str::before(session('user_data')['email'], '@'));
                    $file = $request->file('passport_id_file');
                    $filename = $applicationID . '_' . $safeEmail.'.'. $file->getClientOriginalExtension();

                    $destination = storage_path('app\passport_files');
                    if (!is_dir($destination)) {
                        mkdir($destination, 0755, true);
                    }
                    $file->move($destination, $filename);
                    $fullPath = $destination . DIRECTORY_SEPARATOR . $filename;
                    

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
        return Inertia::render('Application/BioData/UploadPassportPhoto');
    }

    public function postUploadPhoto(Request $request){
         $validated = $request->validate([  
            'passportImage' => 'required|image|mimes:jpeg,png',
        ]);
        try{
            if($request->hasFile('passportImage')){
                if ($request->file('passportImage')->isValid()) {
                 
                    $applicationID =session('user_data')['application_no'];
                    $safeEmail = Str::slug(Str::before(session('user_data')['email'], '@'));
                    $file = $request->file('passportImage');
                    $filename = $applicationID . '_' . $safeEmail.'.'. $file->getClientOriginalExtension();

                    $destination = storage_path('app\student_images');
                    if (!is_dir($destination)) {
                        mkdir($destination, 0755, true);
                    }
                    $file->move($destination, $filename);
                    $fullPath = $destination . DIRECTORY_SEPARATOR . $filename;
                    

                }
            }
            return redirect()->route('page.one');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

    public function getBiodataSummary(){
        try{
            $applicationID =session('user_data')['application_no'];
            $applicant = Applicant::where('id', $applicationID)->first();
            $emergencyContact = EmergencyContact::where('applicant_id', $applicationID)->first();
            return Inertia::render('Application/BioData/BioDataSummary', [
                'applicantData' => $applicant,
                'emergencyContact'=> $emergencyContact
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
            $applicationID =session('user_data')['application_no'];
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

}
