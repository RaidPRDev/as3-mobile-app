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
						<div class="caption">
							Edit Setting
						</div>
					</div>
					<div class="portlet-body form" style="display: block;">
						@include('error')
						<!-- BEGIN FORM-->
						<form action="{{ route('admin.settings.update', $setting->id) }}" method="POST" class="form-horizontal" id="update_setting" novalidate="novalidate" enctype="multipart/form-data">
							{{csrf_field()}}
							<input type="hidden" name="_method" value="PUT" />
							<div class="form-body">
								<div class="form-group">
									<label class="col-md-3 control-label"><b>Description :</b></label>
									<div class="col-md-4">
										{{ $setting->description }}
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-3 control-label"><b>Keyword :</b></label>
									<div class="col-md-4">
										{{ $setting->keyword }}
									</div>
								</div>

								<div class="form-group @if($errors->has('value')) has-error @endif">
									<label class="col-md-3 control-label" for="value"><b>Value *</b></label>
									<div class="col-md-4">
										@if($setting->id == 2)
											<input type="text" name="value" id="value" class="form-control validate[required]" value="{{ old('value', $setting->value) }}" />					
										@elseif(in_array($setting->id, [3,4,8]))
											<input type="number" name="value" id="value" class="form-control validate[required]" value="{{ old('value', $setting->value) }}" />
										@else
											<input type="number" name="value" id="value" class="form-control validate[required]" value="{{ old('value', $setting->value) }}" />
										@endif
										
										@if($errors->has("value"))
										<span class="help-block">{{ $errors->first("value") }}</span>
										@endif
									</div>
								</div>

							</div>

							<div class="form-actions">
								<div class="row">
									<div class="col-md-offset-3 col-md-9">
										<button type="submit" class="btn green">
											Update
										</button>
										<a class="btn default" href="{{ route('admin.settings.index') }}">Back</a>
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
		$("#update_setting").validationEngine({
			scroll : false
		});
	}); 
</script>
@stop
