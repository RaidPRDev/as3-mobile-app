@extends('layouts.admin.login')

<!-- Main Content -->
@section('content')

<div class="content">
	<form class="login-form" role="form" method="POST" action="{{ url('/password/email') }}" style="display: block;">
        {{ csrf_field() }}
        
        <h3 class="font-green">Reset Password</h3>
        <p> Enter your e-mail address below to reset your password. </p>
        
        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email" class="control-label visible-ie8 visible-ie9">E-Mail Address</label>

            <input id="email" type="email" placeholder="Email Id" class="form-control placeholder-no-fix" name="email" placeholder="E-Mail Address" value="{{ old('email') }}">

            @if ($errors->has('email'))
                <span class="help-block">
                    {{ $errors->first('email') }}
                </span>
            @endif
            
        </div>

        <div class="form-group">
        	<a href="{{ url('/login') }}" id="back-btn" class="btn green btn-outline">Back</a>
            <button type="submit" class="btn btn-success uppercase pull-right">Send Password Reset Link</button>
        </div>
        
    </form>
</div>


<!-- <div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Reset Password</div>
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-envelope"></i> Send Password Reset Link
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> -->
@endsection
