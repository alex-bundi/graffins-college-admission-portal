<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Exception;


class ApplicationController extends Controller
{
    //

    public function getDepartmentPage(){
        return Inertia::render('Application/Department');
        
    }
    public function postDepartment(){
        try{

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
        try{

            return redirect()->route('department');
            
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
