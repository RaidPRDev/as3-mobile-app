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
						<div class="caption" style="float:right;">
							<a class="btn btn-xs default" href="{{ route('admin.users.index') }}">Close</a>
						</div>

						<div class="caption">
							<span>User Profile</span>
						</div>
					</div>
					<div class="portlet-body form" style="display: block;">
						<div style="padding:16px;">
							<div style="font-size:18px; border-bottom:1px solid;">
								Account Details
							</div>

							<div style="padding-top:8px;">
								<table width="100%">
									<tr>
										<td width="25%">Username</td>
										<td>:</td>
										<td>{{ $user->username }}</td>
									<tr>

									<tr>
										<td width="25%">Email</td>
										<td>:</td>
										<td>{{ $user->useremail }}</td>
									<tr>

									<tr>
										<td width="25%">Password</td>
										<td>:</td>
										<td>******</td>
									<tr>

									<tr>
										<td width="25%">Account Type</td>
										<td>:</td>
										<td>{{ $user->accountType }}</td>
									<tr>

									<tr>
										<td width="25%">Gender</td>
										<td>:</td>
										<td>{{ $user->gender }}</td>
									<tr>

									<tr>
										<td width="25%">Birth Date</td>
										<td>:</td>
										<td>{{ $user->birthDate }}</td>
									<tr>

									<tr>
										<td width="25%">Register Date</td>
										<td>:</td>
										<td>{{ $user->registerDate }}</td>
									<tr>

									<tr>
										<td width="25%">SignedIn Date</td>
										<td>:</td>
										<td>{{ $user->signedInDate }}</td>
									<tr>

									<tr>
										<td width="25%">UserCode</td>
										<td>:</td>
										<td>{{ $user->user_code }}</td>
									<tr>

									<tr>
										<td width="25%">Referred User</td>
										<td>:</td>
										<td>{{ $user->refer_name }}</td>
									<tr>

									<tr>
										<td width="25%">Organisation referred user</td>
										<td>:</td>
										<td>{{ $user->organisation_name }}</td>
									<tr>

									<tr>
										<td width="25%">Provider</td>
										<td>:</td>
										<td>{{ $user->provider }}</td>
									<tr>

									<tr>
										<td width="25%">Wallet</td>
										<td>:</td>
										<td>${{ $user->wallet }}</td>
									<tr>

									<tr>
										<td width="25%">Stars</td>
										<td>:</td>
										<td>{{ $user->stars }} stars</td>
									<tr>

									<tr>
										<td style="text-align:right;">
											<a class="btn btn-xs btn-warning" href="route('admin.users.edit', $user->uid)">
												<i class="glyphicon glyphicon-edit"></i> Edit
											</a>
										</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									<tr>
								</table>
							</div>
						</div>

						<div style="padding:16px;">
							<div style="font-size:18px; border-bottom:1px solid;">
								Options
							</div>

							<div style="padding-top:8px;">
								<a class="btn btn-xs default" href="{{ route('admin.{user}.listProjectTags', $user->uid) }}">
									Project Tags({{ $projectTags }})
								</a>

								<a class="btn btn-xs default" href="{{ route('admin.{user}.projects.index', $user->uid) }}">
									Projects({{ $projects }})
								</a>

								<a class="btn btn-xs default" href="{{route('admin.{user}.projects.receivedProjectFunds', $user->uid)}}">
									Recieved Project Funds(${{ $receivedProjectFunds }})
								</a>

								<a class="btn btn-xs default" href="{{route('admin.{user}.projects.givenProjectFunds', $user->uid)}}">
									Given Project Funds(${{ $givenProjectFunds }})
								</a>

								<a class="btn btn-xs default" href="{{ route('admin.{user}.transactions.index', $user->uid) }}">
									Transaction Logs({{ $transaction }})
								</a>
							</div>
						</div>
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
</script>
@stop
