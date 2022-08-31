@extends('layouts.karyawan')

@section('title', 'Cash Advance')

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
                    <h4 class="page-title">Form Cash Advance</h4> </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                    <ol class="breadcrumb">
                        <li><a href="javascript:void(0)">Dashboard</a></li>
                        <li class="active">Cash Advance</li>
                    </ol>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- .row -->
            <div class="row">
                <form class="form-horizontal" autocomplete="off" id="form_cash_advance" enctype="multipart/form-data" action="{{ route('karyawan.cash-advance.store') }}" method="POST">
                    <div class="col-md-12">
                        <div class="white-box">
                            <h3 class="box-title m-b-0">Data Cash Advance</h3>
                            <br />
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
                            <div class="col-md-6" style="padding-left:0;">
                                <div class="form-group">
                                    <label class="col-md-12">From</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" value="{{ Auth::user()->nik .' / '. Auth::user()->name  }}" readonly="true">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-6">To : Accounting Department</label>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Purpose</label>
                                    <div class="col-md-10">
                                        <textarea class="form-control" name="tujuan"></textarea>
                                    </div>
                                </div>
                                <hr />
                                <div class="form-group">
                                    <label class="col-md-12">Payment Method</label>
                                    <div class="col-md-12">
                                        <label style="font-weight: normal;"><input type="radio" name="payment_method" value="Cash" /> Cash</label> &nbsp;&nbsp;
                                        <label style="font-weight: normal;"><input type="radio" name="payment_method" value="Bank Transfer" /> Bank Transfer</label>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-12">Name of Account</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" readonly="true" value="{{ Auth::user()->nama_rekening }}" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Account Number</label>
                                    <div class="col-md-12">
                                        <input type="number" class="form-control" readonly="true" value="{{ Auth::user()->nomor_rekening }}" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Name Of Bank</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" readonly="true" value="{{ isset(Auth::user()->bank) ? Auth::user()->bank->name : '' }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <hr />
                            <div class="table-responsive">
                                <table class="table table-hover manage-u-table">
                                    <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>TYPE</th>
                                        <th>{{get_setting('period_ca_pr') == 'yes' ? 'AVAILABLE ' : '' }} PLAFOND (IDR)</th>
                                        <th>DESCRIPTION</th>
                                        {{-- <th>QUANTITY</th> --}}
                                        <!--<th>ESTIMATION COST</th>-->
                                        <th>AMOUNT (IDR)</th>
                                        <th>AMOUNT APPROVED (IDR)</th>
                                        {{-- <th>RECEIPT TRANSACTION</th> --}}
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody class="table-content-lembur">
                                    <tr class="oninput">
                                        <td class="nomor">1</td> 
                                        <td>
                                            <div class="col-md-10" style="padding-left:0;">
                                                <select name="type[]" class="form-control type_form input" onchange="select_type_(this)">
                                                    <option value=""> - Select Type - </option>
                                                    @forelse($type as $t)
                                                    <option value="{{$t->type}}" data-plafond="{{$t->plafond}}">{{$t->type}}</option>
                                                    @empty
                                                    <option>Parking</option>
                                                    <option>Gasoline</option>
                                                    <option>Toll</option>
                                                    <option>Transportation</option>
                                                    <option>Transport(Overtime)</option>
                                                    <option>Others</option>
                                                    @endforelse
                                                </select>
                                            </div>
                                            <div class="content_bensin"></div>
                                            <div class="content_overtime"></div>
                                        </td>
                                        <td>
                                            <input type="hidden" class="form-control plafond_value input" name="plafond[]" readonly="true">
                                            <input type="text" class="form-control sisa_plafond_value input" name="sisa_plafond[]" readonly="true">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control input" name="description[]">
                                        </td>
                                        {{--<td>
                                            <input type="number" name="quantity[]" min="1" value="1" class="form-control input">
                                        </td>--}}
                                        <td>
                                            <input type="text" name="amount[]" min="1" class="form-control input amount price_format" onchange="cek_amount(this)">
                                        </td>
                                        <td>
                                            <input type="number" name="amount_approved[]" min="1" class="form-control" readonly="true">
                                        </td>
                                        <td id="showhide"><a class="btn btn-xs btn-danger" onclick="delete_item(this);"><i class="fa fa-trash"></i></a></td>
                                        {{-- <td>
                                            <input type="file" name="file_struk[]" class="form-control input"  accept="image/jpeg,image/gif,image/png,application/pdf,image/x-eps">
                                        </td> --}}
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                    <tr style="background: #eee;">
                                        <th colspan="4" class="text-right" style="font-size: 14px;">Total: </th>
                                        <th class="total_amount" style="font-size: 14px;" colspan="3">0</th>
                                    </tr>
                                    </tfoot>
                                </table>
                                <a class="btn btn-info btn-xs pull-right" id="add"><i class="fa fa-plus"></i> Add</a>
                            </div>
                            <div class="clearfix"></div>
                            <br />

                            <a href="{{ route('karyawan.cash-advance.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Cancel</a>
                            <a class="btn btn-sm btn-success waves-effect waves-light m-r-10" id="submit_cash_advance"><i class="fa fa-save"></i> Submit Cash Advance</a>
                            <br style="clear: both;" />
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.row -->
            <!-- ============================================================== -->
        </div>
        <!-- /.container-fluid -->
        @include('layouts.footer')
    </div>

    <!-- sample modal content -->
    <div id="modal_overtime" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Data Overtime</h4> </div>
                <div class="modal-body">
                    <div class="form-horizontal modal-form-overtime">
                        @if(!data_overtime_user_cash_advance(Auth::user()->id))
                            <p><i>No Data Overtime</i></p>
                        @endif

                        @if(data_overtime_user_cash_advance(Auth::user()->id))
                            <table class="table tabl-hover">
                                <thead>
                                <tr>
                                    <th width="50">NO</th>
                                    <th>DATE</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach(data_overtime_user_cash_advance(Auth::user()->id) as $item)
                                    <?php if($item->is_payment_request != ""){ continue; } ?>
                                    <tr>
                                        <td><input type="checkbox" name="overtime_item" value="{{ $item->id }}"></td>
                                        <td>{{ $item->created_at }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect btn-sm" id="btn_cancel_overtime" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-info btn-sm" id="add_overtime">Tambah</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <!-- sample modal content -->
    <div id="modal_bensin" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Data Gasoline</h4> </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="form_modal_bensin">
                        <div class="form-group">
                            <label class="col-md-12">Date of purchase of gasoline</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control datepicker modal_tanggal_struk_bensin" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12">Odometer (KM)</label>
                            <div class="col-md-6">
                                <input type="number" class="form-control modal_odo_from" placeholder="From Odo Meter" />
                            </div>
                            <div class="col-md-6">
                                <input type="number" class="form-control modal_odo_to" placeholder="To Odo Meter" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Liter</label>
                            <div class="col-md-12">
                                <input type="number" class="form-control modal_liter" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Cost (IDR)</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control price_format modal_cost" />
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect btn-sm" id="btn_cancel_bensin" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info btn-sm" id="add_modal_bensin">Add</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@section('footer-script')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript">
        jQuery('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
        });

        $("#add").click(function(){
            var no = $('.table-content-lembur tr').length;
            if((no+1) <= 15) {
            var html = '<tr class="oninput">';
                html += '<td class="nomor">'+ (no+1) +'</td>';
                html += '<td><div class="col-md-10" style="padding-left:0;">\
                                <select name="type[]" class="form-control type_form input" onchange="select_type_(this)">\
                                <option value=""> - Select Type - </option>'+
                                '@forelse($type as $t)'+
                                '<option value="{{$t->type}}" data-plafond="{{$t->plafond}}">{{$t->type}}</option>'+
                                '@empty @endforelse';

                html += '<div class="content_bensin"></div><div class="content_overtime"></div></td>';
                html += '<td><input type="hidden" class="form-control plafond_value input" name="plafond[]" readonly="true"/><input type="text" class="form-control sisa_plafond_value input" name="sisa_plafond[]" readonly="true"/></td>';
                html += '<td class="description_td"><input type="text" class="form-control input" name="description[]"></td>';
                // html += '<td><input type="number" name="quantity[]" value="1" class="form-control input" /></td>';
                //html += '<td><input type="number" name="estimation_cost[]" class="form-control estimation" /></td>';
                html += '<td><input type="text" name="amount[]" min="1" class="form-control amount price_format" onchange="cek_amount(this)"/></td>';
                html += '<td><input type="number" name="amount_approved[]" class="form-control" readonly="true" /></td>';
                //html += '<td><input type="file" name="file_struk[]" class="form-control input" accept="image/jpeg,image/gif,image/png,application/pdf,image/x-eps"/></td>';
                html += '<td id="showhide"><a class="btn btn-xs btn-danger" onclick="delete_item(this);"><i class="fa fa-trash"></i></a></td>';
                html += '</tr>';

            $('.table-content-lembur').append(html);

            $('.estimation').on('input', function(){

                var total = 0;

                $('.estimation').each(function(){

                    if($(this).val() != "")
                    {
                        total += parseInt($(this).val());
                    }
                });

                $('.total').html('Rp. '+ numberWithComma(total).replace(/,/g, "."));
            });
            price_format();

            $(".amount").on('input', function(){
                calculate_amount();
            });
            show_hide_add();
            cek_button_add();
            calculate_amount();
            }
            else{
                alert('Maximal of items are 15, Please make a new form!')
            }
        });
    </script>
    <script src="{{ asset('js/cash-advance/karyawan.js') }}?v={{ date('ymdhis') }}"></script>
@endsection
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@endsection
