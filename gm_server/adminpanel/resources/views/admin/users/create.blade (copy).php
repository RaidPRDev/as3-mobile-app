@extends('layouts.admin.base')

@section('styles')
<link href="{{asset('admin_design/css/validationEngine.jquery.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('admin_design/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css')}}" rel="stylesheet" type="text/css" />
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
							Create User
						</div>
					</div>
					<div class="portlet-body form" style="display: block;">
						@include('error')
						<!-- BEGIN FORM-->
						<form action="{{ route('admin.users.store') }}" method="POST" class="form-horizontal" id="create_user" novalidate="novalidate" enctype="multipart/form-data">
							{{csrf_field()}}
							<div class="form-body">

								<div class="form-group @if($errors->has('name')) has-error @endif">
									<label class="col-md-3 control-label" for="name">Name *</label>
									<div class="col-md-4">
										<input type="text" id="name" name="name" class="form-control validate[required]" value="{{ old("name") }}"/>
										@if($errors->has("name"))
										<span class="help-block">{{ $errors->first("name") }}</span>
										@endif
									</div>
								</div>

								<div class="form-group @if($errors->has('email')) has-error @endif">
									<label class="col-md-3 control-label" for="email">Email *</label>
									<div class="col-md-4">
										<input type="text" id="email" name="email" class="form-control validate[required,custom[email]]" value="{{ old("email") }}"/>
										@if($errors->has("email"))
										<span class="help-block">{{ $errors->first("email") }}</span>
										@endif
									</div>
								</div>

								<div class="form-group @if($errors->has('password')) has-error @endif">
									<label class="col-md-3 control-label" for="password">Password *</label>
									<div class="col-md-4">
										<input type="password" id="password" name="password" class="form-control validate[required]" />
										@if($errors->has("password"))
										<span class="help-block">{{ $errors->first("password") }}</span>
										@endif
									</div>
								</div>
								<div class="form-group @if($errors->has('confirm_password')) has-error @endif">
									<label class="col-md-3 control-label" for="confirm_password">Confirm Password *</label>
									<div class="col-md-4">
										<input type="password" id="confirm_password" name="confirm_password" class="form-control validate[required]"/>
										@if($errors->has("confirm_password"))
										<span class="help-block">{{ $errors->first("confirm_password") }}</span>
										@endif
									</div>
								</div>

								<!--<div class="form-group @if($errors->has('is_admin')) has-error @endif">
								<label class="col-md-3 control-label" for="is_admin">User Role *</label>
								<div class="col-md-4">
								<select id="is_admin" name="is_admin" class="form-control">
								<option value="0" selected>Normal User</option>
								<option value="1" >Admin</option>
								</select>

								@if($errors->has("is_admin"))
								<span class="help-block">{{ $errors->first("is_admin") }}</span>
								@endif
								</div>
								</div>-->

								<div class="form-group @if($errors->has('about_me')) has-error @endif">
									<label class="col-md-3 control-label" for="title-field">About me *</label>
									<div class="col-md-9">
										<textarea class="about_me form-control validate[required]" name="about_me" rows="6">{{ old("about_me") }}</textarea>
										<!-- <input type="text" id="raw_content-field" name="raw_content" class="form-control" value="{{ old("raw_content") }}"/> -->
										@if($errors->has("about_me"))
										<span class="help-block">{{ $errors->first("about_me") }}</span>
										@endif
									</div>
								</div>

								<div class="form-group @if($errors->has('image')) has-error @endif">
									<label class="col-md-3 control-label" for="image-field">Image *</label>
									<div class="col-md-9">
										<!-- <input type="text" id="image-field" name="image" class="form-control" value="{{ old("image") }}"/> -->
										<!-- <label class="custom-file">
										<input type="file" name="image" id="file" >
										<span class="custom-file-control"></span>
										</label> -->
										<div class="fileinput fileinput-new" data-provides="fileinput">
											<div class="input-group input-large">
												<div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
													<i class="fa fa-file fileinput-exists"></i>&nbsp; <span class="fileinput-filename"> </span>
												</div>
												<span class="input-group-addon btn default btn-file"> <span class="fileinput-new"> Select file </span> <span class="fileinput-exists"> Change </span>
													<input type="file" name="image" class="custom-file-input validate[required]">
												</span>
												<a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
											</div>
										</div>
										@if($errors->has("image"))
										<span class="help-block">{{ $errors->first("image") }}</span>
										@endif
									</div>
								</div>

								<div class="form-group @if($errors->has('role')) has-error @endif">
									<label class="col-md-3 control-label" for="is_admin">User Role *</label>
									<div class="col-md-4">
										<select id="is_admin" name="role" class="form-control">
											<!-- <option value="0" selected>Normal User</option>
											<option value="1" >Admin</option> -->
											@php $r=0 @endphp
											@foreach($roles as $role)
											<option value="{{$role->id}}" {{($r==0)?'selected':''}}>{{$role->display_name}}</option>
											@php $r++ @endphp
											@endforeach
										</select>

										@if($errors->has("is_admin"))
										<span class="help-block">{{ $errors->first("role") }}</span>
										@endif
									</div>
								</div>
							</div>

							<div class="form-actions">
								<div class="row">
									<div class="col-md-offset-3 col-md-9">
										<button type="submit" class="btn green" id="user_create_btn">
											Create
										</button>
										<a class="btn default" href="{{ route('admin.users.index') }}">Back</a>
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
<script src="{{asset('admin_design/global/plugins/bootstrap-multiselect/js/bootstrap-multiselect.js')}}" type="text/javascript"></script>
<script src="{{asset('ckeditor/ckeditor.js')}}"></script>

<script>
	$(document).ready(function() {
		$("#create_user").validationEngine({
			scroll : false
		});
	}); 
	
	CKEDITOR.replace( 'about_me' );
</script>
@stop
