<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\HttpHelper;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sales;
use App\Models\Transaction;

class AnalyticController extends Controller
{

    public function index()
    {
        /**
         * total transaksi berapa
         * Total obat
         * TOtal penjualan perbulan
         * Total pembelian perbulan
         * 
         * table transaksi hari ini
         * Table obat expired
         * Table obat low stock
         */
        $totalTransaction = Transaction::count();
        $transactionToday = Sales::select(['name', 'quantity', 'type', 'total_price'])->whereMonth('created_at', now())->get();
        $totalTransactionToday = Transaction::whereDate('created_at', now())->sum('amount');
        $totalTransactionThisMonth = Transaction::whereMonth('created_at', now()->month)->sum('amount');

        $in3month = now()->addMonths(3);
        $totalProduct = Product::count();
        $expiredProducts = Product::select(['name', 'pack_stok', 'total_item'])->whereDate('expired_date', '<=', $in3month)->get();
        $lowStockProducts = Product::select(['name', 'pack_stok', 'total_item'])->where('total_item', '<=', 20)->get();

        $purchaseThisMonth = Purchase::whereMonth('created_at', now()->month)->sum('total_price');
        return HttpHelper::successResponse("Data analytics", [
            "cardStatistic" => [
                'totalTransaction' => $totalTransaction,
                'totalProduct' => $totalProduct,
                'totalTransactionThisMonth' => $totalTransactionThisMonth,
                'totalPurchasesThisMonth' => $purchaseThisMonth,
            ],
            "orderTodayList" => [
                'data' => $transactionToday,
                'total' => $totalTransactionToday,

            ],
            "lowStockProductList" => [
                'data' => $lowStockProducts,
                'total' => count($lowStockProducts),

            ],
            "expiredProductList" => [
                'data' => $expiredProducts,
                'total' => count($expiredProducts),

            ]
        ]);
    }
}
