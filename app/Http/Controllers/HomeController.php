<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AddSites;
use App\User;
use App\Category;
use App\Industry;
use App\Plans;
use Auth;

class HomeController extends Controller
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
        $categories = Category::all();
        $industries = Industry::all();
        $plans = Plans::all();
        $user = Auth::user();
        $user_id = $user->id;
        $add_sites = AddSites::where('user_id', $user_id)->get();
        return view('dashboard', [
            'add_sites' => $add_sites,
            'categories' => $categories,
            'industries' => $industries,
            'plans' => $plans,
        ]);
    }

    public function top()
    {
        return redirect('/dashboard');
    }
}
