<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index() {
        return view('login');
    }

    public function homePage() {
        return view('home');
    }
}
