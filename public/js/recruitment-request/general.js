function detail(id) {
    $.ajax({
        url: url,
        type: "GET",
        data:{'_token':"{{csrf_token()}}", 'id':id},
        dataType: "JSON",
        contentType: "application/json",
        success: function (data) {
            console.log(data);
            if(data!=null && data!= ''){
                $('#approval_request_number').html(data.request_number);
                $('#approval_position').html(data.position);
                $('#approval_branch').html(data.branch.name);
                $('#approval_date_request').html(data.request_date);
                var dot_hr,status_hr;
                if(data.approval_hr == null){
                    dot_hr = "dot-waiting";
                    status_hr = 'Waiting';
                }
                else if(data.approval_hr == 0){
                    dot_hr = "dot-rejected";
                    status_hr = 'Rejected';
                }
                else{
                    dot_hr = "dot-approved";
                    status_hr = 'Approved';
                }
                var approver = (data.approver!=null)?data.approver.name:'';
                var html_approval = '<div class="col-xs-1" style="padding: 0px">'+
                    '                                    <span class="'+dot_hr+' centerize"></span>'+
                    '                                    <div class="vl" style="margin: 7px"></div>'+
                    '                                </div>';
                html_approval += '<div class="col-xs-11" style="padding: 0px">'+
                    '                                    <b class="centerize">Approval HR</b>'+
                    '                                    <table class="table-status" width="100%">'+
                    '                                        <tr>'+
                    '                                            <td width="30%">Status</td>'+
                    '                                            <td width="20"> : </td>'+
                    '                                            <td>'+status_hr+'</td>'+
                    '                                        </tr>';
                if(data.approval_hr != null){
                    html_approval += '<tr>'+
                        '                                            <td>Date Approval</td>'+
                        '                                            <td> : </td>'+
                        '                                            <td>'+data.approval_hr_date+'</td>'+
                        '                                        </tr>'+
                        '                                        <tr>'+
                        '                                            <td>Approved By</td>'+
                        '                                            <td> : </td>'+
                        '                                            <td>'+approver+'</td>'+
                        '                                        </tr>';
                }
                html_approval += '</table>' +
                    '                                </div>';
                if(data.approval_hr != 0 && data.approvals.length>0){
                    var dot_user,status_user;
                    if(data.approval_user == null){
                        dot_user = "dot-waiting";
                        status_user = 'Waiting';
                    }
                    else if(data.approval_user == 0){
                        dot_user = "dot-rejected";
                        status_user = 'Rejected';
                    }
                    else{
                        dot_user = "dot-approved";
                        status_user = 'Approved';
                    }
                    html_approval += '<div class="col-xs-1" style="padding: 0px">' +
                        '                                    <span class="'+dot_user+' centerize"></span>' +
                        '                                </div>' +
                        '                                <div class="col-xs-11" style="padding: 0px">' +
                        '                                    <b class="centerize">Approval User</b>' +
                        '                                    <table class="table-status" width="100%">' +
                        '                                        <tr>' +
                        '                                            <td width="30%">Status</td>' +
                        '                                            <td width="20"> : </td>' +
                        '                                            <td>'+status_user+'</td>' +
                        '                                        </tr>' +
                        '                                    </table>' +
                        '<div class="form-group col-lg-12" style="margin-left: -15px">';
                    for(var i = 0; i < data.approvals.length; i++){
                        var approval = data.approvals[i];
                        if(approval.is_approved == null){
                            dot_user = "dot-waiting";
                            status_user = 'Waiting';
                        }
                        else if(approval.is_approved == 0){
                            dot_user = "dot-rejected";
                            status_user = 'Rejected';
                        }
                        else{
                            dot_user = "dot-approved";
                            status_user = 'Approved';
                        }

                        html_approval += '<div class="col-xs-1" style="padding: 0px">'+
                            '                                    <span class="'+dot_user+' centerize"></span>'+
                            '                                    <div class="vl" style="margin: 7px"></div>'+
                            '                                </div>';
                        html_approval += '<div class="col-xs-11" style="padding: 0px">'+
                            '                                    <b class="centerize"> Approval '+approval.setting_approval_level_id+' (' +approval.position+')</b>'+
                            '                                    <table class="table-status" width="100%">'+
                            '                                        <tr>'+
                            '                                            <td width="30%">Status</td>'+
                            '                                            <td width="20"> : </td>'+
                            '                                            <td>'+status_user+'</td>'+
                            '                                        </tr>';
                        if(approval.is_approved != null){
                            html_approval += '<tr>'+
                                '                                            <td>Date Approval</td>'+
                                '                                            <td> : </td>'+
                                '                                            <td>'+approval.date_approved+'</td>'+
                                '                                        </tr>'+
                                '                                        <tr>'+
                                '                                            <td>Approved By</td>'+
                                '                                            <td> : </td>'+
                                '                                            <td>'+approval.user_approved.name+'</td>'+
                                '                                        </tr>';
                        }
                        html_approval += '</table>' +
                            '                                </div>';
                    }
                    html_approval += '</div>';
                }

                $("#approval").html(html_approval);


                $('#modal-detail').modal('show');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });


}