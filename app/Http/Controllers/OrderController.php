<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmation;
use App\Models\Delivery;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function createOrder(Request $request)
    {
        $params = $request->all();

        $data = [
            "email" => $params["email"],
            "food_name" => $params["food_name"],
            "quantity"=> $params["quantity"],
            "status"=> "PENDING",
        ];

        $order = Order::create($data);

        return response()->json($order->order_id, 201);
    }

    public function getAllOrders()
    {
        $orders = Order::orderBy("created_at","desc")->get();
        return response()->json($orders,200);
    }

    public function confirmOrder(int $id)
    {
        $order = Order::where("order_id", $id)->first();
        if ($order)
        {
            $order->status = "CONFIRMED";
            $order->save();
            $this->sendConfirmationEmail($order);
            Delivery::create(["order_id" => $order->order_id, "driver_id" => null]);
            return response()->json($order->order_id, 200);
        }
        else
        {
            return response()->json(["message" => "Order not found"], 404);
        }
    }

    private function sendConfirmationEmail($order)
    {
        $toEmail = $order->email;
        $message = "Pesanan Anda dengan ID #" . $order->order_id . " telah dikonfirmasi.";
        // Kirim email
        \Mail::to($toEmail)->send(new OrderConfirmation($message));
    }

}
