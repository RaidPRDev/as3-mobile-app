  @extends('layouts.admin.base')
  @section('styles')
  <link href="{{asset('admin_design/css/validationEngine.jquery.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{asset('admin_design/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{asset('admin_design/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{asset('admin_design/global/plugins/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" rel="stylesheet" type="text/css" />
  
  <!-- <script src="{{asset('ckeditor/init_editor.js')}}"></script> -->
  @stop
  @section('content')
  <div class="page-content">
      <div class="row">
        <div class="col-md-12">
			@if(Session::has('success'))
		        <div class="note note-success">
		            <p>{{ Session::get('success') }}</p>
		        </div>
		    @elseif(Session::has('error'))
		    	<div class="note note-danger">
		            <p>{{ Session::get('error') }}</p>
		        </div>    
		    @endif
        <div class="tabbable-line boxless tabbable-reversed">
        <div class="portlet box blue-hoki">
          <div class="portlet-title">
            <div class="caption">Create email</div>
          </div>
            
          <div class="portlet-body form" style="display: block;">
            @include('error')
            <form action="{{ route('admin.emails.store') }}" method="POST" id="create_article" class="form-horizontal" enctype="multipart/form-data">
                <div class="form-body">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                  <div class="form-group @if($errors->has('title')) has-error @endif">
                    <label class="col-md-3 control-label" for="slug-field">emails *</label>
                    <div class="col-md-9">
                 <!--   <input type="text" id="title-field" name="email" class="form-control validate[required]" value="{{ old('email') }}"/>
                      @if($errors->has("title"))
                        <span class="help-block">{{ $errors->first("email") }}</span>
                      @endif-->
                      <select name="email" id="title-field" class="form-control validate[required]"> 
                        <option value="">Select</option>
						@if(!empty($emails))
                          @foreach($emails as $email)
                            <option value="{{$email->email}}" >{{$email->email}}</option>
                          @endforeach
                        @endif
                      </select>  
                      
                      
                    </div>
                  </div> 
                  <!-- -->
                  
                  <div class="form-group @if($errors->has('title')) has-error @endif">
                    <label class="col-md-3 control-label" for="slug-field">subject *</label>
                    <div class="col-md-9">
                    <input type="text" id="title-field" name="subject" class="form-control validate[required]" value="{{ old('subject') }}"/>
                      @if($errors->has("title"))
                        <span class="help-block">{{ $errors->first("subject") }}</span>
                      @endif
                    </div>
                  </div>


                  <div class="form-group @if($errors->has('body')) has-error @endif">
                    <label class="col-md-3 control-label" for="title-field">body *</label>
                    <div class="col-md-9">
                      <textarea class="raw_content form-control validate[required]" name="raw_content" rows="6">{{ old("raw_content") }}</textarea>
                      <!-- <input type="text" id="raw_content-field" name="raw_content" class="form-control" value="{{ old("raw_content") }}"/> -->
                      @if($errors->has("body"))
                      <span class="help-block">{{ $errors->first("raw_content") }}</span>
                      @endif
                    </div>  
                  </div>
      
                 
                  <div class="form-actions">
                    <div class="row">
                      <div class="col-md-offset-3 col-md-9">
                      <button type="submit" class="btn green">Send</button>
                      <a class="btn default" href="{{ route('admin.emails.index') }}">Back</a>
                      </div>
                      
                    </div>
                  </div>  
                </div>
              </form>
            </div>
            </div>
        </div>
      </div>
      </div>
  </div>    
  @endsection
  @section('scripts')
	<script src="{{asset('admin_design/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js')}}" type="text/javascript"></script>
	<script src="{{asset('admin_design/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js')}}" type="text/javascript"></script>
	<script src="{{asset('admin_design/js/jquery.validationEngine.js')}}" type="text/javascript"></script>
	<script src="{{asset('admin_design/js/jquery.validationEngine-en.js')}}" type="text/javascript"></script>
    <script src="{{asset('admin_design/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js')}}" type="text/javascript"></script>
    <script src="{{asset('admin_design/global/plugins/bootstrap-multiselect/js/bootstrap-multiselect.js')}}" type="text/javascript"></script>
    <script src="{{asset('admin_design/pages/scripts/components-bootstrap-multiselect.min.js')}}" type="text/javascript"></script>
    
    <script src="{{asset('ckeditor/ckeditor.js')}}"></script>
    <script>
      $('#tag_id').multiselect({
      	buttonWidth: '100%',
      	enableFiltering: true,
        includeSelectAllOption: true,
        
      });
    
      $("#create_article").validationEngine({scroll: false});
      
      $('#meta_category_id').trigger('change');
      
      $('#meta_category_id').change(function(){
       var meta_category_id = $(this).val();
       var url = "<?php echo url('/admin/ajax_get_templates/');?>/"+meta_category_id;
       
       $.ajax({
        url: url,
        method: "GET",
        success: function(result){
        	$('#select_templates').html(result);
          }
        });
      });
      
      CKEDITOR.replace( 'raw_content' );
    </script>
  @endsection
