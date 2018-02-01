<!DOCTYPE html>
<html>
<head>

    
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <title>Tea Era</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="{{asset('css/icons/icomoon/styles.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/app.css')}}" rel="stylesheet">
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
    
    <!-- Global stylesheets -->
	<link href="{{asset('css/bootstrap.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('css/core.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('css/components.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('css/colors.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('css/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->
    
    <!-- Core JS files -->
    <script type="text/javascript" src="{{asset('js/plugins/loaders/pace.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/core/libraries/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/core/libraries/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/loaders/blockui.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/bootstrap-datepicker.min.js')}}"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script type="text/javascript" src="{{asset('js/plugins/visualization/d3/d3.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/visualization/d3/d3_tooltip.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/forms/styling/switchery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/forms/styling/uniform.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/forms/selects/bootstrap_multiselect.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/ui/moment/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/core/app.js')}}"></script>
    <!--<script type="text/javascript" src="{{asset('js/pages/dashboard.js')}}"></script>    -->

    <!-- Theme JS files -->
	<script type="text/javascript" src="{{asset('js/plugins/tables/datatables/datatables.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/plugins/tables/datatables/extensions/row_reorder.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/plugins/tables/datatables/extensions/responsive.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/plugins/forms/selects/select2.min.js')}}"></script>

	<script type="text/javascript" src="{{asset('js/pages/datatables_extension_row_reorder.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/pages/datatables_advanced.js')}}"></script>
 	<script type="text/javascript" src="{{asset('js/plugins/notifications/bootbox.min.js')}}"></script>
 	<script type="text/javascript" src="{{asset('js/pages/form_inputs.js')}}"></script>
   	<!--<script type="text/javascript" src="{{asset('js/pages/form_select2.js')}}"></script>-->
<!-- /theme JS files -->

    <script type="text/javascript" src="{{asset('js/main.js')}}"></script>
</head>
    <body class="@if(!Auth::check()) login-container @endif  pace-done">
        @if(Auth::check())
            @include('shared.header')
            <!-- Page container -->
            <div class="page-container">
                <!-- Page content -->
                <div class="page-content">
                    @include('shared.sidebar')
                    <!-- Main content -->
                    <div class="content-wrapper">
                        @yield('content')
                    </div>
                    <!-- /main content -->
                </div>
                <!-- /page content -->
                
            </div>
            <!-- /page container -->
        @else
            <!-- Page container -->
            <div class="page-container">
                <!-- Page content -->
                <div class="page-content">
                    <!-- Main content -->
                    <div class="content-wrapper">
                        @yield('content')
                    </div>
                    <!-- /main content -->
                </div>
                <!-- /page content -->
                
            </div>
            <!-- /page container -->
        @endif
    </body>
</html>