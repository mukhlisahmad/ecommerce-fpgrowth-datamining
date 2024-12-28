<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Keranjang;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $customer = Auth::guard('customer')->user();
        $orders = Order::where('customer_id', $customer->id)
            ->with('product')
            ->get();

        $keranjangItems = Keranjang::where('customer_id', $customer->id)
            ->with('product')
            ->get();

        $data = [
            'title' => 'Brotherhood',
            'customer' => $customer,
            'keranjangItems' => $keranjangItems,
            'orders' => $orders
        ];

        return view('layout.order', $data);
    }
}
