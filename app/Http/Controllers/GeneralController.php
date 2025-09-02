<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class GeneralController extends Controller
{
    //

    public function getHomePage(){
        return Inertia::render('Home');
    }

    public function getWelcomePage(){
        return Inertia::render('Welcome');
    }
}
