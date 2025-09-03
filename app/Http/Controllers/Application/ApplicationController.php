<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;


class ApplicationController extends Controller
{
    //

    public function getDepartmentPage(){
        
    }

    public function getModeOfStudyPage(){
        return Inertia::render('Application/ModeOfStudy');
    }
}
