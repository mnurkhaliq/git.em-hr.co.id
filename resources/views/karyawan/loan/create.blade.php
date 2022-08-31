@extends('layouts.karyawan')

@section('title', 'Loan')

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
                <h4 class="page-title">Form Loan</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Loan</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <form class="form-horizontal" enctype="multipart/form-data" action="{{ route('karyawan.loan.store') }}" id="form-loan" method="POST"  autocomplete="off">
                <div class="col-md-12">
                    <div class="white-box">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                                </ul>
                            </div>
                        @endif
                        {{ csrf_field() }}
                        <h3>Form Loan</h3>
                        <br />
                        <div class="col-md-6" style="padding-left:0;">
                            <div class="form-group">
                                <label class="col-md-12">NIK / Employee Name</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" value="{{ Auth::user()->nik .' - '. Auth::user()->name }}" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Position</label>
                                <div class="col-md-12">
                                    <input type="text" readonly="true" class="form-control" value="{{ isset(Auth::user()->structure->position) ? Auth::user()->structure->position->name:''}}{{ isset(Auth::user()->structure->division) ? ' - '. Auth::user()->structure->division->name:''}}{{ isset(Auth::user()->structure->title) ? ' - '. Auth::user()->structure->title->name:'' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Loan Number</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12">Name of Account</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" value="{{ Auth::user()->nama_rekening }}" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Account Number</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" value="{{ Auth::user()->nomor_rekening }}" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Name Of Bank</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" value="{{ isset(Auth::user()->bank) ? Auth::user()->bank->name : '' }}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" style="padding-left:0;">
                            <div class="form-group">
                                <label class="col-md-12">Max Loan Plafond (IDR)</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" value="{{ format_idr($plafond) }}" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Available Loan Plafond (IDR)</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="plafond" value="{{ format_idr($available_plafond) }}" disabled>
                                </div>
                                <div class="col-md-3">
                                    <label class="btn btn-info" onclick="$('#modal-history').modal('show')" style="width: 100%; height: 38px;"><i class="fa fa-history"></i> History</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Amount (IDR)</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control price_format " name="amount" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Total Tenor(Month)</label>
                                <div class="col-md-12">
                                    <select class="form-control" name="rate" required>
                                        <option value="">Choose Tenor</option>
                                        @foreach($rate as $item)
                                        <option value="{{ $item->id }}" data-interest="{{ $item->interest }}">{{ $item->rate }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Interest(%)</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" id="interest" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Calculated (IDR)</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" id="calculated" disabled>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12">Purpose</label>
                                <div class="col-md-12">
                                    <select class="form-control" name="loan_purpose" required>
                                        <option value="">Choose Purpose</option>
                                        @foreach($purpose as $item)
                                        <option>{{ $item->purpose }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Request Date</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" value="{{ date('Y-m-d') }}" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Expected Cash Disbursement Date</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control datepicker" name="expected_disbursement_date" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Actual Cash Disbursement Date</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Refund Method</label>
                                <div class="col-md-12">
                                    <select class="form-control" name="payment_type" required>
                                        <option value="">Choose Refund Method</option>
                                        @foreach($payment as $key => $item)
                                        <option value="{{ $key }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <br />
                        <div class="col-md-12" style="padding-left:0;">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="term" value="1" required>
                                        <label class="form-check-label">
                                            I agree to <span style="cursor: pointer;" onclick="preview_term()"><b><u>Term & Condition</u></b></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 asset" style="padding-left:0; display: none;">
                            <div class="clearfix"></div>
                            <hr />
                            <h3>Collateral</h3>
                            <br />
                            @foreach($asset as $item)
                            <div class="form-group">
                                <label class="col-md-12">Photo {{ $item->name }}</label>
                                <div class="col-md-12">
                                    <div class="col-md-6">
                                        <input type="file" name="photos[{{ $item->name }}]" data-key="key-{{ $item->name }}" data-name="{{ $item->name }}" class="form-control image" accept="image/*, application/pdf" />
                                    </div>
                                    <div class="col-md-6">
                                        <a onclick="preview_image('{{ $item->name }}')" class="btn btn-default" style="display: none"><i class="fa fa-search-plus"></i> View</a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            <div class="form-group" style="margin-bottom: 0">
                                <label class="col-md-12" style="color: red">Upload 1 or more collateral data</label>
                            </div>
                            <div class="clearfix"></div>
                            <br />
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="collateral" value="1" required>
                                        <label class="form-check-label">
                                            I agree to <span style="cursor: pointer;" onclick="preview_collateral()"><b><u>Collateral Receipt</u></b></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="user_assign"/>
                        <input type="hidden" name="collateral_assign"/>
                        <div class="clearfix"></div>
                        <br />
                        <div class="form-group">
                            <div class="col-md-12">
                                <a href="{{ route('karyawan.loan.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Cancel</a>
                                <a class="btn btn-sm btn-success waves-effect waves-light m-r-10" id="btn_submit"><i class="fa fa-save"></i> Submit Loan</a>
                                <br style="clear: both;" />
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.row -->
        <!-- ============================================================== -->
    </div>
    <!-- /.container-fluid -->
    @extends('layouts.footer')
</div>

<div id="modal_file" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body form-horizontal">
                <output id="result_image" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div id="modal_assign" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Enter Signature</h4>
            </div>
            <div class="modal-body form-horizontal text center">
                <div id="enterSignature"></div>
                <div class="form-group" style="margin:10px; margin-bottom:0; margin-top:30px;">
                    <label>Or Upload Signature Image File</label>
                    <div>
                        <input type="file" id="uploadSignature" class="form-control" accept="image/*" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning waves-effect btn-sm" onclick="resetCanvas()">Reset</button>
                <button type="button" class="btn btn-danger waves-effect btn-sm" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary waves-effect btn-sm">Done</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div id="modal_term" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body form-horizontal">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h3 style="font-weight:bold">LOAN AGREEMENT</h3>
                    </div>
                    <div class="col-md-3">
                        Employee
                    </div>
                    <div class="col-md-9">
                        : {{ Auth::user()->nik .' - '. Auth::user()->name }}
                    </div>
                    <div class="col-md-3">
                        Position
                    </div>
                    <div class="col-md-9">
                        : {{ isset(Auth::user()->structure->position) ? Auth::user()->structure->position->name:''}}{{ isset(Auth::user()->structure->division) ? ' - '. Auth::user()->structure->division->name:''}}{{ isset(Auth::user()->structure->title) ? ' - '. Auth::user()->structure->title->name:'' }}
                    </div>
                </div>
                <hr style="height:2px; border-top:1px solid black; border-bottom:1px solid black;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-12" style="margin:0">Purpose</label>
                            <div class="col-md-12">
                                <h4 style="margin:0" id="loan_purpose"></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-12" style="margin:0">Refund Method</label>
                            <div class="col-md-12">
                                <h4 style="margin:0" id="payment_type"></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-12" style="margin:0">Amount</label>
                            <div class="col-md-12">
                                <h4 style="margin:0" id="amount"></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-12" style="margin:0">Total Tenor(Month)</label>
                            <div class="col-md-12">
                                <h4 style="margin:0" id="rate"></h4>
                            </div>
                        </div>
                    </div>
                </div>
                {!! get_setting('term_condition') !!}
                <br>
                <div class="row">
                    <div class="col-md-6 text-center">
                        <div><label>Requestor</label></div>
                        <img class="user_assign" style="height: 80px; width: auto;" />
                        <div style="font-weight:bold">{{ Auth::user()->name }}</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" onclick="resetCanvas()" data-dismiss="modal">Close</button>
                <button type="button" onclick="$('input[name=term]').prop('checked', false)" class="btn btn-danger waves-effect btn-sm" data-dismiss="modal">Cancel</button>
                <button type="button" onclick="showCanvas('term'); resetCanvas();" class="btn btn-primary waves-effect btn-sm" data-dismiss="modal">I Agree</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div id="modal_collateral" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body form-horizontal">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h3 style="font-weight:bold">COLLATERAL RECEIPT</h3>
                    </div>
                    <div class="col-md-3">
                        Employee
                    </div>
                    <div class="col-md-9">
                        : {{ Auth::user()->nik .' - '. Auth::user()->name }}
                    </div>
                    <div class="col-md-3">
                        Position
                    </div>
                    <div class="col-md-9">
                        : {{ isset(Auth::user()->structure->position) ? Auth::user()->structure->position->name:''}}{{ isset(Auth::user()->structure->division) ? ' - '. Auth::user()->structure->division->name:''}}{{ isset(Auth::user()->structure->title) ? ' - '. Auth::user()->structure->title->name:'' }}
                    </div>
                </div>
                <hr style="height:2px; border-top:1px solid black; border-bottom:1px solid black;">
                <div id="collateral_letter">{!! get_setting('collateral_receipt') !!}</div>
                <br>
                <div class="row">
                    <div class="col-md-6 text-center">
                        <div><label>Requestor</label></div>
                        <img class="collateral_assign" style="height: 80px; width: auto;" />
                        <div style="font-weight:bold">{{ Auth::user()->name }}</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" onclick="resetCanvas()" data-dismiss="modal">Close</button>
                <button type="button" onclick="$('input[name=collateral]').prop('checked', false)" class="btn btn-danger waves-effect btn-sm" data-dismiss="modal">Cancel</button>
                <button type="button" onclick="showCanvas('collateral'); resetCanvas();" class="btn btn-primary waves-effect btn-sm" data-dismiss="modal">I Agree</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade none-border" id="modal-history">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Remaining Loan History</h4>
            </div>
            <div class="modal-body">
                <div class="panel-body form-horizontal">
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="1%">No</th>
                                <th>Loan Number</th>
                                <th>Request Date</th>
                                <th>Status</th>
                                <th>Remaining Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($no = 0)
                            @foreach($history as $item)
                            @if ($item->status == 2 && !$item->payment->whereNotIn('status', [2, 5])->count())
                                @continue
                            @endif
                            <tr>
                                <td>{{ ++$no }}</td>
                                <td>{{ $item->number }}</td>
                                <td>{{ date('Y-m-d', strtotime($item->created_at)) }}</td>
                                <td>{!! status_loan($item->status) !!}</td>
                                <td>{{ $item->status == 1 ? $item->calculated_amount : $item->payment->whereNotIn('status', [2, 5])->sum('amount') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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

@section('footer-script')
<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/fabric.js/1.4.0/fabric.min.js'></script>
<style type='text/css'>
    #sheet {
        border:1px solid black;
    }
    .canvas-container {
        margin:0 auto ;
    }
</style>
<script>
    var canvas = null;
    var emptyCanvas = null;
    var collateral_letter_default = $('#collateral_letter').html();

    $("input[name='term'], input[name='collateral']").change(function() {
        if(this.checked) {
            if ($(this).attr('name') == 'term') {
                preview_term(true);
            } else {
                preview_collateral(true);
            }
        } else {
            if ($(this).attr('name') == 'term') {
                $(".user_assign").attr('src', 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
                $("input[name='user_assign']").val("");
            } else {
                $(".collateral_assign").attr('src', 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
                $("input[name='collateral_assign']").val("");
            }
        }
    });

    function showCanvas(type) {
        $('#modal_assign .btn-primary').unbind("click").click(function () {
            if (($('#sheet').length && document.getElementById("sheet").toDataURL() != emptyCanvas) || document.getElementById("uploadSignature").files.length != 0) {
                $('#modal_assign').modal('hide');

                setCanvas(type);
                
                if (type == 'term') {
                    preview_term();
                } else {
                    preview_collateral();
                }
            } else {
                bootbox.alert("Signature can't be blank!");
            }
        });

        $('#modal_assign .btn-danger').unbind("click").click(function () {
            resetCanvas();

            if (type == 'term') {
                $('input[name=term]').prop('checked', false)
            } else {
                $('input[name=collateral]').prop('checked', false)
            }
        });

        $('#modal_assign').modal({backdrop: 'static', keyboard: false}, 'show').css('overflow-y', 'auto');

        setTimeout(function(){
            canvas.calcOffset();
        }, 1000);
    }

    function resetCanvas() {
        $("#uploadSignature").val('');

        $('#modal_assign #enterSignature').html('<canvas id="sheet" width="550px" height="400"></canvas>');

        canvas = new fabric.Canvas("sheet");
        canvas.isDrawingMode = true;
        canvas.freeDrawingBrush.width = 5;
        canvas.freeDrawingBrush.color = "#000000";

        emptyCanvas = document.getElementById("sheet").toDataURL();
    }

    function calculate() {
        if ($("[name='rate']").val() != "") {
            let interest = $("[name='rate']").find(':selected').attr('data-interest')
            $("#interest").val(interest);
            if ($("[name='amount']").val() != "") {
                var value = $("[name='amount']").val();
                total_nominal = parseInt(value.split('.').join(''));
                $("#calculated").val(numberWithComma(parseFloat(total_nominal) + parseFloat(total_nominal * interest / 100)).replace(/,/g, "."))
            } else {
                $("#calculated").val("")
            }
        } else {
            $("[name='rate']").find(':selected').attr('data-interest')
            $("#interest").val("")
            $("#calculated").val("")
        }

        var value = $("[name='amount']").val();
        amount = parseFloat(value.split('.').join(''));
        var val = $("#plafond").val();
        plafond = parseFloat(val.split('.').join(''));

        if (parseFloat(amount) > parseFloat(plafond)) {
            $(".asset").show()
        } else {
            $(".asset").hide()
        }
    }

    $("[name='rate']").change(function () {
        calculate();
    })

    $("[name='amount']").keyup(function () {
        calculate();
    })

    function preview_image(params) {
        $("#key-"+params).siblings().hide();
        $("#key-"+params).show();
        $('#modal_file').modal('show');
    }

    function preview_term(agreement = false) {
        $("#loan_purpose").html($("select[name='loan_purpose'] option:selected").text());
        $("#payment_type").html($("select[name='payment_type'] option:selected").text());
        $("#amount").html($("input[name='amount']").val());
        $("#rate").html($("select[name='rate'] option:selected").text());

        if (agreement) {
            $('#modal_term .btn-default').hide();
            $('#modal_term .btn-primary').show();
            $('#modal_term .btn-danger').show();
        } else {
            $('#modal_term .btn-primary').hide();
            $('#modal_term .btn-danger').hide();
            $('#modal_term .btn-default').show();
        }

        $('#modal_term').modal({backdrop: 'static', keyboard: false}, 'show').css('overflow-y', 'auto');
    }
    
    function preview_collateral(agreement = false) {
        if (agreement) {
            $('#modal_collateral .btn-default').hide();
            $('#modal_collateral .btn-danger').show();
            $('#modal_collateral .btn-primary').show();
        } else {
            $('#modal_collateral .btn-primary').hide();
            $('#modal_collateral .btn-danger').hide();
            $('#modal_collateral .btn-default').show();
        }

        var collateral_asset = '<ol>';
        $(".image").filter(function() {
            return this.value != '';
        }).each(function () {
            collateral_asset += '<li>' + $(this).attr('data-name') + '</li>';
        });
        collateral_asset += '</ol>';

        $('#collateral_letter').html(collateral_letter_default.split('$collateral').join(collateral_asset));

        $('#modal_collateral').modal({backdrop: 'static', keyboard: false}, 'show').css('overflow-y', 'auto');
    }

    function setCanvas(type) {
        if ($('#sheet').length) {
            if (type == 'term') {
                $(".user_assign").attr('src', document.getElementById("sheet").toDataURL());
                $("input[name='user_assign']").val(document.getElementById("sheet").toDataURL());
            } else {
                $(".collateral_assign").attr('src', document.getElementById("sheet").toDataURL());
                $("input[name='collateral_assign']").val(document.getElementById("sheet").toDataURL());
            }
        } else {
            let reader = new FileReader();
            reader.readAsDataURL(document.getElementById('uploadSignature').files[0]);
            reader.onload = function () {
                if (type == 'term') {
                    $(".user_assign").attr('src', reader.result);
                    $("input[name='user_assign']").val(reader.result);
                } else {
                    $(".collateral_assign").attr('src', reader.result);
                    $("input[name='collateral_assign']").val(reader.result);
                }
            }
        }
    }

    window.onload = function() {
        //Check File API support
        if (window.File && window.FileList && window.FileReader) {
            $(".image").on("change", function(event) {
                var files = event.target.files; //FileList object
                var output = document.getElementById("result_image");
                var key = $(this).attr('data-key');
                $("#"+key).remove();
                if (files.length) {
                    $(this).parent().next().children().show();
                } else {
                    $(this).parent().next().children().hide();
                }
                for (var i = 0; i < files.length; i++) {
                    var file = files[i];
                    if (!file.type.match('image') && !file.type === 'application/pdf') {
                        $(this).parent().next().children().hide();
                        continue;
                    }
                    var picReader = new FileReader();
                    picReader.addEventListener("load", function(event) {
                        var picFile = event.target;
                        var div = document.createElement("div");
                        div.setAttribute("id", key);
                        if(!file.type.match('image')){
                            div.innerHTML = "<embed src='" + picFile.result + "' frameborder=\'0\' width=\'100%\' height=\'400px\' >";
                        } else {
                            div.innerHTML = "<img src='" + picFile.result + "' style=\'width: 100%;\' />";
                        }
                        output.insertBefore(div, null);
                    });
                    //Read the image
                    picReader.readAsDataURL(file);
                }
            });

            $("#uploadSignature").on("change", function(event) {
                var files = event.target.files; //FileList object
                var output = document.getElementById("enterSignature");
                $("#enterSignature canvas, #enterSignature div").remove();
                if (!files.length) {
                    resetCanvas();
                }
                for (var i = 0; i < files.length; i++) {
                    var file = files[i];
                    if (!file.type.match('image')) {
                        resetCanvas();
                        bootbox.alert("Can't upload other than image file!");
                        continue;
                    }
                    var picReader = new FileReader();
                    picReader.addEventListener("load", function(event) {
                        var picFile = event.target;
                        var div = document.createElement("div");
                        div.innerHTML = '<img src="' + picFile.result + '" width="550px" height="400" />';
                        output.insertBefore(div, null);
                    });
                    //Read the image
                    picReader.readAsDataURL(file);
                }
            });
        } else {
            console.log("Your browser does not support File API");
        }
    }

    $("#btn_submit").click(function(){
        if($("input[name='amount']").val() == "")
            $("input[name='amount']").parent().addClass('has-error');
        else
            $("input[name='amount']").parent().removeClass('has-error');

        if($("select[name='rate']").val() == "")
            $("select[name='rate']").parent().addClass('has-error');
        else
            $("select[name='rate']").parent().removeClass('has-error');

        if($("select[name='loan_purpose']").val() == "")
            $("select[name='loan_purpose']").parent().addClass('has-error');
        else
            $("select[name='loan_purpose']").parent().removeClass('has-error');

        if($("input[name='expected_disbursement_date']").val() == "")
            $("input[name='expected_disbursement_date']").parent().addClass('has-error');
        else
            $("input[name='expected_disbursement_date']").parent().removeClass('has-error');

        if($("select[name='payment_type']").val() == "")
            $("select[name='payment_type']").parent().addClass('has-error');
        else
            $("select[name='payment_type']").parent().removeClass('has-error');

        if(
            $("input[name='amount']").val() == "" ||
            $("select[name='rate']").val() == "" || 
            $("select[name='loan_purpose']").val() == "" || 
            $("input[name='expected_disbursement_date']").val() == "" || 
            $("select[name='payment_type']").val() == ""
        ) {
            bootbox.alert("Loan form data is incomplete !");
            return false;
        }

        if($(".asset").css('display') != 'none') {
            let error = false;
            if ($(".image").filter(function() {
                return this.value != ''
            }).length < 1) {
                $(".image").each(function() {
                    $(this).parent().addClass('has-error');
                    error = true;
                });
            } else {
                $(".image").each(function() {
                    $(this).parent().removeClass('has-error');
                });
            }

            if (error) {
                bootbox.alert("1 or more collateral data must be uploaded!");
                return false;
            }
        }

        if(!$("input[name='term']").is(':checked')) {
            bootbox.alert("Term & Condition agreement should be checked !");
            return false;
        }

        if(!$("input[name='collateral']").is(':checked') && $(".asset").css('display') != 'none') {
            bootbox.alert("Collateral Receipt agreement should be checked !");
            return false;
        }

        bootbox.confirm('Process Form Loan ?', function(result){
            if(result) {
                $("#form-loan").submit();
            }
        });
    });
</script>
@endsection
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@endsection
