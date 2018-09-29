@extends('layouts.admin.base')

@section('styles')
<link href="{{asset('admin_design/css/validationEngine.jquery.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('admin_design/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css')}}" rel="stylesheet" type="text/css" />
@stop

@section('content')
<div class="page-content">
	<div class="row">
		<div class="col-md-12">

			<div class="tabbable-line boxless tabbable-reversed">
				<div class="portlet box blue-hoki">
					<div class="portlet-title">
						<div class="caption">
							<a href="{{ route('admin.users.index') }}" style="color:white;">User>></a>
							<a href="{{ route('admin.{user}.projects.index', $userid) }}" style="color:white;">Project>></a>
							Create Project
						</div>
					</div>
					<div class="portlet-body form" style="display: block;">
						@include('error')
						<!-- BEGIN FORM-->
						<form action="{{ route('admin.{user}.projects.store', $userid) }}" method="POST" class="form-horizontal" id="create_project" novalidate="novalidate" enctype="multipart/form-data">
							{{csrf_field()}}
							<div class="form-body">
								<div class="form-group @if($errors->has('userid')) has-error @endif">
									<label class="col-md-3 control-label" for="userid">Select User *</label>
									<div class="col-md-4">
										<select id="userid" name="userid" class="form-control validate[required]">
											<option value="" selected disabled>Select User</option>
											@foreach($users as $userval)
												<option value="{{ $userval->id }}">{{ $userval->useremail }}</option>
											@endforeach
										</select>

										@if($errors->has("userid"))
										<span class="help-block">{{ $errors->first("userid") }}</span>
										@endif
									</div>
								</div>

								<div class="form-group @if($errors->has('title')) has-error @endif">
									<label class="col-md-3 control-label" for="title">Project Title *</label>
									<div class="col-md-4">
										<input type="text" name="title" id="title" class="form-control validate[required]" value="{{ old('title') }}" />
										@if($errors->has("title"))
										<span class="help-block">{{ $errors->first("title") }}</span>
										@endif
									</div>
								</div>

								<div class="form-group @if($errors->has('description')) has-error @endif">
									<label class="col-md-3 control-label" for="description">Description *</label>
									<div class="col-md-4">
										<textarea id="description" name="description" class="form-control validate[required]">{{ old('description') }}</textarea>
										@if($errors->has("description"))
										<span class="help-block">{{ $errors->first("description") }}</span>
										@endif
									</div>
								</div>

								<div class="form-group @if($errors->has('tags')) has-error @endif">
									<label class="col-md-3 control-label" for="tags">Select Tag *</label>
									<div class="col-md-4">
										<select id="tags" name="tags" class="form-control validate[required]">
											<option value="" selected disabled>Select Tag</option>
											@foreach($projecttag as $projecttag_val)
												<option value="{{ $projecttag_val->id }}">{{ $projecttag_val->name }}</option>
											@endforeach
										</select>

										@if($errors->has("tags"))
											<span class="help-block">{{ $errors->first("tags") }}</span>
										@endif
									</div>
								</div>

								{{--<div class="form-group @if($errors->has('image')) has-error @endif">
									<label class="col-md-3 control-label" for="image-field">Image</label>
									<div class="col-md-9">
										<div class="fileinput fileinput-new" data-provides="fileinput">
											<div class="input-group input-large">
												<div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
													<i class="fa fa-file fileinput-exists"></i>&nbsp; <span class="fileinput-filename"> </span>
												</div>
												<span class="input-group-addon btn default btn-file"> <span class="fileinput-new"> Select file </span> <span class="fileinput-exists"> Change </span>
													<input type="file" name="image" class="custom-file-input validate[required]">
												</span>
												<a href="javascript:void(0);" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
											</div>
										</div>
										@if($errors->has("image"))
										<span class="help-block">{{ $errors->first("image") }}</span>
										@endif
									</div>
								</div>--}}

							</div>

							<div class="form-actions">
								<div class="row">
									<div class="col-md-offset-3 col-md-9">
										<button type="submit" class="btn green">
											Create
										</button>
										<a class="btn default" href="{{ route('admin.{user}.projects.index', $userid) }}">Back</a>
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
<script src="{{asset('admin_design/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js')}}" type="text/javascript"></script>
</script>

<script>
	$(document).ready(function() {
		$("#create_project").validationEngine({
			scroll : false
		});
	}); 
</script>
@stop
