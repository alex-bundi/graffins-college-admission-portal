<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Exception;

class BioDataController extends Controller
{
     public function getFullNamePage(){
        return Inertia::render('Application/BioData/FullName');
    }

    public function postFullNamePage(Request $request){
        try{

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
        try{

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
