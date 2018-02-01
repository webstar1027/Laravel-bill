@extends('layouts.master')

@section('content')

<div class="content">

    <!-- Main charts -->
    <div class="row">
        <div class="panel panel-flat">
            <table class="table table-bordered table-hover datatable-highlight">
                <thead>
                    <tr>
                        <th>Order Number</th>
                        <th>User Name</th>
                        <th>User Email</th>
                        <th>Sub Total</th>
                        <th>Rewards Credit</th>
                        <th>Tax</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $key=>$order)
                    <tr>
                        <td>#{{$order->id}}</td>
                        <td>{{$order->firstname}} {{$order->lastname}}</td>
                        <td>{{$order->email}}</td>
                        <td>{{$order->subTotal}}</td>
                        <td>{{$order->rewardsCredit}}</td>
                        <td>{{$order->tax}}</td>
                        <td>{{$order->totalPrice}}</td>
                        <td>
                            @if ($order->status == "0")
                                <span class="label label-danger">New</span>
                            @elseif ($order->status == "1")
                                <span class="label label-warning">Ready for Pickup</span>
                            @elseif ($order->status == "2")
                                <span class="label label-primary">In Progress</span>
                            @else
                                <span class="label label-success">Completed</span>
                            @endif
                        </td>
                        <td>{{$order->timestamp}}</td>
                        <td><a class="btn btn-warning" href=""><i class="icon-file-check position-left"></i>Details</a></td>
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