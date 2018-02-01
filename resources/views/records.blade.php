@extends('layouts.master')

@section('content')
<script>

</script>

<div class="content">
    
    <div class="page-header-content">
        <div class="page-title">
            <h4>Call Records</h4>
        </div>
    </div>
    @if($error_messages = Session::get('error_messages'))
        @foreach($error_messages as $key=>$message)
            <div class="alert alert-danger no-border">
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
                {{$message}}
            </div>
        @endforeach
    @endif
    <div class="row filter-control">
        <form action="/records/pay" method="post">
            <input type="hidden" id = "_token" name="_token" value="{{ csrf_token() }}">
            <div class = "col-sm-3">
                <div class="has-feedback has-feedback-left">
                    <input type="text" class="form-control filter-control from" id = "month" name = "month" readonly="readonly" value="{{$selected_month}}">
                    <div class="form-control-feedback">
                        <i class="icon-calendar"></i>
                    </div>
                </div>
            </div>
            <div class = "col-sm-3">
                <div class="has-feedback has-feedback-left">
                    <div class="form-control-feedback">
                        <i class="icon-mobile position-left"></i>
                    </div>
                    <select class="filter-control form-control select" id = "phone" name = "phone">
                    @foreach($phones as $key=>$phone)
                        @if($phone->phone == $selected_phone)
                        <option value="{{$phone->phone}}" selected>{{$phone->phone}}</option>
                        @else
                        <option value="{{$phone->phone}}">{{$phone->phone}}</option>
                        @endif
                    @endforeach
                    </select>
                </div>
            </div>
            <div class = "col-sm-3">
                <h5 class="filter-control">
                    <i class="icon-cash3 position-left"></i>
                    NGN15256.20
                </h5>
            </div>
            <div class = "col-sm-3">
                <button type="submit" class="btn btn-success filter-control"><i class="icon-credit-card position-left"></i>Pay</button>
            </div>
        </form>
    </div>
        <!-- Filter -->    
        <!--<div class="breadcrumb-line breadcrumb-line-component">
            <ul class="breadcrumb-elements bread-filter">
                <li class="dropdown">
                    <div class="has-feedback has-feedback-left">
                        <input type="text" class="form-control filter-control from" id = "month" name = "month" readonly="readonly" value="{{$selected_month}}">
                        <div class="form-control-feedback">
                            <i class="icon-calendar"></i>
                        </div>
                    </div>
                </li>
                <li class="dropdown">
                    <div class="has-feedback has-feedback-left">
                        <div class="form-control-feedback">
                            <i class="icon-mobile position-left"></i>
                        </div>
                        <select class="filter-control form-control select" id = "phone" name = "phone">
                        @foreach($phones as $key=>$phone)
                            @if($phone == $selected_phone)
                            <option value="{{$phone->phone}}" selected>{{$phone->phone}}</option>
                            @endif
                            <option value="{{$phone->phone}}">{{$phone->phone}}</option>
                        @endforeach
                        </select>
                    </div>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="icon-cash3 position-left"></i>
                        NGN15256.20
                    </a>
                </li>
            </ul>
            <ul class="breadcrumb-elements">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle bg-green" data-toggle="dropdown">
                        <i class="icon-cash position-left"></i>
                        Pay
                    </a>
                </li>
            </ul>
        </div>-->
                

        <!-- /Filter -->    

        <!-- SMS Table --> 
    <div class="row">
        <div class="panel panel-flat">            
            <table class="table table-borderd  table-hover datatable-highlight">
                <thead>
                    <tr>
                        <th class="text-center table-header" colspan = 8>SMS MESSAGING</th>
                    </tr>
                    <tr class="text-center">
                        <th colspan = 2 class="text-center">Date</th>
                        <th>Time</th>
                        <th>Reference</th>
                        <th>Number</th>
                        <th>Destination</th>
                        <th>Operator</th>
                        <th>Charge</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($records as $key=>$record)
                    @if($record->call_type == 'sms')
                    <tr>
                        <td><button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal_default" onclick="showFillDispute('{{$record->id}}', '{{$record->created_at}}', '{{$record->reference}}', '{{$record->call_type}}', '{{$record->operator}}', '{{$record->charge}}')"> <i class="icon-help"></i></button</td>
                        <td>{{date('d/m/Y', strtotime($record->created_at))}}</td>
                        <td>{{date('HH:ii:ss', strtotime($record->created_at))}}</td>
                        <td>{{$record->reference}}</td>
                        <td>{{$record->number}}</td>
                        <td>{{$record->destination}}</td>
                        <td>{{$record->operator}}</td>
                        <td>{{$record->charge}}</td>
                    </tr>
                    @endif
                @endforeach

                </tbody>
            </table>
        </div>
        <!-- /SMS Table -->
    </div>

    <div class="row">
        <!-- Voice call Table -->            
        <div class="panel panel-flat">            
            <table class="table table-borderd table-hover datatable-highlight">
                <thead>
                    <tr>
                        <th class="text-center table-header" colspan = 8>Voice Call</th>
                    </tr>
                    <tr class="text-center">
                        <th colspan = 2 class="text-center">Date</th>
                        <th>Time</th>
                        <th>Reference</th>
                        <th>Duration</th>
                        <th>Destination</th>
                        <th>Operator</th>
                        <th>Charge</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($records as $key=>$record)
                    @if($record->call_type == 'voice')
                    <tr>
                        <td><button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal_default" onclick="showFillDispute('{{$record->id}}', '{{$record->created_at}}', '{{$record->reference}}', '{{$record->call_type}}', '{{$record->operator}}', '{{$record->charge}}')"> <i class="icon-help"></i></button</td>
                        <td>{{date('d/m/Y', strtotime($record->created_at))}}</td>
                        <td>{{date('HH:ii:ss', strtotime($record->created_at))}}</td>
                        <td>{{$record->reference}}</td>
                        <td>{{$record->number}}</td>
                        <td>{{$record->destination}}</td>
                        <td>{{$record->operator}}</td>
                        <td>{{$record->charge}}</td>
                    </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
        <!-- /Voice call Table -->            
    </div>

    <input type="hidden" id = "record_id" name = "record_id">

    <!-- Dispute modal -->
    <div id="modal_default" class="modal fade" data-backdrop="false">
        <div class="modal-dialog">
            <div class="modal-content" id = "modal-1" style="display: block;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" onclick="closeModal();">&times;</button>
                    <h5 class="modal-title">File a Dispute</h5>
                </div>

                <div class="modal-body">
                    <h6 class="text-semibold note">!IMPORTANT NOTE</h6>                    
                    <p>Please double check and verify your personal record before disputing a subscription activity record. If you dispute a voice call or SMS message to another number, the number <b>may be blacklisted and barred</b>, if determined to be fraudulent after our review. Be sure to verify properly before disputing an interaction with another phone number.</p>
                    <hr>

                    <h6 class="text-semibold details">ACTIVITY DETAILS</h6>
                    <form action="#" class="form-horizontal">
                        <div class="modal-body">
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
                    </form>
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
            <div class="modal-content" id = "modal-2" style="display: none;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" onclick="closeModal();">&times;</button>
                    <h5 class="modal-title">Dispute Review</h5>
                </div>
                <div class="alert alert-danger display-hide" style="display: none;" id="modal-token-error">
                    <button class="close" data-close="alert"></button>
                    <span> Failed to send Token to Email or SMS. </span>
                </div>
                <div class="modal-body">
                    <h6 class="text-semibold note">!IMPORTANT NOTE</h6>                    
                    <p>Please double check and verify your personal record before disputing a subscription activity record. If you dispute a voice call or SMS message to another number, the number <b>may be blacklisted and barred</b>, if determined to be fraudulent after our review. Be sure to verify properly before disputing an interaction with another phone number.</p>

                    <h6 class="text-semibold details">RECORD DETAILS</h6>
                    <div class="row">
                        <div class="col-sm-6">Activity reference: </div>
                        <div class="col-sm-6">
                            <input type="text" class="form-control modal-control" id = "modal_reference2" name = "modal_reference2" readonly = "readonly">
                        </div>
                    </div>
                    <h6 class="text-semibold details">DISPUTE COMMENTS</h6>                    
                    <p id="str_dispute"></p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal" onclick="closeModal();">Close</button>
                    <button type="button" class="btn btn-primary" onclick="onSendToken()">Proceed</button>
                </div>
            </div>
            <div class="modal-content" id = "modal-3" style="display: none;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" onclick="closeModal();">&times;</button>
                    <h5 class="modal-title">Dispute Authorization</h5>
                </div>
                <div class="alert alert-danger display-hide" style="display: none;" id="modal-dispute-error">
                    <button class="close" data-close="alert"></button>
                    <span> Failed to send Token to Email or SMS. </span>
                </div>
                <div class="modal-body">
                    <h6 class="text-semibold note">Enter SMS Token</h6>                    
                    <h7 class="text-semibold note">{{$selected_phone}}</h7>                    
                    <input type="text" class="form-control" id = "modal_sms_token" name = "modal_sms_token" required>

                    <h6 class="text-semibold note">Enter Email Token</h6>                    
                    <h7 class="text-semibold note">{{Auth::user()->email}}</h7>                    
                    <input type="text" class="form-control" id = "modal_email_token" name = "modal_email_token" required>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal" onclick="closeModal();">Close</button>
                    <button type="button" class="btn btn-primary" onclick = "onVerifyToken();">Proceed</button>
                </div>
            </div>
            <div class="modal-content" id = "modal-4" style="display: none;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" onclick="closeModal();">&times;</button>
                </div>

                <div class="modal-body">
                    <h6 class="text-semibold note">Dispute Successful</h6>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal" onclick="closeModal();">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /Dispute modal -->


    <!-- Footer -->
    <div class="footer text-muted">
        &copy; 2017. <a href="#">Tea Era</a> by <a href="" target="_blank">Tea Era</a>
    </div>
    <!-- /footer -->

</div>    
     
@stop