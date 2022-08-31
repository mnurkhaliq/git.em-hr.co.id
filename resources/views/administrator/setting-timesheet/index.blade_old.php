@extends('layouts.administrator')

@section('title', 'Timesheet Setting')

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
                <h4 class="page-title">Setting Timesheet</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Timesheet</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>

        <!-- .row -->
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">

                    <ul class="nav customtab nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#Category" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs">Category</span></a></li>
                        <li role="presentation" class=""><a href="#Activity" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs">Activity</span></a></li>
                    </ul>

                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="Category">
                            <h3 class="box-title m-b-0">Setting for Timesheet Category Activity</h3>
                            <a href="{{ route('administrator.setting-timesheet.create') }}" class="btn btn-success btn-sm  hidden-sm waves-effect waves-light"> <i class="fa fa-plus"></i> Add Category</a>
                            <br />
                            <br />
                            <div class="table-responsive">
                                <table class="data_table_no_pagging display nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>Category Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($categories as $no => $item)
                                        <tr>
                                            <td>{{ $no+1 }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>
                                                <a href="{{ route('administrator.setting-timesheet.edit', $item->id) }}" style="float: left; margin-right:5px"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-edit"></i> edit</button></a>
                                                <form action="{{ route('administrator.setting-timesheet.destroy', $item->id) }}" onsubmit="return confirm('Delete category will also delete its activity, continue?')" method="post" style="margin-left: 5px;">
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
                        <div role="tabpanel" class="tab-pane fade" id="Activity">
                            <h3 class="box-title m-b-0">Setting for Timesheet Activity</h3>
                            <a href="{{ route('administrator.setting-timesheet.create-activity') }}" class="btn btn-success btn-sm  hidden-sm waves-effect waves-light"> <i class="fa fa-plus"></i> Add Activity</a>
                            <br />
                            <br />
                            <div class="table-responsive">
                                <table class="data_table_no_pagging display nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>Category Name</th>
                                            <th>Activity Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($activities as $no => $item)
                                        <tr>
                                            <td>{{ $no+1 }}</td>
                                            <td>{{ $item->timesheetCategory->name }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>
                                                <a href="{{ route('administrator.setting-timesheet.edit-activity', $item->id) }}" style="float: left; margin-right:5px"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-edit"></i> edit</button></a>
                                                <form action="{{ route('administrator.setting-timesheet.destroy-activity', $item->id) }}" onsubmit="return confirm('Delete this data?')" method="post" style="margin-left: 5px;">
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
<script>

</script>
@endsection

@endsection