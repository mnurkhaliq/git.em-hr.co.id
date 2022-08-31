@extends('layouts.karyawan')

@section('title', 'Recruitment Request')

@section('sidebar')

@endsection

@section('content')
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">Form Recruitment Request</h4> </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="javascript:void(0)">Dashboard</a></li>
                        <li class="active">Recruitment Request</li>
                    </ol>
                </div>
            </div>
            <!-- .row -->
            <div class="row">
                <form class="form-horizontal" id="form" enctype="multipart/form-data">
                    <div class="col-md-12 p-l-0 p-r-0">
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
                            <div class="col-md-12">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label class="col-md-12">Request Number</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" value="{{$recruitment->request_number}}" placeholder="" autocomplete="off" disabled/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Requestor</label>
                                        <div class="col-md-12">
                                            <input type="text" id="deadline" class="form-control" value="{{$recruitment->requestor->nik." - ".$recruitment->requestor->name}}" placeholder="" autocomplete="off" disabled/>
                                            <input type="hidden" name="requestor" value="{{$recruitment->requestor->id}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Reason</label>
                                        <div class="col-md-12">
                                            <textarea name="reason" class="form-control" disabled>{{$recruitment->reason}}</textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-12">Select Position</label>
                                        <div class="col-md-12">
                                            <select class="form-control" name="position" id="position" disabled>
                                                <option value="">- Applicant -</option>
                                                @php($positions = getStructureName())
                                                @foreach($positions as $item)
                                                    <option  value="{{$item['id']}}" {{$item['id']==$recruitment->structure_organization_custom_id?"selected":""}}>{{$item['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div id="container_position" style="margin-left: 5%">
                                        <div class="form-group">
                                            <label class="col-md-12">Job Position Name</label>
                                            <div class="col-md-12">
                                                <input disabled type="text" class="form-control" name="job_position" id="job_position" value="{{$recruitment->job_position}}">
                                            </div>
                                        </div>
                                        @if(checkModule(26))
                                        @if($recruitment->grade_id)
                                        <div class="form-group">
                                            <label class="col-md-12">Select Grade</label>
                                            <div class="col-md-12">
                                                <select class="form-control" name="grade" id="grade" disabled>
                                                    <option value="{{$recruitment->grade_id}}">{{$recruitment->grade->name}}</option>
                                                </select>
                                            </div>
                                        </div>
                                        @endif
                                        @if($recruitment->subgrade_id)
                                        <div class="form-group" id="container_subgrade">
                                            <label class="col-md-12">Select Sub Grade</label>
                                            <div class="col-md-12">
                                                <select class="form-control" name="subgrade" id="subgrade" disabled>
                                                    <option value="{{$recruitment->subgrade_id}}">{{$recruitment->subgrade->name}}</option>
                                                </select>
                                            </div>
                                        </div>
                                        @endif
                                        @endif
                                        <div class="form-group hidden">
                                            <div class="col-md-6">
                                                <label class="col-md-12">Range Minimum Salary</label>
                                                <input type="number" class="form-control" name="min_salary" value="{{$recruitment->min_salary}}" id="min_salary" disabled>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="col-md-12">Range Maximum Salary</label>
                                                <input type="number" class="form-control" name="max_salary" value="{{$recruitment->max_salary}}" id="max_salary" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12">Job Description</label>
                                            <div class="col-md-12">
                                                <textarea required class="content" name="job_desc" id="job_desc" disabled>{{htmlspecialchars_decode($recruitment->job_desc)}}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12">Requirements</label>
                                            <div class="col-md-12">
                                                <textarea required class="content" name="job_requirement" id="job_requirement" disabled>{{htmlspecialchars_decode($recruitment->job_requirement)}}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12">Benefits</label>
                                            <div class="col-md-12">
                                                <textarea required class="content" name="benefit" id="benefit" disabled>{{htmlspecialchars_decode($recruitment->benefit)}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label class="col-md-12">Branch</label>
                                        <div class="col-md-12">
                                            <select required class="form-control" name="branch" disabled>
                                                <option value="">- Select Branch -</option>
                                                @php($branches = get_branches())
                                                @foreach($branches as $item)
                                                    <option  value="{{$item->id}}" {{$item->id==$recruitment->branch_id?"selected":""}}>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Headcount</label>
                                        <div class="col-md-12">
                                            <input type="number" name="headcount" class="form-control"  placeholder="" autocomplete="off" min="1" value="{{$recruitment->headcount}}" disabled/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-12">Expected Start Date</label>
                                        <div class="col-md-12">
                                            <input type="text" id="expected_date" name="expected_date" class="form-control datepicker"  placeholder="" autocomplete="off" value="{{$recruitment->expected_date}}" disabled/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-12">Employment Type</label>
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <label class="form-check-label" for="radio1">
                                                        <input disabled type="radio" class="form-check-input" id="radio1" name="employment_type" value="1" {{1==$recruitment->employment_type?"checked":""}}> Permanent
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <label class="form-check-label" for="radio2">
                                                        <input disabled type="radio" class="form-check-input" id="radio2" name="employment_type" value="2" {{2==$recruitment->employment_type?"checked":""}}> Contract
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <label class="form-check-label" for="radio3">
                                                        <input disabled type="radio" class="form-check-input" id="radio3" name="employment_type" value="3" {{3==$recruitment->employment_type?"checked":""}}> Internship
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <label class="form-check-label" for="radio4">
                                                        <input disabled type="radio" class="form-check-input" id="radio4" name="employment_type" value="4" {{4==$recruitment->employment_type?"checked":""}}> Outsource
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <label class="form-check-label" for="radio5">
                                                        <input disabled type="radio" class="form-check-input" id="radio5" name="employment_type" value="5" {{5==$recruitment->employment_type?"checked":""}}> Freelance
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <label class="form-check-label" for="radio6">
                                                        <input disabled type="radio" class="form-check-input" id="radio6" name="employment_type" value="6" {{6==$recruitment->employment_type?"checked":""}}> Consultant
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group {{$recruitment->employment_type && $recruitment->employment_type!=1?'':'hidden'}}" id="container_duration">
                                        <label class="col-md-12">Duration</label>
                                        <div class="col-md-11">
                                            <input type="number" name="contract_duration" class="form-control"  placeholder="" autocomplete="off"  min="1" value="{{$recruitment->contract_duration}}" disabled/>
                                        </div>
                                        <div class="col-md-1">

                                            <b>Month(s)</b>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Recruitment Type</label>
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <div class="form-check-inline">
                                                    <label class="form-check-label" for="radio1">
                                                        <input disabled type="checkbox" class="form-check-input" id="radio1" name="recruitment_type[]" value="1"> Internal
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check-inline">
                                                    <label class="form-check-label" for="radio2">
                                                        <input disabled type="checkbox" class="form-check-input" id="radio2" name="recruitment_type[]" value="2"> External
                                                    </label>
                                                </div>
                                            </div>
                                            @if($recruitment->approval_user == 1)
                                                <div class="col-md-6">
                                                    <button type="button" onclick="post()" class="btn btn-sm btn-info pull-right"><i class="fa fa-info-circle"></i> Post</button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Additional Information</label>
                                        <div class="col-md-12">
                                            <textarea name="additional_information" class="form-control" disabled>{{$recruitment->additional_information}}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Job Category</label>
                                        <div class="col-md-12">
                                            <select class="form-control" name="job_category" disabled>
                                                <option value="">- Select Job Category -</option>
                                                @php($categories = getJobCategories())
                                                @foreach($categories as $item)
                                                    <option  value="{{$item->id}}" {{$item->id==$recruitment->job_category_id?"selected":""}}>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Recruiter</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control autocomplete-recruiter" value="{{$recruitment->recruiter?$recruitment->recruiter->nik." - ".$recruitment->recruiter->name:''}}" placeholder="select recruiter.." disabled>
                                            <input type="hidden" name="recruiter" value="{{$recruitment->recruiter?$recruitment->recruiter_id:''}}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Interviewer(s)</label>
                                        @php($interviewers = $recruitment->interviewers)
                                        @for($i = 1; $i <= 4; $i++)
                                            <div class="col-md-12">
                                                <label class="col-md-12"></label>
                                                <input type="text" class="form-control autocomplete-interviewer" id="interviewer{{$i}}" value="{{isset($interviewers[$i-1])?$interviewers[$i-1]->user->nik." - ".$interviewers[$i-1]->user->name:''}}" placeholder="select interviewer {{$i}}.." disabled>
                                                <input type="hidden" value="{{isset($interviewers[$i-1])?$interviewers[$i-1]->user->id:''}}" name="interviewer[{{$i}}]" disabled>
                                            </div>
                                        @endfor
                                    </div>

                                </div>
                            </div>
                            <br><br>
                            <div class="col-md-12">
                                <div class="col-md-12 text-center">
                                    <a href="{{ route('karyawan.recruitment-request.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Back</a>
                                    {{--<button type="submit" class="btn btn-sm btn-success waves-effect waves-light m-r-10" id="btn_save"><i class="fa fa-save"></i> Save</button>--}}
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <br />

                            <br style="clear: both;" />
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.row -->
            <!-- ============================================================== -->
        </div>

        <!-- BEGIN MODAL -->
        <div  class="modal fade none-border" id="modal-post">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><strong>Recruitment Post Status</strong></h4>
                    </div>
                    <form id="form">
                        <div class="modal-body" id="modal-body">
                            <div class="form-group col-xs-12 table-responsive">
                                <table class="table table-striped" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="25%">Type</th>
                                        <th width="25%">Status</th>
                                        <th width="35%">Date Posted</th>
                                        <th width="35%">Date Expired</th>
                                    </tr>
                                    </thead>
                                    <tbody id="tbl_post">
                                    <tr>
                                        <td>Internal</td>
                                        <td><b>UNPOSTED</b></td>
                                        <td>27 Desember 2019</td>
                                        <td>27 Desember 2019</td>
                                        <td><button type="button" class="btn btn-xs btn-success"> Post</button></td>
                                    </tr>
                                    <tr>
                                        <td>External</td>
                                        <td><b>POSTED</b></td>
                                        <td>27 Desember 2019</td>
                                        <td>27 Desember 2019</td>
                                        <td><button type="button" class="btn btn-xs btn-warning"> Unpost</button></td>
                                    </tr>
                                    </tbody>
                                </table>

                            </div>
                            <hr/>
                            <div class="form-group col-xs-12" id="approval">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default"  data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
        @include('layouts.footer')
    </div>
@section('js')


    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script type="text/javascript">

        @foreach($recruitment->details as $detail)
        $(":checkbox[name='recruitment_type[]'][value='{{$detail->recruitment_type_id}}']").attr('checked', true);
        @endforeach
        var sub_grades;
        CKEDITOR.replace( 'job_desc' );
        CKEDITOR.replace( 'job_requirement' );
        CKEDITOR.replace( 'benefit' );
        $('#position').on('change',function () {
            if($(this).val()==''){
                $('#container_position').addClass('hidden');
                $("#subgrade").attr('disabled',true);
            }
            else{
                $('#container_subgrade').addClass('hidden');
                $("#subgrade").attr('disabled',true);
                $.ajax({
                    url: "{{route('ajax.get-structure-organization-detail')}}",
                    type: "GET",
                    data:{'_token':"{{csrf_token()}}",'id':$(this).val()},
                    dataType: "JSON",
                    success: function (data) {
                        if(data.grade != null){
                            $('#grade').html('<option value="'+data.grade.id+'">'+data.grade.name+'</option>');
                            sub_grades = data.grade.sub_grade;
                            if(sub_grades.length > 0){
                                $('#container_subgrade').removeClass('hidden');
                                $("#subgrade").attr('disabled',false);
                                var subgrade_html = "";
                                for(var i = 0; i < sub_grades.length; i++){
                                    console.log(sub_grades[i]);
                                    subgrade_html += '<option value="'+sub_grades[i].id+'">'+sub_grades[i].name+'</option>';
                                }
                                $("#subgrade").html(subgrade_html);
                                var salary_range = sub_grades[0].salary_range.split(" - ");
                                if(salary_range.length>1) {
                                    $('#min_salary').val(parseInt(salary_range[0]));
                                    $('#max_salary').val(parseInt(salary_range[1]));
                                }
                                $("#subgrade").on('change',function () {
                                    var subgrade_id = $(this).val();
                                    for(var i = 0; i < sub_grades.length; i++){
                                        if(sub_grades[i].id == subgrade_id){
                                            salary_range = sub_grades[i].salary_range.split(" - ");
                                            $('#min_salary').val(parseInt(salary_range[0]));
                                            $('#max_salary').val(parseInt(salary_range[1]));
                                        }
                                    }
                                });
                            }
                            else{
                                var salary_range = data.grade.salary_range.split(" - ");
                                if(salary_range.length>1) {
                                    $('#min_salary').val(parseInt(salary_range[0]));
                                    $('#max_salary').val(parseInt(salary_range[1]));
                                }

                            }
                        }
                        else{
                            $('#grade').html('<option value="">- Select Grade -</option>');
                            $('#min_salary').val('');
                            $('#max_salary').val('');
                        }
                        CKEDITOR.instances['job_desc'].setData(data.description);
                        CKEDITOR.instances['job_requirement'].setData(data.requirement);
                        if(data.grade != null)
                            CKEDITOR.instances['benefit'].setData(data.grade.benefit);
                        console.log(data);
                        $('#container_position').removeClass('hidden');
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR);
                        console.log(textStatus);
                        console.log(errorThrown);
                    }
                });
            }
        });



        $('input[name ="employment_type"]').on('change',function () {
            var value = $(this).val();
            if(value && value != '1'){
                $('#container_duration').removeClass('hidden');
            }
            else{
                $('#container_duration').addClass('hidden');
            }

        });
        $('#form').on('submit',function () {
            var form = $('#form')[0]; // You need to use standart javascript object here
            var formData = new FormData(form);
            formData.set('job_desc',CKEDITOR.instances['job_desc'].getData());
            formData.set('job_requirement',CKEDITOR.instances['job_requirement'].getData());
            formData.set('benefit',CKEDITOR.instances['benefit'].getData());
            swal({
                title: 'Are you sure?',
                text: 'The Request will be saved!',
                buttons: true,
            }).then((willSubmit) => {
                if (willSubmit) {
                    $("#btn_save").attr("disabled", true);
                    $.ajax({
                        url: "{{route('karyawan.recruitment-request.store')}}",
                        type: "POST",
                        data:formData,
                        dataType: "JSON",
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            if (data.status == 'success') {
                                swal("Success!", data.message, "success");
                                setTimeout(function(){
                                    window.location = data.data+'/edit';
                                }, 1000);
                            } else {
                                swal("Failed!", data.message, "error");
                            }
                            console.log(data);
                            $("#btn_save").attr("disabled", false);
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log(jqXHR);
                            console.log(textStatus);
                            console.log(errorThrown);
                            $("#btn_save").attr("disabled", false);
                        }
                    });
                } else {

                }
            });
            return false;
        });
        function post() {
            $.ajax({
                url: "{{route('ajax.get-recruitment-request-detail')}}",
                type: "GET",
                data:{'_token':"{{csrf_token()}}", 'id':{{$recruitment->id}}},
                dataType: "JSON",
                contentType: "application/json",
                success: function (data) {
                    console.log(data);
                    var html = '', status;
                    for(var i = 0; i < data.length; i++){
                        if(data[i].status_post == null) {
                            status = '<b class="text-warning">PENDING</b>';
                        }
                        else if(data[i].status_post == '0') {
                            status = '<b class="text-warning">UNPOSTED</b>';
                        }
                        else {
                            status = '<b class="text-success">POSTED</b>';
                        }
                        html += '<tr>' +
                            '<td><b>'+data[i].type.name+'</b></td>' +
                            '<td>'+status+'</td>' +
                            '<td>'+data[i].posting_date+'</td>' +
                            '<td>'+(data[i].expired_date ? data[i].expired_date : '-')+'</td>' +
                            '</tr>';
                    }
                    $('#tbl_post').html(html);
                    $('#modal-post').modal('show');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });


        }
    </script>
@endsection
@endsection
