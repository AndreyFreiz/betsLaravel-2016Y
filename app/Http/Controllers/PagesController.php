<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PagesController extends Controller
{

    public function index()
    {
        return view('pages.index');
    }

    public function banned()
    {
    	if($this->user->is_admin == 5){
    		return view('pages.banned');
    	}else{
    		return redirect('/banneds');
    	}
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function about()
    {
        return view('pages.about');
    }

}
