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

            return redirect()->route('department');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }
}
