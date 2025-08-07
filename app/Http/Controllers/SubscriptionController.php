<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Stripe\Stripe;
use App\Models\Subscription;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Log;


class SubscriptionController extends Controller
{
    public function planSelect(Request $request){
        
        $user = auth()->user();
        $planId = $request->plan_id;
        $planData = Plan::where('id',$planId)->first();
        $planPrice = $planData->amount;
        $planDataPriceId = $planData->stripe_price_id;

        $stripeSecretKey = env('STRIPE_SECRET_KEY');
          \Stripe\Stripe::setApiKey($stripeSecretKey);

        if($user){
            $stripe = new \Stripe\StripeClient($stripeSecretKey);
            $customer = $stripe->customers->create([
              'name' => $user->first_name.' ' .$user->last_name,
              'email' => $user->email,
            ]);
            
            $user->stripe_customer_id = $customer->id;
            $user->save();
        
            $checkoutSession = $stripe->checkout->sessions->create([
                'customer' => $customer->id,
                'payment_method_types' => ['card'],
                'line_items' => [
                        [
                            'price' => $planDataPriceId,
                            'quantity'=>1,
                        ],
                    ],
                'mode' => 'subscription',
                'success_url' => route('checkout/success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout/cancel'),
            ]);
            
         return redirect($checkoutSession->url);
        }
        
      return redirect()->route('login');
    }
    
    
    public function checkoutSuccess(Request $request){
       $sessionId = $request->get('session_id'); 
        
       $stripeSecretKey = env('STRIPE_SECRET_KEY');
       \Stripe\Stripe::setApiKey($stripeSecretKey);
       
       try{
           $session = \Stripe\Checkout\Session::retrieve($sessionId);
           $subscriptionId = $session->subscription;
           $user = auth()->user();
           
           $subscriptionModel = new Subscription();
           
           $subscriptionModel->user_id = $user->id;
           $subscriptionModel->stripe_subscription_id = $subscriptionId;
           $subscriptionModel->status = $session->status;
           $subscriptionModel->save();
           
           return view('checkout/success');
          
       }catch(\Exception $e){
           return redirect()->route('checkout/cancel')->with('error','Error Completing Payment');
       }
    }
    
    public function checkoutCancel(Request $request){
        return view('checkout/cancel');
    }
    
    
   public function handleStripeWebhook(Request $request)
    {
        \Log::info('Stripe Webhook Received:', $request->all());
    
         return response()->json(['status' => 'received']);
    }
}