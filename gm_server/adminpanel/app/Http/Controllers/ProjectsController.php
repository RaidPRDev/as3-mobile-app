<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Project;
use App\GeneralUser;
use App\Project_tag;
use DB;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   /* public function index()
    {
        return view('admin.projects.index');
    }*/

    public function index($userid)
    {
        return view('admin.projects.index',compact('userid'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($userid)
    {
        $users = GeneralUser::orderBy('useremail', 'asc')->get();

        $projecttag = Project_tag::orderBy('name', 'asc')->get();
        return view('admin.projects.create', compact('users', 'projecttag', 'userid'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $userid)
    {
        //Validations
        $this->validate($request, [
            'userid' => 'required',
            'title' => 'required',
            'description' => 'required',
            'tags' => 'required',
        ]);

        $data = $request->all();

        $usertype = GeneralUser::find($data['userid'])->pluck('accountType');

        $project['userid'] = $data['userid'];
        $project['title'] = $data['title'];
        $project['description'] = $data['description'];
        $project['tags'] = $data['tags'];
        $project['type'] = $usertype[0];
        $project['timestamp'] = round(microtime(true) * 1000);
        $project['month'] = date("n");
        $project['year'] = date("Y");
        
        if(Project::create($project))
        {
            $request->session()->flash('success', 'Project created successfully!');
        }
        else
        {
            $request->session()->flash('error', 'Sorry some error has occured!');
        }

        return redirect()->route('admin.{user}.projects.index', $userid);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($userid, $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($userid, $id)
    {
        $project = Project::findOrFail($id);
        $users = GeneralUser::orderBy('useremail', 'asc')->get();
        $projecttag = Project_tag::orderBy('name', 'asc')->get();
        return view('admin.projects.edit', compact('project', 'users', 'projecttag', 'userid'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $userid, $id)
    {
        //Validations
        $this->validate($request, [
            'userid' => 'required',
            'title' => 'required',
            'description' => 'required',
            'tags' => 'required',
        ]);

        $data = $request->all();

        $usertype = GeneralUser::find($data['userid'])->pluck('accountType');

        $project['userid'] = $data['userid'];
        $project['title'] = $data['title'];
        $project['description'] = $data['description'];
        $project['tags'] = $data['tags'];
        $project['type'] = $usertype[0];
        
        $findProject = Project::findOrFail($id);

        if($findProject->update($project))
        {
            $request->session()->flash('success', 'Project updated successfully!');
        }
        else
        {
            $request->session()->flash('error', 'Sorry some error has occured!');
        }

        return redirect()->route('admin.{user}.projects.index', $userid);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $userid, $id)
    {
        $project = Project::findOrFail($id);
        
        if($project->delete())
        {

            $request->session()->flash('success', 'Project deleted successfully!');
        }
        else {
            $request->session()->flash('error', 'Sorry some error has occured!');
        }
        
        return redirect()->route('admin.{user}.projects.index', $userid);
    }

    public function ajax_get_project($userid)
    //public function index($userid)
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
            $skip = $_GET['start'];
        }
        else
        {
            $skip = 0;
        }

        $getProjectList = DB::table('projects')
                            ->join('users', 'projects.userid', '=', 'users.id')
                            ->join('project_tags', 'projects.tags', '=', 'project_tags.id')
                            ->where('projects.userid', '=', $userid)
                            ->select('users.username', 'projects.id', 'projects.title', 'project_tags.name')
                            ->orderBy('projects.id', 'desc')
                            ->offset($skip)
                            ->limit($max)
                            ->get();

        $getTotalProjectList = DB::table('projects')
                                ->where('userid', '=', $userid)
                                ->count('id');
        
        $res['recordsTotal'] = $getTotalProjectList;
        $res['recordsFiltered'] = $getTotalProjectList;
        
        $res['data'] = array();
        $i = $skip;
        foreach($getProjectList as $project_val)
        {
            $i++;
            $data[0] = $i;
            $data[1] = $project_val->username;   //project owner
            $data[2] = $project_val->title;      //project title
            $data[3] = $project_val->name;       //project tag

            $actions = '<a class="btn btn-xs btn-warning" href="'.route("admin.{user}.projects.edit", [$userid, $project_val->id]).'"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
                        
            //if( $logged_in_user->id != $user['id'])
            //{
                $actions .= '<form action="'.route('admin.{user}.projects.destroy', [$userid, $project_val->id]).'" method="POST" style="display: inline;" onsubmit="if(confirm(\'Delete? Are you sure?\')) { return true } else {return false };">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="'.csrf_token().'">
                                <button type="submit" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                            </form>';
            //}   
     //       $actions ='test';                    
            $data[4] = $actions;
            $res['data'][] = $data; 
        }
        
        return json_encode($res);
    }

    //Retrieve the details of Recieved Project Funds
    public function showReceivedProjectFunds($userid)
    {
        return view('admin.projects.receivedProjectFunds', compact('userid'));
    }

    /*

    */
    public function ajaxReceivedProjectFunds($userid)
    {
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
            $skip = $_GET['start'];
        }
        else
        {
            $skip = 0;
        }

        $project_funds = DB::table('project_funds')
                        ->join('projects', 'project_funds.project_id', '=', 'projects.id')
                        ->leftJoin('users as fundeduser', 'project_funds.user_id', '=', 'fundeduser.id')
                        ->leftJoin('users as referuser', 'project_funds.refer_id', '=', 'referuser.id')
                        ->where('projects.userid', '=', $userid)
                        ->select('project_funds.*', 'projects.description as projectdesc', 'fundeduser.username as fundedusername', 'referuser.username as referusername')
                        ->orderBy('project_funds.id', 'desc')
                        ->offset($skip)
                        ->limit($max)
                        ->get();

        $totalProjectFundList = DB::table('project_funds')
                                ->join('projects', 'project_funds.project_id', '=', 'projects.id')
                                ->where('projects.userid', '=', $userid)
                                ->count('project_funds.id');
        
        $res['recordsTotal'] = $totalProjectFundList;
        $res['recordsFiltered'] = $totalProjectFundList;
        
        $res['data'] = array();
        
        $i = $skip;
        foreach($project_funds as $project_fund)
        {
            $i++;
            $data[0] = $i;
            if(strlen($project_fund->projectdesc)>26)
            {
                $data[1] = substr($project_fund->projectdesc, 0, 25)."...";
            }
            else
            {
                $data[1] = substr($project_fund->projectdesc, 0, 25);
            }
            
            $data[2] = $project_fund->amount;
            $data[3] = $project_fund->fundedusername;
            $data[4] = ($project_fund->refer_id==0 || $project_fund->refer_id==-1)?'-':$project_fund->referusername;
            $data[5] = $project_fund->goaliemind_amt;
            $data[6] = $project_fund->refer_amt;
            $data[7] = $project_fund->isWithdrawl==1?true:false;
            $data[8] = $project_fund->updated_on;
            
            $res['data'][] = $data; 
        }
        
        return json_encode($res);
    }

    //Retrieve the details of Given Project Funds
    public function showGivenProjectFunds($userid)
    {
        return view('admin.projects.givenProjectFunds', compact('userid'));
    }

    /*

    */
    public function ajaxGivenProjectFunds($userid)
    {
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
            $skip = $_GET['start'];
        }
        else
        {
            $skip = 0;
        }

        $project_funds = DB::table('project_funds')
                        ->join('projects', 'project_funds.project_id', '=', 'projects.id')
                        ->leftJoin('users as fundeduser', 'project_funds.user_id', '=', 'fundeduser.id')
                        ->leftJoin('users as referuser', 'project_funds.refer_id', '=', 'referuser.id')
                        ->where('project_funds.user_id', '=', $userid)
                        ->select('project_funds.*', 'projects.description as projectdesc', 'fundeduser.username as fundedusername', 'referuser.username as referusername')
                        ->orderBy('project_funds.id', 'desc')
                        ->offset($skip)
                        ->limit($max)
                        ->get();

        $totalProjectFundList = DB::table('project_funds')
                                ->join('projects', 'project_funds.project_id', '=', 'projects.id')
                                ->where('project_funds.user_id', '=', $userid)
                                ->count('project_funds.id');
        
        $res['recordsTotal'] = $totalProjectFundList;
        $res['recordsFiltered'] = $totalProjectFundList;
        
        $res['data'] = array();
        
        $i = $skip;
        foreach($project_funds as $project_fund)
        {
            $i++;
            $data[0] = $i;
            if(strlen($project_fund->projectdesc)>26)
            {
                $data[1] = substr($project_fund->projectdesc, 0, 25)."...";
            }
            else
            {
                $data[1] = substr($project_fund->projectdesc, 0, 25);
            }
            
            $data[2] = $project_fund->amount;
            $data[3] = $project_fund->fundedusername;
            $data[4] = ($project_fund->refer_id==0 || $project_fund->refer_id==-1)?'-':$project_fund->referusername;
            $data[5] = $project_fund->goaliemind_amt;
            $data[6] = $project_fund->refer_amt;
            $data[7] = $project_fund->isWithdrawl==1?true:false;
            $data[8] = $project_fund->updated_on;
            
            $res['data'][] = $data; 
        }
        
        return json_encode($res);
    }

    /*Project Tags start*/
    public function listProjectTags()
    {
        return view('admin.projects.projectTags.listProjectTags');
    }

    public function createProjectTags()
    {
        return view('admin.projects.projectTags.createProjectTag');
    }

    public function storeProjectTags(Request $request)
    {
        $requestData = $request->all();
        Project_tag::create($requestData);
        return redirect()->route('admin.projects.listProjectTags');
    }

    public function editProjectTags($id)
    {
        $project_tag = Project_tag::findOrFail($id);
        return view('admin.projects.projectTags.editProjectTag',compact('project_tag'));
    }

    public function updateProjectTags(Request $request, $id)
    {
        $requestData = $request->all();
        $project_tag = Project_tag::findOrFail($id);

        if($project_tag->update($requestData))
        {
            $request->session()->flash('success', 'Project\'s tag updated successfully!');
        }
        else 
        {
            $request->session()->flash('error', 'Sorry some error has occured!');
        }
        
        return redirect()->route('admin.projects.listProjectTags');
    }

    public function destroyProjectTags(Request $request, $id)
    {
        $project_tag = Project_tag::findOrFail($id);
        
        if($project_tag->delete())
        {
            $request->session()->flash('success', 'Project\'s tag deleted successfully!');
        }
        else {
            $request->session()->flash('error', 'Sorry some error has occured!');
        }
        
        return redirect()->route('admin.projects.listProjectTags');
    }
    /*Project Tags end*/

    public function ajax_get_projectTags()
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
        
        $projectTags = DB::table('project_tags')
                        ->offset($from)
                        ->limit($max)
                        ->get();

        $totalProjectTags = DB::table('project_tags')->count('id');
        
        $res['recordsTotal'] = $totalProjectTags;
        $res['recordsFiltered'] = $totalProjectTags;
        
        $res['data'] = array();
        $i = $from;
        foreach($projectTags as $projectTags_val)
        {
            $i++;
            $data[0] = $i;
            $data[1] = $projectTags_val->name;

            $actions = '<a class="btn btn-xs btn-warning" href="'.route('admin.projects.editProjectTags', $projectTags_val->id).'"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
                        
            //if( $logged_in_user->id != $user['id'])
            //{
                $actions .= '<form action="'.route('admin.projects.destroyProjectTags', $projectTags_val->id).'" method="POST" style="display: inline;" onsubmit="if(confirm(\'Delete? Are you sure?\')) { return true } else {return false };">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="'.csrf_token().'">
                                <button type="submit" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                            </form>';
            //}   
                                
            $data[2] = $actions;
            $res['data'][] = $data; 
        }
        
        return json_encode($res);
    }

    //User Project Tag
    public function listUserProjectTags($userid)
    {
        return view('admin.projects.projectTags.listUserProjectTags', compact('userid'));
    }

    public function ajaxUserProjectTags($userid)
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
        
        $projectTags = DB::table('project_tags')
                        ->join('projects', 'project_tags.id', '=', 'projects.tags')
                        ->where('projects.userid', '=', $userid)
                        ->groupBy('project_tags.id')
                        ->offset($from)
                        ->limit($max)
                        ->get();

        $totalProjectTags = DB::table('project_tags')
                            ->join('projects', 'project_tags.id', '=', 'projects.tags')
                            ->where('projects.userid', '=', $userid)
                            ->groupBy('project_tags.id')
                            ->pluck('project_tags.id');
        
        $res['recordsTotal'] = count($totalProjectTags);
        $res['recordsFiltered'] = count($totalProjectTags);
        
        $res['data'] = array();
        $i = $from;
        foreach($projectTags as $projectTags_val)
        {
            $i++;
            $data[0] = $i;
            $data[1] = $projectTags_val->name;

            $res['data'][] = $data; 
        }
        
        return json_encode($res);
    }

}
