<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Exception;


class CourseController extends Controller
{
    public function getCourseList(){
        return Inertia::render('CourseList');
    }
}
