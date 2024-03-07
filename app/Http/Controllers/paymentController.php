<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class paymentController extends mainController
{
    public function send(Request $request) {

        $validate = Validator::make($request->all(), [
            'user_id' => 'required',
            'order_items' => 'required',
            'order_items.*.product_id' => 'required',
            'order_items.*.quantity' => 'required|integer',
            'request_form' => 'required'
        ]);

        if ($validate->fails()) {
            return $this->Response('Error', $validate->messages(), null,500);
        }
        $total_amount = 0;
        $delivery_amount = 0;
        foreach($request->order_items as $orderItem){
            $product = Product::findOrFail($orderItem['product_id']);
            if ($product->quantity < $orderItem['quantity']) {
                return $this->Response('Error', 'Not available', null, 422);
            }
            $total_amount += $product->price * $orderItem['quantity'];
            $delivery_amount += $product->delivery_amount;
        }

        $paying_amount = $total_amount + $delivery_amount;
        // dd($total_amount , $delivery_amount , $paying_amount);

        $amounts = [
            'total_amount' => $total_amount,
            'delivery_amount' => $delivery_amount,
            'paying_amount' => $paying_amount
        ];

        $api = env('YOUR_API_KEY');
        $amount = $paying_amount;
        $mobile = "شماره موبایل";
        $factorNumber = "شماره فاکتور";
        $description = "توضیحات";
        $redirect = env('YOUR_CALLBACK_URL');
        $result = $this->sendRequest($api, $amount, $redirect, $mobile, $factorNumber, $description);
        $result = json_decode($result);
        // dd($result);
        if($result->status) {
            orderController::create($request ,$amounts ,$result->token);
            $go = "https://pay.ir/pg/$result->token";
            return $this->Response('success','redirect for pay' ,$go, 200);
        } else {
            return $this->Response('Error',$result->errorMessage ,null, 500);
        }
    }

    public function verify(Request $request) {
        $api = env('YOUR_API_KEY');
        $token = $request->token;
        $result = json_decode($this->verifyRequest($api,$token));
        // return response()->json($result);
        if(isset($result->status)){
            if($result->status == 1){
                orderController::update($token, $result->transId);
                return $this->Response('success', 'تراکنش با موفقیت انجام شد', null, 200);
            } else {
                return $this->Response('Erroe', 'تراکنش با خطا مواجه شد', null, 422);
            }
        } else {
            if($_GET['status'] == 0){
                return $this->Response('Erroe', 'تراکنش با خطا مواجه شد', null, 422);
            }
        }
    }


    public function sendRequest($api, $amount, $redirect, $mobile = null, $factorNumber = null, $description = null) {
        return $this->curl_post('https://pay.ir/pg/send', [
            'api'          => $api,
            'amount'       => $amount,
            'redirect'     => $redirect,
            'mobile'       => $mobile,
            'factorNumber' => $factorNumber,
            'description'  => $description,
        ]);
    }

    public function verifyRequest($api, $token) {
        return $this->curl_post('https://pay.ir/pg/verify', [
            'api' 	=> $api,
            'token' => $token,
        ]);
    }

    public function curl_post($url, $params) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);
        $res = curl_exec($ch);
        curl_close($ch);
    
        return $res;
    }
}
