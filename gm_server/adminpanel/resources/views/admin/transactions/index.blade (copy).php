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
			<!-- Begin: Demo Datatable 1 -->
            <div class="portlet light portlet-fit portlet-datatable bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-settings font-dark"></i>
                        <span class="caption-subject font-dark sbold uppercase">Transaction Logs</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="table-container">
                        <table class="table table-striped table-bordered table-hover table-checkable" id="list-records">
                            <thead>
                                <tr>
                                	<th width="10%">Sr. No. </th>
		                            <!--<th width="10%"> User </th>
		                            <th width="15%"> Project </th>
		                            <th width="17%"> Type </th>-->
		                            <th width="90%"> Description </th>
		                            <!--<th width="16%"> Time </th>-->
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

<script>
	$(document).ready(function(){
		$('#list-records').dataTable({
			//"processing": true,
    		//"serverSide": true,
			"ajax": "<?php echo url('/admin/transactions/ajax_get_transactions');?>",
	        "columns": [
	            { "data": "sr_no" },
	            //{ "data": "username" },
	            //{ "data": "projecttitle" },
	            //{ "data": "type" },
	            { "data": "description" },
	            //{ "data": "timestamp" },
	        ],
	    });
	});

	
	$(window).load(function(){
		setTimeout(function(){
			$('.note').fadeOut();	
		},2000);
	});
</script>
@stop
