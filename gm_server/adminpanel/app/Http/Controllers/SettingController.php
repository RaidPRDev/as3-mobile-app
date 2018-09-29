<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Setting;

class SettingController extends Controller
{
    //List all Setting Options
    public function index()
    {
    	return view('admin.settings.index');
    }

    public function edit($id)
    {
        $setting = Setting::findorfail($id);
        return view('admin.settings.edit', compact('setting'));
    }

    public function update(Request $request, $id)
    {
        if($id == 2)
        {
            $this->validate($request, [
                'value' => 'required'
            ]);
        }
        else if(in_array($id, [3,4,8]))
        {
            $this->validate($request, [
                'value' => 'required|integer|min:0|max:100'
            ]);
        }
        else
        {
            $this->validate($request, [
                'value' => 'required|integer|min:0'
            ]);
        }

        $request_data = $request->all();
        $setting = Setting::findorfail($id);

        if($setting->update($request_data))
        {
            $request->session()->flash('success', 'Setting updated successfully.');
        }
        else
        {
            $request->session()->flash('error', 'Sorry some error has occured!');
        }
        return redirect()->route('admin.settings.index');
    }

    public function ajax_get_settings()
    {
    	$settings = Setting::orderBy('id', 'asc')->get();
        
        $settings = $settings->toArray();
        $res['data'] = array();
        
        $i = 1;
        foreach($settings as $setting)
        {
            $data['sr_no'] = $i;
            $data['description'] = $setting['description'];
            $data['keyword'] = $setting['keyword'];
            $data['value'] = $setting['value'];
            
            $actions = '<a class="btn btn-xs btn-warning" href="'.route('admin.settings.edit', $setting['id']).'"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
           	
            //$actions ="test";
            $data['action'] = $actions;
           	$res['data'][] = $data; 
            $i++;
        }
        
        return json_encode($res);
    }
}
