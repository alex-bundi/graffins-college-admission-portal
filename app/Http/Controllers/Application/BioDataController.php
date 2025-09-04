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

            return redirect()->route('department');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

    }

}
