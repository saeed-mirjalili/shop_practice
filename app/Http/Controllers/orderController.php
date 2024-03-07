<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class orderController extends Controller
{
    public static function create($request ,$amounts ,$token) {
        $order = Order::create([
            'user_id' => $request->user_id,
            'total_amount' => $amounts['total_amount'],
            'delivery_amount' => $amounts['delivery_amount'],
            'paying_amount' => $amounts['paying_amount'],
            'description' => 'efwefwqefdwqefwefwe',
        ]);
        foreach($request->order_items as $orderItem){
            $product = Product::findOrFail($orderItem['product_id']);
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'price' => $product->price,
                'quantity' => $orderItem['quantity'],
                'subtotal' => ($product->price * $orderItem['quantity'])
            ]);
        }

        Transaction::create([
            'user_id' => $request->user_id,
            'order_id' => $order->id,
            'amount' => $amounts['paying_amount'],
            'token' => $token,
            'request_form' => $request->request_form
        ]);
    }
    public static function update($token, $transId) {
        DB::beginTransaction();
        $transaction = Transaction::where('token' , $token)->firstOrFail();
        $transaction->update([
            'status' => 1,
            'trans_id' => $transId
        ]);

        $order = Order::findOrFail($transaction->order_id);
        $order->update([
            'status' => 1,
            'payment_status' => 1
        ]);

        foreach(OrderItem::where('order_id', $order->id)->get() as $item){
            $product = Product::find($item->product_id);
            $product->update([
                'quantity' => ($product->quantity - $item->quantity)
            ]);
        }
        DB::commit();
    }
}
