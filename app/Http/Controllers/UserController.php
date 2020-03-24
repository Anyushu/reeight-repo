<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\AddSites;
use App\User;
use App\Settlement;
use Auth;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $add_sites = AddSites::where('user_id', $user_id)->get()->count();

        return view('auth.account', [
            'add_sites' => $add_sites,
            'user' => $user
        ]);
    }

    // アカウント情報表示
    public function account_form()
    {
        $user = Auth::user();
        return view('account.edit')->with([
            'user' => $user
        ]);
    }

    // アカウント情報変更
    public function account_edit(Request $request)
    {
        $all = $request->only(['email','tell']);
        $validator = Validator::make($all, [
            'email' => 'required|unique:users,email,'.$request->user_id.'|max:255|email',
            'tell' => 'required|numeric|digits_between:8,11',
        ]);
        if ($validator->fails()) {
            return redirect('/dashboard/account/edit')
                ->withErrors($validator)
                ->withInput();
        } else {
            $user = Auth::user();
            $user->company = $request->company;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->tell = $request->tell;
            $user->update();
            return redirect()->action('UserController@account_form');
        }
    }

    // 退会処理
    public function cards()
    {
        $user = Auth::user();
        $card = Settlement::where('user_id', $user->id)->get();

        return view('account.cards')->with([
            'user' => $user,
            'card' => $card,
        ]);
    }
}
