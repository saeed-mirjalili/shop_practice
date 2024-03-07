<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;

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
}
