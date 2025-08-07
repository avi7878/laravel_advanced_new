<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Stripe\Stripe;

class BillingController extends Controller
{
    public function createBillingPortal(){
        $user = auth()->user();
        if(!$user){
            return redirect()->back()->with('error','User Not Found');
        }
        
        $stripeSecretKey = env('STRIPE_SECRET_KEY');
        \Stripe\Stripe::setApiKey($stripeSecretKey);
        
        if($user->stripe_customer_id == ''){
            try{
                $stripe = new \Stripe\StripeClient($stripeSecretKey);
                $customer = $stripe->customers->create([
                  'name' => $user->first_name.' ' .$user->last_name,
                  'email' => $user->email,
                ]);
                $user->stripe_customer_id = $customer->id;
                $user->save();
            }catch(\Exception $e){
                return redirect()->back()->with('error',$e->getMessage());
            }
        }
        
        $customerId = $user->stripe_customer_id;
       
        try{
            $session = \Stripe\BillingPortal\Session::create(['customer' => $customerId,'return_url' => url('/'),]);
            return redirect($session->url);
        }catch(\Exception $e){
            return redirect()->back()->with('error',$e->getMessage());
        }
    }
}