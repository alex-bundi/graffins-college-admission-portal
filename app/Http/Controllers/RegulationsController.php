<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Exception;
use App\Models\EmergencyContact;



class RegulationsController extends Controller
{
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

    public function getPageOne(){
        $applicant = null;
        $applicantCourse = null;

        $completedSteps = $this->getCompletedSteps($applicantCourse, $applicant);
        
        return Inertia::render('Regulations/PageOne', [
            'completedSteps' => $completedSteps,
        ]);
    }

    public function getPageTwo(){
        $applicant = null;
        $applicantCourse = null;

        $completedSteps = $this->getCompletedSteps($applicantCourse, $applicant);
        
        return Inertia::render('Regulations/PageTwo', [
            'completedSteps' => $completedSteps,
        ]);
    }

    public function getPageThree(){
        $applicant = null;
        $applicantCourse = null;

        $completedSteps = $this->getCompletedSteps($applicantCourse, $applicant);
        
        return Inertia::render('Regulations/PageThree', [
            'completedSteps' => $completedSteps,
        ]);
    }

    public function getPageFour(){
        $applicant = null;
        $applicantCourse = null;

        $completedSteps = $this->getCompletedSteps($applicantCourse, $applicant);
        
        return Inertia::render('Regulations/PageFour', [
            'completedSteps' => $completedSteps,
        ]);
    }
    public function getPageFive(){
        $applicant = null;
        $applicantCourse = null;

        $completedSteps = $this->getCompletedSteps($applicantCourse, $applicant);
        
        return Inertia::render('Regulations/PageFive', [
            'completedSteps' => $completedSteps,
        ]);
    }

    public function getPageSix(){
        $applicant = null;
        $applicantCourse = null;

        $completedSteps = $this->getCompletedSteps($applicantCourse, $applicant);
        
        return Inertia::render('Regulations/PageSix', [
            'completedSteps' => $completedSteps,
        ]);
    }

    public function getPageSeven(){
        $applicant = null;
        $applicantCourse = null;

        $completedSteps = $this->getCompletedSteps($applicantCourse, $applicant);
        
        return Inertia::render('Regulations/PageSeven', [
            'completedSteps' => $completedSteps,
        ]);
    }

    public function getPageEight(){
        $applicant = null;
        $applicantCourse = null;

        $completedSteps = $this->getCompletedSteps($applicantCourse, $applicant);
        
        return Inertia::render('Regulations/PageEight', [
            'completedSteps' => $completedSteps,
        ]);

    }

    public function getDeclaration(){
        $applicant = null;
        $applicantCourse = null;

        $completedSteps = $this->getCompletedSteps($applicantCourse, $applicant);
        
        return Inertia::render('Regulations/Declaration', [
            'completedSteps' => $completedSteps,
        ]);

    }

}
