@extends('layouts.karyawan')
@section('title', 'Business Trip')

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
                <h4 class="page-title"></h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Business Trip</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <form class="form-horizontal" id="form-actual-bill" enctype="multipart/form-data" action="{{ route('karyawan.training-custom.prosesclaim') }}" method="POST">
                <div class="col-md-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Form Actual Bill</h3>
                        <hr />
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

                        <?php 
                        $readonly = ''; 
                        if($data->status_actual_bill >= 2)
                        {
                            $readonly = ' readonly="true"'; 
                        }

                        ?>
                        <div class="table-responsive">
                            <table class="table data_table_no_pagging display nowrap" cellspacing="0" width="100%" {{$data->status_actual_bill <= 0 || $data->status_actual_bill == 4 || $data->status_actual_bill == 3 ? 'data-ordering=false' : ''}}>
                                <thead>
                                    <tr>
                                        <th colspan="8" style="background-color: rgba(120,130,140,.13)"><h5 style="margin: 0; font-weight: bold; text-transform: uppercase;">1. Accommodation & Transportation</h5></th>
                                    </tr>
                                    <tr>
                                        <th style="width: 12%">Date</th>
                                        <th style="width: 12%">Description</th>
                                        <th style="width: 16%">Claimed (IDR)</th>
                                        <th style="width: 27%">Approved (IDR)</th>
                                        <th style="width: 10%">Note</th>
                                        <th style="width: 10%">Note Approval</th>
                                        <th style="width: 10%">Receipt</th>
                                        <th style="width: 3%"></th>
                                    </tr>
                                </thead>
                                @if($data->status_actual_bill >0 && $data->status_actual_bill <3)
                                <tbody class="table-content-value">
                                    @foreach($data->training_acomodation as $key => $item)
                                        <tr>
                                            <td>{{ $item->date }}</td>
                                            <td>{{ isset($item->transportation_type)? $item->transportation_type->name:''}}</td>
                                            <td>{{ format_idr($item->nominal) }}</td>
                                            <td>{{ format_idr($item->nominal_approved) }} 
                                                @if(count($item->report) > 0)
                                                <label style="float: right;" onclick="show_history('{{$item->id}}', 'acomodation')" class="btn btn-info btn-xs">history</label>
                                                @endif
                                            </td>
                                            <td title="{{$item->note}}">{{ str_limit($item->note, $limit = 20, $end = '...')}}</td>
                                            <td title="{{$item->note_approval}}">{{ str_limit($item->note_approval, $limit = 20, $end = '...')}}</td>
                                            <td>
                                                @if(!empty($item->file_struk))
                                                <label onclick="show_img('{{ asset('storage/file-acomodation/'. $item->file_struk)  }}')" class="btn btn-info btn-xs"><i class="fa fa-image"></i> view</label>
                                                @endif
                                            </td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                @else
                                <tbody class="table-content-acomodation">
                                </tbody>
                                @endif
                                <tfoot>
                                    <tr>
                                    <th colspan="2" style="text-align: center;">Sub Total</th>
                                    <th class="sub_total_1">{{ format_idr($data->sub_total_1) }}</th>
                                    <th >{{ format_idr($data->sub_total_1_disetujui) }}</th>
                                    <th colspan="4"></th>
                                    </tr>
                                </tfoot>
                            </table>
                            @if($data->status_actual_bill < 1 || $data->status_actual_bill =="" || $data->status_actual_bill == 4 || $data->status_actual_bill == 3)
                            <a class="btn btn-info btn-xs pull-right" id="addAcomodation"><i class="fa fa-plus"></i> Add</a>
                            @endif
                        </div>
                        <div class="clearfix"></div>
                        <br />
                        <div class="table-responsive">
                            <table class="table data_table_no_pagging display nowrap" cellspacing="0" width="100%" {{$data->status_actual_bill <= 0 || $data->status_actual_bill == 4 || $data->status_actual_bill == 3 ? 'data-ordering=false' : ''}}>
                                <thead>
                                    <tr>
                                    <th colspan="13" style="background-color: rgba(120,130,140,.13)"><h5 style="margin: 0; font-weight: bold; text-transform: uppercase;">2. Meal Allowance</h5></th>
                                    </tr>
                                    <tr>
                                        <th rowspan="2" style="width: 12%">Date</th>
                                        <th rowspan="2" style="width: 12%">Plafond</th>
                                        <th colspan="2" style="text-align: center;">Morning (IDR)</th>
                                        <th colspan="2" style="text-align: center;">Afternoon (IDR)</th>
                                        <th colspan="2" style="text-align: center;">Evening (IDR)</th>
                                        <th rowspan="2" style="width: 10%">Note</th>
                                        <th rowspan="2" style="width: 10%">Note Approval</th>
                                        <th rowspan="2" style="width: 10%">Receipt</th>
                                        <th rowspan="2" style="width: 3%"></th>
                                    </tr>
                                    <tr>
                                        <th>Claimed</th>
                                        <th>Approved</th>
                                        <th>Claimed</th>
                                        <th>Approved</th>
                                        <th>Claimed</th>
                                        <th>Approved</th>
                                        
                                    </tr>
                                </thead>
                                @if($data->status_actual_bill >0 && $data->status_actual_bill <3)
                                <tbody class="table-content-value">
                                    @foreach($data->training_allowance as $key => $item2)
                                        <tr>
                                            <td>{{ $item2->date }}</td>
                                            <td>{{ format_idr($item2->meal_plafond) }}</td>
                                            <td>{{ format_idr($item2->morning) }}</td>
                                            <td>{{ format_idr($item2->morning_approved) }}</td>
                                            <td>{{ format_idr($item2->afternoon) }}</td>
                                            <td>{{ format_idr($item2->afternoon_approved) }}</td>
                                            <td>{{ format_idr($item2->evening) }}</td>
                                            <td>{{ format_idr($item2->evening_approved) }}
                                                @if(count($item2->report) > 0)
                                                <label style="float: right;" onclick="show_history('{{$item2->id}}', 'allowance')" class="btn btn-info btn-xs">h</label>
                                                @endif
                                            </td>
                                            <td title="{{$item2->note}}">{{ str_limit($item2->note, $limit = 20, $end = '...')}}</td>
                                            <td title="{{$item2->note_approval}}">{{ str_limit($item2->note_approval, $limit = 20, $end = '...')}}</td>
                                            <td>
                                                @if(!empty($item2->file_struk))
                                                <label onclick="show_img('{{ asset('storage/file-allowance/'. $item2->file_struk)  }}')" class="btn btn-info btn-xs"><i class="fa fa-image"></i> view</label>
                                                @endif
                                            </td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                @else
                                <tbody class="table-content-allowance">
                                </tbody>
                                @endif
                                <tfoot>
                                    <tr hidden="true"> 
                                        <th colspan="2"> Sub Total</th>
                                        <th class="totalMorning"> 0</th>
                                        <th class="totalMorningApproved"> 0</th>
                                        <th class="totalAfternoon"> 0</th>
                                        <th class="totalAfternoonApproved"> 0</th>
                                        <th class="totalEvening"> 0</th>
                                        <th colspan="3" class="totalEveningApproved"> 0</th>
                                    </tr>
                                    <tr>
                                        <th colspan="2" style="text-align: center;"> Sub Total Claimed</th>
                                        <th colspan="11" class="sub_total_2">{{ format_idr($data->sub_total_2) }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="2" style="text-align: center;"> Sub Total Approved</th>
                                        <th colspan="11" >{{ format_idr($data->sub_total_2_disetujui) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                            @if($data->status_actual_bill < 1 || $data->status_actual_bill =="" || $data->status_actual_bill == 4 || $data->status_actual_bill == 3)
                            <a class="btn btn-info btn-xs pull-right" id="addAllowance"><i class="fa fa-plus"></i> Add</a>
                            @endif
                        </div>
                    <div class="clearfix"></div>
                    <br />
                    <div class="table-responsive">
                            <table class="table data_table_no_pagging display nowrap" cellspacing="0" width="100%" {{$data->status_actual_bill <= 0 || $data->status_actual_bill == 4 || $data->status_actual_bill == 3 ? 'data-ordering=false' : ''}}>
                                <thead>
                                    <tr>
                                    <th colspan="8" style="background-color: rgba(120,130,140,.13)"><h5 style="margin: 0; font-weight: bold; text-transform: uppercase;">3. Daily Allowance</h5></th>
                                    </tr>
                                    <tr>
                                        <th style="width: 12%">Date</th>
                                        <th style="width: 12%">Plafond</th>
                                        <th style="width: 16%">Claimed (IDR)</th>
                                        <th style="width: 27%">Approved (IDR)</th>
                                        <th style="width: 10%">Note</th>
                                        <th style="width: 10%">Note Approval</th>
                                        <th style="width: 10%">Receipt</th>
                                        <th style="width: 3%"></th>
                                    </tr>
                                </thead>
                                @if($data->status_actual_bill >0 && $data->status_actual_bill <3)
                                <tbody class="table-content-value">
                                    @foreach($data->training_daily as $key => $item3)
                                        <tr>
                                            <td>{{ $item3->date }}</td>
                                            <td>{{ format_idr($item3->daily_plafond) }}</td>
                                            <td>{{ format_idr($item3->daily) }}</td>
                                            <td>{{ format_idr($item3->daily_approved) }} 
                                            @if(count($item3->report) > 0)
                                                <label style="float: right;" onclick="show_history('{{$item3->id}}', 'daily')" class="btn btn-info btn-xs">history</label>
                                            @endif
                                            </td>
                                            <td title="{{$item3->note}}">{{ str_limit($item3->note, $limit = 20, $end = '...')}}</td>
                                            <td title="{{$item3->note_approval}}">{{ str_limit($item3->note_approval, $limit = 20, $end = '...')}}</td>
                                            <td>
                                                @if(!empty($item3->file_struk))
                                                <label onclick="show_img('{{ asset('storage/file-daily/'. $item3->file_struk)  }}')" class="btn btn-info btn-xs"><i class="fa fa-image"></i> view</label>
                                                @endif
                                            </td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                @else
                                <tbody class="table-content-daily">
                                </tbody>
                                @endif
                                <tfoot>
                                    <tr>
                                    <th colspan="2" style="text-align: center;">Sub Total</th>
                                    <th class="sub_total_3">{{ format_idr($data->sub_total_3) }}</th>
                                    <th >{{ format_idr($data->sub_total_3_disetujui) }}</th>
                                    <th colspan="4"></th>
                                    </tr>
                                </tfoot>
                            </table>
                            @if($data->status_actual_bill < 1 || $data->status_actual_bill =="" || $data->status_actual_bill == 4 || $data->status_actual_bill == 3)
                            <a class="btn btn-info btn-xs pull-right" id="addDaily"><i class="fa fa-plus"></i> Add</a>
                            @endif
                        </div>
                    <div class="clearfix"></div>
                    <br />
                    <div class="table-responsive">
                        <table class="table data_table_no_pagging display nowrap" cellspacing="0" width="100%" {{$data->status_actual_bill <= 0 || $data->status_actual_bill == 4 || $data->status_actual_bill == 3 ? 'data-ordering=false' : ''}}>
                            <thead>
                                <tr>
                                    <th colspan="8" style="background-color: rgba(120,130,140,.13)"><h5 style="margin: 0; font-weight: bold; text-transform: uppercase;">4. Other</h5></th>
                                </tr>
                                <tr>
                                    <th style="width: 12%">Date</th>
                                    <th style="width: 12%">Description</th>
                                    <th style="width: 16%">Claimed (IDR)</th>
                                    <th style="width: 28%">Approved (IDR)</th>
                                    <th style="width: 10%">Note</th>
                                    <th style="width: 10%">Note Approval</th>
                                    <th style="width: 10%">Receipt</th>
                                    <th style="width: 3%"></th>
                                </tr>
                            </thead>
                             @if($data->status_actual_bill >0 && $data->status_actual_bill <3)
                                <tbody class="table-content-value">
                                    @foreach($data->training_other as $key => $item4)
                                        <tr>
                                            <td>{{ $item4->date }}</td>
                                            <td>{{ $item4->description }}</td>
                                            <td>{{ format_idr($item4->nominal) }}</td>
                                            <td>{{ format_idr($item4->nominal_approved) }} 
                                            @if(count($item4->report) > 0)
                                                <label style="float: right;" onclick="show_history('{{$item4->id}}', 'other')" class="btn btn-info btn-xs">history</label>
                                            @endif
                                            </td>
                                            <td title="{{$item4->note}}">{{ str_limit($item4->note, $limit = 20, $end = '...')}}</td>
                                            <td title="{{$item4->note_approval}}">{{ str_limit($item4->note_approval, $limit = 20, $end = '...')}}</td>
                                            <td>
                                                @if(!empty($item4->file_struk))
                                                <label onclick="show_img('{{ asset('storage/file-other/'. $item4->file_struk)  }}')" class="btn btn-info btn-xs"><i class="fa fa-image"></i> view</label>
                                                @endif
                                            </td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                @else
                            <tbody class="table-content-other">
                            </tbody>
                            @endif
                            <tfoot>
                                    <tr>
                                    <th colspan="2" style="text-align: center;">Sub Total</th>
                                    <th class="sub_total_4">{{ format_idr($data->sub_total_4) }}</th>
                                    <th >{{ format_idr($data->sub_total_4_disetujui) }}</th>
                                    <th colspan="4"> </th>
                                    </tr>
                            </tfoot>
                        </table>
                       @if($data->status_actual_bill < 1 || $data->status_actual_bill =="" || $data->status_actual_bill == 4 || $data->status_actual_bill == 3)
                        <a class="btn btn-info btn-xs pull-right" id="addOther"><i class="fa fa-plus"></i> Add</a>
                        @endif
                    </div>
                    <div class="clearfix"></div>
                    <br />

                        <div class="col-md-6 table-total" style="padding-left:0;">
                            <table class="table table-hover">
                                <tr>
                                    <th>Total Claimed (IDR)</th>
                                    <th style="text-align: left; width: 30%;" class="total_actual_bill">
                                        {{ format_idr($data->sub_total_1 + $data->sub_total_2 + $data->sub_total_3 + $data->sub_total_4) }}
                                    </th>
                                </tr>
                                <tr>
                                    <th>Cash Advance (IDR)</th>
                                    <th style="text-align: left; width: 30%;">{{ format_idr($data->pengambilan_uang_muka) }}</th>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6 table-total" style="padding-right:0;">
                            <table class="table table-hover">
                                <tr>
                                    <th>Total Approved (IDR)</th>
                                    <th style="text-align: left; width: 30%;" class="total_actual_bill_disetujui">
                                         {{ format_idr($data->sub_total_1_disetujui + $data->sub_total_2_disetujui + $data->sub_total_3_disetujui + $data->sub_total_4_disetujui) }}
                                    </th>
                                </tr>
                                <tr>
                                    @php( $total_reimbursement_disetujui = $data->sub_total_1_disetujui + $data->sub_total_2_disetujui + $data->sub_total_3_disetujui + $data->sub_total_4_disetujui - $data->pengambilan_uang_muka )
                                    @if($total_reimbursement_disetujui < 0)
                                    <th>Total Payment by Employee (IDR)</th>
                                    @php ($total_reimbursement_disetujui = abs($total_reimbursement_disetujui))
                                    @else
                                    <th>Total Payment by Company (IDR)</th>
                                    @endif
                                    <th style="text-align: left; width: 30%;" class="total_reimbursement_disetujui">
                                        {{ format_idr($total_reimbursement_disetujui) }}
                                    </th>
                                </tr>
                            </table>
                        </div>
                        <div class="clearfix"></div>
                        <br />
                        @foreach($data->historyApproval as $key => $item)
                            <div class="form-group">
                                <label class="col-md-12">Note Claim Approval {{$item->setting_approval_level_id}}</label>
                                <div class="col-md-12">
                                    <input type="text" readonly="true" class="form-control note" value="{{ $item->note_claim }}">
                                </div>
                            </div>
                        @endforeach
                        <div class="form-group">
                            @if($data->status_actual_bill==2 && ($total_reimbursement_disetujui != 0))
                                <div class="col-md-3">
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" {{$data->is_transfer_claim==1 ? 'checked disabled' : ''}} {{$total_reimbursement_disetujui < 0 ? 'required' : 'disabled'}} id="is_transfer_claim" name="is_transfer_claim" value="1" >
                                            <label class="form-check-label">
                                                Has been Proccessed
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3" id="disbursement_claim_div" {{ $data->disbursement_claim == NULL ? 'style=display:none;' : ''}}>
                                    <div class="form-group">
                                        <label class="col-md-12">Disbursement</label>
                                        <div class="col-md-12">
                                        <label style="font-weight: normal;"><input type="radio" id="disbursement_claim_id_next" name="disbursement_claim" {{ $data->disbursement_claim == 'Next Payroll' ? 'checked="true" disabled' : '' }} value="Next Payroll"/> Next Payroll</label>&nbsp;&nbsp;
                                            <label style="font-weight: normal;"><input type="radio" id="disbursement_claim_id" name="disbursement_claim" {{ $data->disbursement_claim == 'Transfer' ? 'checked="true" disabled' : '' }} value="Transfer"/> Transfer</label> 
                                        </div>
                                    </div>
                                </div>
                                @if($data->transfer_proof_claim == NULL)
                                <div class="col-md-6" id="transfer_proof_claim_div" {{ $data->transfer_proof_claim == NULL ? 'style=display:none;' : ''}}>
                                    <div class="form-group">
                                        <label class="col-md-12">Transfer Proof</label>
                                        <div class="col-md-8">
                                            <input type="file" id="transfer_proof_claim_by_admin" name="transfer_proof_claim_by_admin" {{$data->is_transfer_claim==1 ? 'disabled' : ''}} {{$total_reimbursement_disetujui < 0 ? '' : ''}} class="form-control " accept="image/*, application/pdf"/>
                                        </div>
                                        <div class="col-md-4">
                                            <a onclick="preview()" class="btn btn-default preview" style="display: none"><i class="fa fa-search-plus"></i> View</a>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <a onclick="show_proof('{{ $data->transfer_proof_claim }}')" class="btn btn-default btn-xs"><i class="fa fa-search-plus"></i>View </a>
                                @endif
                            @endif
                        </div>
                        <div class="clearfix"></div>
                        <hr style="margin-top:0;" />
                    
                    <input type="hidden" name="id" value="{{ $data->id }}" />
                    <input type="hidden" name="status_actual_bill" value="1">
                    <input type="hidden" name="sub_total_1" value="{{ $data->sub_total_1 }}" />
                    <input type="hidden" name="sub_total_2" value="{{ $data->sub_total_2 }}" />
                    <input type="hidden" name="sub_total_3" value="{{ $data->sub_total_3 }}" />
                    <input type="hidden" name="sub_total_4" value="{{ $data->sub_total_4 }}" />

                    <div class="col-md-12" style="padding-left: 0;">
                        <a href="{{ route('karyawan.training-custom.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Back</a>
                        @if($data->status_actual_bill < 1 || $data->status_actual_bill =="" || $data->status_actual_bill == 4 || $data->status_actual_bill == 3)
                        <!--<button type="submit" class="btn btn-sm btn-warning waves-effect waves-light m-r-10" id="save-as-draft-form"><i class="fa fa-save"></i> Save as Draft</button>-->

                        <a class="btn btn-sm btn-success waves-effect waves-light m-r-10" id="submit-form"><i class="fa fa-save"></i> Submit Actual Bill</a>
                        <a class="btn btn-sm btn-primary waves-effect waves-light m-r-10" id="draft-form"><i class="fa fa-save"></i> Save Draft</a>
                        @endif
                        <br style="clear: both;" />
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>    
        </form>                    
    </div>

    <div id="modal_file" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body form-horizontal">
                    <div id="modalcontent">

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

    <div id="modal_file_proof" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body form-horizontal">
                    <output id="result_modal_file"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.row -->
    <!-- ============================================================== -->
</div>
<!-- /.container-fluid -->
@extends('layouts.footer')
</div>
<style type="text/css">
    .custome_table tr th {
        padding-top: 5px !important;
        padding-bottom: 5px !important;
    }
    .table-total table tr th {
        font-size: 14px !important; 
        background-color: #eee !important;
    } 
   
    
    
    table.data_table_no_pagging thead tr td, table.data_table_no_pagging thead tr th {
        border-bottom: 1px solid rgb(193, 190, 190) !important;
        border-top: 1px solid rgb(193, 190, 190) !important;
        border-right: 1px solid rgb(193, 190, 190) !important;
        border-left: 1px solid rgb(193, 190, 190) !important;
        text-align: center !important;

    }
    table.data_table_no_pagging tfoot tr td, table.data_table_no_pagging tfoot tr th{
         border-bottom: 1px solid rgb(193, 190, 190) !important;
        border-top: 1px solid rgb(193, 190, 190) !important;
        border-right: 1px solid rgb(193, 190, 190) !important;
        border-left: 1px solid rgb(193, 190, 190) !important;
    }

    table.data_table_no_pagging tbody tr td,table.data_table_no_pagging tbody tr th{
            border-bottom: 0 solid rgb(193, 190, 190) !important;
            border-top: 0 solid rgb(193, 190, 190) !important;
            border-left: 1px solid rgb(193, 190, 190) !important;
    }

    table.data_table_no_pagging tbody.table-content-value tr td{
            border-bottom: 0 solid rgb(193, 190, 190) !important;
            border-top: 1px solid rgb(193, 190, 190) !important;
            border-left: 1px solid rgb(193, 190, 190) !important;
            border-right: 1px solid rgb(193, 190, 190) !important;

    }

    table.data_table_no_pagging tbody tr td {
        border: 1px solid rgb(193, 190, 190) !important;
    }
    /*
    table.dataTable tfoot th, table.dataTable tfoot td
    {
        border-bottom: 0 !important;
        border-top: 0 !important;
    }
    */
</style>
@section('footer-script')

<script type="text/javascript">

var general_el;
var validate_form = false;

show_hide_addAcomodation();
cek_button_addAcomodation();
show_hide_addAllowance();
cek_button_addAllowance();
show_hide_addOther();
cek_button_addOther();

function toDataURL(url, callback) {
    var xhr = new XMLHttpRequest();
    xhr.onload = function() {
        var reader = new FileReader();
        reader.onloadend = function() {
            callback(reader.result);
        }
        reader.readAsDataURL(xhr.response);
    };
    xhr.open('GET', url);
    xhr.responseType = 'blob';
    xhr.send();
}

//acomodation 
function show_hide_addAcomodation()
{       
    validate_form = true;
    
    $('.input').each(function(){
     
        if($(this).val() == "")
        {
            validate_form = false;
        }
    });
}

function cek_button_addAcomodation()
{
    $('.input').each(function(){
        $(this).on('keyup',function(){
            show_hide_addAcomodation();
        })
        $(this).on('change',function(){
            show_hide_addAcomodation();
        })
    });
}
function delete_itemAcomodation(el)
{
    if(confirm('Delete this data?'))
    {
        $(el).parent().parent().hide(function(){
            $(el).parent().parent().remove();
            setTimeout(function(){
                show_hide_addAcomodation();
                cek_button_addAcomodation();
                calculate_nominalAcomodation();
                calculate_all();
                if($('.table-content-acomodation tr').length < 1) {
                    $('.table-content-acomodation').append(emptyAcomodation);
                }
            });
        }); 
    }
}

//allowance
function show_hide_addAllowance()
{       
    validate_form = true;
    
    $('.input').each(function(){
     
        if($(this).val() == "")
        {
            validate_form = false;
        }
    });
}

function cek_button_addAllowance()
{
    $('.input').each(function(){
        $(this).on('keyup',function(){
            show_hide_addAllowance();
        })
        $(this).on('change',function(){
            show_hide_addAllowance();
        })
    });
}
function delete_itemAllowance(el)
{
    if(confirm('Delete this data?'))
    {
        $(el).parent().parent().hide("slow", function(){
            $(el).parent().parent().remove();
            setTimeout(function(){
                show_hide_addAllowance();
                cek_button_addAllowance();
                calculate_nominalMorning();
                calculate_nominalAfternoon();
                calculate_nominalEvening();
                calculate_allAllowance();
                calculate_all();                
                if($('.table-content-allowance tr').length < 1) {
                    $('.table-content-allowance').append(emptyAllowance);
                }
            });
        }); 
    }
}
//Daily
function show_hide_addDaily()
{       
    validate_form = true;
    $('.input').each(function(){
        if($(this).val() == "")
        {
            validate_form = false;
        }
    });
}

function cek_button_addDaily()
{
    $('.input').each(function(){
        $(this).on('keyup',function(){
            show_hide_addDaily();
        })
        $(this).on('change',function(){
            show_hide_addDaily();
        })
    });
}
function delete_itemDaily(el)
{
    if(confirm('Delete this data?'))
    {
        $(el).parent().parent().hide("slow", function(){
            $(el).parent().parent().remove();
            setTimeout(function(){
                show_hide_addDaily();
                cek_button_addDaily();
                calculate_nominalDaily();
                calculate_all();
                if($('.table-content-daily tr').length < 1) {
                    $('.table-content-daily').append(emptyDaily);
                }
            });
        }); 
    }
}
//Other
function show_hide_addOther()
{       
    validate_form = true;
    $('.input').each(function(){
        if($(this).val() == "")
        {
            validate_form = false;
        }
    });
}

function cek_button_addOther()
{
    $('.input').each(function(){
        $(this).on('keyup',function(){
            show_hide_addOther();
        })
        $(this).on('change',function(){
            show_hide_addOther();
        })
    });
}
function delete_itemOther(el)
{
    if(confirm('Delete this data?'))
    {
        $(el).parent().parent().hide("slow", function(){
            $(el).parent().parent().remove();
            setTimeout(function(){
                show_hide_addOther();
                cek_button_addOther();
                calculate_nominalOther();
                calculate_all();
                if($('.table-content-other tr').length < 1) {
                    $('.table-content-other').append(emptyOther);
                }
            });
        }); 
    }
}

var calculate_nominalAcomodation  = function(){
    var totalnominalAcomodation = 0;
        $('.nominalAcomodation').each(function(){
            if($(this).val() != ""){
                    var value = $(this).val();
                    totalnominalAcomodation += parseInt(value.split('.').join(''));
                }
        });
    $('.sub_total_1').html(numberWithComma(totalnominalAcomodation).replace(/,/g, "."));
    $("input[name='sub_total_1']").val(totalnominalAcomodation);
}

var emptyAcomodation = $('.table-content-acomodation').html();
var emptyAllowance = $('.table-content-allowance').html();
var emptyDaily = $('.table-content-daily').html();
var emptyOther = $('.table-content-other').html();

$("#addAcomodation").click(function(){
    
    if ($(".table-content-acomodation tr td").hasClass("dataTables_empty")) {
        $(".table-content-acomodation").html('')
    }

    var no = $('.table-content-acomodation tr').length;
    if((no+1) <= 15) {
    var html = '<tr>';
        html += '<td><input type="date" style="width: 165px" name="dateAcomodation[]" required class="form-control" placeholder="Date"></td>';
        html += '<td><select class="form-control training_transportation_type_id" name="training_transportation_type_id[]">\
                                        <option value=""> - choose - </option>\
                                        @foreach($transportationtype as $item)\
                                        <option value="{{ $item->id }}" data-attachment="{{ $item->is_attachment }}">{{ $item->name }}</option>\
                                        @endforeach\
                                </select></td>';
        html += '<td><input type="text" class="form-control price_format nominalAcomodation" name="nominalAcomodation[]"></td>';
        html += '<td><input type="text" name="nominalAcomodation_approved[]"  class="form-control price_format" readonly="true"></td>';
        html += '<td><input type="text" name="noteAcomodation[]" class="form-control noteAcomodation"></td>';
        html += '<td><input type="text" readonly name="note_approvalAcomodation[]" class="form-control note_approvalAcomodation"></td>';
        html += '<td>';
        html += '<input type="file" name="file_strukAcomodation[]" accept="image/*, application/pdf" class="form-control input" id="acomodation_'+no+'">';
        html += '<div id="default_acomodation_'+no+'"></div><div id="preview_acomodation_'+no+'" style="display: none"></div>';  
        html += '</td>';
        html += '<td><a class="btn btn-xs btn-danger" onclick="delete_itemAcomodation(this);"><i class="fa fa-trash"></i></a></td>';
        html += '</tr>';

    $('.table-content-acomodation').append(html);
    show_hide_addAcomodation();
    cek_button_addAcomodation();
    price_format();

    $(".nominalAcomodation").on('input', function(){
        calculate_nominalAcomodation();
        calculate_all();
    });

    initImage()
    }
    else{
        alert('Maximal of items are 15, Please make a new form!')
    }
});

@if($data->status_actual_bill == 4 || $data->status_actual_bill == 3)
    defaultAcomodation()
@endif

function defaultAcomodation() { 
    @foreach($data->training_acomodation as $key => $val)

        if ($(".table-content-acomodation tr td").hasClass("dataTables_empty")) {
            $(".table-content-acomodation").html('')
        }

        var no = $('.table-content-acomodation tr').length;
        var html = '<tr><input type="hidden" value="{{ $val->id }}" name="idAcomodation[]">';
            html += '<td><input type="date" value="{{ $val->date }}" style="width: 165px" name="dateAcomodation[]" required class="form-control" placeholder="Date"></td>';
            html += '<td><select class="form-control training_transportation_type_id" name="training_transportation_type_id[]">\
                                    <option value=""> - choose - </option>\
                                        @foreach($transportationtype as $item)\
                                        <option value="{{ $item->id }}" data-attachment="{{ $item->is_attachment }}" {{ isset($val->transportation_type) && $val->transportation_type->id == $item->id ? "selected" : "" }}>{{ $item->name }}</option>\
                                        @endforeach\
                                    </select></td>';
            html += '<td><input type="text" value="{{ $val->nominal }}" class="form-control price_format nominalAcomodation" name="nominalAcomodation[]"></td>';
            html += '<td><input type="text" value="{{ $val->nominal_approved }}" name="nominalAcomodation_approved[]"  class="form-control price_format" readonly="true"></td>';
            html += '<td><input type="text" value="{{ $val->note }}" name="noteAcomodation[]" class="form-control noteAcomodation"></td>';
            html += '<td><input type="text" value="{{ $val->note_approval}}" readonly name="note_approvalAcomodation[]" class="form-control note_approvalAcomodation"></td>';
            html += '<td>';
            html += '<input type="file" name="file_strukAcomodation[]" accept="image/*, application/pdf" class="form-control input" id="acomodation_'+no+'">';
            html += '<div id="default_acomodation_'+no+'" class="default_acomodation">';  
            @if(!empty($val->file_struk))
                html += '<label onclick="show_img(\''+ "{{ asset('storage/file-acomodation/'. $val->file_struk) }}" +'\')" class="btn btn-info btn-xs"><i class="fa fa-image"></i> view</label>';
            @endif
            html += '</div><div id="preview_acomodation_'+no+'" style="display: none"></div>';  
            html += '</td>';
            html += '<td><a class="btn btn-xs btn-danger" onclick="delete_itemAcomodation(this);"><i class="fa fa-trash"></i></a></td>';
            html += '</tr>';

        $('.table-content-acomodation').append(html);
        show_hide_addAcomodation();
        cek_button_addAcomodation();
        price_format();

        $(".nominalAcomodation").on('input', function(){
            calculate_nominalAcomodation();
            calculate_all();
        });

        initImage()
    @endforeach
}

var calculate_nominalMorning  = function(){
    var totalnominalMorning = 0;
        $('.morning').each(function(){
            if($(this).val() != ""){
                    var value = $(this).val();
                    totalnominalMorning += parseInt(value.split('.').join(''));
                }
        });
    $('.totalMorning').html(numberWithComma(totalnominalMorning));
}

var calculate_nominalAfternoon  = function(){
    var totalnominalAfternoon = 0;
        $('.afternoon').each(function(){
            if($(this).val() != ""){
                    var value = $(this).val();
                    totalnominalAfternoon += parseInt(value.split('.').join(''));
                }
        });
    $('.totalAfternoon').html(numberWithComma(totalnominalAfternoon));
}

var calculate_nominalEvening  = function(){
    var totalnominalEvening = 0;
        $('.evening').each(function(){
            if($(this).val() != ""){
                    var value = $(this).val();
                    totalnominalEvening += parseInt(value.split('.').join(''));
                }
        });
    $('.totalEvening').html(numberWithComma(totalnominalEvening));
}

var calculate_allAllowance  = function(){
        var totalAll = 0;

        var totalMorning    = parseInt(document.getElementsByClassName("totalMorning")[0].innerHTML.replace(/,/g, ""));
        var totalAfternoon  = parseInt(document.getElementsByClassName("totalAfternoon")[0].innerHTML.replace(/,/g, ""));
        var totalEvening    = parseInt(document.getElementsByClassName("totalEvening")[0].innerHTML.replace(/,/g, ""));
        totalAll =(parseInt(totalMorning + totalAfternoon + totalEvening));
        
        $('.sub_total_2').html(numberWithComma(totalAll).replace(/,/g, "."));
        $("input[name='sub_total_2']").val(totalAll);
}

$("#addAllowance").click(function(){
    @php ($plafond_dinas = getPlafondTraining($data->lokasi_kegiatan,$data->tempat_tujuan))
    @if($plafond_dinas->tunjangan_makanan == 0)
    {
        alert('Plafond your meal allowance and type of location not define yet. Please contact your admin !');
    }
    @endif

    if ($(".table-content-allowance tr td").hasClass("dataTables_empty")) {
        $(".table-content-allowance").html('')
    }

    var no = $('.table-content-allowance tr').length;
    if((no+1) <= 15) {
    var html = '<tr>';
        html += '<td><input type="date" style="width: 165px" name="dateAllowance[]" required class="form-control" placeholder="Date"></td>';
        html += '<td><input type="text" class="form-control meal_plafond price_format" readonly="true" name="meal_plafond[]" value="{{$plafond_dinas->tunjangan_makanan}}">';
        html += '<td><input type="text" class="form-control morning price_format" name="morning[]"></td>';
        html += '<td><input type="text" name="morning_approved[]"  class="form-control price_format" readonly="true"></td>';
        html += '<td><input type="text" name="afternoon[]" class="form-control afternoon price_format "></td>';
        html += '<td><input type="text" name="afternoon_approved[]" class="form-control price_format" readonly="true"></td>';
        html += '<td><input type="text" name="evening[]" class="form-control evening price_format"></td>';
        html += '<td><input type="text" name="evening_approved[]" class="form-control price_format" readonly="true"></td>';
        html += '<td><input type="text" name="noteAllowance[]" class="form-control noteAllowance"></td>';
        html += '<td><input type="text" name="note_approvalAllowance[]" class="form-control note_approvalAllowance" readonly></td>';
        html += '<td>';
        html += '<input type="file" name="file_strukAllowance[]" accept="image/*, application/pdf" class="form-control input" id="allowance_'+no+'">';
        html += '<div id="default_allowance_'+no+'"></div><div id="preview_allowance_'+no+'" style="display: none"></div>';  
        html += '</td>';
        html += '<td><a class="btn btn-xs btn-danger" onclick="delete_itemAllowance(this);"><i class="fa fa-trash"></i></a></td>';
        html += '</tr>';

    $('.table-content-allowance').append(html);
    show_hide_addAllowance();
    cek_button_addAllowance();
    price_format();
    $(".morning").on('input', function(){
        calculate_nominalMorning();
        calculate_allAllowance();
        calculate_all();
    });
    $(".afternoon").on('input', function(){
        calculate_nominalAfternoon();
        calculate_allAllowance();
        calculate_all();
    });
    $(".evening").on('input', function(){
        calculate_nominalEvening();
        calculate_allAllowance();
        calculate_all();
    });

    initImage()
    }
    else{
        alert('Maximal of items are 15, Please make a new form!')
    }
});

@if($data->status_actual_bill == 4 || $data->status_actual_bill == 3)
    defaultAllowance()
@endif

function defaultAllowance() { 
    @foreach($data->training_allowance as $key => $val)

        if ($(".table-content-allowance tr td").hasClass("dataTables_empty")) {
            $(".table-content-allowance").html('')
        }

        var no = $('.table-content-allowance tr').length;
        var html = '<tr><input type="hidden" value="{{ $val->id }}" name="idAllowance[]">';
            html += '<td><input type="date" value="{{ $val->date }}" style="width: 165px" name="dateAllowance[]" required class="form-control" placeholder="Date"></td>';
            html += '<td><input type="text" value="{{ $val->meal_plafond }}" class="form-control meal_plafond price_format" readonly="true" name="meal_plafond[]">';
            html += '<td><input type="text" value="{{ $val->morning }}" class="form-control morning price_format" name="morning[]"></td>';
            html += '<td><input type="text" value="{{ $val->morning_approved }}" name="morning_approved[]"  class="form-control price_format" readonly="true"></td>';
            html += '<td><input type="text" value="{{ $val->afternoon }}" name="afternoon[]" class="form-control afternoon price_format "></td>';
            html += '<td><input type="text" value="{{ $val->afternoon_approved }}" name="afternoon_approved[]" class="form-control price_format" readonly="true"></td>';
            html += '<td><input type="text" value="{{ $val->evening }}" name="evening[]" class="form-control evening price_format"></td>';
            html += '<td><input type="text" value="{{ $val->evening_approved }}" name="evening_approved[]" class="form-control price_format" readonly="true"></td>';
            html += '<td><input type="text" value="{{ $val->note }}" name="noteAllowance[]" class="form-control noteAllowance"></td>';
            html += '<td><input type="text" value="{{ $val->note_approval }}" name="note_approvalAllowance[]" class="form-control note_approvalAllowance" readonly></td>';
            html += '<td>';
            html += '<input type="file" name="file_strukAllowance[]" accept="image/*, application/pdf" class="form-control input" id="allowance_'+no+'">';
            html += '<div id="default_allowance_'+no+'">';  
            @if(!empty($val->file_struk))
                html += '<label onclick="show_img(\''+ "{{ asset('storage/file-allowance/'. $val->file_struk) }}" +'\')" class="btn btn-info btn-xs"><i class="fa fa-image"></i> view</label>';
            @endif
            html += '</div><div id="preview_allowance_'+no+'" style="display: none"></div>';
            html += '</td>';
            html += '<td><a class="btn btn-xs btn-danger" onclick="delete_itemAllowance(this);"><i class="fa fa-trash"></i></a></td>';
            html += '</tr>';

        $('.table-content-allowance').append(html);
        show_hide_addAllowance();
        cek_button_addAllowance();
        price_format();
        $(".morning").on('input', function(){
            calculate_nominalMorning();
            calculate_allAllowance();
            calculate_all();
        });
        $(".afternoon").on('input', function(){
            calculate_nominalAfternoon();
            calculate_allAllowance();
            calculate_all();
        });
        $(".evening").on('input', function(){
            calculate_nominalEvening();
            calculate_allAllowance();
            calculate_all();
        });

        calculate_nominalMorning();
        calculate_nominalAfternoon();
        calculate_nominalEvening();

        initImage()

    @endforeach
}

var calculate_nominalDaily  = function(){
    var totalnominalDaily = 0;
        $('.nominalDaily').each(function(){
            if($(this).val() != ""){
                    var value = $(this).val();
                    totalnominalDaily += parseInt(value.split('.').join(''));
                }
        });
    $('.sub_total_3').html(numberWithComma(totalnominalDaily).replace(/,/g, "."));
    $("input[name='sub_total_3']").val(totalnominalDaily);
}
$("#addDaily").click(function(){
    @php ($plafond_dinas = getPlafondTraining($data->lokasi_kegiatan,$data->tempat_tujuan))
    @if($plafond_dinas->tunjangan_harian == 0)
    {
        alert('Plafond your daily allowance and type of location not define yet. Please contact your admin !');
    }
    @endif

    if ($(".table-content-daily tr td").hasClass("dataTables_empty")) {
        $(".table-content-daily").html('')
    }

    var no = $('.table-content-daily tr').length;
    if((no+1) <= 15) {
    var html = '<tr>';
        html += '<td><input type="date" style="width: 165px" name="dateDaily[]" required class="form-control" \
        placeholder="Date"></td>';
        html += '<td><input type="text" class="form-control daily_plafond price_format" readonly="true" name="daily_plafond[]" value="{{$plafond_dinas->tunjangan_harian}}">';
        html += '<td><input type="text" name="nominalDaily[]"  class="form-control price_format nominalDaily"></td>';
        html += '<td><input type="text" name="nominalDaily_approved[]"  class="form-control price_format" readonly="true"></td>';
        html += '<td><input type="text" name="noteDaily[]" class="form-control noteDaily"></td>';
        html += '<td><input type="text" name="note_approvalDaily[]" class="form-control note_approvalDaily" readonly></td>';
        html += '<td>';
        html += '<input type="file" name="file_strukDaily[]" accept="image/*, application/pdf" class="form-control input" id="daily_'+no+'">';
        html += '<div id="default_daily_'+no+'"></div><div id="preview_daily_'+no+'" style="display: none"></div>';  
        html += '</td>';
        html += '<td><a class="btn btn-xs btn-danger" onclick="delete_itemDaily(this);"><i class="fa fa-trash"></i></a></td>';
        html += '</tr>';
    
    $('.table-content-daily').append(html);
    show_hide_addDaily();
    cek_button_addDaily();
    price_format();

    $(".nominalDaily").on('input', function(){
        calculate_nominalDaily();
        calculate_all();
    });

    initImage()
    }
    else{
        alert('Maximal of items are 15, Please make a new form!')
    }
});

@if($data->status_actual_bill == 4 || $data->status_actual_bill == 3)
    defaultDaily()
@endif

function defaultDaily() { 
    @foreach($data->training_daily as $key => $val)

        if ($(".table-content-daily tr td").hasClass("dataTables_empty")) {
            $(".table-content-daily").html('')
        }

        var no = $('.table-content-daily tr').length;
        var html = '<tr><input type="hidden" value="{{ $val->id }}" name="idDaily[]">';
            html += '<td><input type="date" value="{{ $val->date }}" style="width: 165px" name="dateDaily[]" required class="form-control" \
            placeholder="Date"></td>';
            html += '<td><input type="text" value="{{ $val->daily_plafond }}" class="form-control daily_plafond price_format" readonly="true" name="daily_plafond[]">'
            html += '<td><input type="text" value="{{ $val->daily }}" name="nominalDaily[]"  class="form-control price_format nominalDaily"></td>';
            html += '<td><input type="text" value="{{ $val->daily_approved }}" name="nominalDaily_approved[]"  class="form-control price_format" readonly="true"></td>';
            html += '<td><input type="text" value="{{ $val->note }}" name="noteDaily[]" class="form-control noteDaily"></td>';
            html += '<td><input type="text" value="{{ $val->note_approval }}" name="note_approvalDaily[]" class="form-control note_approvalDaily" readonly></td>';
            html += '<td>';
            html += '<input type="file" name="file_strukDaily[]" accept="image/*, application/pdf" class="form-control input" id="daily_'+no+'">';
            html += '<div id="default_daily_'+no+'">';  
            @if(!empty($val->file_struk))
                html += '<label onclick="show_img(\''+ "{{ asset('storage/file-daily/'. $val->file_struk) }}" +'\')" class="btn btn-info btn-xs"><i class="fa fa-image"></i> view</label>';
            @endif
            html += '</div><div id="preview_daily_'+no+'" style="display: none"></div>';  
            html += '</td>';
            html += '<td><a class="btn btn-xs btn-danger" onclick="delete_itemDaily(this);"><i class="fa fa-trash"></i></a></td>';
            html += '</tr>';
        
        $('.table-content-daily').append(html);
        show_hide_addDaily();
        cek_button_addDaily();
        price_format();

        $(".nominalDaily").on('input', function(){
            calculate_nominalDaily();
            calculate_all();
        });

        initImage()
    @endforeach
}

var calculate_nominalOther  = function(){
    var totalnominalOther = 0;
        $('.nominalOther').each(function(){
            if($(this).val() != ""){
                    var value = $(this).val();
                    totalnominalOther += parseInt(value.split('.').join(''));
                }
        });
    $('.sub_total_4').html(numberWithComma(totalnominalOther).replace(/,/g, "."));
    $("input[name='sub_total_4']").val(totalnominalOther);
}

$("#addOther").click(function(){

    if ($(".table-content-other tr td").hasClass("dataTables_empty")) {
        $(".table-content-other").html('')
    }

    var no = $('.table-content-other tr').length;
    if((no+1) <= 15) {
    var html = '<tr>';
        html += '<td><input type="date" style="width: 165px" name="dateOther[]" required class="form-control" \
        placeholder="Date"></td>';
        html += '<td><input type="text" class="form-control descriptionOther" name="descriptionOther[]"></td>';
        html += '<td><input type="text" class="form-control price_format nominalOther" name="nominalOther[]"></td>';
        html += '<td><input type="text" name="nominalOther_approved[]"  class="form-control price_format" readonly="true"></td>';
        html += '<td><input type="text" name="noteOther[]" class="form-control noteOther"></td>';
        html += '<td><input type="text" name="note_approvalOther[]" class="form-control note_approvalOther" readonly></td>';
        html += '<td>';
        html += '<input type="file" name="file_strukOther[]" accept="image/*, application/pdf" class="form-control input" id="other_'+no+'">';
        html += '<div id="default_other_'+no+'"></div><div id="preview_other_'+no+'" style="display: none"></div>';  
        html += '</td>';
        html += '<td><a class="btn btn-xs btn-danger" onclick="delete_itemOther(this);"><i class="fa fa-trash"></i></a></td>';
        html += '</tr>';

    $('.table-content-other').append(html);
    show_hide_addOther();
    cek_button_addOther();
    price_format();
    
    $(".nominalOther").on('input', function(){
        calculate_nominalOther();
        calculate_all();
    });

    initImage()
    }
    else{
        alert('Maximal of items are 15, Please make a new form!')
    }
});

@if($data->status_actual_bill == 4 || $data->status_actual_bill == 3)
    defaultOther()
@endif

function defaultOther() { 
    @foreach($data->training_other as $key => $val)
    
        if ($(".table-content-other tr td").hasClass("dataTables_empty")) {
            $(".table-content-other").html('')
        }

        var no = $('.table-content-other tr').length;
        var html = '<tr><input type="hidden" value="{{ $val->id }}" name="idOther[]">';
            html += '<td><input type="date" value="{{ $val->date }}" style="width: 165px" name="dateOther[]" required class="form-control" \
            placeholder="Date"></td>';
            html += '<td><input type="text" value="{{ $val->description }}" class="form-control descriptionOther" name="descriptionOther[]"></td>';
            html += '<td><input type="text" value="{{ $val->nominal }}" class="form-control nominalOther price_format" name="nominalOther[]"></td>';
            html += '<td><input type="text" value="{{ $val->nominal_approved }}" name="nominalOther_approved[]"  class="form-control price_format" readonly="true"></td>';
            html += '<td><input type="text" value="{{ $val->note }}" name="noteOther[]" class="form-control noteOther"></td>';
            html += '<td><input type="text" value="{{ $val->note_approval}}" name="note_approvalOther[]" class="form-control note_approvalOther" readonly></td>';
            html += '<td>';
            html += '<input type="file" name="file_strukOther[]" accept="image/*, application/pdf" class="form-control input" id="other_'+no+'">';
            html += '<div id="default_other_'+no+'">';  
            @if(!empty($val->file_struk))
                html += '<label onclick="show_img(\''+ "{{ asset('storage/file-other/'. $val->file_struk) }}" +'\')" class="btn btn-info btn-xs"><i class="fa fa-image"></i> view</label>';
            @endif
            html += '</div><div id="preview_other_'+no+'" style="display: none"></div>';  
            html += '</td>';
            html += '<td><a class="btn btn-xs btn-danger" onclick="delete_itemOther(this);"><i class="fa fa-trash"></i></a></td>';
            html += '</tr>';

        $('.table-content-other').append(html);
        show_hide_addOther();
        cek_button_addOther();
        price_format();
        $(".nominalOther").on('input', function(){
            calculate_nominalOther();
            calculate_all();
        });

        initImage()
    @endforeach
}

    function show_proof(img)
    {
        var images = ['png','gif','jpg','jpeg'];
        var ext = img.split('.').pop().toLowerCase();
        if(ext === 'pdf'){
            $('#modalcontent').html('<embed src="{{ asset('storage/training-custom/transfer-proof/')}}/'+ img +'" frameborder="0" width="100%" height="400px">');
            $('#modal_file').modal('show');
        }
        else if(images.includes(ext)){
            $('#modalcontent').html('<img src="{{ asset('storage/training-custom/transfer-proof/')}}/'+ img +'" style = \'width: 100%;\' />');
            $('#modal_file').modal('show');
        }
        else{
            alert("Filetype is not supported!");
        }

    }

    function initImage() {
        if (window.File && window.FileList && window.FileReader) {
            var filesInput = document.getElementsByClassName("input");
            for (var i = 0; i < filesInput.length; i++) {
                filesInput[i].replaceWith(filesInput[i].cloneNode(true));
                filesInput[i].addEventListener("change", function(event) {
                    var files = event.target.files; //FileList object
                    var id = event.target.id;
                    var output = document.getElementById("preview_" + id);
                    $("#preview_" + id).html("");
                    if (files.length) {
                        $("#default_" + id).hide();
                        $("#preview_" + id).show();
                    } else {
                        $("#preview_" + id).hide();
                        $("#default_" + id).show();
                    }
                    for (var i = 0; i < files.length; i++) {
                        var file = files[i];
                        //Only pics
                        if (!file.type.match('image') && !file.type === 'application/pdf')
                            continue;
                        var picReader = new FileReader();
                        picReader.addEventListener("load", function(event) {
                            var picFile = event.target;
                            var div = document.createElement("div");
                            div.innerHTML = '<label onclick="show_img(\'' + picFile.result + '\')" class="btn btn-info btn-xs"><i class="fa fa-image"></i> view</label>';
                            output.insertBefore(div, null);
                        });
                        //Read the image
                        picReader.readAsDataURL(file);
                    }
                });
            }
        } else {
            console.log("Your browser does not support File API");
        }
    }

    window.onload = function() {
        initImage();
    }
   
</script>

<script type="text/javascript">

    function calculate_all()
    {
        var total_actual_bill = 0;
       var total_reimbursement = 0;
 
        if($("input[name='sub_total_1']").val() != "")
        {  
            total_actual_bill       += parseInt($("input[name='sub_total_1']").val());
             total_reimbursement     += parseInt($("input[name='sub_total_1']").val());
        }
       
        if( $("input[name='sub_total_2']").val() != "")
        {
            total_actual_bill       += parseInt($("input[name='sub_total_2']").val());
             total_reimbursement     += parseInt($("input[name='sub_total_2']").val());
        }
        
        if( $("input[name='sub_total_3']").val() != "")
        {
            total_actual_bill       += parseInt($("input[name='sub_total_3']").val());
             total_reimbursement     += parseInt($("input[name='sub_total_4']").val());
        }

        if( $("input[name='sub_total_4']").val() != "")
        {
            total_actual_bill       += parseInt($("input[name='sub_total_4']").val());
             total_reimbursement     += parseInt($("input[name='sub_total_4']").val());
        }
        
        {{ !empty($data->pengambilan_uang_muka) ? ' total_reimbursement -='. $data->pengambilan_uang_muka .';' : '' }};

        if(total_reimbursement <0 )
        {
            total_reimbursement  = Math.abs(total_reimbursement);
        }

        $('.total_actual_bill').html(numberWithComma(total_actual_bill).replace(/,/g, "."));
        $('.total_reimbursement').html(numberWithComma(total_reimbursement).replace(/,/g, "."));
    }

    function show_img(img)
    {
        var images = ['png','gif','jpg','jpeg'];
        var ext = img.split('.').pop().toLowerCase();
        if (ext === 'pdf' || img.match('pdf')) {
            bootbox.alert({
                message : '<embed src="'+ img +'" frameborder="0" width="100%" height="600px">',
                size: 'large' 
            });
        } else if(images.includes(ext) || img.match('image')) {
            bootbox.alert({
                message : '<img src="'+ img +'" style="width: 100%;" />',
                size: 'large' 
            });
        } else {
            alert("Filetype is not supported!");
        }
    }

    $("#draft-form").click(function(){
        bootbox.confirm('Save as Draft?', function(res){
            if(res)
            {
                $("input[name='status_actual_bill']").val(4);
                $("#form-actual-bill").submit();
            }
        });
    });

    $("#submit-form").click(function(){
        var jumlahAcomodation=!$(".table-content-acomodation tr td").hasClass("dataTables_empty");
        var jumlahAllowance=!$(".table-content-allowance tr td").hasClass("dataTables_empty");
        var jumlahDaily=!$(".table-content-daily tr td").hasClass("dataTables_empty");
        var jumlahOther=!$(".table-content-other tr td").hasClass("dataTables_empty");

        var ret = true;
        var errorAcomodation = false;
        var errorAllowance = false;
        var errorDaily = false;
        var errorOther = false;
        if(jumlahAcomodation)
        {
            $("tbody.table-content-acomodation tr").each(function() {
                var date = $(this).find("td").eq(0).find("input").val();
                var description = $(this).find("td").eq(1).find("select").val();
                var attachment = $(this).find("td").eq(5).find("input").val();
                var defaultAttachment = $(this).find("td").eq(5).find(".default_acomodation").html();
                if(date== "" || description == "" || (attachment == "" && defaultAttachment == "" && $(this).find("td").eq(1).find("select").find(':selected').data('attachment') == 1))
                {
                    // bootbox.alert("Data Acommodation & Transportation is incomplete!");
                    ret = false;
                    errorAcomodation = true;
                }
           });       
        }
        if(jumlahAllowance)
        {
            $("tbody.table-content-allowance tr").each(function() {
                var date = $(this).find("td").eq(0).find("input").val();
                if(date== "")
                {
                    // bootbox.alert("Date Meal Allowance is incomplete!");
                    ret = false;
                    errorAllowance = true;
                }
           });       
        }
        if(jumlahDaily)
        {
            $("tbody.table-content-daily tr").each(function() {
                var date = $(this).find("td").eq(0).find("input").val();
                if(date== "")
                {
                    // bootbox.alert("Data Daily Allowance is incomplete!");
                    ret = false;
                    errorDaily = true;
                }
           });       
        }
        if(jumlahOther)
        {
            $("tbody.table-content-other tr").each(function() {
                var date = $(this).find("td").eq(0).find("input").val();
                var description = $(this).find("td").eq(1).find("select").val();
                if(date== "" || description == "")
                {
                    // bootbox.alert("Data Other's is incomplete!");
                    ret = false;
                    errorOther = true;
                }
           });       
        }


        if(ret)
        {
            if (jumlahAcomodation || jumlahAllowance || jumlahDaily || jumlahOther) {
                bootbox.confirm('Submit actual bill?', function(res){
                    if(res)
                    {
                        $("input[name='status_actual_bill']").val(1);
                        $("#form-actual-bill").submit();
                    }
                });
            } else {
                bootbox.alert("Cant submit without bill!");
            }
        } else {
            let errorText = "";
            if (errorAcomodation)
                errorText += "Data Acommodation & Transportation is incomplete!<br>";
            if (errorAllowance)
                errorText += "Date Meal Allowance is incomplete!<br>";
            if (errorDaily)
                errorText += "Data Daily Allowance is incomplete!<br>";
            if (errorOther)
                errorText += "Data Other's is incomplete!<br>";
            bootbox.alert(errorText);
        }     
    });

    function show_history(id, jenis)
    {
        $('#modalcontent').html('');
        $.ajax({
            url: "{{url('/ajax/get-report-training')}}",
            type: "get", //send it through get method
            data: { 
                id: id, 
                type: jenis,
            },
            success: function(response) {
                content =  ( '<div class="table-responsive"><table class="table table-bordered">' );
                if(jenis=='allowance'){
                    content += ('<tr><th>Approval</th><th>NIK</th><th>Name</th><th>Morning</th><th>Afternoon</th><th>Evening</th><th>Updated at</th><th>Note</th></tr>')
                    if(response.length > 0){
                        $.each(response, function(i, item) {
                            note = item.note != null ? item.note : '';
                            content += ( '<tr><td>' + item.level_id + '</td><td>' + item.user_approved.nik + '</td><td>' + item.user_approved.name + '</td><td>' + item.morning_approved + '</td><td>' + item.afternoon_approved + '</td><td>' + item.evening_approved + '</td><td>' + new Date(item.updated_at).toLocaleString() + '</td><td>' + note + '</td></tr>' );
                        });
                    }
                    else{
                        content +='<tr><td colspan="7" class="text-center">Data is empty</td></tr>'
                    }
                }
                else{
                    content += ('<tr><th>Approval</th><th>NIK</th><th>Name</th><th>Ammount</th><th>Updated at</th><th>Note</th></tr>')
                    if(response.length>0){
                        $.each(response, function(i, item) {
                            note = item.note != null ? item.note : '';
                            content += ( '<tr><td>' + item.level_id + '</td><td>' + item.user_approved.nik + '</td><td>' + item.user_approved.name + '</td><td>' + item.approved + '</td><td>' + new Date(item.updated_at).toLocaleString()  + '</td><td>' + note +  '</td></tr>' );
                        });
                    }
                    else{
                        content +='<tr><td colspan="5" class="text-center">Data is empty</td></tr>'
                    }
                }

                // console.log(response)
                content += (  '</table></div>' );
                $('#modalcontent').append(content);
            },
            error: function(xhr) {
                //Do Something to handle error
            }
        });
        $('#modal_file').modal('show');
    }
</script>


<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@endsection
@endsection
