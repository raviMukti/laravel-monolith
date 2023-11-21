<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmation;
use App\Models\Delivery;
use App\Models\Order;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function findDriver(Request $request)
    {
        // get order with status confirmed
        $orders = Order::where("status", "CONFIRMED")->get();
        // random id for driver
        foreach ($orders as $order)
        {
            // update delivery table with driver id
            $delivery = Delivery::where("order_id", $order->order_id)->first();
            $driver_id = random_int(1, 10);
            $delivery->driver_id = $driver_id;
            $delivery->save();

            // update order table with status picked up
            $order->status = "PICKED UP";
            $order->save();
            // Send email again
            $this->sendConfirmationEmail($order);
        }

        return response()->json(["status"=> "success"]);
    }

    public function getDeliveries()
    {
        $deliveries = Delivery::orderBy("created_at","desc")->get();
        return response()->json($deliveries,200);
    }

    private function sendConfirmationEmail($order)
    {
        $toEmail = $order->email;
        $message = "Pesanan Anda dengan ID #" . $order->order_id . " telah dipick up.";
        // Kirim email
        \Mail::to($toEmail)->send(new OrderConfirmation($message));
    }
}
