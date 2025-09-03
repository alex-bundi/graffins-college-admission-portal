<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Exception;


class GeneralController extends Controller
{
    //

    public function getHomePage(){
        return Inertia::render('Home');
    }

    public function getWelcomePage(){
        return Inertia::render('Welcome');
    }

    public function getRegistrationStatus(){
        return Inertia::render('RegistrationStatus');
    }

    public function getRegisterPage(){
        return Inertia::render('Auth/Register');
    }

    public function getLoginPage(){
           return Inertia::render('Auth/Login');
    }


    public function getDashboard(){
        return Inertia::render('Dashboard');
    }

    public function postLogin(Request $request){
        try{

            return redirect()->route('dashboard');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function postRegistration(Request $request){
        try{

            return redirect()->route('dashboard');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }
}
