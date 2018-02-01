@extends('layouts.master')

@section('content')

<div class="panel panel-default">
    <div class="panel-body">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <h3>{{$placement['media']}}: {{$placement['name']}}</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <a href="http://tracker.ama.com/stats/placement/69">
                            <span class="glyphicon glyphicon-stats" aria-hidden="true"></span> Show Delivery Statistics
                        </a><br>
                        <a href="http://tracker.ama.com/stats/placement/device/69">
                            <span class="glyphicon glyphicon-stats" aria-hidden="true"></span> Show Device Statistics
                        </a><br>
                        <a href="http://tracker.ama.com/stats/placement/geo/69"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> Show Geo Statistics</a><br>
                        <a href="http://tracker.ama.com/stats/placement/unique/69"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> Show Unique Statistics</a><br>
                        <a href="http://tracker.ama.com/stats/placement/viewability/69"><span class="glyphicon glyphicon-scale" aria-hidden="true"></span> Show Viewabilty Report</a><br>
                    </div>
                    <div class="col-md-4">Total Views: {{$placement['views']}}<br>Total Clicks: {{$placement['clicks']}}<br>
                    </div>
                    <div class="col-md-4"><a href="http://tracker.ama.com/edit/placement/69"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit Placement</a><br><a href="http://tracker.ama.com/list/placementbookings/69"><span class="glyphicon glyphicon-modal-window" aria-hidden="true"></span> List Bookings</a><br><a href="http://tracker.ama.com/add/placementbookings/69"><span class="glyphicon glyphicon-sort" aria-hidden="true"></span> Add Bookings</a><br><a href="http://tracker.ama.com/add/externalplacementbookings/69"><span class="glyphicon glyphicon-sort" aria-hidden="true"></span> Add External Bookings</a><br><a href="http://tracker.ama.com/show/placementTag/69"><span class="glyphicon glyphicon-tag" aria-hidden="true"></span> Get Tags</a></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading"><strong>Placement</strong></div>
    <div class="panel-body"><p>Edit placement</p></div>
    
    <ul class="list-group">
        <form method="post">
            {{ csrf_field() }}
            <div class="form-group">
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-xs-5">
                            <label for="name">Name:</label>
                        </div>
                        <div class="col-xs-7">
                            <input size="32" data-parsley-trigger="change" required="" placeholder="" class="form-control" value="{{$placement['name']}}" name="name" type="text">
                            <span style="opacity: 1; background-size: 19px 13px; left: 627px; top: 16px; width: 19px; min-width: 19px; height: 13px; position: absolute; background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABMAAAANCAYAAABLjFUnAAAACXBIWXMAAAsTAAALEwEAmpwYAAABMmlDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjarZG9SsNQGIaf04qCQxAJbsLBQVzEn61j0pYiONQokmRrkkMVbXI4Of508ia8CAcXR0HvoOIgOHkJboI4ODgECU4i+EzP9w4vL3zQWPE6frcxB6PcmqDnyzCK5cwj0zQBYJCW2uv3twHyIlf8RMD7MwLgadXr+F3+xmyqjQU+gc1MlSmIdSA7s9qCuATc5EhbEFeAa/aCNog7wBlWPgGcpPIXwDFhFIN4BdxhGMXQAHCTyl3AtercArQLPTaHwwMrN1qtlvSyIlFyd1xaNSrlVp4WRhdmYFUGVPuq3Z7Wx0oGPZ//JYxiWdnbDgIQC5M6q0lPzOn3D8TD73fdMb4HL4Cp2zrb/4DrNVhs1tnyEsxfwI3+AvOlUD7FY+VVAAAAIGNIUk0AAHolAACAgwAA9CUAAITRAABtXwAA6GwAADyLAAAbWIPnB3gAAAECSURBVHjapNK9K8UBFIfxD12im8LEYLgZjcpgUFgUCUlZTJRkMpqUxR+hDFIGhWwGwy0MFiUxSCbZ5DXiYjnqdvvpvnim0+mcZ/ieU9U6tySBNEbQjBpMoANZnGIdl4VLKcm8YCPqbgzhAT1ox1mSrFpxjtCHLdygH5tJgymlMYhhtKH+r6FishYsYBpN0dvFHu5wi9okWToC/0AmcpqM+pd7PKMRDajDNb5xlS97Qyem0BsL8IT9uOAhXpGLvL/wGXO5fFkOO7FwHvJtLOOilGALM8vE1d6xiFVlkP8aXTjAI8bKFeXLBuJ3shjFiQpIYR6zWMFaXFOlsnHM4Ng/+RkAdVE2mEeC7WYAAAAASUVORK5CYII=&quot;); background-repeat: no-repeat; background-position: 0px 0px; border: none; display: inline; visibility: visible; z-index: auto;"></span>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-xs-5">
                            <label for="sendoutlockdown">Lock down adselection pr sendout:</label>
                        </div>
                        <div class="col-xs-7">
                            <select class="form-control" name="sendoutlockdown">
                                <option selected="" value="0">no</option>
                                <option value="1">yes</option>
                            </select>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-xs-5">
                            <label for="media_id">Media:</label>
                        </div>
                        <div class="col-xs-7">
                            <select class="form-control" name="media_id">
                                @foreach($medias as $media)
                                    @if($media['value'] == $placement['media'])
                                        <option selected="selected" value="{{$media['id']}}">{{$media['value']}}</option>
                                    @else
                                        <option value="{{$media['id']}}">{{$media['value']}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-xs-5">
                            <label for="format_id">Format:</label>
                        </div>
                        <div class="col-xs-7">
                            <select class="form-control" name="format_id">
                                @foreach($formats as $format)
                                    @if($format['value'] == $placement['format'])
                                        <option selected="selected" value="{{$format['id']}}">{{$format['value']}}</option>
                                    @else
                                        <option value="{{$format['id']}}">{{$format['value']}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </li>
                    <input value="true" name="Placement_posted" type="hidden">
                    <input value="4ecfc894662007241c2513bba0a5cf8c" name="Placement_key" type="hidden">
                <li class="list-group-item">&nbsp;
                    <div class="btn-group pull-right" role="group" aria-label="...">
                        <input value="Send" class="btn btn-success " type="submit"> &nbsp;
                    </div>
                    <div class="btn-group pull-right" role="group" aria-label="...">
                        <input value="Back" class="btn btn-default" onclick="window.history.back()" type="button">  &nbsp;
                    </div>
                </li>
            </div>
        </form>
    </ul>
</div>

@stop