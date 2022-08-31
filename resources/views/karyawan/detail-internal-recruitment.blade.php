@extends('layouts.karyawan')

@section('title', 'Internal Recruitment Job Detail')

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
                <h4 class="page-title">HOME</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Home</a></li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>

        <div class="row">
            {{--<div class="col-lg-3 col-sm-3 col-md-3">--}}
            {{--</div>--}}
            <div class="col-lg-6 col-sm-6 col-md-6">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <i class="fa fa-user-plus"></i> Internal Recruitment Job Detail</h2>
                    </div>
                    <div class="panel-body panel-group">
                            <div class="col-md-12 panel" style="padding:0; font-size: small">
                                    <div  class="">
                                        <h4 style="margin-top: 0px; margin-bottom: 5px" class="mb-0">
                                            <a>
                                                {{ $vacancy->job_position }}
                                            </a>
                                        </h4>
                                        <p style="margin-bottom: 0px;">Published on: {{ date('d F Y',strtotime($vacancy->posting_date)) }}</p>
                                        @if($vacancy->last_posted_date)
                                        <p style="margin-bottom: 0px;">Expired on: {{ date('d F Y',strtotime($vacancy->last_posted_date)) }}</p>
                                        @endif
                                        <p style="margin-bottom: 10px;"></p>
                                        @if($vacancy->show_salary_range == '1')
                                            <p style="margin-bottom: 0px"><i class="fa fa-dollar"></i> IDR {{format_idr($vacancy->min_salary)." - ".format_idr($vacancy->max_salary)}}</p>
                                        @endif
                                        <p style="margin-bottom: 0px"><i class="fa fa-map-marker"></i> {{$vacancy->branch}}</p>
                                    </div>
                                    <div style="padding-top: 10px">
                                        <div class="">
                                            <p><b>Job Description : </b></p>
                                            {!! htmlspecialchars_decode($vacancy->job_desc) !!}
                                            <p><b>Job Requirement : </b></p>
                                            {!! htmlspecialchars_decode($vacancy->job_requirement) !!}
                                            <center>
                                                @if(getInternalApplicationByUser($vacancy->recruitment_id))
                                                    <button class="btn btn-sm btn-default"><i class="fa fa-check"></i> Applied</button>
                                                @else
                                                    <button class="btn btn-sm btn-success" onclick="apply({{$vacancy->recruitment_id}})"><i class="fa fa-envelope"></i> Apply</button>
                                                @endif
                                            </center>
                                        </div>
                                    </div>
                            </div>
                            <div class="clearfix"></div>
                            <hr style="margin-top:10px; margin-bottom:10px;" />

                        <br />
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-3 col-md-3">
            </div>
            <div class="clearfix"></div>    
        </div>

    </div>


    <div id="modal-apply" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title"><strong>Recruitment Application</strong></h4>
                    <h4 class="modal-title" id="title_structure_organization"></h4> </div>
                <form id="form" class="form-horizontal">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <input type="hidden" name="recruitment_id" id="recruitment_id" class="form-control" />
                        <div class="form-group">
                            <label class="col-md-12">Job Title</label>
                            <div class="col-md-12">
                                <input type="text" id="job_title" class="form-control" value="{{ $vacancy->job_position }}" placeholder="" autocomplete="off" disabled/>
                            </div>
                        </div>
                        <div class="form-group" style="margin-top: 16px">
                            <label class="col-md-12">Applicant</label>
                            <div class="col-md-12">
                                <input type="text" id="applicant" class="form-control" value="{{\Auth::user()->nik." - ".\Auth::user()->name}}" placeholder="" autocomplete="off" disabled/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Cover Letter (Optional)</label>
                            <div class="col-md-12">
                                <textarea class="form-control" name="cover_letter" id="cover_letter"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">CV</label>
                            <div class="col-md-12">
                                <input type="file" class="form-control" name="cv" id="cv" required/>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                        <button type="submit" class="btn btn-info btn-sm" id="btn_apply"><i class="fa fa-envelope"></i> Apply</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
    @include('layouts.footer')
</div>
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
</div>
<style type="text/css">
    .col-in h3 {
        font-size: 20px;
    }
</style>
@section('js')
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script>
        CKEDITOR.replace( 'cover_letter' );
        function apply(recruitment_id) {
            $('#recruitment_id').val(recruitment_id);
            $('#modal-apply').modal('show');
        }

        $('#form').on('submit',function () {
            var form = $('#form')[0]; // You need to use standart javascript object here
            var formData = new FormData(form);
            formData.set('cover_letter',CKEDITOR.instances['cover_letter'].getData());
            $("#btn_apply").attr("disabled", true);
            $.ajax({
                url: "{{route('karyawan.internal-recruitment.apply')}}",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.status == 'success') {
                        $('#form')[0].reset();
                        swal("Success!", data.message, "success");
                        setTimeout(window.location.href = window.location.href, 1000);
                    } else {
                        swal("Failed!", data.message, "error");
                    }
                    console.log(data);
                    $("#btn_apply").attr("disabled", false);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                    $("#btn_apply").attr("disabled", false);
                }
            });
            return false;
        });
    </script>
@endsection
@endsection
