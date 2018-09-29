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
                    	<div class="caption">Edit User</div>
                    </div>
                    <div class="portlet-body form" style="display: block;">
                    	@include('error')
                        <!-- BEGIN FORM-->
                        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="form-horizontal" id="update_user">
                            <input type="hidden" name="_method" value="PUT">
                            {{csrf_field()}}
                            <div class="form-body">
								<div class="form-group @if($errors->has('username')) has-error @endif">
									<label class="col-md-3 control-label" for="username">Username *</label>
									<div class="col-md-4">
										<input type="text" id="username" name="username" class="form-control validate[required]" value="{{ old('username', $user->username) }}" />
										@if($errors->has("username"))
										<span class="help-block">{{ $errors->first("username") }}</span>
										@endif
									</div>
								</div>

								<div class="form-group @if($errors->has('useremail')) has-error @endif">
									<label class="col-md-3 control-label" for="useremail">Useremail *</label>
									<div class="col-md-4">
										<input type="email" id="useremail" name="useremail" class="form-control validate[required,custom[email]]" value="{{ old('useremail', $user->useremail) }}" />
										@if($errors->has("useremail"))
										<span class="help-block">{{ $errors->first("useremail") }}</span>
										@endif
									</div>
								</div>

								<div class="form-group @if($errors->has('password')) has-error @endif">
									<label class="col-md-3 control-label" for="password">Password *</label>
									<div class="col-md-4">
										<input type="password" id="password" name="password" class="form-control" />
										@if($errors->has("password"))
										<span class="help-block">{{ $errors->first("password") }}</span>
										@endif
									</div>
								</div>
								<div class="form-group @if($errors->has('confirm_password')) has-error @endif">
									<label class="col-md-3 control-label" for="confirm_password">Confirm Password *</label>
									<div class="col-md-4">
										<input type="password" id="confirm_password" name="confirm_password" class="form-control" />
										@if($errors->has("confirm_password"))
										<span class="help-block">{{ $errors->first("confirm_password") }}</span>
										@endif
									</div>
								</div>

								<div class="form-group @if($errors->has('accountType')) has-error @endif">
								<label class="col-md-3 control-label" for="accountType">Account Type *</label>
								<div class="col-md-4">
								<select id="accountType" name="accountType" class="form-control validate[required]">
								<option value="" selected disabled>Select Account Type</option>
								<option value="1" {{ (old('accountType', $user->accountType) == 1 ? 'selected':'') }}>Goaliemind Account</option>
								<option value="2" {{ (old('accountType', $user->accountType) == 2 ? 'selected':'') }}>Organisation Mindgoalie</option>
								<option value="3" {{ (old('accountType', $user->accountType) == 3 ? 'selected':'') }}>Personal Mindgoalie</option>
								</select>

								@if($errors->has("accountType"))
								<span class="help-block">{{ $errors->first("accountType") }}</span>
								@endif
								</div>
								</div>

								<div class="form-group @if($errors->has('gender')) has-error @endif">
									<label class="col-md-3 control-label" for="gender">Gender *</label>
									<div class="col-md-4">
										<select id="gender" name="gender" class="form-control validate[required]">
										<option value="" selected disabled>Select Gender</option>
										<option value="boy" {{ (old('gender', $user->gender) == 'boy' ? 'selected':'') }}>Boy</option>
										<option value="girl" {{ (old('gender', $user->gender) == 'girl' ? 'selected':'') }}>Girl</option>
										</select>

								@if($errors->has("gender"))
								<span class="help-block">{{ $errors->first("gender") }}</span>
								@endif
									</div>
								</div>

								<div class="form-group @if($errors->has('birthDate')) has-error @endif">
									<label class="col-md-3 control-label" for="birthDate">Birth Date *</label>
									<div class="col-md-4">
										<input type="date" id="birthDate" name="birthDate" class="form-control validate[required]" value="{{ $user->birthDate }}" />
										@if($errors->has("birthDate"))
										<span class="help-block">{{ $errors->first("birthDate") }}</span>
										@endif
									</div>
								</div>

								<div class="form-group @if($errors->has('isAuthorized')) has-error @endif">
									<label class="col-md-3 control-label" for="isAuthorized">Is Authorized? *</label>
									<div class="col-md-4">
										<select id="isAuthorized" name="isAuthorized" class="form-control validate[required]">
										<option value="" selected disabled>Select Authorization Option</option>
										<option value="1" {{ (old('isAuthorized', $user->isAuthorized) == 1 ? 'selected':'') }}>Yes</option>
										<option value="0" {{ (old('isAuthorized', $user->isAuthorized) == 0 ? 'selected':'') }}>No</option>
										</select>

										@if($errors->has("isAuthorized"))
											<span class="help-block">{{ $errors->first("isAuthorized") }}</span>
										@endif
									</div>
								</div>

							</div>
                            
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">Update</button>
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

<script>
	$(document).ready(function(){
		$("#update_user").validationEngine({scroll: false});
	});
</script>
@stop
