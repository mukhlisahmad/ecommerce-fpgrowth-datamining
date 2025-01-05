<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Keranjang;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
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
        if ($keranjangCount > 0) {
            $fpgrowthData = $this->fpgrowthV2();
            $fpgrowthData = json_decode($fpgrowthData->getContent(), true);
            $data['fpgrowthData'] = $fpgrowthData;
        }
        return view('layout.keranjang', $data);
    }

    public function fpgrowthV2()
    {
        $transactions = DB::table('order_product')->get();
        $data = [];
        foreach ($transactions as $transaction) {
            $decoded = json_decode($transaction->product_id);
            $data[] = $decoded ?: [];
        }
        $frequency = [];
        foreach ($data as $productIds) {
            foreach ($productIds as $productId) {
                if (!isset($frequency[$productId])) {
                    $frequency[$productId] = 0;
                }
                $frequency[$productId]++;
            }
        }
        arsort($frequency);
        $orderedItems = array_keys($frequency);
        $transformedData = [];
        foreach ($data as $productIds) {
            $filtered = array_intersect($orderedItems, $productIds);
            usort($filtered, function ($a, $b) use ($orderedItems) {
                return array_search($a, $orderedItems) - array_search($b, $orderedItems);
            });

            $transformedData[] = $filtered;
        }
        $supportThreshold = 2;
        $frequentPatterns = $this->getFrequentPatterns($transformedData, $supportThreshold);
        $associationRules = $this->generateAssociationRules($frequentPatterns);
        $fpTree = $this->buildFPTree($transformedData);
        return response()->json([
            'frequency' => $frequency,
            'fp_tree' => $fpTree,
            'ordered_items' => $orderedItems,
            'transformed_data' => $transformedData,
            'frequent_patterns' => $frequentPatterns,
            'association_rules' => $associationRules
        ], 200, [], JSON_PRETTY_PRINT);

    }

    private function buildFPTree($transactions)
    {
        $tree = [];
        foreach ($transactions as $transaction) {
            $current = &$tree;
            foreach ($transaction as $productId) {
                if (!isset($current[$productId])) {
                    $current[$productId] = ['_count' => 0];
                }
                $current[$productId]['_count']++;
                $current = &$current[$productId];
            }
        }
        return $tree;
    }
    private function getFrequentPatterns($transactions, $supportThreshold)
    {
        $itemsets = [];
        foreach ($transactions as $transaction) {
            $n = count($transaction);
            for ($i = 0; $i < $n; $i++) {
                for ($j = $i + 1; $j < $n; $j++) {
                    $itemset = [$transaction[$i], $transaction[$j]];
                    sort($itemset);
                    $itemsets[] = implode(",", $itemset);
                }
            }
        }
        $frequentPatterns = [];
        foreach ($itemsets as $itemset) {
            if (!isset($frequentPatterns[$itemset])) {
                $frequentPatterns[$itemset] = 0;
            }
            $frequentPatterns[$itemset]++;
        }
        foreach ($frequentPatterns as $itemset => $count) {
            if ($count < $supportThreshold) {
                unset($frequentPatterns[$itemset]);
            }
        }
        return $frequentPatterns;
    }
    private function generateAssociationRules($frequentPatterns)
    {
        $associationRules = [];

        foreach ($frequentPatterns as $itemset => $support) {
            $items = explode(",", $itemset);
            $antecedents = $items[0];
            $consequents = $items[1];
            $confidence = $this->calculateConfidence($antecedents, $consequents, $support);
            $lift = $this->calculateLift($antecedents, $consequents, $support);

            if ($confidence >= 0.5) {
                $associationRules[] = [
                    'antecedents' => $antecedents,
                    'consequents' => $consequents,
                    'confidence' => $confidence,
                    'lift' => $lift
                ];
            }
        }

        return $associationRules;
    }
    private function calculateConfidence($antecedent, $consequent, $support)
    {
        $antecedentSupport = $this->getSupport($antecedent);
        $consequentSupport = $this->getSupport($consequent);
        return $support / $antecedentSupport;
    }

    private function calculateLift($antecedent, $consequent, $support)
    {
        $antecedentSupport = $this->getSupport($antecedent);
        $consequentSupport = $this->getSupport($consequent);
        return $support / ($antecedentSupport * $consequentSupport);
    }

    private function getSupport($item)
    {
        $transactions = DB::table('order_product')->get();
        $count = 0;
        foreach ($transactions as $transaction) {
            $decoded = json_decode($transaction->product_id);
            if (in_array($item, $decoded)) {
                $count++;
            }
        }
        return $count;
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
