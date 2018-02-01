@extends('layouts.master')

@section('content')

<div class="content">

    <!-- Main charts -->
    <div class="row">
        <div class="panel panel-flat">
            <table class="table table-bordered table-hover datatable-basic">
                <thead>
                    <tr>
                        <th>Register Date/Time</th>
                        <th>LR Date/Time</th>
                        <th>Dispute No.</th>
                        <th>Subscriber</th>
                        <th>Record No.</th>
                        <th>Message</th>
                        <th>Value</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($disputes as $key=>$dispute)
                    <tr>
                        <td>{{$dispute->created_at}}</td>
                        <td>{{$dispute->updated_at}}</td>
                        <td>{{$dispute->id}}</td>
                        <td>{{$dispute->subscriber_id}}</td>
                        <td>
                            @if ($dispute->state == "0")
                                <a onclick="showRecordDetail('{{$dispute->record_id}}')"  data-toggle="modal" data-target="#modal_form_horizontal">{{$dispute->record_id}}</a>                            
                            @else
                                {{$dispute->record_id}}
                            @endif
                        </td>
                        <td>{{$dispute->comment}}</td>
                        <td>{{$dispute->value}}</td>
                        <td>
                            @if ($dispute->state == "0")                        
                                <span class="label label-success">OPEN</span>
                            @else
                                <span class="label label-default">CLOSED</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($dispute->state == "0")
                                <a href=""> Respond </a>
                            @else
                                Respond
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>
    <input type="hidden" id = "_token" name="_token" value="{{ csrf_token() }}">

    <!-- Dispute modal -->
    <!--<div id="modal_default" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content" id = "modal-1" style="display: none;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" onclick="closeModal();">&times;</button>
                    <h5 class="modal-title">File a Dispute</h5>
                </div>

                <div class="modal-body">
                    <h6 class="text-semibold note">!IMPORTANT NOTE</h6>                    
                    <p>Please double check and verify your personal record before disputing a subscription activity record. If you dispute a voice call or SMS message to another number, the number <b>may be blacklisted and barred</b>, if determined to be fraudulent after our review. Be sure to verify properly before disputing an interaction with another phone number.</p>
                    <hr>

                    <h6 class="text-semibold details">ACTIVITY DETAILS</h6>
                    <div class="row">
                        <div class="col-sm-6">Activity reference: </div>
                        <div class="col-sm-6">
                            <input type="text" class="form-control modal-control" id = "modal_reference" name = "modal_reference" readonly = "readonly">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">Activity type: </div>
                        <div class="col-sm-6">
                            <input type="text" class="form-control modal-control" id = "modal_type" name = "modal_type" readonly = "readonly">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">Date: </div>
                        <div class="col-sm-6">
                            <input type="text" class="form-control modal-control" id = "modal_date" name = "modal_date" readonly = "readonly">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">Time: </div>
                        <div class="col-sm-6">
                            <input type="text" class="form-control modal-control" id = "modal_time" name = "modal_time" readonly = "readonly">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">Operator: </div>
                        <div class="col-sm-6">
                            <input type="text" class="form-control modal-control" id = "modal_operator" name = "modal_operator" readonly = "readonly">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">Amount: </div>
                        <div class="col-sm-6">
                            <input type="text" class="form-control modal-control" id = "modal_amount" name = "modal_amount" readonly = "readonly">
                        </div>
                    </div>
                    <hr>
                    <h6 class="text-semibold details">DISPUTE COMMENT</h6>                    
                    <p>Enter your reason for disputing this all record. Please include all detils.</p>
                    <textarea rows="5" cols="5" class="form-control" placeholder="Type comment here..." id = "comment" name = "comment"></textarea>

                    <div class="alert alert-danger display-hide" style="display: none;" id="modal-comment-error">
                        <button class="close" data-close="alert"></button>
                        <span> You must enter at least 320 characters. We need sufficient details for dispute analytics. </span>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal" onclick="closeModal();">Close</button>
                    <button type="button" class="btn btn-primary" onclick = "onValidateComment();">Submit</button>
                </div>
            </div>
        </div>
    </div>-->
    <!-- /Dispute modal -->


    <!-- Horizontal form modal -->
    <div id="modal_form_horizontal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Record Detail</h5>
                </div>

                <form action="#" class="form-horizontal">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label col-sm-6">Record NO:</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control modal-control" id = "modal_id" name = "modal_id" readonly = "readonly">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-sm-6">Activity reference:</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control modal-control" id = "modal_reference" name = "modal_reference" readonly = "readonly">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-sm-6">Activity type:</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control modal-control" id = "modal_type" name = "modal_type" readonly = "readonly">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-6">Date:</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control modal-control" id = "modal_date" name = "modal_date" readonly = "readonly">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-6">Time:</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control modal-control" id = "modal_time" name = "modal_time" readonly = "readonly">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-6">Operator:</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control modal-control" id = "modal_operator" name = "modal_operator" readonly = "readonly">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-6">Amount:</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control modal-control" id = "modal_amount" name = "modal_amount" readonly = "readonly">
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /horizontal form modal -->


    <!-- Footer -->
    <div class="footer text-muted">
        &copy; 2017. <a href="#">Tea Era</a> by <a href="" target="_blank">Tea Era</a>
    </div>
    <!-- /footer -->

</div>    
     
@stop