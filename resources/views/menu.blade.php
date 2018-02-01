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
                        <th>Menu</th>
                        <th>Category</th>
                        <th>Price ($)</th>
                        <th>Promoted1</th>
                        <th>Promoted2</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $k = 1 ?>
                    @foreach($menus as $key=>$menu)
                    <tr>
                        <td>{{$k++}}</td>
                        <td><img src="{{asset('images/img/'.$menu->image)}}" alt="" height="50" width="100"></td>
                        <td>{{$menu->name}}</td>
                        <td>{{$menu->categoryName}}</td>
                        <td>{{$menu->price}}</td>
                        <td>{{$menu->promoted1}}</td>
                        <td>{{$menu->promoted2}}</td>
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