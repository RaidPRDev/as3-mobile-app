<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\User;
use DB;
use App\Http\Requests;

class ChangepasswordController extends Controller
{
    //
    public function index()
    {
    	 $users = User::orderBy('id', 'desc')->paginate(10);
		return view('admin.genral.changepassword',compact('users','roles'));    	
    }

    public function store(Request $request)
    {
        $user = Auth::user();
       // echo $user->name;
     //  echo"<pre>"; print_r($user);exit;

       User::where('id', $user->id)
         ->update(['password' => $request->p2]);
        /*    $u = new User();
            $u->email ="abc@yahoo.com";
            $u->save();*/
        // $u =  User::find(5);       // print_r($u);exit;
          //  $u->delete();
            //User::where('email','abc@yahoo.com')->delete();
            DB::table('users')->where('id', '=', 5)->delete();
            //User::destroy(5);

    	return("hello");

    }
}
