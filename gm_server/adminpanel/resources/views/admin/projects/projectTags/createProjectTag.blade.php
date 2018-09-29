@extends('layouts.admin.base')

@section('styles')
<link href="{{asset('admin_design/css/validationEngine.jquery.css')}}" rel="stylesheet" type="text/css" />
<!--<link href="{{--asset('admin_design/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css')--}}" rel="stylesheet" type="text/css" />
-->@stop

@section('content')
<div class="page-content">
	<div class="row">
		<div class="col-md-12">

			<div class="tabbable-line boxless tabbable-reversed">
				<div class="portlet box blue-hoki">
					<div class="portlet-title">
						<div class="caption">
							Create Project Tag
						</div>
					</div>
					<div class="portlet-body form" style="display: block;">
						@include('error')
						<!-- BEGIN FORM-->
						<form action="{{ route('admin.projects.storeProjectTags') }}" method="POST" class="form-horizontal" id="create_projecttag" novalidate="novalidate" enctype="multipart/form-data">
							{{csrf_field()}}
							<div class="form-body">
								<div class="form-group @if($errors->has('name')) has-error @endif">
									<label class="col-md-3 control-label" for="name">Name *</label>
									<div class="col-md-4">
										<input type="text" id="name" name="name" class="form-control validate[required]" value="{{ old('name') }}" />
										@if($errors->has("name"))
										<span class="help-block">{{ $errors->first("name") }}</span>
										@endif
									</div>
								</div>
							</div>

							<div class="form-actions">
								<div class="row">
									<div class="col-md-offset-3 col-md-9">
										<button type="submit" class="btn green">
											Create
										</button>
										<a class="btn default" href="{{ route('admin.projects.listProjectTags') }}">Back</a>
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
		$("#create_projecttag").validationEngine({
			scroll : false
		});
	}); 
</script>
@stop
 