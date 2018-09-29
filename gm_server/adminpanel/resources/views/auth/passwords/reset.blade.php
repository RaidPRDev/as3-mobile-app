@extends('layouts.admin.login')

@section('content')

<div class="content">
	<form class="login-form" role="form" method="POST" action="{{ url('/password/reset') }}"  style="display: block;">
        {{ csrf_field() }}
        <input type="hidden" name="token" value="{{ $token }}">
        <h3 class="font-green">Reset Password</h3>
        <p> Enter your e-mail address below to reset your password. </p>
        
        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email" class="control-label visible-ie8 visible-ie9">E-Mail Address</label>

            <input id="email" type="email"  placeholder="Email Id" class="form-control placeholder-no-fix" name="email" value="{{ $email or old('email') }}">

            @if ($errors->has('email'))
                <span class="help-block">
                    {{ $errors->first('email') }}
                </span>
            @endif
            
        </div>
        
        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password" class="control-label visible-ie8 visible-ie9">Password</label>

            <input id="password" type="password" placeholder="Password" class="form-control placeholder-no-fix" name="password">

            @if ($errors->has('password'))
                <span class="help-block">
                    {{ $errors->first('password') }}
                </span>
            @endif
        </div>

        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
            <label for="password-confirm" class="control-label visible-ie8 visible-ie9">Confirm Password</label>
            <input id="password-confirm" placeholder="Confirm Password" type="password" class="form-control placeholder-no-fix" name="password_confirmation">

            @if ($errors->has('password_confirmation'))
                <span class="help-block">
                    {{ $errors->first('password_confirmation') }}
                </span>
            @endif
           
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-success uppercase pull-right">Submit</button>
        </div>
    </form>
</div>
        

@endsection
