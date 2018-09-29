<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;

class DonationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.donations.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.donations.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return "Hello World";
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('admin.donations.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function ajax_get_donations()
    {
        $donations = DB::table('donations')->get();
        
        $res['data'] = array();
        
        $i = 1;
        foreach($donations as $donation)
        {
            $data['sr_no'] = $i;
            $data['name'] = $donation->name;
            $data['api'] = $donation->api;
            $actions = '<a class="btn btn-xs btn-warning" href="'.route('admin.donations.edit', $donation->id).'"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
                        
            //if( $logged_in_user->id != $user['id'])
            //{
                $actions .= '<form action="'.route('admin.donations.destroy', $donation->id).'" method="POST" style="display: inline;" onsubmit="if(confirm(\'Delete? Are you sure?\')) { return true } else {return false };">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="'.csrf_token().'">
                                <button type="submit" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                            </form>';
            //}   
                                
            $data['actions'] = $actions;
             
            $res['data'][] = $data; 
            $i++;
        }
        
        return json_encode($res);
    }
}
