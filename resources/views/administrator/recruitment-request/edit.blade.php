@extends('layouts.administrator')

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
                            @if($recruitment->approval_hr == null)
                                <div class="col-md-12">
                                    <div class="col-md-2 pull-right">
                                        <button type="submit" class="btn btn-sm btn-success waves-effect waves-light m-r-10 pull-right" onclick="proses(1)"><i class="fa fa-check"></i> Approve</button>
                                        <button type="submit" class="btn btn-sm btn-danger waves-effect waves-light m-r-10 pull-right" onclick="proses(0)"><i class="fa fa-times"></i> Reject</button>
                                    </div>
                                </div>
                            @endif
                                <br>
                            <input type="hidden" name="approve" />
                            <input type="hidden" name="_method" value="PUT">
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
                                            <textarea required name="reason" class="form-control" {{isset($recruitment->approval_hr)?"disabled":""}}>{{$recruitment->reason}}</textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-12">Select Position</label>
                                        <div class="col-md-12">
                                            <select required class="form-control" name="position" id="position" disabled>
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
                                        @if(checkModuleAdmin(26))
                                        <div class="form-group hidden" id="container_grade">
                                            <label class="col-md-12">Select Grade</label>
                                            <div class="col-md-12">
                                                <select class="form-control" name="grade" id="grade" {{isset($recruitment->approval_hr)?"disabled":""}}>
                                                    <option value="">- Select Grade -</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group hidden" id="container_subgrade">
                                            <label class="col-md-12">Select Sub Grade</label>
                                            <div class="col-md-12">
                                                <select class="form-control" name="subgrade" id="subgrade" {{isset($recruitment->approval_hr)?"disabled":""}}>
                                                    <option value="">- Select Sub Grade -</option>
                                                </select>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                <label class="col-md-12">Range Minimum Salary</label>
                                                <input required type="number" class="form-control" name="min_salary" value="{{$recruitment->min_salary}}" id="min_salary"  {{isset($recruitment->approval_hr)?"disabled":""}}>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="col-md-12">Range Maximum Salary</label>
                                                <input required type="number" class="form-control" name="max_salary" value="{{$recruitment->max_salary}}" id="max_salary"  {{isset($recruitment->approval_hr)?"disabled":""}}>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12">Job Description</label>
                                            <div class="col-md-12">
                                                <textarea required class="content" name="job_desc" id="job_desc"  {{isset($recruitment->approval_hr)?"disabled":""}}>{{htmlspecialchars_decode($recruitment->job_desc)}}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12">Requirements</label>
                                            <div class="col-md-12">
                                                <textarea required class="content" name="job_requirement" id="job_requirement"  {{isset($recruitment->approval_hr)?"disabled":""}}>{{htmlspecialchars_decode($recruitment->job_requirement)}}</textarea>
                                            </div>
                                        </div>
                                            <div class="form-group">
                                                <label class="col-md-12">Benefits</label>
                                                <div class="col-md-12">
                                                    <textarea required class="content" name="benefit" id="benefit"  {{isset($recruitment->approval_hr)?"disabled":""}}>{{htmlspecialchars_decode($recruitment->benefit)}}</textarea>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label class="col-md-12">Branch</label>
                                        <div class="col-md-12">
                                            <select required class="form-control" name="branch" {{isset($recruitment->approval_hr)?"disabled":""}}>
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
                                            <input required type="number" name="headcount" class="form-control"  placeholder="" autocomplete="off" min="1" value="{{$recruitment->headcount}}" {{isset($recruitment->approval_hr)?"disabled":""}}/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-12">Expected Start Date</label>
                                        <div class="col-md-12">
                                            <input required type="text" id="expected_date" name="expected_date" class="form-control datepicker"  placeholder="" autocomplete="off" value="{{$recruitment->expected_date}}" {{isset($recruitment->approval_hr)?"disabled":""}}/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-12">Employment Type</label>
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <label class="form-check-label" for="radio1">
                                                        <input type="radio" class="form-check-input" id="radio1" name="employment_type" value="1" {{1==$recruitment->employment_type?"checked":""}} {{isset($recruitment->approval_hr)?"disabled":""}}> Permanent
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <label class="form-check-label" for="radio2">
                                                        <input type="radio" class="form-check-input" id="radio2" name="employment_type" value="2" {{2==$recruitment->employment_type?"checked":""}} {{isset($recruitment->approval_hr)?"disabled":""}}> Contract
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <label class="form-check-label" for="radio3">
                                                        <input type="radio" class="form-check-input" id="radio3" name="employment_type" value="3" {{3==$recruitment->employment_type?"checked":""}} {{isset($recruitment->approval_hr)?"disabled":""}}> Internship
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <label class="form-check-label" for="radio4">
                                                        <input type="radio" class="form-check-input" id="radio4" name="employment_type" value="4" {{4==$recruitment->employment_type?"checked":""}} {{isset($recruitment->approval_hr)?"disabled":""}}> Outsource
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <label class="form-check-label" for="radio5">
                                                        <input type="radio" class="form-check-input" id="radio5" name="employment_type" value="5" {{5==$recruitment->employment_type?"checked":""}} {{isset($recruitment->approval_hr)?"disabled":""}}> Freelance
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
                                            <input type="number" name="contract_duration" class="form-control"  placeholder="" autocomplete="off"  min="1" value="{{$recruitment->contract_duration}}" {{isset($recruitment->approval_hr)?"disabled":""}} {{$recruitment->employment_type && $recruitment->employment_type!=1?'required':''}}/>
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
                                                    <label class="form-check-label" for="box1">
                                                        <input required type="checkbox" class="form-check-input" id="box1" name="recruitment_type[]" value="1" {{isset($recruitment->approval_hr)?"disabled":""}}> Internal
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check-inline">
                                                    <label class="form-check-label" for="box2">
                                                        <input type="checkbox" class="form-check-input" id="box2" name="recruitment_type[]" value="2" {{isset($recruitment->approval_hr)?"disabled":""}}> External
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
                                            <textarea name="additional_information" class="form-control" {{isset($recruitment->approval_hr)?"disabled":""}}>{{$recruitment->additional_information}}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Job Category</label>
                                        <div class="col-md-12">
                                            <select class="form-control" name="job_category" {{$recruitment->approval_hr == 1 ? "required" : ""}}>
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
                                            <input required type="text" class="form-control autocomplete-recruiter" value="{{$recruitment->recruiter?$recruitment->recruiter->nik." - ".$recruitment->recruiter->name:''}}" placeholder="select recruiter..">
                                            <input type="hidden" name="recruiter" value="{{$recruitment->recruiter?$recruitment->recruiter_id:''}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Interviewer(s)</label>
                                        @php($interviewers = $recruitment->interviewers)
                                        @for($i = 1; $i <= 4; $i++)
                                            <div class="col-md-12">
                                                <label class="col-md-12"></label>
                                                <input required type="text" class="form-control autocomplete-interviewer" id="interviewer{{$i}}" value="{{isset($interviewers[$i-1])?$interviewers[$i-1]->user->nik." - ".$interviewers[$i-1]->user->name:''}}" placeholder="select interviewer {{$i}}..">
                                                <input type="hidden" class="value-interviewer" value="{{isset($interviewers[$i-1])?$interviewers[$i-1]->user->id:''}}" name="interviewer[{{$i}}]">
                                            </div>
                                        @endfor

                                    </div>
                                </div>
                            </div>
                            <br><br>
                            <div class="col-md-12">
                                <div class="col-md-12 text-center">
                                    <a href="{{ route('administrator.recruitment-request.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Back</a>
                                    @if($recruitment->approval_hr==null || ($recruitment->approval_hr=='1' && $recruitment->approval_user=='1'))
                                        <button type="submit" class="btn btn-sm btn-success waves-effect waves-light m-r-10" id="btn_save"><i class="fa fa-save"></i> Save</button>
                                    @endif
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
                                            <th width="20%">Status</th>
                                            <th width="30%">Date Posted</th>
                                            <th width="50%">Date Expired</th>
                                            <th width="10%">Show Salary</th>
                                            <th>Action</th>
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

        var recruiters = [],interviewers = [];

        @foreach(getRecruiters() as $item)
            recruiters.push({id : {{ $item->id }}, value : "{{ $item->nik .' - '. $item->name }}" });
        @endforeach
        @foreach(getInterviewers() as $item)
            interviewers.push({id : {{ $item->id }}, value : "{{ $item->nik .' - '. $item->name }}" });
        @endforeach

        $(".autocomplete-recruiter" ).autocomplete({
            source: recruiters,
            minLength:0,
            select: function( event, ui ) {
                $( "input[name='recruiter']" ).val(ui.item.id);

                var id = ui.item.id;
            }
        }).on('focus', function () {
            $(this).autocomplete("search", "");
        });

        $(".autocomplete-interviewer" ).autocomplete({
            source: interviewers,
            minLength:0,
            select: function( event, ui ) {
                var id = $(this).attr('id').substr(11);
                $( "input[name='interviewer["+id+"]']" ).val(ui.item.id);
            },
            change: function() {
                if ($(".autocomplete-interviewer").filter(function () {
                    return $.trim($(this).val()).length > 0
                }).length > 0)  {
                    $(".autocomplete-interviewer").removeAttr('required');
                } else {
                    $(".autocomplete-interviewer").attr('required', true);
                }
            }
        }).on('focus', function () {
            $(this).autocomplete("search", "");
        });

        $(".autocomplete-recruiter").on('change keyup', function() {
            if($(this).val().trim() == ''){
                $( "input[name='recruiter']" ).val('');
            }
        });

        $(".autocomplete-interviewer").on('change keyup', function() {
            if($(this).val().trim() == ''){
                var id = $(this).attr('id').substr(11);
                $( "input[name='interviewer["+id+"]']" ).val('');
            }
        });


        @foreach($recruitment->details as $detail)
        $(":checkbox[name='recruitment_type[]'][value='{{$detail->recruitment_type_id}}']").attr('checked', true);
        @endforeach
        var sub_grades = @json($recruitment->grade ? $recruitment->grade->sub_grade : []);

        var grades;
        var sub_grades;
        var position;
        var first = true;
        var salary_range;
        var sub_salary_range;
        CKEDITOR.replace( 'job_desc' );
        CKEDITOR.replace( 'job_requirement' );
        CKEDITOR.replace( 'benefit' );
        $( document ).ready(function() {
            $('#position').trigger("change");
            if ($(".value-interviewer").filter(function () {
                return $(this).val() != '';
            }).length > 0)  {
                $(".autocomplete-interviewer").removeAttr('required');
            } else {
                $(".autocomplete-interviewer").attr('required', true);
            }
        });
        $('#position').on('change', function () {
            position = $(this).val();
            $('#container_grade, #container_subgrade').addClass('hidden');
            $("#grade, #subgrade").attr('disabled', true);
            $('#grade').html('<option value="">- Select Grade -</option>');
            $('#subgrade').html('<option value="">- Select Sub Grade -</option>');
            $("#subgrade").removeAttr('required');
            $.ajax({
                url: "{{route('ajax.get-structure-organization-detail')}}",
                type: "GET",
                data:{'_token':"{{csrf_token()}}",'id':position},
                dataType: "JSON",
                success: function (data) {
                    if(position == '') {
                        grades = data;
                    } else {
                        if(data.grade != null) {
                            grades = [data.grade];
                        } else {
                            grades = [];
                        }
                    }
                    var grade_html = '<option value="">- Select Grade -</option>';
                    grades.forEach(function (item, index) {
                        grade_html += '<option value="'+item.id+'">'+item.name+'</option>'; 
                    });
                    $("#grade").html(grade_html);
                    $("#grade").on('change', function () {
                        $('#container_subgrade').addClass('hidden');
                        $("#subgrade").attr('disabled', true);
                        $('#subgrade').html('<option value="">- Select Sub Grade -</option>');
                        $("#subgrade").removeAttr('required');
                        if (!first) {
                            $('#min_salary').val('');
                            $('#max_salary').val('');
                        }
                        var grade_id = $(this).val();
                        if (!grade_id) {
                            if (!first) {
                                CKEDITOR.instances['benefit'].setData('');
                            }
                            first = false;
                        }
                        for(var i = 0; i < grades.length; i++) {
                            if(grades[i].id == grade_id) {
                                salary_range = grades[i].salary_range.split(" - ");
                                if (!first) {
                                    if(salary_range.length>1) {
                                        $('#min_salary').val(parseInt(salary_range[0]));
                                        $('#max_salary').val(parseInt(salary_range[1]));
                                    }
                                    CKEDITOR.instances['benefit'].setData(grades[i].benefit);
                                }
                                sub_grades = grades[i].sub_grade;
                                if(sub_grades.length > 0){
                                    var subgrade_html = '<option value="">- Select Sub Grade -</option>';
                                    sub_grades.forEach(function (item, index) {
                                        subgrade_html += '<option value="'+item.id+'">'+item.name+'</option>'; 
                                    });
                                    $("#subgrade").html(subgrade_html);
                                    $("#subgrade").on('change', function () {
                                        if (!first) {
                                            if(salary_range.length>1) {
                                                $('#min_salary').val(parseInt(salary_range[0]));
                                                $('#max_salary').val(parseInt(salary_range[1]));
                                            } else {
                                                $('#min_salary').val('');
                                                $('#max_salary').val('');
                                            }
                                            var subgrade_id = $(this).val();
                                            for(var i = 0; i < sub_grades.length; i++){
                                                if(sub_grades[i].id == subgrade_id){
                                                    sub_salary_range = sub_grades[i].salary_range.split(" - ");
                                                    $('#min_salary').val(parseInt(sub_salary_range[0]));
                                                    $('#max_salary').val(parseInt(sub_salary_range[1]));
                                                }
                                            }
                                        }
                                        first = false;
                                    });
                                    if (first) {
                                        $("#subgrade").val("{{ $recruitment->subgrade_id }}").trigger('change');
                                    }
                                    if ("{{!isset($recruitment->approval_hr) || isset($recruitment->subgrade_id)}}") {
                                        $('#container_subgrade').removeClass('hidden');
                                    }
                                    if ("{{!isset($recruitment->approval_hr)}}") {
                                        $("#subgrade").attr('disabled', false);
                                    }
                                    // $("#subgrade").attr('required', true);
                                } else {
                                    $("#subgrade").removeAttr('required');
                                    first = false;
                                }
                            }
                        }
                    });
                    if (first) {
                        $("#grade").val("{{ $recruitment->grade_id }}").trigger('change');
                    }
                    if ("{{!isset($recruitment->approval_hr) || isset($recruitment->grade_id)}}") {
                        $('#container_grade').removeClass('hidden');
                    }
                    if ("{{!isset($recruitment->approval_hr)}}") {
                        $("#grade").attr('disabled', false);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
        });

        $('input[name ="employment_type"]').on('change',function () {
            var value = $(this).val();
            if(value && value != '1'){
                $('#container_duration').removeClass('hidden');
                $("input[name^='contract_duration']").addAttr('required');
            }
            else{
                $('#container_duration').addClass('hidden');
                $("input[name^='contract_duration']").removeAttr('required');
            }

        });

        $("#box2").change(function() {
            if(this.checked) {
                $("#box1").removeAttr('required');
            } else {
                $("#box1").attr('required', true);
            }
        });
        $("#box2").trigger('change');

        function proses(approval)
        {
            $("input[name='approve']").val(approval);
        }

        $('#form').on('submit', function () {

            var form = $('#form')[0]; // You need to use standart javascript object here
            var formData = new FormData(form);
            formData.set('job_desc',CKEDITOR.instances['job_desc'].getData());
            formData.set('job_requirement',CKEDITOR.instances['job_requirement'].getData());
            formData.set('benefit',CKEDITOR.instances['benefit'].getData());

            var interviewer_arr = [];
            var status = true;
            $("input[name^='interviewer']").each(function() {
                var interviewer_id = $(this).val();
                if(interviewer_id!='' && interviewer_arr.includes(interviewer_id)){
                    status = false;
                }
                else
                    interviewer_arr.push(interviewer_id);
            });
            if(!status){
                swal("Failed!", "Interviewers should be unique!", "error");
                return false;
            }

            swal({
                title: 'Are you sure?',
                text: 'The Request will be saved!',
                buttons: true,
            }).then((willSubmit) => {
                if (willSubmit) {
                    $("#btn_save").attr("disabled", true);
                    $.ajax({
                        url: "{{route('administrator.recruitment-request.update',$recruitment->id)}}",
                        type: "POST",
                        data:formData,
                        dataType: "JSON",
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            if (data.status == 'success') {
                                swal("Success!", data.message, "success");
                                setTimeout(function(){
                                    location.reload();
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

        function updatePost(detail_id, status) {
            $.ajax({
                url: "/administrator/recruitment-request/update-post/"+detail_id,
                type: "PUT",
                data: JSON.stringify({'_token': "{{csrf_token()}}",'status':status}),
                dataType: "JSON",
                contentType: "application/json",
                success: function (data) {
                    console.log(data);
                    if(data.status == 'success'){
                        swal("Success!", data.message, "success");
                        post();
                    }
                    else{
                        swal("Failed!", data.message, "error");
                    }
                }
            });
        }
        
        function post() {
            $.ajax({
                url: "{{route('ajax.get-recruitment-request-detail')}}",
                type: "GET",
                data:{'_token':"{{csrf_token()}}", 'id':{{$recruitment->id}}},
                dataType: "JSON",
                contentType: "application/json",
                success: function (data) {
                    console.log(data);
                    var html = '', status,show_salary,action,expired;
                    for(var i = 0; i < data.length; i++){
                        var check = (data[i].show_salary_range==1)?"checked":"";
                        show_salary = '<input type="checkbox" class="form-check-input show_salary" value="'+data[i].id+'" '+check+'>';
                        if(data[i].status_post == null) {
                            status = '<b class="text-warning">PENDING</b>';
                            action = '<button onclick="updatePost('+data[i].id+',1)" type="button" class="btn btn-sm btn-success"><i class="fa fa-paper-plane"></i>  Post</button>';
                        }
                        else if(data[i].status_post == '0') {
                            status = '<b class="text-warning">UNPOSTED</b>';
                            action = '<button onclick="updatePost('+data[i].id+',1)" type="button" class="btn btn-sm btn-success"><i class="fa fa-paper-plane"></i>  Post</button>';
                        }
                        else {
                            status = '<b class="text-success">POSTED</b>';
                            action = '<button onclick="updatePost('+data[i].id+',0)" type="button" class="btn btn-sm btn-warning"><i class="fa fa-times"></i>  Unpost</button>';
                        }
                        expired = '<input onchange="updateExpired('+data[i].id+',$(this).val())" type="text" class="form-control datepicker custom-datepicker" autocomplete="off" value="'+(data[i].expired_date ? data[i].expired_date : '')+'" style="font-size: 13px" />'
                        html += '<tr>' +
                                    '<td><b>'+data[i].type.name+'</b></td>' +
                                    '<td>'+status+'</td>' +
                                    '<td>'+data[i].posting_date+'</td>' +
                                    '<td style="padding: 11px 0">'+expired+'</td>' +
                                    '<td class="text-center">'+show_salary+'</td>' +
                                    '<td>'+action+'</td>' +
                                '</tr>';
                    }
                    $('#tbl_post').html(html);
                    $('#modal-post').modal('show');
                    salaryChange();
                    $('.custom-datepicker').datepicker({
                        changeMonth: true, 
                        changeYear: true,
                        dateFormat: 'dd M yy',
                        minDate: 1
                    });
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
        }

        function salaryChange() {
            $('.show_salary').change(function() {
                var id = $(this).val();
                var check = $(this).is(":checked")? 1 : 0;
                $.ajax({
                    url: "/administrator/recruitment-request/update-post/"+id,
                    type: "PUT",
                    data: JSON.stringify({'_token': "{{csrf_token()}}",'show_salary_range':check}),
                    dataType: "JSON",
                    contentType: "application/json",
                    success: function (data) {
                        console.log(data);
                    }
                });
            });
        }
        
        function updateExpired(detail_id, date) {
            $.ajax({
                url: "/administrator/recruitment-request/update-post/"+detail_id,
                type: "PUT",
                data: JSON.stringify({'_token': "{{csrf_token()}}",'date':date}),
                dataType: "JSON",
                contentType: "application/json",
                success: function (data) {
                    console.log(data);
                }
            });
        }
    </script>
@endsection
@endsection
