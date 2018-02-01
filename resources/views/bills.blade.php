@extends('layouts.master')

@section('content')

<div class="content">

    <!-- Main charts -->
    <div class="row">
        <div class="panel panel-flat">            
            <table class="table table-borderd table-hover datatable-highlight">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Zip Code</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($bills as $key=>$bill)
                    <tr>
                        <td></td>
                        <td class="td-month"><h5>{{date_format(date_create($bill['month']), 'F Y')}}</h5></td>
                        <td class="td-money" align = right><h5>NGN {{$bill['charge']}}</h5></td>
                        <td><a class="btn btn-warning" href="/records/{{$bill['month']}}"><i class="icon-file-check position-left"></i>Review</a></td>
                        <td><a class="btn btn-primary" ><i class="icon-share4 position-left"></i>Share</a></td>
                        <td><a class="btn btn-success" ><i class="icon-cash3 position-left"></i>Pay</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer text-muted">
        &copy; 2017. <a href="#">Tea Era</a> by <a href="" target="_blank">Tea Era</a>
    </div>
    <!-- /footer -->

</div>    
     
@stop