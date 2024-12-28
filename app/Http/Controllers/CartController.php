<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Keranjang;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
class CartController extends Controller
{
    public function index()
    {
        $customer = Auth::guard('customer')->user();

        if (!$customer) {
            return redirect()->route('customer.login')->withErrors('Silakan login terlebih dahulu.');
        }

        $categories = Category::with('products')->get();
        $keranjangCount = Keranjang::where('customer_id', $customer->id)->count();
        $keranjangItems = Keranjang::where('customer_id', $customer->id)
            ->with('product')
            ->get();

        $data = [
            'title' => 'Brotherhood',
            'categories' => $categories,
            'customer' => $customer,
            'cartCount' => $keranjangCount,
            'keranjangItems' => $keranjangItems,
        ];

        return view('layout.keranjang', $data);
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|integer',
            'category_id' => 'required|integer',
            'produk_id' => 'required|integer',
        ]);

        try {
            $productPrice = $this->getProductPrice($request->produk_id);

            if (!$productPrice) {
                return response()->json(['error' => 'Produk tidak ditemukan.'], 404);
            }

            $keranjang = new Keranjang();
            $keranjang->customer_id = $request->customer_id;
            $keranjang->category_id = $request->category_id;
            $keranjang->id_product = $request->produk_id;
            $keranjang->total_price = $productPrice;
            $keranjang->status = 'active';
            $keranjang->save();

            return response()->json(['success' => 'Produk berhasil ditambahkan ke keranjang.']);
        } catch (\Exception $e) {
            Log::error('Error adding to cart: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menambahkan ke keranjang.'], 500);
        }
    }
    private function getProductPrice($productId)
    {
        $product = Product::find($productId);
        return $product ? $product->price : null;
    }

    public function destroy($id)
    {
        $customer = Auth::guard('customer')->user();
        $keranjangItem = Keranjang::where('id', $id)->where('customer_id', $customer->id)->first();

        if ($keranjangItem) {
            $keranjangItem->delete();
            return redirect()->route('customer.cart')->with('success', ' berhasil dihapus dari keranjang.');
        }

        return redirect()->route('customer.cart')->with('error', ' tidak ditemukan.');
    }
}
