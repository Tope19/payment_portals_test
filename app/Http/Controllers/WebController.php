<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Paystack;
use Rave;
use Illuminate\Support\Facades\Redirect;

class WebController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function placeOrder(Request $request){

        // request()->validate([
        //     'type' => 'required|string|max:100',


        // ]);
        //     //Available alpha caracters
        // $type = $request->input('type');
        // if ($type == 'Paystack') {
        //     try{
        //         return Paystack::getAuthorizationUrl()->redirectNow();
        //     }catch (\Exception $e){
        //         return $e->getMessage();
        //     }


        // }

        // if ($type == 'Flutterwave'){
        //     try{
        //         return  Rave::initialize(route('callback'));
        //     }catch(\Exception $e) {
        //         return $e->getMessage();
        //     }
        // }

        // if ($type == 'BemaPay'){
            try{
                $api_url = 'https://dashboard.teflonhub.com/v1/charges/initiate';

                $data = [
                    // 'public_key' => env('BEMA_PUBLIC_KEY',''),
                    'public_key' => 'bspk_test_549fadfad9',
                    "uuid" =>"KAS38F4B634DA58963B7545",
                    'charge_type' => 'card',
                    "card_number"=>$request->input('card_number'),
                    "expiry_month" => $request->input('expiry_month'),
                    "expiry_year"=> $request->input('expiry_year'),
                    "cvv"=> $request->input('cvv'),
                    "suggested_auth"=> "PIN",
                    "pin"=>"0000",
                    'amount' => '5000',
                    'currency' => 'NGN',
                    'medium' => 'web',
                    'transaction_reference' => '093883874',
                    'email' => 'topeolotu75@gmail.com',
                    'redirect_url' => 'https://bemaswitch-beta-prod.herokuapp.com/v1/charges/validate_redirect',
                ];

                $headers = [
                    'Connection'=> 'keep-alive',
                    'Content-Type'=> 'application/json',
                    'Accept'=> 'application/json',
                    // 'Authorization'=> 'Bearer '.env('BEMA_SECRET_KEY'),
                ];

                $client = new \GuzzleHttp\Client();
                $response = $client->request('POST', $api_url, [
                    'headers' => $headers,
                    'json' => $data
                ]);

                $responseBody = json_decode($response->getBody()->getContents(), true);
                echo $responseBody['status'];
                // dd($responseBody);

                // dd($responseBody);
            }catch(\Exception $e) {
                return $e->getMessage();
            }

        // }
    }


    public function handleGatewayCallback()
    {
        $paymentDetails = Paystack::getPaymentData();
        dd($paymentDetails);
        // if($status == "success"){
        //     dd($status);
        // }

    }

      public function callback() {
        // This verifies the transaction and takes the parameter of the transaction reference
        $paymentDetails = Rave::verifyTransaction(request()->txref);


        $chargeResponsecode = $paymentDetails['data']['status'];
        $chargeAmount = $paymentDetails['data']['amount'];
        $chargeCurrency = $paymentDetails['data']['currency'];


        $amount = $paymentDetails['data']['amount'];
        $currency = $paymentDetails['data']['currency'];

        if (($chargeResponsecode == "00" || $chargeResponsecode == "0") && ($chargeAmount == $amount)  && ($chargeCurrency == $currency)) {

            return redirect('/success');

        } else {

            return redirect('/failed');
        }
      }


}
