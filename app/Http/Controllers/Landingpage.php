<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Keranjang;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Order_product;
use App\Models\Product;

class Landingpage extends Controller
{
    public function index()
    {
        $categories = Category::with('products')->get();
        $fpgrowthData = $this->fpgrowthV2();
        $fpgrowthData = json_decode($fpgrowthData->getContent(), true);
        $data = [
            'title' => 'Brotherhood',
            'categories' => $categories,
            'fpgrowthData' => $fpgrowthData,
        ];
        // dd($fpgrowthData);
        return view('layout.index', $data);
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
        $supportThreshold = 1;
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



}
