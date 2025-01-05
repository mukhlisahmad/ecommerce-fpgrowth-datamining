<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keranjang;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Order_product;

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

        $productIds = [];
        foreach ($keranjangItems as $item) {
            $productIds[] = $item->id_product;
        }
        $orderProduct = Order_product::where('customer_id', $customer->id)->first();

        if ($orderProduct) {
            $existingProductIds = json_decode($orderProduct->product_id, true);
            $mergedProductIds = array_merge($existingProductIds, $productIds);
            $orderProduct->product_id = json_encode(array_values(array_unique($mergedProductIds)));
            $orderProduct->updated_at = now();
            $orderProduct->save();
        } else {
            Order_product::create([
                'customer_id' => $customer->id,
                'product_id' => json_encode(array_values(array_unique($productIds))),
                'updated_at' => now(),
            ]);
        }

        foreach ($keranjangItems as $item) {
            Order::create([
                'customer_id' => $customer->id,
                'id_product' => $item->id_product,
                'category_id' => $item->category_id,
                'total_price' => $item->total_price,
                'status' => 'completed',
            ]);
        }
        Keranjang::where('customer_id', $customer->id)->delete();

        return redirect()->route('customer.orders')->with('success', 'Checkout berhasil, pesanan Anda sedang diproses.');
    }

}
