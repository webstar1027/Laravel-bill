@extends('layouts.master')

@section('content')
    <!-- Content area -->
    <div class="content" id = "login-area" style = "display: block;">

        <!-- Simple login form -->
        <form action="{{ url('login')}}" method="post">
            {{ csrf_field() }}
            <div class="panel panel-body login-form">
                <div class="text-center">
                    <div class="icon-object border-slate-300 text-slate-300"><i class="icon-reading"></i></div>
                    <h5 class="content-group">Login to your account <small class="display-block">Enter your credentials below</small></h5>
                </div>

                <div class="form-group has-feedback has-feedback-left">
                    <input type="text" class="form-control" placeholder="Email" name="email" required>
                    <div class="form-control-feedback">
                        <i class="icon-user text-muted"></i>
                    </div>
                </div>

                <div class="form-group has-feedback has-feedback-left">
                    <input type="password" class="form-control" placeholder="Password" name="password" required>
                    <div class="form-control-feedback">
                        <i class="icon-lock2 text-muted"></i>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">Sign in <i class="icon-circle-right2 position-right"></i></button>
                </div>

            </div>
        </form>
        <!-- /simple login form -->


        {{ csrf_field() }}
        <div class="panel panel-body login-form">
            <div class="text-center">
                <h6 class="content-group">NEW USER SIGNUP / FORGOT PASSWORD </h6>
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-primary btn-block" onclick = "javascript: $('#login-area').hide(); $('#register-area').show();">Login with Email token <i class="icon-circle-right2 position-right"></i></button>
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-primary btn-block" onclick = "javascript: $('#login-area').hide(); $('#register-area').show();">Login with SMS token <i class="icon-circle-right2 position-right"></i> </button>
            </div>
        </div>
        <!-- /simple login form -->
    </div>
    <!-- /content area -->

    <div class="content" id = "register-area"  style = "display: none;">

        <!-- Simple login form -->
        <form action="{{ url('sendtoken')}}" method="post">
            {{ csrf_field() }}
            <div class="panel panel-body login-form">
                <div class="text-center">
                    <h5 class="content-group">Login with Email or SMS token</h5>
                </div>

                <div class="form-group has-feedback has-feedback-left">
                    <input type="text" class="form-control" placeholder="Enter Email" name="email">
                    <div class="form-control-feedback">
                        <i class="icon-user text-muted"></i>
                    </div>
                </div>

                <div class="form-group has-feedback has-feedback-left">
                    <input type="text" class="form-control" placeholder="Mobile phone number" name="phone">
                    <div class="form-control-feedback">
                        <i class="icon-phone"></i>
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