<?php

namespace App\Http\Controllers\API\Auth;

use App\{
    Dto\Auth\RegisterDto,
    Dto\Auth\LoginDto,
    Http\Controllers\Controller,
    Services\Auth\AuthService
};


use Illuminate\{
    Http\Request,
    Support\Facades\Auth,   
};
use Illuminate\Support\Facades\Log;

class AuthC extends Controller
{

    public function __construct(protected AuthService $authService){}
    public function index(){
        return view('admin.auth.login');
    }


    public function regis(){
        return view('admin.auth.register');
    }

    public function register(Request $req)
    {
        $dto    = RegisterDto::fromRequest($req);
        $result = $this->authService->register((array)$dto);
    
        if ($result['success']) {
            session()->flash('success', $result['message']);
            return redirect('/login');
        }
    
        session()->flash('error', $result['message']);
        return redirect()->back()->withInput();
    }
    
    public function login(Request $req)
    {
        $loginDto = LoginDto::fromRequest($req);
        $result = $this->authService->login($loginDto);
    
        if ($result['success'] && Auth::attempt([
            'email' => $loginDto->email,
            'password' => $loginDto->password,
        ])) {
            $req->session()->regenerate();
    
            $user = Auth::user();
            if ($user->hasRole('pengguna')) {
                return redirect()->route('order')->with('success', $result['message']);
            }
    
            return redirect()->route('dashboard')->with('success', $result['message']);
        }
    
        return redirect()->route('login')->withErrors(['login' => $result['message']]);
    }
    

    public function logout(Request $req){
        $this->authService->logout();
        $req->session()->invalidate();
        $req->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'You have successfully logged out.');
    }
}
