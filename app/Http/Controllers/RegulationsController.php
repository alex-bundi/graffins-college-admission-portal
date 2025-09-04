<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Exception;


class RegulationsController extends Controller
{
    public function getPageOne(){
        return Inertia::render('Regulations/PageOne');
    }

    public function getPageTwo(){
        return Inertia::render('Regulations/PageTwo');
    }

    public function getPageThree(){
        return Inertia::render('Regulations/PageThree');
    }

    public function getPageFour(){
        return Inertia::render('Regulations/PageFour');
    }
    public function getPageFive(){
        return Inertia::render('Regulations/PageFive');
    }

    public function getPageSix(){
        return Inertia::render('Regulations/PageSix');
    }

    public function getPageSeven(){
        return Inertia::render('Regulations/PageSeven');
    }

    public function getPageEight(){
        return Inertia::render('Regulations/PageEight');

    }

    public function getDeclaration(){
        return Inertia::render('Regulations/Declaration');

    }

}
