<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Transaction;
use DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$transaction = Transaction::orderBy('id', 'desc')->paginate(10);
        return view('admin.transactions.index');
    }

    public function ajax_get_transactions()
    {
    	//$logged_in_user = Auth::user();
    	$transactions = DB::table('transaction')
    					->join('transaction_types', 'transaction.typeid', '=', 'transaction_types.id')
    					->leftJoin('users', 'transaction.user_id', '=', 'users.id')
    					->leftJoin('projects', 'transaction.project_id', '=', 'projects.id')
    					->select('transaction.*', 'users.username', 'projects.title as projecttitle', 'transaction_types.description_template as description')
    					->orderBy('transaction.id', 'desc')
    					->get();
		
		$res['data'] = array();
		
		$i = 1;
		foreach($transactions as $transaction)
		{
			$data['sr_no'] = $i;
			$desc = $transaction->description;
			if($transaction->username != "")
			{
				$desc = str_replace("{uname}","<b>".$transaction->username."</b>",$desc);
			}
			

			if($transaction->projecttitle != "")
			{
				$desc = str_replace("{projectname}","<b>".$transaction->projecttitle."</b>",$desc);
			}

			if($transaction->timestamp != "")
			{
				$desc = str_replace("{date}","<b>".$transaction->timestamp."</b>",$desc);
			}

			if($transaction->amount != "")
			{
				$desc = str_replace("{amt}","<b>".$transaction->amount."</b>",$desc);
			}

			$data['description'] = $desc;
			
			$res['data'][] = $data; 
			$i++;
		}
		
		return json_encode($res);
    }
}
