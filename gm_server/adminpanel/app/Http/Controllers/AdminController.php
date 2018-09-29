<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;

class AdminController extends Controller
{
	
    public function index()
	{
		return view('admin.dashboard');
	}
}
