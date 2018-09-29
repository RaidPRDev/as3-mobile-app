<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Email;

use App\User;

use DB;

use Mail;

class EmailController extends Controller
{
    
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$emails = Email::orderBy('id', 'desc')->paginate(10);
		
		//$emails = DB::table('emails')->get();

		return view('admin.emails.index', compact('emails'));
	}
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//$meta_categories = MetaCategory::orderBy('id', 'desc')->get();
		//$emails = User::orderBy('id', 'asc')->get();
		$emails = DB::table('users')->get();
		return view('admin.emails.create', compact('emails'));
	}
	public function destroy(Request $request,$id)
	{
		//$delcategory = DB::table('categories')->where('id', '=', $id)->delete();
		$email = Email::findOrFail($id);
	//echo "<pre>";print_r($category);exit;
		if($email->delete())
		{
			$request->session()->flash('success', 'email deleted successfully!');
		}
		else {
			$request->session()->flash('error', 'Sorry some error has occured!');
		}
				
		return redirect()->route('admin.emails.index');
	}
	
	public function store(Request $request)
	{
		$email = new Email();
		$toemail = $request->input('email');
		$mailbody = $request->input('raw_content');
		//echo $toemail;exit;
		$data = array('name'=>"Rahul Patil",'to'=>$toemail,'body'=>$mailbody);
		
		Mail::send([], $data, function($message) use ($data){
        // $message->to('rahulpneo@gmail.com', 'Tutorials Point')->subject
        $message->to( $data['to'])->subject('Laravel HTML Testing Mail');
        $message->from('rahul.p@wwindia.com','Rahul Patil');
        $message->setBody($data['body']); //
		});
		echo "HTML Email Sent. Check your inbox.";
		
		if (Mail::failures()) {
        // return response showing failed emails
			$emails = DB::table('users')->get();	
			$users = DB::table('emails')
			->insert(['email'=>$request->input('email'),'subject'=>$request->input('subject'),'body'=>$request->input('raw_content'),'datetime'=>date('Y-m-d H:i:s'),'status'=>0]);
			$request->session()->flash('error', 'Sorry some error has occured!');
			return view('admin.emails.create', compact('emails'));
		} else {
			$emails = DB::table('users')->get();			
			$users = DB::table('emails')
			->insert(['email'=>$request->input('email'),'subject'=>$request->input('subject'),'body'=>$request->input('raw_content'),'datetime'=>date('y-m-d H:i:s'),'status'=>1]);
			$request->session()->flash('success', 'Email sent successfully!');
			return view('admin.emails.create', compact('emails'));			
		}			
		
	}
	
	public function ajax_get_emails()
	{
		$emails = Email::orderBy('id', 'desc')->get();
		
		$emails = $emails->toArray();
		
		$res['data'] = array();
		
		$i = 1;
		foreach($emails as $email)
		{
			//$data['sr_no'] = '<span class="tag-id">'.$i.'</span>';
			$data['email'] = '<span class="tag-title">'.$email['email'].'</span>';
			$data['subject'] = '<span class="tag-title">'.$email['subject'].'</span>';
			$data['body'] = '<span class="tag-title">'.$email['body'].'</span>';
			$data['datetime'] = '<span class="tag-title">'.$email['datetime'].'</span>';
			//$data['status'] = '<span class="tag-title">'.$email['datetime'].'</span>';
		/*	$status = '<form id="status_change_form_'.$category['id'].'" method="POST" style="display: inline;">
                                	<input type="hidden" name="_method" value="PUT">	
                                	<input type="hidden" name="_token" value="'.csrf_token().'">
                                	<input type="hidden" name="id" value="'.$email['id'].'">
                       ';  	*/
                                	
            if($email['status'] == 1){
				$data['status'] = '<span class="tag-title">Sent</span>';
              //  $status .= '<input id="status_'.$email['id'].'" type="hidden" name="status" value="1">';
               // $status .= '<button type="submit" class="btn btn-xs btn-success change_status" data-id='.$email['id'].' data-status=1>Sent</button>';
            }else{
				$data['status'] = '<span class="tag-title">Pending</span>';
              //  $status .= '<input id="status_'.$email['id'].'" type="hidden" name="status" value="0">';
              // $status .= '<button type="submit" class="btn btn-xs btn-danger change_status" data-id='.$email['id'].' data-status=0>Pending</button>';
            }
            //endif;
            //$status .= '</form>';
		//	$data['status'] = $status;
			$data['actions'] = '<form action="'.route('admin.emails.destroy', $email['id']).'" method="POST" style="display: inline;" onsubmit="if(confirm(\'Delete? Are you sure?\')) { return true } else {return false };">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="'.csrf_token().'">
                                    <button type="submit" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                                </form>';
			 
			$res['data'][] = $data; 
			//$i++;
		}
		
		//echo "<pre>";print_r($res);
		
		return json_encode($res);
		//return json_encode($data);
		//return $users;
	}

}
