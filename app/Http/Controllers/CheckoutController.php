<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keranjang;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class CheckoutController extends Controller
{
    public function index()
    {
        $customer = Auth::guard('customer')->user();
        $keranjangCount = Keranjang::where('customer_id', $customer->id)->count();
        $keranjangItems = Keranjang::where('customer_id', $customer->id)->with('product')->get();
        $data = [
            'title' => 'Brotherhood',
            'customer' => $customer,
            'cartCount' => $keranjangCount,
            'keranjangItems'=>$keranjangItems
        ];

        return view('layout.checkout', $data);
    }

    public function processCheckout(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        $keranjangItems = Keranjang::where('customer_id', $customer->id)->get();

        foreach ($keranjangItems as $item) {
            Order::create([
                'customer_id' => $customer->id,
                'id_product' => $item->id_product,
                'category_id' => $item->category_id,
                'total_price' => $item->total_price,
                'status' => 'pending',
            ]);
        }
        Keranjang::where('customer_id', $customer->id)->delete();

        return redirect()->route('customer.orders')->with('success', 'Checkout berhasil, pesanan Anda sedang diproses.');
    }
}
