@extends('layouts.administrator')

@section('title', 'Setting Payroll')

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Setting Payroll</h4> 
            </div>
        </div>
        <!-- .row -->
        <div class="row">
            <div class="col-md-12 p-l-0 p-r-0">
                <div class="white-box p-l-1 p-r-1">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="{{ !isset($tab) ? 'active' : '' }}"><a href="#general" aria-controls="general" role="tab" data-toggle="tab" aria-expanded="true"> General</a></li>
                        <li role="presentation" class="{{ isset($tab) && $tab == 'pph' ? 'active' : '' }}"><a href="#pph" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs"> PPH</span></a></li>
                        <li role="presentation" class="{{ isset($tab) && $tab == 'ptkp' ? 'active' : '' }}"><a href="#ptkp" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs"> PTKP</span></a></li>
                        <li role="presentation" class="{{ isset($tab) && $tab == 'others' ? 'active' : '' }}"><a href="#others" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs"> Others</span></a></li>
                        <li role="presentation" class="{{ isset($tab) && $tab == 'npwp' ? 'active' : '' }}"><a href="#npwp" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs">NPWP</span></a></li>
                        <li role="presentation" class="{{ isset($tab) && $tab == 'umr' ? 'active' : '' }}"><a href="#umr" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs">UMR</span></a></li>
                        <li role="presentation" class="{{ isset($tab) && $tab == 'cycleAttendance' ? 'active' : '' }}"><a href="#cycleAttendance" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs">Attendance Cycle</span></a></li>
                        <li role="presentation" class="{{ isset($tab) && $tab == 'cyclePayroll' ? 'active' : '' }}"><a href="#cyclePayroll" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs">Payroll Cycle</span></a></li>
                        <li role="presentation" class="{{ isset($tab) && $tab == 'lock' ? 'active' : '' }}"><a href="#lock" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs">Lock Type</span></a></li>
                        <li role="presentation" class="{{ isset($tab) && $tab == 'prorate' ? 'active' : '' }}"><a href="#prorate" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs">Prorate</span></a></li>
                        <li role="presentation" class="{{ isset($tab) && $tab == 'country' ? 'active' : '' }}"><a href="#country" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs">Country Code</span></a></li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade {{ !isset($tab) ? 'active in' : '' }}" id="general">
                            
                            <div class="col-lg-12 col-sm-8 col-md-8 col-xs-12">
                                <button type="submit" class="btn btn-sm btn-info waves-effect waves-light m-r-10 pull-left" onclick="document.getElementById('form-setting').submit()"><i class="fa fa-save"></i> Save Setting </button>
                            </div>
                            <br><br><br><br>
                            <div class="col-md-12">
                                <form method="POST" id="form-setting" action="{{ route('administrator.payroll-setting.store-general') }}" class="form-horizontal">
                                    {{ csrf_field() }}
                                    <div class="col-md-6 p-l-0">
                                        <div class="form-group">
                                            <label class="col-md-4">BPJS Jaminan Kecelakaan Kerja (JKK)  (Company) </label>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <input type="number" name="setting[bpjs_jkk_company]" value="{{ get_setting('bpjs_jkk_company') }}" class="form-control" />
                                                    <span class="input-group-addon" id="basic-addon2">%</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4">BPJS Jaminan Kematian (JKM)  (Company) </label>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <input type="number" name="setting[bpjs_jkm_company]" value="{{ get_setting('bpjs_jkm_company') }}" class="form-control" />
                                                    <span class="input-group-addon" id="basic-addon2">%</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4">BPJS Jaminan Hari Tua (JHT)  (Company) </label>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <input type="number" name="setting[bpjs_jht_company]" value="{{ get_setting('bpjs_jht_company') }}" class="form-control" />
                                                    <span class="input-group-addon" id="basic-addon2">%</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4">BPJS Pensiun  (Company) </label>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <input type="number" name="setting[bpjs_pensiun_company]" class="form-control" value="{{ get_setting('bpjs_pensiun_company') }}" />
                                                    <span class="input-group-addon" id="basic-addon2">%</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4">BPJS Kesehatan  (Company)</label>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <input type="number" name="setting[bpjs_kesehatan_company]" value="{{ get_setting('bpjs_kesehatan_company') }}" class="form-control" />
                                                    <span class="input-group-addon" id="basic-addon2">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-4">BPJS Jaminan Hari Tua (JHT) (Employee) </label>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <input type="number" name="setting[bpjs_jaminan_jht_employee]" class="form-control" value="{{ get_setting('bpjs_jaminan_jht_employee') }}" />
                                                    <span class="input-group-addon" id="basic-addon2">%</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4">BPJS Jaminan Pensiun (JP) (Employee) </label>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <input type="number" name="setting[bpjs_jaminan_jp_employee]" class="form-control" value="{{ get_setting('bpjs_jaminan_jp_employee') }}" />
                                                    <span class="input-group-addon" id="basic-addon2">%</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4">BPJS Kesehatan (Employee)</label>
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <input type="number" name="setting[bpjs_kesehatan_employee]" class="form-control" value="{{ get_setting('bpjs_kesehatan_employee') }}" />
                                                    <span class="input-group-addon" id="basic-addon2">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div><div class="clearfix"></div>
    
                            <div class="col-md-6" style="border: 1px solid #eee;width: 49%;">
                                <h3>Earnings</h3>
                                <table class="table table-stripped data_table">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">No</th>
                                            <th>Title</th>
                                            <th>Taxable</th>
                                            <th style="width: 10px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($earnings as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $item->title }}</td>
                                            <td>{{ $item->taxable==1?"Taxable":"Untaxable" }}</td>
                                            <td><a href="javascript:void(0)" onclick="_confirm('@lang('general.confirm-message-delete')', '{{ route('administrator.payroll-setting.delete-earnings', $item->id) }}')"> <button class="btn btn-danger btn-xs m-r-5"><i class="fa fa-trash"></i> delete</button></a></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <a href="" class="btn btn-info btn-xs pull-right" data-toggle="modal" data-target=".modal-add-earnings"><i class="fa fa-plus"></i></a>
                            </div>
                            <div class="col-md-6 pull-right" style="margin-left:1%;border: 1px solid #eee;width: 49%;">
                                <h3>Deductions</h3>
                                <table class="table table-stripped data_table">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px">No</th>
                                            <th>Title</th>
                                            <th>Taxable</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($deductions as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $item->title }}</td>
                                            <td>{{ $item->taxable==1?"Taxable":"Untaxable" }}</td>
                                            <td><a href="javascript:void(0)" onclick="_confirm('@lang('general.confirm-message-delete')', '{{ route('administrator.payroll-setting.delete-deductions', $item->id) }}')"> <button class="btn btn-danger btn-xs m-r-5"><i class="fa fa-trash"></i> delete</button></a></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <a href="" class="btn btn-info btn-xs pull-right" data-toggle="modal" data-target=".modal-add-deductions"><i class="fa fa-plus"></i></a>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade {{ isset($tab) && $tab == 'pph' ? 'active in' : '' }}" id="pph">
                            <h3 class="box-title m-b-0">Setting PPH 21</h3>
                            <div class="table-responsive">
                                <table id="data_table_no_pagging" class="display nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>LOWER LIMIT (IDR)</th>
                                            <th>UPPER LIMIT (IDR)</th>
                                            <th>RATE</th>
                                            <th>MINIMAL TAX (IDR)</th>
                                            <th>TAX ACCUMULATION (IDR)</th>
                                            {{--@if(\Auth::user()->project_id == 1)--}}
                                            <th>ACTION</th>
                                            {{--@endif--}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pph as $item)
                                        <tr>
                                            <td>{{ format_idr($item->batas_bawah) }}</td>
                                            <td>{{ $item->batas_atas ? format_idr($item->batas_atas) : '> '.format_idr($item->batas_bawah) }}</td>
                                            <td>{{ $item->tarif }}</td>
                                            <td>{{ format_idr($item->pajak_minimal) }}</td>
                                            <td>{{ format_idr($item->akumulasi_pajak) }}</td>
                                            {{--@if(\Auth::user()->project_id == 1)--}}
                                            <td>
                                                <!-- <a href="{{ route('administrator.payroll-setting.delete-pph', $item->id) }}" onclick="return confirm('Delete this data ?')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a> -->
                                                <a href="{{ route('administrator.payroll-setting.edit-pph', $item->id) }}" class="btn btn-info btn-xs"><i class="fa fa-edit"></i> edit </a>
                                            </td>
                                            {{--@endif--}}
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade {{ isset($tab) && $tab == 'ptkp' ? 'active in' : '' }}" id="ptkp">
                            <h3 class="box-title m-b-0">Setting PTKP</h3>
                            <div class="table-responsive">
                                <table class="display nowrap data_table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>SINGLE (IDR)</th>
                                            <th>MARRIED (IDR)</th>
                                            <th>MARRIED WITH 1 CHILD (IDR)</th>
                                            <th>MARRIED WITH 2 CHILD (IDR)</th>
                                            <th>MARRIED WITH 3 CHILD (IDR)</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       @foreach($ptkp as $item)
                                       <tr>
                                           <td>{{ format_idr($item->bujangan_wanita) }}</td>
                                           <td>{{ format_idr($item->menikah) }}</td>
                                           <td>{{ format_idr($item->menikah_anak_1) }}</td>
                                           <td>{{ format_idr($item->menikah_anak_2) }}</td>
                                           <td>{{ format_idr($item->menikah_anak_3) }}</td>
                                           <td>
                                            <a href="{{ route('administrator.payroll-setting.edit-ptkp', $item->id) }}" class="btn btn-info btn-xs"><i class="fa fa-edit"></i> edit</a>
                                           </td>
                                       </tr>
                                       @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade {{ isset($tab) && $tab == 'others' ? 'active in' : '' }}" id="others">
                            <h3 class="box-title m-b-0">Others Setting</h3>
                            <!-- <a href="{{ route('administrator.payroll-setting.add-others') }}" class="btn btn-info btn-sm" style="position: absolute;z-index: 99999;"><i class="fa fa-plus"></i> ADD OTHERS SETTING</a> -->
                            <div class="table-responsive">
                                <table class="display nowrap data_table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="70" class="text-center">NO</th>
                                            <th>LABEL</th>
                                            <th>VALUE (IDR)</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       @foreach($others as $no => $item)
                                        <tr>
                                            <td>{{ $no+1 }}</td>
                                            <td>{{ $item->label }}</td>
                                            <td>{{ format_idr($item->value) }}</td>
                                            <td>
                                                <a href="{{ route('administrator.payroll-setting.edit-others', $item->id) }}" class="btn btn-info btn-xs"><i class="fa fa-edit"></i> edit </a>
                                            </td>
                                        </tr>
                                       @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade {{ isset($tab) && $tab == 'npwp' ? 'active in' : '' }}" id="npwp">
                            <div class="col-lg-12 col-sm-8 col-md-8 col-xs-12">
                                <button type="submit" class="btn btn-sm btn-info waves-effect waves-light m-r-10 pull-left" onclick="document.getElementById('form-setting-npwp').submit();"><i class="fa fa-save"></i> Save Setting </button>
                            </div>
                            <br><br><br><br>
                            <h3 class="box-title m-b-0">NPWP Setting</h3>
                            <form method="POST" id="form-setting-npwp" action="{{ route('administrator.payroll-setting.store-npwp') }}" class="form-horizontal">
                                {{ csrf_field() }}
                                <div class="table-responsive">
                                    <br>
                                    <div class="col-md-12 p-l-0">
                                        <div class="form-group">
                                            <label class="col-md-2">Nama Perusahaan </label>
                                            <div class="col-md-2">
                                                <div class="input-form">
                                                    <input type="hidden" name="label[]" value="Nama Perusahaan" class="form-control" />
                                                    <input type="text" name="npwp[]" value="{{ get_setting_payroll('1') }}" class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2">NPWP Perusahaan </label>
                                            <div class="col-md-2">
                                                <div class="input-form">
                                                    <input type="hidden" name="label[]" value="NPWP Perusahaan" class="form-control" />
                                                    <input type="text" name="npwp[]" value="{{ get_setting_payroll('2') }}" class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <label class="col-md-2">Nama Pemotong </label>
                                            <div class="col-md-2">
                                                <div class="input-form">
                                                    <input type="hidden" name="label[]" value="Nama Pemotong" class="form-control" />
                                                    <input type="text" name="npwp[]" value="{{ get_setting_payroll('3') }}" class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2">NPWP Pemotong </label>
                                            <div class="col-md-2">
                                                <div class="input-form">
                                                    <input type="hidden" name="label[]" value="NPWP Pemotong" class="form-control" />
                                                    <input type="text" name="npwp[]" value="{{ get_setting_payroll('4') }}" class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <!--table class="display nowrap data_table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="70" class="text-center">#</th>
                                            <th>LABEL</th>
                                            <th>VALUE (IDR)</th>
                                            @if(\Auth::user()->project_id == 1)
                                            <th>#</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                       @foreach($npwp as $no => $item)
                                        <tr>
                                            <td>{{ $no+1 }}</td>
                                            <td>{{ $item->label }}</td>
                                            <td>{{ $item->value }}</td>
                                            @if(\Auth::user()->project_id == 1)
                                            <td>
                                                <a href="{{ route('administrator.payroll-setting.edit-npwp', $item->id) }}" class="btn btn-info btn-xs"><i class="fa fa-edit"></i> edit </a>
                                            </td>
                                            @endif
                                        </tr>
                                       @endforeach
                                    </tbody>
                                </table-->
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade {{ isset($tab) && $tab == 'umr' ? 'active in' : '' }}" id="umr">
                            <h3 class="box-title m-b-0">UMR Setting</h3>
                            <br>
                            <div class="table-responsive">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <a href="{{ route('administrator.payroll-setting.add-umr') }}" class="btn btn-info btn-sm"><i class="fa fa-plus"></i> Add UMR</a>
                                    </div>
                                </div>
                                <table class="display nowrap data_table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="70" class="text-center">NO</th>
                                            <th>REGION</th>
                                            <th>VALUE (IDR)</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       @foreach($umr as $no => $item)
                                        <tr>
                                            <td>{{ $no+1 }}</td>
                                            <td>{{ $item->label }}</td>
                                            <td>{{ format_idr($item->value) }}</td>
                                            <td>
                                                <button onclick="assignToUMR('{{ $item->id }}')" type="button" class="btn btn-primary btn-xs" style="float: left; margin-right:10px"><i class="ti-check"></i> assign</button>
                                                <a href="{{ route('administrator.payroll-setting.edit-umr', $item->id) }}" class="btn btn-info btn-xs" style="float: left; margin-right:10px"><i class="fa fa-edit"></i> edit </a>
                                                <form action="{{ route('administrator.payroll-setting.delete-umr', $item->id) }}" onsubmit="return confirm('Delete this data?')" method="post">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <button type="submit" class="btn btn-danger btn-xs m-r-5"><i class="ti-trash"></i> delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                       @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade {{ isset($tab) && $tab == 'cycleAttendance' ? 'active in' : '' }}" id="cycleAttendance">
                            <div class="col-lg-12 col-sm-8 col-md-8 col-xs-12">
                                <button type="submit" class="btn btn-sm btn-info waves-effect waves-light m-r-10 pull-left" onclick="document.getElementById('form-setting-cycle').submit();"><i class="fa fa-save"></i> Save Setting </button>
                            </div>
                            <br><br><br><br>
                            <h3 class="box-title m-b-0">Default Attendance Cycle</h3>
                            <form method="POST" id="form-setting-cycle" action="{{ route('administrator.payroll-setting.store-cycle') }}" class="form-horizontal">
                                {{ csrf_field() }}
                                <div class="table-responsive">
                                    <br>
                                    <div class="col-md-12 p-l-0">
                                        <?php $cycle = get_payroll_cycle() ?>
                                        <div class="form-group">
                                            <label class="col-md-2">Start Date</label>
                                            <div class="col-md-2">
                                                <div class="input-form">
                                                    <select  id="start_date" class="form-control" disabled>
                                                        <option value="0" selected hidden>Select Start Date</option>
                                                        @for($i=1; $i<=31; $i++)
                                                            <option value="{{$i}}" {{($cycle && $cycle->start_date==$i)?"Selected":""}}>{{$i}}</option>
                                                        @endfor
                                                    </select>
                                                    <input type="hidden" name="start_date" value="{{($cycle)?$cycle->start_date:"0"}}"/>
                                                </div>
                                            </div>
                                            <div class="col-md-4" style="color: red" id="cycle_status">
                                                *Started from last month's attendance cycle
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <label class="col-md-2">End Date</label>
                                            <div class="col-md-2">
                                                <div class="input-form">
                                                    <select  id="end_date" name="end_date" class="form-control">
                                                        <option value="0" selected hidden>Select End Date</option>
                                                        @for($i=1; $i<=31; $i++)
                                                            <option value="{{$i}}" {{($cycle && $cycle->end_date==$i)?"Selected":""}}>{{$i}}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            @if($cycle)
                                <h3 class="box-title m-b-0">Custom Attendance Cycle</h3>
                                <br>
                                <div class="table-responsive">
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <a href="{{ route('administrator.payroll-setting.add-attendance-cycle') }}" class="btn btn-info btn-sm"><i class="fa fa-plus"></i> Add Attendance Cycle</a>
                                        </div>
                                    </div>
                                    <table class="display nowrap data_table" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th width="70" class="text-center">NO</th>
                                                <th>LABEL</th>
                                                <th>START DATE</th>
                                                <th>END DATE</th>
                                                <th>ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php $cycle = get_payroll_cycle('attendance_custom') ?>
                                        @foreach($cycle as $no => $item)
                                            <tr>
                                                <td>{{ $no+1 }}</td>
                                                <td>{{ $item->label }}</td>
                                                <td>{{ $item->start_date }}</td>
                                                <td>{{ $item->end_date }}</td>
                                                <td>
                                                    <button onclick="assignToAttendanceCycle('{{ $item->id }}')" type="button" class="btn btn-primary btn-xs" style="float: left; margin-right:10px"><i class="ti-check"></i> assign</button>
                                                    <a href="{{ route('administrator.payroll-setting.edit-attendance-cycle', $item->id) }}" class="btn btn-info btn-xs" style="float: left; margin-right:10px"><i class="fa fa-edit"></i> edit </a>
                                                    <form action="{{ route('administrator.payroll-setting.delete-attendance-cycle', $item->id) }}" onsubmit="return confirm('Delete this data?')" method="post">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                        <button type="submit" class="btn btn-danger btn-xs m-r-5"><i class="ti-trash"></i> delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                        <div role="tabpanel" class="tab-pane fade {{ isset($tab) && $tab == 'cyclePayroll' ? 'active in' : '' }}" id="cyclePayroll">
                            <div class="col-lg-12 col-sm-8 col-md-8 col-xs-12">
                                <button type="submit" class="btn btn-sm btn-info waves-effect waves-light m-r-10 pull-left" onclick="document.getElementById('form-setting-cycle-payroll').submit();"><i class="fa fa-save"></i> Save Setting </button>
                            </div>
                            <br><br><br><br>
                            <h3 class="box-title m-b-0">Default Payroll Cycle</h3>
                            <form method="POST" id="form-setting-cycle-payroll" action="{{ route('administrator.payroll-setting.store-cycle') }}" class="form-horizontal">
                                {{ csrf_field() }}
                                <input type="hidden" name="key_name" value="payroll" />
                                <div class="table-responsive">
                                    <br>
                                    <div class="col-md-12 p-l-0">
                                        <?php $cycle = get_payroll_cycle('payroll') ?>
                                        <div class="form-group">
                                            <label class="col-md-2">Start Date</label>
                                            <div class="col-md-2">
                                                <div class="input-form">
                                                    <select  id="start_date_payroll" class="form-control" disabled>
                                                        <option value="0" selected hidden>Select Start Date</option>
                                                        @for($i=1; $i<=31; $i++)
                                                            <option value="{{$i}}" {{($cycle && $cycle->start_date==$i)?"Selected":""}}>{{$i}}</option>
                                                        @endfor
                                                    </select>
                                                    <input type="hidden" name="start_date" value="{{($cycle)?$cycle->start_date:"0"}}"/>
                                                </div>
                                            </div>
                                            <div class="col-md-4" style="color: red" id="cycle_status_payroll">
                                                *Started from last month's payroll cycle
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <label class="col-md-2">End Date</label>
                                            <div class="col-md-2">
                                                <div class="input-form">
                                                    <select  id="end_date_payroll" name="end_date" class="form-control">
                                                        <option value="0" selected hidden>Select End Date</option>
                                                        @for($i=1; $i<=31; $i++)
                                                            <option value="{{$i}}" {{($cycle && $cycle->end_date==$i)?"Selected":""}}>{{$i}}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            @if($cycle)
                                <h3 class="box-title m-b-0">Custom Payroll Cycle</h3>
                                <br>
                                <div class="table-responsive">
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <a href="{{ route('administrator.payroll-setting.add-payroll-cycle') }}" class="btn btn-info btn-sm"><i class="fa fa-plus"></i> Add Payroll Cycle</a>
                                        </div>
                                    </div>
                                    <table class="display nowrap data_table" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th width="70" class="text-center">NO</th>
                                                <th>LABEL</th>
                                                <th>START DATE</th>
                                                <th>END DATE</th>
                                                <th>ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php $cycle = get_payroll_cycle('payroll_custom') ?>
                                        @foreach($cycle as $no => $item)
                                            <tr>
                                                <td>{{ $no+1 }}</td>
                                                <td>{{ $item->label }}</td>
                                                <td>{{ $item->start_date }}</td>
                                                <td>{{ $item->end_date }}</td>
                                                <td>
                                                    <button onclick="assignToPayrollCycle('{{ $item->id }}')" type="button" class="btn btn-primary btn-xs" style="float: left; margin-right:10px"><i class="ti-check"></i> assign</button>
                                                    <a href="{{ route('administrator.payroll-setting.edit-payroll-cycle', $item->id) }}" class="btn btn-info btn-xs" style="float: left; margin-right:10px"><i class="fa fa-edit"></i> edit </a>
                                                    <form action="{{ route('administrator.payroll-setting.delete-payroll-cycle', $item->id) }}" onsubmit="return confirm('Delete this data?')" method="post">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                        <button type="submit" class="btn btn-danger btn-xs m-r-5"><i class="ti-trash"></i> delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                        <div role="tabpanel" class="tab-pane fade {{ isset($tab) && $tab == 'lock' ? 'active in' : '' }}" id="lock">
                            <div class="col-lg-12 col-sm-8 col-md-8 col-xs-12">
                                <button type="submit" class="btn btn-sm btn-info waves-effect waves-light m-r-10 pull-left" onclick="submitLock()"><i class="fa fa-save"></i> Save Setting </button>
                            </div>
                            <br><br><br><br>
                            <h3 class="box-title m-b-0">Payroll Lock Type</h3>
                            <form method="POST" id="form-setting-lock" action="{{ route('administrator.payroll-setting.store-lock') }}" class="form-horizontal">
                                {{ csrf_field() }}
                                <div class="table-responsive">
                                    <br>
                                    <div class="col-md-12 p-l-0">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1" name="button_lock" id="button_lock" {{ $lock[0]->value ? "checked" : "" }}>
                                            <label class="form-check-label" style="margin-left: 5px;">
                                                Lock by click lock button
                                            </label>
                                        </div>
                                        <br>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1" name="payslip_lock" id="payslip_lock" {{ $lock[1]->value ? "checked" : "" }}>
                                            <label class="form-check-label" style="margin-left: 5px;">
                                                Lock by send payslip
                                            </label>
                                        </div>
                                        <br>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1" name="schedule_lock" id="schedule_lock" {{ $lock[2]->value ? "checked" : "" }}>
                                            <label class="form-check-label" style="margin-left: 5px;">
                                                Auto lock 1 month after cutoff
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div role="tabpanel" class="tab-pane fade {{ isset($tab) && $tab == 'prorate' ? 'active in' : '' }}" id="prorate">
                            <div class="col-lg-12 col-sm-8 col-md-8 col-xs-12">
                                <button type="submit" class="btn btn-sm btn-info waves-effect waves-light m-r-10 pull-left" onclick="submitProrate()"><i class="fa fa-save"></i> Save Setting </button>
                            </div>
                            <br><br><br><br>
                            <h3 class="box-title m-b-0">Payroll Prorate Setting</h3>
                            <form method="POST" id="form-setting-prorate" action="{{ route('administrator.payroll-setting.store-prorate') }}" class="form-horizontal">
                                {{ csrf_field() }}
                                <div class="table-responsive">
                                    <br>
                                    <div class="col-md-12 p-l-0">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="button_prorate" {{ $prorate ? "checked" : "" }}>
                                            <label class="form-check-label" style="margin-left: 5px;">
                                                Use Prorate
                                            </label>
                                        </div>
                                        <br>
                                    </div>
                                    <div class="col-md-11">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="button_prorate_type" id="button_prorate_type1" value="1" {{ $prorate ? "" : "disabled" }} {{ $prorate == 1 ? "checked" : "" }}>
                                            <label class="form-check-label" style="margin-left: 5px;">
                                                Prorate by Calendar Day
                                            </label>
                                        </div>
                                        <br>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="button_prorate_type" id="button_prorate_type2" value="2" {{ $prorate ? "" : "disabled" }} {{ $prorate == 2 ? "checked" : "" }}>
                                            <label class="form-check-label" style="margin-left: 5px;">
                                                Prorate by Active Day
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div role="tabpanel" class="tab-pane fade {{ isset($tab) && $tab == 'country' ? 'active in' : '' }}" id="country">
                            <h3 class="box-title m-b-0">Country Code</h3>
                            <br>
                            <div class="table-responsive">
                                <div class="row">
                                    <div class="form-group col-md-2">
                                        <a href="{{ route('administrator.payroll-setting.add-country') }}" class="btn btn-info btn-sm" style="position: absolute;z-index: 99999;"><i class="fa fa-plus"></i> Add Country</a>
                                    </div>
                                    <div class="form-group col-md-10">
                                        <a href="javascript:void(0)" class="btn btn-info btn-sm pull-right" data-toggle="modal" data-target="#modal_import_country">Import Excel <i class="fa fa-upload"></i></a>
                                    </div>
                                </div>
                                <table class="display nowrap data_table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="70" class="text-center">NO</th>
                                            <th>NAME</th>
                                            <th>CODE</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       @foreach($country as $no => $item)
                                        <tr>
                                            <td>{{ $no+1 }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->code }}</td>
                                            <td>
                                                <a href="{{ route('administrator.payroll-setting.edit-country', $item->id) }}" class="btn btn-info btn-xs"><i class="fa fa-edit"></i> edit </a>
                                                <button type="button" onclick="$(this).next('form').submit()" class="btn btn-danger btn-xs" style="margin-left: 5px"><i class="fa fa-trash"></i> delete </button>
                                                <form type="hidden" method="POST" action="{{ route('administrator.payroll-setting.delete-country', $item->id) }}" onsubmit="return confirm('Delete this country data?')">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                </form>
                                            </td>
                                        </tr>
                                       @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </div>
    @include('layouts.footer')
</div>

<div id="modal_import_country" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Import Data</h4>
            </div>
            <form method="POST" id="form-upload" enctype="multipart/form-data" class="form-horizontal frm-modal-education" action="{{ route('administrator.payroll-setting.import-country') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-3">File (xls)</label>
                        <div class="col-md-9">
                            <input type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" name="file" class="form-control" />
                        </div>
                    </div>
                    <a href="{{ asset('storage/sample/Sample-Country.xlsx') }}"><i class="fa fa-download"></i> Download Sample Excel</a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                    <label class="btn btn-info btn-sm" id="btn_import">Import</label>
                </div>
            </form>
            <div style="text-align: center;display: none;" class="div-proses-upload">
                <h3>Uploading !</h3>
                <h1 class=""><i class="fa fa-spin fa-spinner"></i></h1>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-add-deductions" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Deductions</h4> </div>
                <form method="POST" id="form-upload" enctype="multipart/form-data" class="form-horizontal frm-modal-education" action="{{ route('administrator.payroll-setting.store-deductions') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-2">Title</label>
                        <div class="col-md-9">
                            <input type="text" name="title" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2">Status</label>
                        <div class="col-md-9">
                            <select class="form-control" name="taxable">
                                <option value="1">Taxable</option>
                                <option value="0">Untaxable</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                    <button type="submit" class="btn btn-info btn-sm"><i class="fa fa-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade modal-add-earnings" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Earnings</h4> </div>
                <form method="POST" id="form-upload" enctype="multipart/form-data" class="form-horizontal frm-modal-education" action="{{ route('administrator.payroll-setting.store-earnings') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-2">Title</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="title" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2">Status</label>
                        <div class="col-md-9">
                            <select class="form-control" name="taxable">
                                <option value="1">Taxable</option>
                                <option value="0">Untaxable</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                    <button type="submit" class="btn btn-info btn-sm" id="btn_import"><i class="fa fa-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- modal content education  -->
<div id="modal_import" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Import Data</h4> </div>
                <form method="POST" id="form-upload" enctype="multipart/form-data" class="form-horizontal frm-modal-education" action="{{ route('administrator.plafond-dinas.import') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-3">Business Trip Type</label>
                        <div class="col-md-9">
                            <select name="jenis_plafond" class="form-control">
                                <option>Local</option>
                                <option>Abroad</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3">File (xls)</label>
                        <div class="col-md-9">
                            <input type="file" name="file" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                    <label class="btn btn-info btn-sm" id="btn_import">Import</label>
                </div>
            </form>
            <div style="text-align: center;display: none;" class="div-proses-upload">
                <h3>Process upload, please wait !</h3>
                <h1 class=""><i class="fa fa-spin fa-spinner"></i></h1>
            </div>
        </div>
    </div>
</div>

<div class="modal fade none-border" id="modal-assign">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><strong>Assign Users to UMR</strong></h4>
            </div>
            <div class="modal-body" id="modal-assign-body">
                <div class="table-responsive">
                    <input type="hidden" id="UmrId">
                    <input type="text" class="form-control" id="searchUser">
                    <br />
                    <table class="table" id="tableList">
                        <tr>
                            <th><input type="checkbox" id="checkTopUser"></th>
                            <th>NO</th>
                            <th>NIK</th>
                            <th>NAME</th>
                            <th>POSITION</th>
                            <th>DIVISION</th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade none-border" id="modal-assign-payroll-cycle">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><strong>Assign Users to Payroll Cycle</strong></h4>
            </div>
            <div class="modal-body" id="modal-assign-body">
                <div class="table-responsive">
                    <input type="hidden" id="PayrollCycleId">
                    <input type="text" class="form-control" id="searchUserPayrollCycle">
                    <br />
                    <table class="table" id="tableListPayrollCycle">
                        <tr>
                            <th><input type="checkbox" id="checkTopUserPayrollCycle"></th>
                            <th>NO</th>
                            <th>NIK</th>
                            <th>NAME</th>
                            <th>POSITION</th>
                            <th>DIVISION</th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade none-border" id="modal-assign-attendance-cycle">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><strong>Assign Users to Attendance Cycle</strong></h4>
            </div>
            <div class="modal-body" id="modal-assign-body">
                <div class="table-responsive">
                    <input type="hidden" id="AttendanceCycleId">
                    <input type="text" class="form-control" id="searchUserAttendanceCycle">
                    <br />
                    <table class="table" id="tableListAttendanceCycle">
                        <tr>
                            <th><input type="checkbox" id="checkTopUserAttendanceCycle"></th>
                            <th>NO</th>
                            <th>NIK</th>
                            <th>NAME</th>
                            <th>POSITION</th>
                            <th>DIVISION</th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@section('footer-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<script type="text/javascript">
    var start_date = $('#start_date').val();
    var start_date_payroll = $('#start_date_payroll').val();
    var payroll_cycle = "{{ get_payroll_cycle('payroll') }}";
    calculate_cycle();
    calculate_cycle_payroll();
    $("#btn_import").click(function(){

        $("#form-upload").submit();
        $("#form-upload").hide();
        $('.div-proses-upload').show();
    });

    $("#add-import-karyawan").click(function(){
        $("#modal_import").modal("show");
        $('.div-proses-upload').hide();
        $("#form-upload").show();
    });
    $('#end_date').on('change',function () {
        end_date = $(this).val();
        if(end_date == 0){
            start_date = 0;
        }
        else if(end_date == 31){
            start_date = 1;
        }else{
            start_date = parseInt(end_date) + 1;
        }
        $('#start_date').val(start_date);
        $("#form-setting-cycle input[name='start_date']").val(start_date);
        calculate_cycle();
    });
    $('#end_date_payroll').on('change',function () {
        end_date_payroll = $(this).val();
        if(end_date_payroll == 0){
            start_date_payroll = 0;
        }
        else if(end_date_payroll == 31){
            start_date_payroll = 1;
        }else{
            start_date_payroll = parseInt(end_date_payroll) + 1;
        }
        $('#start_date_payroll').val(start_date_payroll);
        $("#form-setting-cycle-payroll input[name='start_date']").val(start_date_payroll);
        calculate_cycle_payroll();
    });

    function calculate_cycle() {
        if(start_date > end_date){
            $('#cycle_status').removeClass('hidden');
        }
        else{
            $('#cycle_status').addClass('hidden');
        }
    }
    function calculate_cycle_payroll() {
        if(start_date_payroll > end_date_payroll){
            $('#cycle_status_payroll').removeClass('hidden');
        }
        else{
            $('#cycle_status_payroll').addClass('hidden');
        }
    }

    function submitLock() {
        if (!$('#button_lock').prop('checked') && !$('#payslip_lock').prop('checked') && !$('#schedule_lock').prop('checked'))
            swal("Choose at least 1 option", "", "error")
        else if (!payroll_cycle && $('#schedule_lock').prop('checked'))
            swal("Select date and save payroll cycle before use this option", "", "error")
        else
            $('#form-setting-lock').submit()
    }

    function submitProrate() {
        if ($('#button_prorate').prop('checked') && !$('#button_prorate_type1').prop('checked') && !$('#button_prorate_type2').prop('checked'))
            swal("Choose prorate by calendar/active day if use prorate", "", "error")
        else if (!payroll_cycle && $('#button_prorate').prop('checked'))
            swal("Select date and save payroll cycle before use this option", "", "error")
        else
            $('#form-setting-prorate').submit()
    }

    $("#button_prorate").change(function() {
        if(this.checked)
            $('#button_prorate_type1, #button_prorate_type2').prop("disabled", false)
        else
            $('#button_prorate_type1, #button_prorate_type2').prop("disabled", true).removeAttr('checked')
    });

    function assignToUMR(v) {
        $('#searchUser').val('')

        $.ajax({
            url: "{{ route('administrator.payroll-setting.user-list-for-assignment') }}",
            type: "GET",
            dataType: "JSON",
            contentType: "application/json",
            processData: false,
            success: function (data) {
                if (data.message == 'success') {
                    $('#UmrId').val(v)
                    $('#tableList').find('tr:gt(0)').remove()
                    let checkAll = data.data.length ? true : false
                    for (let i = 0; i < data.data.length; i++) {
                        let num = i + 1;
                        let pos = data.data[i].position != null ? data.data[i].position : '-'
                        let div = data.data[i].division != null ? data.data[i].division : '-'
                        $('#tableList tr:last').after(
                            '<tr class="search">' +
                            '<td><input id="checkUser' + num +
                            '" type="checkbox" class="checkUser" ' + (data.data[i]
                                .payroll_umr_id == v ? 'checked' : '') + '></td>' +
                            '<td><input id="idUser-' + num + '" type="hidden" value="' + data.data[i]
                            .id + '">' + num + '</td>' +
                            '<td>' + data.data[i].nik + '</td>' +
                            '<td>' + data.data[i].name + '</td>' +
                            '<td>' + pos + '</td>' +
                            '<td>' + div + '</td>' +
                            '</tr>'
                        )
                        if (data.data[i].payroll_umr_id != v)
                            checkAll = false
                    }
                    $('#tableList tr:last').after(
                        '<tr>' +
                        '<td colspan="6"><button id="assignUmr" type="button" class="btn btn-primary btn-xs m-r-5"><i class="ti-check"></i> Assign</button>' +
                        '</tr>'
                    )
                    $('#modal-assign').modal('show')
                    assignUmr()
                    defaultCheckAll(checkAll)
                    $('.checkUser').click(function () {
                        defaultCheckAll()
                    })
                } else {
                    $('#tableList').find('tr:gt(0)').remove()
                    $('#tableList tr:last').after(
                        '<tr>' +
                        '<td colspan="6">No data.</td>' +
                        '</tr>'
                    )
                    $('#modal-assign').modal('show')
                }
            }
        })
    }

    function assignUmr() {
        $('#assignUmr').click(function () {

            var arr_check = []
            var arr_uncheck = []
            var id_user = []
            var id_user_uncheck = []
            var payroll_umr_id = $('#UmrId').val()

            $('.checkUser').each(function (i) {
                if ($(this).prop('checked') == true)
                    arr_check.push(i + 1)
                else
                    arr_uncheck.push(i + 1)
            })

            for (var i = 0; i < arr_check.length; i++) {
                id_user.push($('#idUser-' + arr_check[i]).val())
            }

            for (var i = 0; i < arr_uncheck.length; i++) {
                id_user_uncheck.push($('#idUser-' + arr_uncheck[i]).val())
            }

            $.ajax({
                url: "{{ route('administrator.payroll-setting.assign-umr') }}",
                type: "POST",
                data: {
                    '_token': '{{csrf_token()}}',
                    'payroll_umr_id': payroll_umr_id,
                    'user_id': id_user,
                    'user_id_uncheck': id_user_uncheck
                },
                dataType: "JSON",
                success: function (data) {
                    swal({
                        title: "Success!",
                        text: data.message,
                        type: "success"
                    }).then(function () {
                        $('#modal-assign').modal('hide')
                    });
                },
            })
        })
    }

    function defaultCheckAll(checkAll = false) {
        if (($('.checkUser:visible:checked').length == $('.checkUser:visible').length && $('.checkUser:visible')
                .length) || checkAll)
            $('#checkTopUser').prop('checked', true)
        else
            $('#checkTopUser').prop('checked', false)
    }

    $(function () {
        $('#searchUser').keyup(function () {
            var val = $(this).val().toLowerCase()
            $('#tableList tr.search').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1)
            })
            defaultCheckAll()
        })
        $('#checkTopUser').click(function () {
            $('.checkUser:visible').prop('checked', $(this).prop('checked'))
        })
    });

    function assignToPayrollCycle(v) {
        $('#searchUserPayrollCycle').val('')

        $.ajax({
            url: "{{ route('administrator.payroll-setting.user-list-for-assignment-payroll-cycle') }}",
            type: "GET",
            dataType: "JSON",
            contentType: "application/json",
            processData: false,
            success: function (data) {
                if (data.message == 'success') {
                    $('#PayrollCycleId').val(v)
                    $('#tableListPayrollCycle').find('tr:gt(0)').remove()
                    let checkAll = data.data.length ? true : false
                    for (let i = 0; i < data.data.length; i++) {
                        let num = i + 1;
                        let pos = data.data[i].position != null ? data.data[i].position : '-'
                        let div = data.data[i].division != null ? data.data[i].division : '-'
                        $('#tableListPayrollCycle tr:last').after(
                            '<tr class="searchPayrollCycle">' +
                            '<td><input id="checkUserPayrollCycle' + num +
                            '" type="checkbox" class="checkUserPayrollCycle" ' + (data.data[i]
                                .payroll_cycle_id == v ? 'checked' : '') + '></td>' +
                            '<td><input id="idUserPayrollCycle-' + num + '" type="hidden" value="' + data.data[i]
                            .id + '">' + num + '</td>' +
                            '<td>' + data.data[i].nik + '</td>' +
                            '<td>' + data.data[i].name + '</td>' +
                            '<td>' + pos + '</td>' +
                            '<td>' + div + '</td>' +
                            '</tr>'
                        )
                        if (data.data[i].payroll_cycle_id != v)
                            checkAll = false
                    }
                    $('#tableListPayrollCycle tr:last').after(
                        '<tr>' +
                        '<td colspan="6"><button id="assignPayrollCycle" type="button" class="btn btn-primary btn-xs m-r-5"><i class="ti-check"></i> Assign</button>' +
                        '</tr>'
                    )
                    $('#modal-assign-payroll-cycle').modal('show')
                    assignPayrollCycle()
                    defaultCheckAllPayrollCycle(checkAll)
                    $('.checkUserPayrollCycle').click(function () {
                        defaultCheckAllPayrollCycle()
                    })
                } else {
                    $('#tableListPayrollCycle').find('tr:gt(0)').remove()
                    $('#tableListPayrollCycle tr:last').after(
                        '<tr>' +
                        '<td colspan="6">No data.</td>' +
                        '</tr>'
                    )
                    $('#modal-assign-payroll-cycle').modal('show')
                }
            }
        })
    }

    function assignPayrollCycle() {
        $('#assignPayrollCycle').click(function () {

            var arr_check = []
            var arr_uncheck = []
            var id_user = []
            var id_user_uncheck = []
            var payroll_cycle_id = $('#PayrollCycleId').val()

            $('.checkUserPayrollCycle').each(function (i) {
                if ($(this).prop('checked') == true)
                    arr_check.push(i + 1)
                else
                    arr_uncheck.push(i + 1)
            })

            for (var i = 0; i < arr_check.length; i++) {
                id_user.push($('#idUserPayrollCycle-' + arr_check[i]).val())
            }

            for (var i = 0; i < arr_uncheck.length; i++) {
                id_user_uncheck.push($('#idUserPayrollCycle-' + arr_uncheck[i]).val())
            }

            $.ajax({
                url: "{{ route('administrator.payroll-setting.assign-payroll-cycle') }}",
                type: "POST",
                data: {
                    '_token': '{{csrf_token()}}',
                    'payroll_cycle_id': payroll_cycle_id,
                    'user_id': id_user,
                    'user_id_uncheck': id_user_uncheck
                },
                dataType: "JSON",
                success: function (data) {
                    swal({
                        title: "Success!",
                        text: data.message,
                        type: "success"
                    }).then(function () {
                        $('#modal-assign-payroll-cycle').modal('hide')
                    });
                },
            })
        })
    }

    function defaultCheckAllPayrollCycle(checkAll = false) {
        if (($('.checkUserPayrollCycle:visible:checked').length == $('.checkUserPayrollCycle:visible').length && $('.checkUserPayrollCycle:visible')
                .length) || checkAll)
            $('#checkTopUserPayrollCycle').prop('checked', true)
        else
            $('#checkTopUserPayrollCycle').prop('checked', false)
    }

    $(function () {
        $('#searchUserPayrollCycle').keyup(function () {
            var val = $(this).val().toLowerCase()
            $('#tableListPayrollCycle tr.searchPayrollCycle').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1)
            })
            defaultCheckAllPayrollCycle()
        })
        $('#checkTopUserPayrollCycle').click(function () {
            $('.checkUserPayrollCycle:visible').prop('checked', $(this).prop('checked'))
        })
    });

    function assignToAttendanceCycle(v) {
        $('#searchUserAttendanceCycle').val('')

        $.ajax({
            url: "{{ route('administrator.payroll-setting.user-list-for-assignment-attendance-cycle') }}",
            type: "GET",
            dataType: "JSON",
            contentType: "application/json",
            processData: false,
            success: function (data) {
                if (data.message == 'success') {
                    $('#AttendanceCycleId').val(v)
                    $('#tableListAttendanceCycle').find('tr:gt(0)').remove()
                    let checkAll = data.data.length ? true : false
                    for (let i = 0; i < data.data.length; i++) {
                        let num = i + 1;
                        let pos = data.data[i].position != null ? data.data[i].position : '-'
                        let div = data.data[i].division != null ? data.data[i].division : '-'
                        $('#tableListAttendanceCycle tr:last').after(
                            '<tr class="searchAttendanceCycle">' +
                            '<td><input id="checkUserAttendanceCycle' + num +
                            '" type="checkbox" class="checkUserAttendanceCycle" ' + (data.data[i]
                                .attendance_cycle_id == v ? 'checked' : '') + '></td>' +
                            '<td><input id="idUserAttendanceCycle-' + num + '" type="hidden" value="' + data.data[i]
                            .id + '">' + num + '</td>' +
                            '<td>' + data.data[i].nik + '</td>' +
                            '<td>' + data.data[i].name + '</td>' +
                            '<td>' + pos + '</td>' +
                            '<td>' + div + '</td>' +
                            '</tr>'
                        )
                        if (data.data[i].attendance_cycle_id != v)
                            checkAll = false
                    }
                    $('#tableListAttendanceCycle tr:last').after(
                        '<tr>' +
                        '<td colspan="6"><button id="assignAttendanceCycle" type="button" class="btn btn-primary btn-xs m-r-5"><i class="ti-check"></i> Assign</button>' +
                        '</tr>'
                    )
                    $('#modal-assign-attendance-cycle').modal('show')
                    assignAttendanceCycle()
                    defaultCheckAllAttendanceCycle(checkAll)
                    $('.checkUserAttendanceCycle').click(function () {
                        defaultCheckAllAttendanceCycle()
                    })
                } else {
                    $('#tableListAttendanceCycle').find('tr:gt(0)').remove()
                    $('#tableListAttendanceCycle tr:last').after(
                        '<tr>' +
                        '<td colspan="6">No data.</td>' +
                        '</tr>'
                    )
                    $('#modal-assign-attendance-cycle').modal('show')
                }
            }
        })
    }

    function assignAttendanceCycle() {
        $('#assignAttendanceCycle').click(function () {

            var arr_check = []
            var arr_uncheck = []
            var id_user = []
            var id_user_uncheck = []
            var attendance_cycle_id = $('#AttendanceCycleId').val()

            $('.checkUserAttendanceCycle').each(function (i) {
                if ($(this).prop('checked') == true)
                    arr_check.push(i + 1)
                else
                    arr_uncheck.push(i + 1)
            })

            for (var i = 0; i < arr_check.length; i++) {
                id_user.push($('#idUserAttendanceCycle-' + arr_check[i]).val())
            }

            for (var i = 0; i < arr_uncheck.length; i++) {
                id_user_uncheck.push($('#idUserAttendanceCycle-' + arr_uncheck[i]).val())
            }

            $.ajax({
                url: "{{ route('administrator.payroll-setting.assign-attendance-cycle') }}",
                type: "POST",
                data: {
                    '_token': '{{csrf_token()}}',
                    'attendance_cycle_id': attendance_cycle_id,
                    'user_id': id_user,
                    'user_id_uncheck': id_user_uncheck
                },
                dataType: "JSON",
                success: function (data) {
                    swal({
                        title: "Success!",
                        text: data.message,
                        type: "success"
                    }).then(function () {
                        $('#modal-assign-attendance-cycle').modal('hide')
                    });
                },
            })
        })
    }

    function defaultCheckAllAttendanceCycle(checkAll = false) {
        if (($('.checkUserAttendanceCycle:visible:checked').length == $('.checkUserAttendanceCycle:visible').length && $('.checkUserAttendanceCycle:visible')
                .length) || checkAll)
            $('#checkTopUserAttendanceCycle').prop('checked', true)
        else
            $('#checkTopUserAttendanceCycle').prop('checked', false)
    }

    $(function () {
        $('#searchUserAttendanceCycle').keyup(function () {
            var val = $(this).val().toLowerCase()
            $('#tableListAttendanceCycle tr.searchAttendanceCycle').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1)
            })
            defaultCheckAllAttendanceCycle()
        })
        $('#checkTopUserAttendanceCycle').click(function () {
            $('.checkUserAttendanceCycle:visible').prop('checked', $(this).prop('checked'))
        })
    });
</script>
@endsection

@endsection
