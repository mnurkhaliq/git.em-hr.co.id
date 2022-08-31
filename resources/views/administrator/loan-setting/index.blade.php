@extends('layouts.administrator')

@section('title', 'Loan Setting')

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
                <h4 class="page-title">Setting Loan</h4>
            </div>
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
            <div class="col-md-12">
                <div class="white-box">

                    <ul class="nav customtab nav-tabs" role="tablist">
                        <li role="presentation" class="{{ !isset($tab) ? 'active' : '' }}"><a href="#Agreement" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs">Agreement</span></a></li>
                        <li role="presentation" class="{{ isset($tab) && $tab == 'purpose' ? 'active' : '' }}"><a href="#Purpose" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs">Purpose</span></a></li>
                        <li role="presentation" class="{{ isset($tab) && $tab == 'plafond' ? 'active' : '' }}"><a href="#Plafond" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs">Plafond</span></a></li>
                        <li role="presentation" class="{{ isset($tab) && $tab == 'rate' ? 'active' : '' }}"><a href="#Rate" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs">Tenor & Interest</span></a></li>
                        <li role="presentation" class="{{ isset($tab) && $tab == 'asset' ? 'active' : '' }}"><a href="#Asset" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs">Asset Type</span></a></li>
                    </ul>

                    <div class="tab-content">

                        <div role="tabpanel" class="tab-pane fade {{ !isset($tab) ? 'active in' : '' }}" id="Agreement">
                            <h3 class="box-title m-b-0">Setting Loan Agreement</h3>
                            <button type="button" class="btn btn-info btn-sm hidden-sm waves-effect waves-light" onclick="form_setting.submit()"> <i class="fa fa-save"></i> Save Setting</button>
                            <br />
                            <br />
                            <form class="form-horizontal" name="form_setting" enctype="multipart/form-data" action="{{ route('administrator.loan-setting.store') }}" method="POST">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label class="col-md-12">Term & Condition</label>
                                    <div class="col-md-12">
                                        <textarea class="form-control" name="setting[term_condition]" style="height: 120px;" id="ckeditor1">{{ get_setting('term_condition') }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Collateral Receipt</label>
                                    <div class="col-md-12">
                                        <textarea class="form-control" name="setting[collateral_receipt]" style="height: 120px;" id="ckeditor2">{{ get_setting('collateral_receipt') }}</textarea>
                                    </div>
                                    <label class="col-md-12 text-danger">*Use $collateral as uploaded collateral asset list</label>
                                </div>
                            </form>
                        </div>

                        <div role="tabpanel" class="tab-pane fade {{ isset($tab) && $tab == 'purpose' ? 'active in' : '' }}" id="Purpose">
                            <h3 class="box-title m-b-0">Setting Loan Purpose</h3>
                            <a href="{{ route('administrator.loan-setting.add-purpose') }}" class="btn btn-success btn-sm hidden-sm waves-effect waves-light"> <i class="fa fa-plus"></i> Add Purpose</a>
                            <br />
                            <br />
                            <div class="table-responsive">
                                <table class="data_table_no_pagging display nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>Purpose</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($purpose as $no => $item)
                                        <tr>
                                            <td>{{ $no+1 }}</td>
                                            <td>{{ $item->purpose }}</td>
                                            <td>
                                                <a href="{{ route('administrator.loan-setting.edit-purpose', $item->id) }}" style="float: left; margin-right:10px"> <button class="btn btn-info btn-xs"><i class="fa fa-edit"></i> edit</button></a>
                                                <form action="{{ route('administrator.loan-setting.delete-purpose', $item->id) }}" onsubmit="return confirm('Delete this data?')" method="post">
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

                        <div role="tabpanel" class="tab-pane fade {{ isset($tab) && $tab == 'plafond' ? 'active in' : '' }}" id="Plafond">
                            <h3 class="box-title m-b-0">Setting Loan Plafond</h3>
                            <a href="{{ route('administrator.loan-setting.add-plafond') }}" class="btn btn-success btn-sm hidden-sm waves-effect waves-light"> <i class="fa fa-plus"></i> Add Plafond</a>
                            <br />
                            <br />
                            <div class="table-responsive">
                                <table class="data_table_no_pagging display nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>Position</th>
                                            <th>Type</th>
                                            <th>Plafond</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($plafond as $no => $item)
                                        <tr>
                                            <td>{{ $no+1 }}</td>
                                            <td>{{ $item->position->name }}</td>
                                            <td>{{ $item->type == 1 ? 'Payroll Default Salary' : 'Custom' }}</td>
                                            <td>{{ format_idr($item->plafond) }}</td>
                                            <td>
                                                <a href="{{ route('administrator.loan-setting.edit-plafond', $item->id) }}" style="float: left; margin-right:10px"> <button class="btn btn-info btn-xs"><i class="fa fa-edit"></i> edit</button></a>
                                                <form action="{{ route('administrator.loan-setting.delete-plafond', $item->id) }}" onsubmit="return confirm('Delete this data?')" method="post">
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

                        <div role="tabpanel" class="tab-pane fade {{ isset($tab) && $tab == 'rate' ? 'active in' : '' }}" id="Rate">
                            <h3 class="box-title m-b-0">Setting Loan Tenor & Interest</h3>
                            <a href="{{ route('administrator.loan-setting.add-rate') }}" class="btn btn-success btn-sm hidden-sm waves-effect waves-light"> <i class="fa fa-plus"></i> Add Tenor & Interest</a>
                            <br />
                            <br />
                            <div class="table-responsive">
                                <table class="data_table_no_pagging display nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>Total Tenor(Month)</th>
                                            <th>Interest(%)</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($rate as $no => $item)
                                        <tr>
                                            <td>{{ $no+1 }}</td>
                                            <td>{{ $item->rate }}</td>
                                            <td>{{ $item->interest }}</td>
                                            <td>
                                                <a href="{{ route('administrator.loan-setting.edit-rate', $item->id) }}" style="float: left; margin-right:10px"> <button class="btn btn-info btn-xs"><i class="fa fa-edit"></i> edit</button></a>
                                                <form action="{{ route('administrator.loan-setting.delete-rate', $item->id) }}" onsubmit="return confirm('Delete this data?')" method="post">
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

                        <div role="tabpanel" class="tab-pane fade {{ isset($tab) && $tab == 'asset' ? 'active in' : '' }}" id="Asset">
                            <h3 class="box-title m-b-0">Setting Loan Asset Type</h3>
                            <a href="{{ route('administrator.loan-setting.add-asset') }}" class="btn btn-success btn-sm hidden-sm waves-effect waves-light"> <i class="fa fa-plus"></i> Add Asset Type</a>
                            <br />
                            <br />
                            <div class="table-responsive">
                                <table class="data_table_no_pagging display nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($asset as $no => $item)
                                        <tr>
                                            <td>{{ $no+1 }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>
                                                <a href="{{ route('administrator.loan-setting.edit-asset', $item->id) }}" style="float: left; margin-right:10px"> <button class="btn btn-info btn-xs"><i class="fa fa-edit"></i> edit</button></a>
                                                <form action="{{ route('administrator.loan-setting.delete-asset', $item->id) }}" onsubmit="return confirm('Delete this data?')" method="post">
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

                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.footer')
</div>
@section('footer-script')
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">
    CKEDITOR.replace('ckeditor1');
    CKEDITOR.replace('ckeditor2');
</script>
@endsection

@endsection