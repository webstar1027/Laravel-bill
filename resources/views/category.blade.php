@extends('layouts.master')

@section('content')

<div class="content">

    <!-- Main charts -->
    <div class="row">
        <div class="panel panel-flat">
            <table class="table table-bordered table-hover datatable-highlight">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Drinkable</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $k = 1 ?>
                    @foreach($categories as $key=>$category)
                    <tr>
                        <td>{{$k++}}</td>
                        <td><img src="{{asset('images/img/'.$category->image)}}" alt="" height="50" width="100"></td>
                        <td>{{$category->name}}</td>
                        <td>{{$category->drinkable}}</td>
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