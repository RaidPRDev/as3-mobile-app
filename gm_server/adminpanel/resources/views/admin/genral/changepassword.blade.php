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
							Change Password
						</div>
					</div>
					<div class="portlet-body form" style="display: block;">
						@include('error')
						<!-- BEGIN FORM-->
						<form action="#" method="POST" class="form-horizontal" id="create_user" novalidate="novalidate" enctype="multipart/form-data">
							{{csrf_field()}}
							<div class="form-body">

								<div class="form-group @if($errors->has('name')) has-error @endif">
									<label class="col-md-3 control-label" for="name">New Password *</label>
									<div class="col-md-4">
										<input type="password" id="p1" name="p1" class="form-control validate[required]" value="{{ old("name") }}"/>
										@if($errors->has("name"))
										<span class="help-block">{{ $errors->first("name") }}</span>
										@endif
									</div>
								</div>

								<div class="form-group @if($errors->has('email')) has-error @endif">
									<label class="col-md-3 control-label" for="email">Re-type Password *</label>
									<div class="col-md-4">
										<input type="password" id="p2" name="p2" class="form-control validate[required]" value=""/>
										@if($errors->has("email"))
										<span class="help-block">{{ $errors->first("email") }}</span>
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

																								
							</div>

							<div class="form-actions">
								<div class="row">
									<div class="col-md-offset-3 col-md-9">
										<button type="submit" class="btn green" id="user_create_btn">
											save
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
