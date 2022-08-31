@extends('layouts.karyawan')

@section('title', 'Loan Payment')

@section('sidebar')

@endsection

@section('content')

  
        
<!-- ============================================================== -->
<!-- Page Content -->
<!-- ============================================================== -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title hidden-xs hidden-sm">Manage Loan Payment</h4> 
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb hidden-xs hidden-sm">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Loan Payment</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <div class="table-responsive">
                        <table id="myTable" class="table display nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>LOAN NUMBER</th>
                                    <th>TENOR</th>
                                    <th>DUE DATE</th>
                                    <th>AMOUNT (IDR)</th>
                                    <th>REFUND METHOD</th>
                                    <th>SUBMIT DATE</th>
                                    <th>PAYROLL</th>
                                    <th>STATUS</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> 
        </div>
        <!-- ============================================================== -->
    </div>
    <!-- /.container-fluid -->
    @include('layouts.footer')
</div>

<div class="modal fade none-border" id="modal-status">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Payment Approval</h4>
            </div>
            <div class="modal-body" id="modal_content_history_approval">
                <div class="panel-body" style="padding: 0 15px;">
                    <div class="steamline">
                        <div class="sl-item"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade none-border" id="modal-action">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><strong>Payment Detail</strong></h4>
            </div>
            <form id="optionForm">
                <input type="hidden" id="loan_payment_id" name="loan_payment_id">
                <div class="modal-body" id="modal-action-body">
                    <div class="form-group col-md-12">
                        <label>Payment Receipt <span class="text-danger">*</span></label>
                        <div>
                            <input type="file" id="photo" name="photo" class="form-control" accept="image/*, application/pdf" required />
                        </div>
                        <output id="result_photo" />
                    </div>
                    <div class="form-group col-md-12">
                        <label>Payment Date <span class="text-danger">*</span></label>
                        <div>
                            <input type="text" id="payment_date" name="payment_date" class="form-control datepicker" required />
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Payment Note <span class="text-danger">*</span></label>
                        <div>
                            <textarea id="user_note" name="user_note" class="form-control" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                    <button id="submit_payment" type="submit" class="btn btn-success save-event waves-effect waves-light btn-sm">Submit Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('footer-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script>
    var myTable = null

    $(document).ready(function() {
        initMyTable();
    });

    function initMyTable() {
        $('#myTable').DataTable().destroy();
        myTable = $('#myTable').DataTable({
            ajax: {
                "url": "{{ route('karyawan.loan-payment.table') }}",
                "type": "GET"
            },
            searching: false,
            paging: true,
            ordering: true,
            lengthChange: true,
            bInfo : true,
            fixedHeader: true,
            scrollCollapse: true,
            scrollX: true,
            columns: [
                { "data": "loan.number", "name":'id' },
                { "data": "tenor" },
                { "data": "due_date" },
                { "data": "column_amount" },
                { "data": "column_payment_type", "name":'loan.payment_type' },
                { "data": "submit_date" },
                { "data": "column_payroll" },
                { "data": "column_status", "orderable": false },
                { "data": "column_action", "orderable": false },
            ]
        });

        $('#myTable tbody').on('click', 'button', function () {
            var data = myTable.row($(this).parents('tr')).data()
            if (this.id == 'status')
                optionStatus(data)
            else
                checkAction(data)
        })
    }

    function checkAction(params) {
        if (params.loan.payment_type == 1 && !params.status) {
            swal({
                text: 'You have selected Deduct Salary payment option are you sure you want to do Transfer to Company?',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            }).then((willSubmit) => {
                if (willSubmit) {
                    optionAction(params)
                }
            });
        } else {
            optionAction(params)
        }
    }

    function optionStatus(data) {
        if (!data.status) {
            $('.sl-item').html('<div class="sl-left bg-default"><i class="fa fa-ban" style="line-height: 40px !important;"></i></div>'+
                '<div class="sl-right">'+
                    '<div>'+
                        '<a href="#">HR</a>'+
                    '</div>'+
                    '<div>'+
                        '<p>-</p>'+
                        '<br>'+
                    '</div>'+
                '</div>')
        } else if (data.status == 1) {
            $('.sl-item').html('<div class="sl-left bg-warning"><i class="fa fa-info" style="line-height: 40px !important;"></i></div>'+
                '<div class="sl-right">'+
                    '<div>'+
                        '<a href="#">LOAN HR</a>'+
                    '</div>'+
                    '<div>'+
                        @foreach(getAdminByModule(33) as $val)
                        '<p>{{ $val->name }}</p>'+
                        @endforeach
                        '<br>'+
                    '</div>'+
                '</div>')
        } else if (data.status == 2) {
            $('.sl-item').html('<div class="sl-left bg-success"><i class="fa fa-check" style="line-height: 40px !important;"></i></div>'+
                '<div class="sl-right">'+
                    '<div>'+
                        '<a href="#">LOAN HR</a>'+
                    '</div>'+
                    '<div>'+
                        '<div>'+data.approver.name+'</div>'+
                        data.approval_date+
                        '<p>'+data.approval_note+'</p>'+
                        '<br>'+
                    '</div>'+
                '</div>')
        } else if (data.status == 3) {
            $('.sl-item').html('<div class="sl-left bg-danger"><i class="fa fa-close" style="line-height: 40px !important;"></i></div>'+
                '<div class="sl-right">'+
                    '<div>'+
                        '<a href="#">LOAN HR</a>'+
                    '</div>'+
                    '<div>'+
                        '<div>'+data.approver.name+'</div>'+
                        data.approval_date+
                        '<p>'+data.approval_note+'</p>'+
                        '<br>'+
                    '</div>'+
                '</div>')
        } else if (data.status == 4) {
            $('.sl-item').html('<div class="sl-left bg-warning"><i class="fa fa-info" style="line-height: 40px !important;"></i></div>'+
                '<div class="sl-right">'+
                    '<div>'+
                        '<a href="#">PAYROLL HR</a>'+
                    '</div>'+
                    '<div>'+
                        @foreach(getAdminByModule(13) as $val)
                        '<p>{{ $val->name }}</p>'+
                        @endforeach
                        '<br>'+
                    '</div>'+
                '</div>')
        } else if (data.status == 5) {
            $('.sl-item').html('<div class="sl-left bg-success"><i class="fa fa-check" style="line-height: 40px !important;"></i></div>'+
                '<div class="sl-right">'+
                    '<div>'+
                        '<a href="#">PAYROLL HR</a>'+
                    '</div>'+
                    '<div>'+
                        '<div>'+ (data.approver != null ? data.approver.name : 'AUTO LOCK SYSTEM') +'</div>'+
                        '<br>'+
                    '</div>'+
                '</div>')
        }

        $('#modal-status').modal('show');
    }

    var result = '';

    $(document).on('hide.bs.modal', '#modal-action', function () {
        $('#result_photo').html(result = '')
        $('#modal-action #photo').val('').removeAttr('disabled').attr('required', true)
        $('#modal-action #payment_date').val('').removeAttr('disabled')
        $('#modal-action #user_note').val('').removeAttr('disabled')
        $('#modal-action #loan_payment_id').val('')
        $('#modal-action #submit_payment').show()
    })

    function optionAction(data) {
        if (data.status) {
            img = "{{ asset('storage/file-loan-payment/') }}/"+data.photo;
            var ext = img.split('.').pop().toLowerCase();
            if(ext === 'pdf'){
                div = '<div><embed src="'+img+'" frameborder=\'0\' width=\'100%\' height=\'400px\'></div>';
            } else {
                div = '<div><img src="'+img+'" style=\'width: 100%;\' /></div>';
            }
            $('#result_photo').html(result = div)
            $('#modal-action #payment_date').val(data.payment_date)
            $('#modal-action #user_note').val(data.user_note)
            if (data.status != 3) {
                $('#modal-action #photo').attr('disabled', true)
                $('#modal-action #payment_date').attr('disabled', true)
                $('#modal-action #user_note').attr('disabled', true)
                $('#modal-action #submit_payment').hide()
            } else {
                $('#modal-action #photo').removeAttr('required')
            }
        }
        $('#modal-action #loan_payment_id').val(data.id)
        $('#modal-action').modal('show');
    }

    $("#optionForm").submit(function(e) {
        e.preventDefault();
        optionSubmit(this);
    });

    function optionSubmit(params) {
        let form_data = new FormData(params);
        
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ URL::to('karyawan/loan/pay/') }}/" + $('#modal-action #loan_payment_id').val(),
            method: 'POST',
            data: form_data,
            contentType: false,
            processData: false,
            success: function(response){
                $('#modal-action').modal('hide');
                myTable.ajax.reload()
                swal(response.type, response.title, response.type)
            }
        })
    }
</script>
@endsection
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@endsection
