@extends('layouts.master')

@section('content')
    <!-- Content area -->

    <div class="container" id="forgotpassword-form">
        <a href="{{url('/')}}" class="login-logo"></a>
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2>Reset Password Form</h2>
                    </div>
                    <form action="{{Asset('reset-password/'.$forgot_token)}}" method="post" class="form-horizontal">
                        <div class="panel-body">
                            @if(Session::has('error_messages') || Session::has('success_message'))
                                <div class="form-group mb-md">
                                    <div class="col-xs-12 ">
                                        @if($error_messages = Session::get('error_messages'))
                                            @foreach($error_messages as $key=>$message)
                                                <div class="alert alert-danger">{{$message}}</div>
                                            @endforeach
                                        @endif
                                        @if($success_message = Session::get('success_message'))
                                            <div class="alert alert-success">
                                                {{$success_message}}
                                            </div>
                                        @endif
                                        
                                    </div>
                                </div>
                            @endif
                            <div class="form-group mb-md">
                                <div class="col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="ti ti-key"></i>
                                        </span>
                                        <input type="password" class="form-control on-blur" name="password" placeholder="Password" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-md">
                                <div class="col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="ti ti-key"></i>
                                        </span>
                                        <input type="password" class="form-control on-blur" name="password_confirmation" placeholder="Confirm Password" required value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <div class="clearfix">
                                <button type="submit" class="btn btn-primary pull-right">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop