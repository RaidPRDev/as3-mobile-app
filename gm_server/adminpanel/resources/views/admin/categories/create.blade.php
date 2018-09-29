@extends('layouts.admin.base')
@section('styles')
<link href="{{asset('admin_design/css/validationEngine.jquery.css')}}" rel="stylesheet" type="text/css" />
@stop
@section('content')
    

    <div class="page-content">
    <div class="row">
        <div class="col-md-12">
        	
            <div class="tabbable-line boxless tabbable-reversed">
                <div class="portlet box blue-hoki">
                    <div class="portlet-title">
                    	<div class="caption">Create Category</div>
                    </div>
                    <div class="portlet-body form" style="display: block;">
                    	@include('error')
                        <!-- BEGIN FORM-->
                        <form action="{{ route('admin.categories.store') }}" method="POST" id="create_category" class="form-horizontal">
                            {{csrf_field()}}
                            <div class="form-body">
                                
                                <div class="form-group @if($errors->has('category')) has-error @endif">
									<label class="col-md-3 control-label" for="category-field">Category *</label>
									<div class="col-md-4">
									<input type="text" id="category-field" name="category" class="form-control validate[required]" value="{{ old("category") }}"/>
				                    @if($errors->has("category"))
				                        <span class="help-block">{{ $errors->first("category") }}</span>
				                    @endif
				                   </div>
			                    </div>
			                    
                                <div class="form-group @if($errors->has('title')) has-error @endif">
									<label class="col-md-3 control-label" for="status-field">Status *</label>
									<div class="col-md-4">
									<select id="status-field" name="status" class="form-control">
										<option value="1" selected>Active</option>
										<option value="0" >Inactive</option>
									</select>
									
				                    @if($errors->has("status"))
				                        <span class="help-block">{{ $errors->first("status") }}</span>
				                    @endif
				                   </div>
			                    </div>
                            </div>
                            
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">Create</button>
                                        <a class="btn default" href="{{ route('admin.categories.index') }}">Back</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- END FORM-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    
                        
@endsection

@section('scripts')
<script src="{{asset('admin_design/js/jquery.validationEngine.js')}}" type="text/javascript"></script>
<script src="{{asset('admin_design/js/jquery.validationEngine-en.js')}}" type="text/javascript"></script>
<script src="{{asset('admin_design/js/custom-jquery.js')}}" type="text/javascript"></script>
<script>
	$(document).ready(function(){
		$("#create_category").validationEngine({scroll: false});
	});
</script>
@stop
