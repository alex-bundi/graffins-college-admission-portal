<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Exception;


class PaymentController extends Controller
{
    public function getAmountPayable(){
        return Inertia::render('Payments/AmountPayable');

    }

    public function getPaymentInstructions(){
        return Inertia::render('Payments/PaymentInstructions');

    }

    public function getUpdatePaymentForm(){
        return Inertia::render('Payments/UpdatePayment');

    }

    public function postPayment(Request $request){
        try{

            return redirect()->route('student.id');
            
        }catch(Exception $e){
            return redirect()->back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }
}
