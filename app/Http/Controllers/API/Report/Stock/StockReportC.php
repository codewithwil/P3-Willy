<?php

namespace App\Http\Controllers\API\Report\Stock;

use App\Http\Controllers\Controller;
use App\Models\Resources\Company\Company;
use App\Models\Transactions\Stock\StockTransac;
use Illuminate\Http\Request;

class StockReportC extends Controller
{
    public function index(){
        $stocks = StockTransac::with(['user', 'commodity'])
                                ->orderBy('created_at', 'desc')
                                ->get();
        return view('admin.report.stockReport.index', compact('stocks'));
    }

    public function invoice(){
        $stocks = StockTransac::with(['user', 'commodity'])
        ->orderBy('created_at', 'desc')
        ->get();
        $company = Company::first();
        return view('admin.report.stockReport.invoice', compact('stocks', 'company'));
    }
}
