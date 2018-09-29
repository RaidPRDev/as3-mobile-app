@extends('layouts.admin.base')
@section('styles')
<link href="{{asset('admin_design/global/plugins/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('admin_design/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('admin_design/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}" rel="stylesheet" type="text/css" />
@stop
@section('content')
<div class="page-content">
	<div class="row">
		<div class="col-md-12">
			  
			@if(Session::has('success'))
		        <div class="note note-success">
		            <p>{{ Session::get('success') }}</p>
		        </div>
		    @elseif(Session::has('error'))
		    	<div class="note note-danger">
		            <p>{{ Session::get('error') }}</p>
		        </div>    
		    @endif
            
            <!-- Begin: Demo Datatable 1 -->
            <div class="portlet light portlet-fit portlet-datatable bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-settings font-dark"></i>
                        <span class="caption-subject font-dark sbold uppercase">Roles</span>
                    </div>
                    
                </div>
                <div class="portlet-body">
                    <div class="table-container">
                        
                        <table class="table table-striped table-bordered table-hover table-checkable" id="role-records">
                            <thead>
                                <tr role="row" class="heading">
                                    <th> Sr. No. </th>
		                            <th> Role Name </th>
		                            <th> Description </th>
		                            <!-- <th> Status </th> -->
		                            <th> Actions </th>
                                </tr>
                                
                            </thead>
                            <tbody>
                            	@php $i=1 @endphp
                            	@if(count($roles)>0)
                            		@foreach($roles as $role)
                            		<tr>
                            			<td>{{$i}}</td>
                            			<td>{{$role->display_name}}</td>
                            			<td>{{$role->description}}</td>
                            			<td>
                            				<a class="btn btn-xs btn-warning" href="{{route('admin.roles.edit', $role->id)}}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
			                                <form action="'.route('admin.roles.destroy', $role->id).'" method="POST" style="display: inline;" onsubmit="if(confirm(\'Delete? Are you sure?\')) { return true } else {return false };">
			                                    <input type="hidden" name="_method" value="DELETE">
			                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
			                                    <button type="submit" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> Delete</button>
			                                </form>
                            			</td>
                            			@php $i++ @endphp
                            		</tr>	
                            		@endforeach
                            	@endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
	
    

</div>
@endsection


@section('scripts')

<script src="{{asset('admin_design/global/scripts/datatable.js')}}" type="text/javascript"></script>
<script src="{{asset('admin_design/global/plugins/datatables/datatables.min.js')}}" type="text/javascript"></script>
<script src="{{asset('admin_design/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js')}}" type="text/javascript"></script>
<script src="{{asset('admin_design/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('admin_design/pages/scripts/table-datatables-ajax.min.js')}}" type="text/javascript"></script>
<script src="{{asset('admin_design/js/custom-jquery.js')}}" type="text/javascript"></script>

<script>
	$(document).ready(function(){
		$('#role-records').dataTable({
			"aoColumnDefs": [
		        { 'bSortable': false, 'aTargets': [3] }
		    ]
		});
	});
	
	$(window).load(function(){
		setTimeout(function(){
			$('.note').fadeOut();	
		},2000);
	});
</script>

@stop
