<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;

use App\GeneralUser;
use App\Role;
use DB;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	$roles = Role::orderBy('id', 'desc')->paginate(10);
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	$this->validate($request, [
	        'username' => 'required|unique:users,username',
	        'useremail' => 'required|email|unique:users,useremail',
	        'password' => 'required|min:6',
	        'confirm_password' => 'required|min:6|same:password',
	        'accountType' => 'required',
	        'gender' => 'required',
	        'birthDate' => 'required',
	    ]);
		
		$data['username'] = addslashes(trim($request->username));
		$data['password'] = md5(trim($request->password));
		$data['useremail'] = addslashes(trim($request->useremail));
		$data['accountType'] = addslashes(trim($request->accountType));
		$data['parentid'] = 0;
		$data['gender'] = addslashes(trim($request->gender));
		$data['birthDate'] = date_format(date_create(trim($request->birthDate)),"F,j,Y"); //Date Fromat e.g January,1,1990
		$data['registerDate'] = round(microtime(true) * 1000);
		$data['signedInDate'] = round(microtime(true) * 1000);
		$data['user_code'] = '';
		$data['provider'] = 'email';
		
		$generaluser = new GeneralUser();
		$createuser = $generaluser->create($data);

		if($createuser)
		{
			$this->setUserCode($createuser->id);
			$request->session()->flash('success', $createuser->id.' User created successfully!');
		}
		else
		{
			$request->session()->flash('error', 'Sorry some error has occured!');
		}
		
		return redirect()->route('admin.users.index');
    }

    public function setUserCode($userid)
    {
    	$flag = true;

    	while($flag)
    	{
    		$usercode = strtoupper(substr(md5($userid.time()), 0, 8));
    		$checkUserCode = DB::table('users')
    				->where('user_code', '=', $usercode)
    				->count('id');

    		if($checkUserCode == 0)
    		{
    			break;
    		}
    	}

    	$data['user_code'] = $usercode;
    	$user = GeneralUser::findorfail($userid);
    	if($user->update($data))
    	{
    		return true;
    	}
    	else
    	{
    		return false;
    	}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    	//$user = GeneralUser::findOrFail($id);
    	$user = DB::table(DB::raw('users u'))
    				->leftjoin(DB::raw('users r'), 'u.refer_id', '=', 'r.id')
    				->leftjoin(DB::raw('users o'), 'u.organisation_refer_id', '=', 'o.id')
    				->select
						(
							'u.id AS uid',
							'u.username', 
							'u.useremail',
							DB::raw(
    							'(
    								CASE 
										WHEN u.accountType = 1 THEN "Goaliemind Account"
										WHEN u.accountType = 2 THEN "Organisation MindGoalie"
										ELSE "Personal MindGoalie"
    								END
    							) AS accountType'
							),
							'u.gender',
							'u.birthDate',
							'u.registerDate',
							'u.signedInDate',
							'u.user_code',
							'u.stars',
							'u.wallet',
							'u.provider',
							'r.username AS refer_name',
							'o.username AS organisation_name'
						)
					->where('u.id', '=', $id)
					->first();
		
		$user->birthDate = date('Y-m-d', ($user->birthDate / 1000));
       	$user->registerDate = date('Y-m-d H:i:s', ($user->registerDate / 1000));
       	$user->signedInDate = date('Y-m-d H:i:s', ($user->signedInDate / 1000));

       	$totalProjectTags = DB::table('project_tags')
                            ->join('projects', 'project_tags.id', '=', 'projects.tags')
                            ->where('projects.userid', '=', $id)
                            ->groupBy('project_tags.id')
                            ->pluck('project_tags.id');

        $projectTags = count($totalProjectTags);

       	$projects = DB::table('projects')
                                ->where('userid', '=', $id)
                                ->count('id');

        $receivedProjectFunds = DB::table('project_funds')
                                ->join('projects', 'project_funds.project_id', '=', 'projects.id')
                                ->where('projects.userid', '=', $id)
                                ->sum('project_funds.amount');

        if($receivedProjectFunds == "")
        {
        	$receivedProjectFunds = 0;	
        }

        $givenProjectFunds = DB::table('project_funds')
                                ->join('projects', 'project_funds.project_id', '=', 'projects.id')
                                ->where('project_funds.user_id', '=', $id)
                                ->sum('project_funds.amount');

        if($givenProjectFunds == "")
        {
        	$givenProjectFunds = 0;	
        }

        $transaction = DB::table('transaction')
    						->where('user_id', '=', $id)
    						->count('id');
        return view('admin.users.showProfile', compact('user','projectTags','projects','receivedProjectFunds','givenProjectFunds','transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       	$user = GeneralUser::findOrFail($id);
       	$explode_bdate = explode(',',$user->birthDate);
       	$user->birthDate = date("Y-m-d", $user->birthDate/1000);
       	return view('admin.users.edit', compact('user'));
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
    	$this->validate($request, [
	        'username' => 'required',
	        'useremail' => 'sometimes|required|email|unique:users,useremail,'.$id.',id',
	        'password' => 'min:6',
	        'confirm_password' => 'same:password',
	        'accountType' => 'required',
	        'gender' => 'required',
	        'birthDate' => 'required',
	        'isAuthorized' => 'required',
	    ]);

    	$data['username'] = addslashes(trim($request->username));
    	if(!empty($request->password))
		{
			$data['password'] = md5(trim($request->paasword));
		}
    	$data['useremail'] = addslashes(trim($request->useremail));
    	$data['accountType'] = addslashes(trim($request->accountType));
		$data['gender'] = addslashes(trim($request->gender));
		$data['birthDate'] = date_format(date_create(trim($request->birthDate)),"F,j,Y"); //Date Fromat e.g January,1,1990
		$data['isAuthorized'] = addslashes(trim($request->isAuthorized));

		$generaluser = GeneralUser::findOrFail($id);
		$updated_user = $generaluser->update($data);

		if($updated_user)
		{
			$request->session()->flash('success', 'User\'s record updated successfully!');
		}
		else {
			$request->session()->flash('error', 'Sorry some error has occured!');
		}
		
		return redirect()->route('admin.users.index');
	}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $user = GeneralUser::findOrFail($id);
		
		if($user->delete())
		{
			$request->session()->flash('success', 'User\'s record deleted successfully!');
		}
		else {
			$request->session()->flash('error', 'Sorry some error has occured!');
		}
		
		return redirect()->route('admin.users.index');
    }
	
	
	public function ajax_get_user()
	{
		if(isset($_GET['draw']))
		{
			$res['draw'] = $_GET['draw'];
		}
		else
		{
			$res['draw'] = 1;
		}

		if(isset($_GET['start']))
		{
			$skip = $_GET['start'];
		}
		else
		{
			$skip = 0;
		}

		if(isset($_GET['length']))
		{
			$max = $_GET['length'];
		}
		else
		{
			$max = 10;
		}

		if(!empty($_GET['search']['value']))
		{
			$search = $_GET['search']['value'];
			$searchString = "username LIKE '%".$search."%' OR useremail LIKE '%".$search."%'";
		}
		else
		{
			$searchString = '1';
		}		

		//Retrive no. of total records
		$res['recordsTotal'] = $this->getNumOfTotalRecords();

		//Retrieve no. of filtered records
		$res['recordsFiltered'] = $this->getNumOfFilteredRecords($searchString);
		
		//Retrieve the user data
		$getRecords = $this->getUserRecords($skip, $max, $searchString);
		
		$logged_in_user = Auth::user();
		
		//$users = $users->toArray();
		$res['data'] = array();
		
		$i = $skip;
		foreach($getRecords as $user)
		{
			$i++;
			$data[0] = $i;
			$data[1] = $user->username;
			$data[2] = $user->useremail;
			$data[3] = $user->accountType;
			$data[4] = date('Y-m-d H:i:s', ($user->registerDate / 1000));
			
			$actions = '<a class="btn btn-xs btn-warning" href="'.route('admin.users.edit', $user->id).'"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
			
			
			if( $logged_in_user->id != $user->id)
			{
				$actions .= '<form action="'.route('admin.users.destroy', $user->id).'" method="POST" style="display: inline;" onsubmit="if(confirm(\'Delete? Are you sure?\')) { return true } else {return false };">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="'.csrf_token().'">
                                <button type="submit" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                            </form>';
			}

			$actions .= '<a class="btn btn-xs btn-warning" href="'.route('admin.users.show', $user->id).'" style="background-color:green;">Profile</a>';	
                                
            $data[5] = $actions;
			 
			$res['data'][] = $data; 
		}
		
		return json_encode($res);
	}

	//Query Functions
	public function getNumOfTotalRecords()
	{
		return DB::table('users')->count('id');
	}

	public function getNumOfFilteredRecords($searchString)
	{
		return DB::table('users')->whereRaw($searchString)->count('id');
	}

	public function getUserRecords($skip, $max, $searchString)
	{
		$query = DB::table('users')
					->select
						(
							'id',
							'username', 
							'useremail',
							DB::raw(
    							'(
    								CASE 
										WHEN accountType = 1 THEN "Goaliemind Account"
										WHEN accountType = 2 THEN "Organisation MindGoalie"
										ELSE "Personal MindGoalie"
    								END
    							) AS accountType'
							),
							'registerDate'
						)
					->whereRaw($searchString)
					->orderBy('id', 'desc')
					->offset($skip)
					->limit($max)
					->get();
		return $query; 
	}
}
