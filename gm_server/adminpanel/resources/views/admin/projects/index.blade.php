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
                        <span class="caption-subject font-dark sbold uppercase">
                        	<a href="{{ route('admin.users.index') }}">User>></a>
                        	Projects
                        </span>
                    </div>
                    <div class="actions">
                    	
                        <div>
                        	<a class="label-info btn-outline btn-circle btn-sm" href="{{ route('admin.{user}.projects.create', $userid) }}" style="color:#fff;text-decoration: none;">
                				<i class="glyphicon glyphicon-plus"></i> Create
                			</a>
                        </div>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="table-container">
                        
                        <table class="table table-striped table-bordered table-hover table-checkable" id="list-records">
                            <thead>
                                <tr>
		                            <th> Sr. No. </th>
		                            <th> Project Owner </th>
		                            <th> Project Title </th>
		                            <th> Project Tag </th>
		                            <th> Actions </th>
		                        </tr>
                            </thead>
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
		$('#list-records').dataTable({
			"processing": true,
        	"serverSide": true,
        	"paging": true,
        	"searching":false,
        	"ajax": "<?php echo url('/admin/ajax_get_projects/'.$userid);?>",
	    });
	});
		
	$(window).load(function(){
		setTimeout(function(){
			$('.note').fadeOut();	
		},2000);
	});
</script>
@stop
