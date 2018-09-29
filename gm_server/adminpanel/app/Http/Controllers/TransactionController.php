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
    public function index($userid)
    {
    	return view('admin.transactions.index',compact('userid'));
    }

    public function ajax_get_transactions($userid)
    {
    	//$logged_in_user = Auth::user();
    	if (isset($_GET['draw']))
		{
			$res['draw'] = $_GET['draw'];
		}
		else
		{
			$res['draw'] = 1;
		}

    	if (isset($_GET['length']))
		{
			$max = $_GET['length'];
		}
		else
		{
			$max = 10;
		}

		if(isset($_GET['start']))
		{
			$from = $_GET['start'];
		}
		else
		{
			$from = 0;
		}
		
		$transactions = DB::table('transaction')
    					->join('transaction_types', 'transaction.type', '=', 'transaction_types.type')
    					->leftJoin('users', 'transaction.user_id', '=', 'users.id')
    					->leftJoin('projects', 'transaction.project_id', '=', 'projects.id')
    					->where('transaction.user_id', '=', $userid)
    					->select('transaction.*', 'users.username', 'projects.title as projecttitle', 'transaction_types.description_template as description')
    					->orderBy('transaction.id', 'desc')
    					->offset($from)
    					->limit($max)
    					->get();

    	$totaltransactions = DB::table('transaction')
    						->where('user_id', '=', $userid)
    						->count('id');
		
		$res['recordsTotal'] = $totaltransactions;
		$res['recordsFiltered'] = $totaltransactions;
		
		$res['data'] = array();
		$i = $from;
		foreach($transactions as $transaction)
		{
			$i++;
			$data[0] = $i;
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

			$data[1] = $desc;
			
			$res['data'][] = $data; 
		}
		
		return json_encode($res);
	}
}
