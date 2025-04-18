<?php

namespace App\Http\Controllers\API\Resources\Member;

use App\Http\Controllers\Controller;
use App\Models\People\Customers\Customers;
use App\Models\Resources\Company\Company;
use App\Models\Resources\Member\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Midtrans\Transaction;

class MemberC extends Controller
{
    // Menampilkan halaman form pendaftaran
    public function indexBack(){
        $member = Member::all();
        return view('admin.resources.member.index', compact('member'));
    }

    public function invoice(){
        $member    = Member::where('status', Member::STATUS_ACTIVE)->get();
        $company = Company::first();
        return view('admin.resources.member.invoice', compact('member', 'company'));
    }

    public function index()
    {
        return view('front.member.index');
    }

    public function store(Request $request)
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;
    
        $order_id = 'invoice-' . time();
        $transaction_details = [
            'order_id' => $order_id,
            'gross_amount' => 50000,
        ];
    
        $customer_details = [
            'first_name' => $request->name,
            'email' => $request->email,
            'phone' => $request->telepon,
        ];
    
        $item_details = [[
            'id' => 'item-1',
            'price' => 50000,
            'quantity' => 1,
            'name' => 'Pendaftaran Member LaundryKu',
        ]];
    
        $userId = $request->Id; 

        $transaction_data = [
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            'item_details' => $item_details,
            'custom_fields' => [
                'custom_field1' => $userId,
            ],
        ];
    
        try {
            session(['user_id_to_register' => $userId]); 
            $snapToken = Snap::getSnapToken($transaction_data);
            return view('front.member.payment', compact('snapToken'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat transaksi: ' . $e->getMessage());
        }
    }

    public function success()
    {
        $userId = session('user_id_to_register');
        
        if ($userId) {
            $existingMember = Member::where('user_id', $userId)->first();
        
            if (!$existingMember) {
                Member::create([
                    'user_id' => $userId,
                    'dateJoin' => Carbon::now(),
                ]);
                
                $customer = Customers::where('user_id', $userId)->first();
                
                if ($customer) {
                    $customer->saldo += 50000; 
                    $customer->save();
                }
            }
    
            session()->forget('user_id_to_register');
        }
        
        return view('front.member.success');
    }
    
    
}
