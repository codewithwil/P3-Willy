<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Resources\Building\Room;
use App\Models\Transactions\Commodity\Commodity;
use App\Models\Transactions\Stock\StockTransac;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardC extends Controller
{
    public function index(){
        $users         = User::count();
        return view('admin.dashboard.index', compact('users'));
    }
}
