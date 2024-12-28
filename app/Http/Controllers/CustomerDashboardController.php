<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Keranjang;
use App\Models\Order;
use App\Models\Order_product;
use App\Models\Product;


class CustomerDashboardController extends Controller
{
    public function index()
    {
        $customer = Auth::guard('customer')->user();

        if (!$customer) {
            return redirect()->route('customer.login')->withErrors('Silakan login terlebih dahulu.');
        }

        $categories = Category::with('products')->get();
        $keranjangCount = Keranjang::where('customer_id', $customer->id)->count();


        $produkRekomendasi = $this->recommendProducts($customer->id);

        $data = [
            'title' => 'Brotherhood',
            'categories' => $categories,
            'customer' => $customer,
            'cartCount' => $keranjangCount,
            'produkRekomendasi' => $produkRekomendasi,
        ];

        return view('layout.customer', $data);
    }

    public function recommendProducts($customerId)
    {
        $orderCategories = Order_product::where('customer_id', $customerId)
            ->select('category_id')
            ->get();
        $transaksi = [];
        foreach ($orderCategories as $order) {
            $categoryIds = array_filter(explode(',', $order->category_id), function($value) { return !empty($value); });
            if (!empty($categoryIds)) {
                $transaksi[] = $categoryIds;
            }
        }
        $frekuensiItem = [];
        foreach ($transaksi as $tran) {
            foreach ($tran as $item) {
                if (!isset($frekuensiItem[$item])) {
                    $frekuensiItem[$item] = 0;
                }
                $frekuensiItem[$item]++;
            }
        }
        $support = 3;
        $itemSering = array_filter($frekuensiItem, function ($count) use ($support) {
            return $count >= $support;
        });
        $polaAsosiasi = [];
        foreach ($transaksi as $tran) {
            $filteredTransaction = array_filter($tran, function ($item) use ($itemSering) {
                return isset($itemSering[$item]);
            });
            $filteredTransaction = array_values($filteredTransaction);

            if (count($filteredTransaction) > 1) {
                $count = count($filteredTransaction);
                for ($i = 0; $i < $count - 1; $i++) {
                    for ($j = $i + 1; $j < $count; $j++) {
                        if (isset($filteredTransaction[$i]) && isset($filteredTransaction[$j])) {
                            $pair = [$filteredTransaction[$i], $filteredTransaction[$j]];
                            sort($pair);
                            $pairKey = implode(',', $pair);
                            if (!isset($polaAsosiasi[$pairKey])) {
                                $polaAsosiasi[$pairKey] = 0;
                            }
                            $polaAsosiasi[$pairKey]++;
                        }
                    }
                }
            }
        }
        $rekomendasiKategori = [];
        foreach ($polaAsosiasi as $pair => $count) {
            if ($count >= $support) {
                $kategoriDariPair = explode(',', $pair);
                foreach ($kategoriDariPair as $categoryId) {
                    if (!in_array($categoryId, $rekomendasiKategori)) {
                        $rekomendasiKategori[] = $categoryId;
                    }
                }
            }
        }
        $rekomendasiProduk = Product::whereIn('category_id', $rekomendasiKategori)->get();
        $produkFrekuensi = [];
        foreach ($rekomendasiProduk as $product) {
            $productFrequency = Order::where('customer_id', $customerId)
                ->where('id_product', $product->id)
                ->count();

            if ($productFrequency > 0) {
                $produkFrekuensi[$product->id] = $productFrequency;
            }
        }
        // dd($produkFrekuensi);
        $produkIds = array_keys($produkFrekuensi);
        $produkRekomendasi = Product::whereIn('id', $produkIds)->get();
        // dd($produkRekomendasi);
        return $produkRekomendasi;

    }

}
