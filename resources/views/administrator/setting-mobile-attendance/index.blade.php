@extends('layouts.administrator')

@section('title', 'Mobile Attendance')

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
                <h4 class="page-title">Setting Mobile Attendance</h4> 
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                
                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Mobile Attendance</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>

        <!-- .row -->
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">

                     <ul class="nav customtab nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#type" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs"> General </span></a></li>
                        <li role="presentation" class=""><a href="#plafond" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs"> Remote (Out of office)</span></a></li>
                    </ul>

                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="type">
                            <h3 class="box-title m-b-0">General Setting</h3>
                            <div class="row">
                                <form class="form-horizontal" id="form-setting-mobile" enctype="multipart/form-data" name="form_setting_mobile" action="{{ route('administrator.attendance.setting-save') }}" method="POST">
                                    {{ csrf_field() }}
                                    <div class="white-box">
                                        <div class="table-responsive">
                                            <div class="col-md-6 p-l-0 p-r-0">
                                                <div class="form-group">
                                                    <label class="col-md-12">Logo</label>
                                                    <div class="col-md-6">
                                                        <input type="file" class="form-control" name="attendance_logo" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        @if(!empty(get_setting('attendance_logo')))
                                                            <img src="{{ get_setting('attendance_logo') }}" style="height: 50px; " />
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-12">Name Company</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control" name="setting_mobile[attendance_company]" value="{{ get_setting('attendance_company') }}" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-12">Notification / News / Memo</label>
                                                    <div class="col-md-12">
                                                        <textarea name="setting_mobile[attendance_news]" class="form-control">{{ get_setting('attendance_news') }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <input type="checkbox" id="attendance_face_detection" class="switch-input" {{ get_setting('attendance_face_detection') ? 'checked' : '' }}/>
                                                        <label style="margin-left: 5px;">Face Detection Required</label>
                                                        <input type="radio" name="setting_mobile[attendance_face_detection]" id="attendance_face_detection_on" value="1" {{ get_setting('attendance_face_detection') ? 'checked' : '' }} hidden>
                                                        <input type="radio" name="setting_mobile[attendance_face_detection]" id="attendance_face_detection_off" value="0" {{ get_setting('attendance_face_detection') ? '' : 'checked' }} hidden>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <input type="checkbox" id="attendance_notification" class="switch-input" {{ get_setting('attendance_notification') ? 'checked' : '' }}/>
                                                        <label style="margin-left: 5px;">Clock Time Notification</label>
                                                        <input type="radio" name="setting_mobile[attendance_notification]" id="attendance_notification_on" value="1" {{ get_setting('attendance_notification') ? 'checked' : '' }} hidden>
                                                        <input type="radio" name="setting_mobile[attendance_notification]" id="attendance_notification_off" value="0" {{ get_setting('attendance_notification') ? '' : 'checked' }} hidden>
                                                    </div>
                                                </div>
                                                <div class="form-group" id="attendance_notification_before" {{ get_setting('attendance_notification') ? '' : 'style=display:none' }}>
                                                    <label class="col-md-12">Notify Before Clock Time in Minutes</label>
                                                    <div class="col-md-8">
                                                        <input type="number" min=0 class="form-control" name="setting_mobile[attendance_notification_before]" value="{{ get_setting('attendance_notification_before') }}" {{ get_setting('attendance_notification') ? 'required' : '' }} />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Save Setting</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="plafond">
                            <h3 class="box-title m-b-0">Remote (Out of Office) Setting</h3>

                            <br />
                            <form class="form-horizontal" id="form-setting-mobile" enctype="multipart/form-data" name="form_setting_mobile" action="{{ route('administrator.attendance.setting-remote-attendance') }}" method="POST">
                                {{ csrf_field() }}
                            <div class="table-responsive">
                                <div class="col-md-6">
                                    <table class="table table-condensed">
                                        <thead>
                                            <tr>
                                                <th width="50px" class="text-center">NO</th>
                                                <th>POSITION</th>
                                                <th> <input type="checkbox" id="check_all"/>Out Of Office</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php($positions = getAllPositions())
                                            @foreach($positions as $index => $position)
                                            <tr>
                                                <td class="text-center">{{$index+1}}</td>
                                                <td>{{$position['position']}}</td>
                                                <td>
                                                    <input type="checkbox" name="remote_attendance[]" value="{{$position['id']}}" {{ $position['remote_attendance'] == '1' ? 'checked' : '' }}>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Save Setting</button>
                                    </div>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div> 
        </div>
        <!-- ============================================================== -->
    </div>
    <!-- /.container-fluid -->
    @include('layouts.footer')
</div>
@section('footer-script')
<script type="text/javascript">
    $("#btn_import").click(function(){

        $("#form-upload").submit();
        $("#form-upload").hide();
        $('.div-proses-upload').show();
    });

    $("#add-import-karyawan").click(function(){
        $("#modal_import").modal("show");
        $('.div-proses-upload').hide();
        $("#form-upload").show();
    })
    $('#check_all').on('change',function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
    $('#attendance_face_detection').on('change',function () {
        if ($(this).is(':checked'))
            $("#attendance_face_detection_on").prop("checked", true);
        else
            $("#attendance_face_detection_off").prop("checked", true);
    });
    $('#attendance_notification').on('change',function () {
        if ($(this).is(':checked')) {
            $("#attendance_notification_on").prop("checked", true);
            $("#attendance_notification_before").show();
            $("#attendance_notification_before input").attr("required", true);
        }
        else {
            $("#attendance_notification_off").prop("checked", true);
            $("#attendance_notification_before").hide();
            $("#attendance_notification_before input").val('').removeAttr("required");
        }
    });
</script>
@endsection

@endsection
