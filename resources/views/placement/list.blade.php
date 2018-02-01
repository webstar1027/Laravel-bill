@extends('layouts.master')

@section('content')
    
    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">Placements</h3></div>
        <div class="panel-body">
            <table class="col-md-12 table-bordered table-striped table-condensed cf sortable">
                <thead class="cf">
                    <tr>
                        <th class="nosort" data-sortcolumn="0" data-sortkey="0-0">name</th>
                        <th class="nosort" data-sortcolumn="1" data-sortkey="1-0">views</th>
                        <th class="nosort" data-sortcolumn="2" data-sortkey="2-0">clicks</th>
                        <th class="nosort" data-sortcolumn="3" data-sortkey="3-0">Media</th>
                        <th class="nosort" data-sortcolumn="4" data-sortkey="4-0">Format</th>
                        <th class="nosort" data-sortcolumn="5" data-sortkey="5-0">Edit</th>
                        <th class="nosort" data-sortcolumn="6" data-sortkey="6-0">Stats</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($placements as $placement)
                        <tr>
                            <td data-value="{{$placement['name']}}">
                                {{$placement['name']}}
                            </td>
                            <td data-value="{{$placement['views']}}">
                                {{$placement['views']}}
                            </td>
                            <td data-value="{{$placement['clicks']}}">
                                {{$placement['clicks']}}
                            </td>
                            <td data-value="{{$placement['media']}}">
                                {{$placement['media']}}
                            </td>
                            <td data-value="{{$placement['format']}}">
                                {{$placement['format']}}
                            </td>
                            <td data-value="">
                                <a href="{{url('placement/'.$placement['id'])}}"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                            </td>
                            <td data-value="">
                                <a href="#"><div class="media"><img src="{{asset('img/'.$placement['stats'])}}"></div></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <a href="http://tracker.ama.com/edit/placement">Add New</a>
            <p></p>
        </div>
    </div>
    
@stop