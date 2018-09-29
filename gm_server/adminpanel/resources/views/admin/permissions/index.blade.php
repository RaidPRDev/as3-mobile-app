@extends('layouts.admin.base')

@section('content')
<div class="page-content">
	<div class="row">
		<div class="col-md-12">
			<div class="tabbable-line boxless tabbable-reversed">
                <div class="portlet box blue-hoki">
                    <div class="portlet-title">
                    	<div class="caption">Update Category</div>
                    </div>
                    <div class="portlet-body form" style="display: block;">
                    	<div class="actions">
                    		<a class="btn btn-success" href="{{route('admin.permissions.create')}}">
                    			Click here
                    		</a> to list all permissions.
                    	</div>
                    	
                    	<div class="row">
                    		@if($permissions->count()>0)
                    			@foreach($permissions as $permission)
                    				
                    			@endforeach
                    		@endif
                    	</div>
                    </div>
                </div>
             </div>        	
		</div>
	</div>
</div>

@stop		