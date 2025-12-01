<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\BusinessCentralSoapController;
use App\Http\Controllers\BusinessCentralAPIController;
use App\Http\Controllers\Application\ApplicationController;
use App\Http\Controllers\Application\BioDataController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\RegulationsController;
use App\Http\Controllers\PaymentController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Artisan;


Route::get('/', [GeneralController::class, 'getHomePage'])->name('home');
Route::get('/welcome', [GeneralController::class, 'getWelcomePage'])->name('welcome');
Route::get('/registration-status', [GeneralController::class, 'getRegistrationStatus'])->name('registration.status');

Route::middleware('guest')->group(function () {
    Route::get('register', [GeneralController::class, 'getRegisterPage'])
        ->name('register');

    Route::post('post-register', [GeneralController::class, 'postRegistration']);

    Route::get('login', [GeneralController::class, 'getLoginPage'])
        ->name('login');

    Route::post('/post-login', [GeneralController::class, 'postLogin']);

 
});


// Test
Route::get('/countries', [BioDataController::class, 'getCountries']);

// API Erros


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [GeneralController::class, 'getDashboard'])->name('dashboard');
    Route::get('/apply-course', [CourseController::class, 'getCourseList'])->name('course.list');

    Route::prefix('application')->group(function () {
        // Route::get('/edit-course/{applicationID}', [ApplicationController::class, 'getEditCourse'])->name('edit.application');
        Route::get('/edit-course/{applicationID}/{courseID}', [ApplicationController::class, 'getEditCourseLine'])->name('edit.course.line');

        Route::get('/edit-application/{applicationID}', [ApplicationController::class, 'getEditApplication'])->name('edit.application');
        
        Route::get('/mode-of-study', [ApplicationController::class, 'getModeOfStudyPage'])->name('mode.of.study');
        Route::post('/post-mode-of-study', [ApplicationController::class, 'postModeOfStudy'])->name('post.mode.of.study');

        Route::get('/department', [ApplicationController::class, 'getDepartmentPage'])->name('department');
        Route::post('/post-department', [ApplicationController::class, 'postDepartment'])->name('post.department');

        Route::get('/pick-course', [ApplicationController::class, 'getPickCoursePage'])->name('pick.course');
        Route::post('/post-pick-course', [ApplicationController::class, 'postPickCourse'])->name('post.pick.course');

        Route::get('/course-levels', [ApplicationController::class, 'getCourseLevels'])->name('course.levels');
        Route::post('/post-course-levels', [ApplicationController::class, 'postCourseLevel'])->name('post.course.levels');


        Route::get('/course-type', [ApplicationController::class, 'getCourseTypePage'])->name('course-type');
        Route::post('/post-course-type', [ApplicationController::class, 'postCourseType'])->name('post.course-type');

         Route::get('/intake', [ApplicationController::class, 'getIntakePage'])->name('intake');
        Route::post('/post-intake', [ApplicationController::class, 'postIntake'])->name('post.intake');

         Route::get('/tutors', [ApplicationController::class, 'getTutorsPage'])->name('tutor');
        Route::post('/post-tutor', [ApplicationController::class, 'postTutor'])->name('post.tutor');

        Route::get('/class-start-date', [ApplicationController::class, 'getClassStartDatePage'])->name('class.start.date');
        Route::post('/post-class-start-date', [ApplicationController::class, 'postClassStartDate'])->name('post.class-start-date');

        Route::get('/class-start-time', [ApplicationController::class, 'getClassStartTimePage'])->name('class.start.time');
        Route::post('/post-class-start-time', [ApplicationController::class, 'postClassStartTime'])->name('post.class.start.time');

        Route::get('/course-summary', [ApplicationController::class, 'getCourseSummmary'])->name('course.summary');
        Route::post('/post-course-summary', [ApplicationController::class, 'postCourseSummmary'])->name('post.course.summary');
        Route::post('/delete-course-line', [ApplicationController::class, 'deleteCourseLine'])->name('delete.course.line');

        Route::post('/post-course-confirmation', [ApplicationController::class, 'confirmCourse'])->name('confirm.course');


        Route::get('/start-bio-data', [ApplicationController::class, 'getStartBioData'])->name('start.bio.data');


        Route::get('/names', [BioDataController::class, 'getNamesPage'])->name('full.name');
        Route::post('/post-names', [BioDataController::class, 'postNames'])->name('post.full.name');

        Route::get('/contacts', [BioDataController::class, 'getContactPage'])->name('contacts');
        Route::post('/post-contacts', [BioDataController::class, 'postContact'])->name('post.contacts');

        Route::get('/nationality', [BioDataController::class, 'getNationalityPage'])->name('nationality');
        Route::post('/post-nationality', [BioDataController::class, 'postNationality'])->name('post.nationality');

        Route::get('/email-address', [BioDataController::class, 'getEmailAddressPage'])->name('email.address');
        Route::post('/post-email-address', [BioDataController::class, 'postEmailAddress'])->name('post.email.address');

        Route::get('/dob', [BioDataController::class, 'getDOBPage'])->name('date.of.birth');
        Route::post('/post-dob', [BioDataController::class, 'postDOB'])->name('post.date.of.birth');

         Route::get('/gender', [BioDataController::class, 'getGenderPage'])->name('gender');
        Route::post('/post-gender', [BioDataController::class, 'postGender'])->name('post.gender');

         Route::get('/passport-id', [BioDataController::class, 'getPassportPage'])->name('passport');
        Route::post('/post-passport-id', [BioDataController::class, 'postPassportID'])->name('post.passport');

        Route::get('/residence', [BioDataController::class, 'getResidencePage'])->name('residence');
        Route::post('/post-residence', [BioDataController::class, 'postResidence'])->name('post.residence');

        Route::get('/marketing', [BioDataController::class, 'getMarketingPage'])->name('marketing');
        Route::post('/post-marketing', [BioDataController::class, 'postMarketing'])->name('post.marketing');

        Route::get('/allergies', [BioDataController::class, 'getAllergiesPage'])->name('allergies');
        Route::post('/post-allergies', [BioDataController::class, 'postAllergies'])->name('post.allergies');

        Route::get('/allergy-description', [BioDataController::class, 'getAllergyDescriptionPage'])->name('allergy.description');
        Route::post('/post-allergy-description', [BioDataController::class, 'postAllergyDescription'])->name('post.allergy.description');

        Route::get('/emergency-contact', [BioDataController::class, 'getEmergencyContactPage'])->name('emergency.contact');
        Route::post('/post-emergency-contact', [BioDataController::class, 'postEmergencyContact'])->name('post.emergency.contact');

        Route::get('/upload-id-passport', [BioDataController::class, 'getUploadIDPage'])->name('upload.id');
        Route::post('/post-upload-id-passport', [BioDataController::class, 'postUploadID'])->name('post.upload.id');
        
        Route::get('/upload-photo', [BioDataController::class, 'getUploadPhotoPage'])->name('upload.photo');
        Route::post('/post-upload-photo', [BioDataController::class, 'postUploadPhoto'])->name('post.upload.photo');

        Route::get('/personal-information-summary', [BioDataController::class, 'getBiodataSummary'])->name('bio.data.summary');
        Route::post('/post-personal-information-summary', [BioDataController::class, 'postBiodataSummary'])->name('post.bio.data.summary');
        
        Route::get('/student-id', [ApplicationController::class, 'getStudentIDPage'])->name('student.id');
        Route::get('/edit-student-id/{applicationID}', [ApplicationController::class, 'editStudentID'])->name('edit.student.id');
        Route::post('/post-student-id', [ApplicationController::class, 'postStudentID'])->name('post.upload.photo');

        Route::get('/admission-letter', [ApplicationController::class, 'getAdmissionLetterPage'])->name('admission.letter');
        Route::get('/download-student-id', [ApplicationController::class, 'getStudentIDPDFPage'])->name('download.student.id');
        Route::get('/post-download-student-id', [ApplicationController::class, 'postStudentIDPDFPage'])->name('post.download.student.id');
        Route::get('/final', [ApplicationController::class, 'getFinalPage'])->name('final');

        // Application Processing
        Route::get('/application-processing', [ApplicationController::class, 'getApplicationProcessingPage'])->name('application.processing');
        Route::get('/processing-bio-data', [BusinessCentralSoapController::class, 'createApplicationInBC']);
        Route::get('/processing-emergency-contacts', [BusinessCentralSoapController::class, 'insertEmergencyContacts']);
        Route::get('/processing-applicant-course', [BusinessCentralSoapController::class, 'insertApplicantCourse']);
        Route::get('/processing-converting-application', [BusinessCentralSoapController::class, 'ConvertApplicationToCustomer']);

        // Admission Letter
        Route::get('/download-admission-letter/{studentNo}', [ApplicationController::class, 'downloadAdmissionLetter'])->name('download.admission.letter');

        Route::get('/add-new-Course', [ApplicationController::class, 'addNewCourse'])->name('add.new.course');
        


    });

    // test
    Route::get('/test-SOAP-API', [BusinessCentralAPIController::class, 'initializeSoapProcess']);


    Route::get('/api-errors', [GeneralController::class, 'getAPIErrorsPage'])->name('api.errors');
   

    Route::prefix('regulations')->group(function () {
        
        Route::get('/rules-regulations', [RegulationsController::class, 'getRulesRegulations'])->name('rules.regulations');
        Route::post('/post-rules-regulations', [RegulationsController::class, 'postRulesRegulations'])->name('post.rules.regulations');

    });

    Route::prefix('payments')->group(function () {
        
        Route::get('/amount-payable', [PaymentController::class, 'getAmountPayable'])->name('amount.payable');
        Route::get('/payment-instructions', [PaymentController::class, 'getPaymentInstructions'])->name('payment.instructions');
        Route::get('/update-payment', [PaymentController::class, 'getUpdatePaymentForm'])->name('update.payment');
        Route::get('/edit-update-payment/{applicationID}', [PaymentController::class, 'editPayment'])->name('edit.payment');
        Route::post('/post-update-payment', [PaymentController::class, 'postPayment'])->name('post.payment');
        

    });

    Route::get('/artisan/migrate', function () {
        Artisan::call('migrate', ['--force' => true]);
        return "✅ Migrations completed.";
    });

    // Clear cache
    Route::get('/artisan/clear-cache', function () {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        return "✅ Cache cleared.";
    });
});





Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
