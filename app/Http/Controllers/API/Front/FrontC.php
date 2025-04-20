<?php

namespace App\Http\Controllers\API\Front;

use App\Http\Controllers\Controller;
use App\Models\Resources\Branch\Branch;
use App\Models\Resources\Promo\Promo;
use App\Models\Resources\Service\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontC extends Controller
{
    public function index(){
        $branch = Branch::get();

            $service = Service::where('status', Service::STATUS_ACTIVE)
                ->with('branch')
                ->get();
        return view('front.page', compact("branch",'service'));
    }

    public function getBranch(Request $req)
    {
        $services = Service::where('branch_id', $req->branch_id)->get();
        
        $promoCabang = Promo::where('branch_id', $req->branch_id)
            ->where('target_audience', Promo::TARGET_BRANCH)
            ->where('status', Promo::STATUS_ACTIVE)
            ->whereDate('startDate', '<=', now())
            ->whereDate('endDate', '>=', now())
            ->get();
    
        $promoMember = [];
        if (auth()->check() && auth()->user()->member) {
            $promoMember = Promo::where('target_audience', Promo::TARGET_MEMBER)
                ->where('status', Promo::STATUS_ACTIVE)
                ->whereDate('startDate', '<=', now())
                ->whereDate('endDate', '>=', now())
                ->get();
        }
    
        return response()->json([
            'services' => $services,
            'promo' => [
                'cabang' => $promoCabang,
                'member' => $promoMember
            ]
        ]);
    }
    
}
