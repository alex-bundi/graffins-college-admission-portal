<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Exception;
use App\Models\User;
use App\Models\Applicant;



class BioDataController extends Controller
{
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

            return redirect()->route('contacts');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

    public function getContactPage(){
        return Inertia::render('Application/BioData/Contacts');
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
        return Inertia::render('Application/BioData/Nationality');
    }

    public function postNationality(Request $request){
        try{

            return redirect()->route('email.address');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

     public function getEmailAddressPage(){
        return Inertia::render('Application/BioData/EmailAddress');
    }

    public function postEmailAddress(Request $request){
        try{

            return redirect()->route('residence');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

    public function getResidencePage(){
        return Inertia::render('Application/BioData/Residence');
    }

    public function postResidence(Request $request){
        try{

            return redirect()->route('marketing');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

    public function getMarketingPage(){
        return Inertia::render('Application/BioData/Marketing');
    }

    public function postMarketing(Request $request){
        try{

            return redirect()->route('allergies');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

    public function getAllergiesPage(){
        return Inertia::render('Application/BioData/Allergies');
    }

    public function postAllergies(Request $request){
        try{

            return redirect()->route('allergy.description');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

     public function getAllergyDescriptionPage(){
        return Inertia::render('Application/BioData/AllergyDescription');
    }

    public function postAllergyDescription(Request $request){
        try{

            return redirect()->route('emergency.contact');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

    public function getEmergencyContactPage(){
        return Inertia::render('Application/BioData/EmergencyContact');
    }

    public function postEmergencyContact(Request $request){
        try{

            return redirect()->route('upload.id');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

     public function getUploadIDPage(){
        return Inertia::render('Application/BioData/IDPassport');
    }

    public function postUploadID(Request $request){
        try{

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
        try{

            return redirect()->route('page.one');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

    public function getBiodataSummary(){
        return Inertia::render('Application/BioData/BioDataSummary');
        
    }

}
