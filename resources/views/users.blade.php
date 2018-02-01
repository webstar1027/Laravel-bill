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
                        <th>Profile Photo</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Rewards</th>
                        <th>Balance($)</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php $k = 1 ?>
                @foreach($users as $key=>$user)
                    <tr>
                        <td>{{$k++}}</td>
                        <td><img src="{{asset('images/profile/'.$user->image)}}" alt="" height="60" width="60"></td>
                        <td>{{$user->firstname}} {{$user->lastname}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->rewardStar}}</td>
                        <td>{{$user->balance}}</td>
                        <td><a class="btn btn-warning" href=""><i class="icon-file-check position-left"></i>Edit</a></td>
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