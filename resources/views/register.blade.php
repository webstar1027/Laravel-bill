@extends('layouts.master')

@section('content')

    <div class="content" id = "register-area"a>

        <!-- Simple login form -->
        <form action="{{ url('doregister')}}" method="post">
            {{ csrf_field() }}
            <div class="panel panel-body login-form">
                @if($error_messages = Session::get('error_messages'))
                    @foreach($error_messages as $key=>$message)
                        <div class="alert alert-danger no-border">
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                            {{$message}}
                        </div>
                    @endforeach
                @endif
                @if($success_messages = Session::get('success_messages'))
                    <div class="alert alert-success no-border">
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                        {{$success_messages}}
                    </div>
                @endif

                <div class="text-center">
                    <h5 class="content-group">Password Creation</h5>
                </div>

                <div class="form-group has-feedback has-feedback-left">
                    <input type="hidden" class="form-control" placeholder="Email address" name="email" value = {{$email}}>
                </div>

                <div class="form-group has-feedback has-feedback-left">
                    <input type="text" class="form-control" placeholder="Enter Email Token" name="email_token" required>
                    <div class="form-control-feedback">
                        <i class="icon-user text-muted"></i>
                    </div>
                </div>

                <div class="form-group has-feedback has-feedback-left">
                    <input type="text" class="form-control" placeholder="Mobile SMS token" name="sms_token" required>
                    <div class="form-control-feedback">
                        <i class="icon-phone text-muted"></i>
                    </div>
                </div>

                <div class="form-group has-feedback has-feedback-left">
                    <input type="password" class="form-control" placeholder="Set Password" name="password" required>
                    <div class="form-control-feedback">
                        <i class="icon-lock2 text-muted"></i>
                    </div>
                </div>

                <div class="form-group has-feedback has-feedback-left">
                    <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirm" required>
                    <div class="form-control-feedback">
                        <i class="icon-lock2 text-muted"></i>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">Submit <i class="icon-circle-right2 position-right"></i></button>
                </div>

            </div>
        </form>
        <!-- /simple login form -->

    </div>
@stop