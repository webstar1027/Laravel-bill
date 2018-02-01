$(document).ready(function(){
    $('.from').datepicker({
        format: "yyyy-mm",
        viewMode: "months", 
        minViewMode: "months"
    }).on('changeDate', function(selected){
    });

    $( ".filter-control" ).change(function() {
        document.location = "/records/" + $("#month").val() + "/" + $("#phone").val()
    });

})

function showFillDispute(id, datetime, reference, type, operator, amount) {
    $("#record_id").val(id);
    $("#modal_reference").val(reference);
    $("#modal_reference2").val(reference);
    $("#modal_type").val(type);

    var dateformat = new Date(datetime);
    var date = dateformat.getFullYear() + "/" +(dateformat.getMonth() + 1) + "/" + dateformat.getDate();
    var time = dateformat.getHours() + "/" + dateformat.getMinutes() + "/" + dateformat.getSeconds();
    $("#modal_date").val(date);
    $("#modal_time").val(time);
    $("#modal_operator").val(operator);
    $("#modal_amount").val(amount);
    
}


function onValidateComment() {
    var comment = $("#comment").val();
    if (comment.length < 320) {
        $("#modal-comment-error").show();
        return;
    }
    $("#modal-comment-error").hide();
    $("#modal-1").hide();
    $("#modal-2").show();
    $("p#str_dispute:last").html($("#comment").val());
}

function onSendToken() {
    var phone = $("#phone").val();
    var _token = $("#_token").val();
    $.post("/sendtoken", 
        {phone: phone, _token: _token}, 
        function(result){
            if (result == "OK") {
                $("#modal-2").hide();
                $("#modal-3").show();
            }
            else {
                $("#modal-token-error").show(); 
            }
    });
}

function onVerifyToken() {
    var record_id = $("#record_id").val();
    var sms_token = $("#modal_sms_token").val();
    var email_token = $("#modal_email_token").val();
    var comment = $("#comment").val();
    var amount = $("#modal_amount").val();
    var _token = $("#_token").val();

    if (sms_token == "") {
        $("#modal-dispute-error").show(); 
        return;
    }
    if (email_token == "") {
        $("#modal-dispute-error").show(); 
        return;
    }

    $.post("/filldispute", 
        {record_id: record_id, comment: comment, amount: amount, _token: _token, sms_token: sms_token, email_token: email_token}, 
        function(result){
            if (result == "OK") {
                $("#modal-3").hide();
                $("#modal-4").show();
            }
            else {
                $("#modal-dispute-error").show(); 
            }
    });

}


function closeModal() {
    $("#modal-1").show();
    $("#modal-2").hide();
    $("#modal-3").hide();
    $("#modal-4").hide();
}

function showRecordDetail(record_id) {
    var _token = $("#_token").val();

    $.ajax({
        url: '/getrecorddetail',
        type: 'POST',
        data: {_token: _token, record_id: record_id},
        dataType: 'JSON',
        success: function (data) {
            $("#modal_id").val(data.id);
            $("#modal_reference").val(data.reference);
            $("#modal_type").val(data.call_type);

            var dateformat = new Date(data.created_at);
            var date = dateformat.getFullYear() + "/" +(dateformat.getMonth() + 1) + "/" + dateformat.getDate();
            var time = dateformat.getHours() + "/" + dateformat.getMinutes() + "/" + dateformat.getSeconds();
            $("#modal_date").val(date);
            $("#modal_time").val(time);
            $("#modal_operator").val(data.operator);
            $("#modal_amount").val(data.number);
        }
    });
}