@extends('layouts.admin.base')

@section('content')
<div class="page-content">
	<div class="row">
		<div class="page-header clearfix">
	        <h1>Tags / Show #{{$tag->id}}</h1>
	        <form action="{{ route('admin.tags.destroy', $tag->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
	            <input type="hidden" name="_method" value="DELETE">
	            <input type="hidden" name="_token" value="{{ csrf_token() }}">
	            <div class="btn-group pull-right" role="group">
	                <a class="btn btn-warning btn-group" role="group" href="{{ route('admin.tags.edit', $tag->id) }}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
	                <button type="submit" class="btn btn-danger">Delete <i class="glyphicon glyphicon-trash"></i></button>
	            </div>
	        </form>
	    </div>
	</div>
	
	<div class="portlet box blue">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cogs"></i>Tags</div>
            
        </div>
        <div class="portlet-body form">
            
            	<form action="#" class="form-horizontal">
            		
            		<div class="form-body">
            			
		                <div class="form-group">
		                	<label class="col-md-3 control-label" for="title-field"><b>ID</b></label>
							<div class="col-md-4">
								{{$tag->id}}
							</div>
		                </div>
		                <div class="form-group">
		                	<label class="col-md-3 control-label" for="title-field"><b>Tag</b></label>
							<div class="col-md-4">
								{{$tag->title}}
							</div>
						</div>
		                <div class="form-group">
		                     <label class="col-md-3 control-label" for="title-field"><b>Status</b></label>
		                     <div class="col-md-4">
		                     	{{($tag->status = 1)?'Active':'Inactive'}}
		                     </div>
		                </div>
	               </div>
	            </form>
           
        </div>    	
	</div>
    
</div>
@endsection