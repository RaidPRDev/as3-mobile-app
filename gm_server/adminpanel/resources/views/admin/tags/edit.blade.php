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
                    	<div class="caption">Update Tags</div>
                    </div>
                    <div class="portlet-body form" style="display: block;">
                    	@include('error')
                        <!-- BEGIN FORM-->
                        <form action="{{ route('admin.tags.update', $tag->id) }}" method="POST" class="form-horizontal" id="update_tag" >
                            <input type="hidden" name="_method" value="PUT">
	                		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	                		
                            <div class="form-body">
                                
                                <div class="form-group @if($errors->has('title')) has-error @endif">
									<label class="col-md-3 control-label" for="title-field">Title *</label>
									<div class="col-md-4">
									<input type="text" id="title-field" name="title" class="form-control validate[required]" value="{{ is_null(old("title")) ? $tag->title : old("title") }}"/>
				                    @if($errors->has("title"))
				                        <span class="help-block">{{ $errors->first("title") }}</span>
				                    @endif
				                   </div>
			                    </div>
			                    
			                    <div class="form-group @if($errors->has('category_id')) has-error @endif">
									<label class="col-md-3 control-label" for="title-field">Category *</label>
									<div class="col-md-4">
									<?php 
									$category_arr = array();
									if(isset($tag['category']) && !empty($tag['category']))
									{
										foreach($tag['category'] as $category)
										{
											$category_arr[] = $category['id'];
										}
									}
									
									?>	
									
									@if($categories->count()>0)
										<select id="category_id" name="category_id[]" class="form-control" multiple>
										<option value="">--Select--</option>
										@foreach($categories as $category)
											<option value="{{$category->id}}" {{(in_array($category->id,$category_arr) )?'selected':''}}>{{$category->category}}</option>
										@endforeach
										</select>
									@endif
									<!-- <input type="text" id="title-field" name="category_id" class="form-control validate[required]" value="{{ old("title") }}"/> -->
				                    @if($errors->has("category_id"))
				                        <span class="help-block">{{ $errors->first("category_id") }}</span>
				                    @endif
				                   </div>
			                    </div>
			                    
                                <div class="form-group @if($errors->has('status')) has-error @endif">
                                	
									<label class="col-md-3 control-label" for="status-field">Status *</label>
									<div class="col-md-4">
									<select id="status-field" name="status" class="form-control">
										<option value="1" {{ ($tag->status == 1) ? 'selected' : '' }}>Active</option>
										<option value="0" {{ ($tag->status == 0) ? 'selected' : '' }}>Inactive</option>
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
                                        <button type="submit" class="btn green">Update</button>
                                        <a class="btn default" href="{{ route('admin.tags.index') }}">Back</a>
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
	    
	    <!-- <div class="row">
	        <div class="col-md-12">
	
	            <form action="{{ route('admin.tags.update', $tag->id) }}" method="POST">
	                <input type="hidden" name="_method" value="PUT">
	                <input type="hidden" name="_token" value="{{ csrf_token() }}">
	
	                <div class="form-group @if($errors->has('title')) has-error @endif">
	                       <label for="title-field">Title</label>
	                    <input type="text" id="title-field" name="title" class="form-control" value="{{ is_null(old("title")) ? $tag->title : old("title") }}"/>
	                       @if($errors->has("title"))
	                        <span class="help-block">{{ $errors->first("title") }}</span>
	                       @endif
	                    </div>
	                
	            </form>
	
	        </div>
	    </div> -->
	</div>
    
@endsection

@section('scripts')
<script src="{{asset('admin_design/js/jquery.validationEngine.js')}}" type="text/javascript"></script>
<script src="{{asset('admin_design/js/jquery.validationEngine-en.js')}}" type="text/javascript"></script>
<script src="{{asset('admin_design/js/custom-jquery.js')}}" type="text/javascript"></script>
<script>
	$(document).ready(function(){
		$("#update_tag").validationEngine({scroll: false});
	});
</script>
@stop
