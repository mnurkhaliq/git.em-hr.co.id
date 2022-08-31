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
                                    <label class="col-md-12">Requestor</label>
                                    <div class="col-md-12">
                                        <input type="text" id="deadline" class="form-control" value="{{\Auth::user()->nik." - ".\Auth::user()->name}}" placeholder="" autocomplete="off" disabled/>
                                        <input type="hidden" name="requestor" value="{{\Auth::user()->id}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Reason</label>
                                    <div class="col-md-12">
                                        <textarea name="reason" class="form-control" required></textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-12">Select Position</label>
                                    <div class="col-md-12">
                                        <select required class="form-control" name="position" id="position">
                                            <option value="">- Select Position -</option>
                                            @php($positions = getJuniorPositions())
                                            @foreach($positions as $item)
                                                <option  value="{{$item->id}}">{{$item->position}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div id="container_position" style="margin-left: 5%" class="hidden">
                                    <div class="form-group">
                                        <label class="col-md-12">Select Grade</label>
                                        <div class="col-md-12">
                                            <select class="form-control" name="grade" id="grade">
                                                <option value="">- Select Grade -</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" id="container_subgrade">
                                        <label class="col-md-12">Select Sub Grade</label>
                                        <div class="col-md-12">
                                            <select class="form-control" name="subgrade" id="subgrade">
                                                <option value="">- Select Sub Grade -</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <label class="col-md-12">Range Minimum Salary</label>
                                            <input type="number" class="form-control" name="min_salary" id="min_salary">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="col-md-12">Range Maximum Salary</label>
                                            <input type="number" class="form-control" name="max_salary" id="max_salary">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Job Description</label>
                                        <div class="col-md-12">
                                            <textarea required class="content" name="job_desc" id="job_desc"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Requirements</label>
                                        <div class="col-md-12">
                                            <textarea required class="content" name="job_requirement" id="job_requirement"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Benefits</label>
                                        <div class="col-md-12">
                                            <textarea required class="content" name="benefit" id="benefit"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label class="col-md-12">Branch</label>
                                    <div class="col-md-12">
                                        <select required class="form-control" name="branch" >
                                            <option value="">- Select Branch -</option>
                                            @php($branches = get_branches())
                                            @foreach($branches as $item)
                                                <option  value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Headcount</label>
                                    <div class="col-md-12">
                                        <input type="number" name="headcount" class="form-control"  placeholder="" autocomplete="off" min="1" required/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-12">Expected Start Date</label>
                                    <div class="col-md-12">
                                        <input type="text" id="expected_date" name="expected_date" class="form-control datepicker" placeholder="" autocomplete="off" required/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-12">Employment Type</label>
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <label class="form-check-label" for="radio1">
                                                    <input type="radio" class="form-check-input" id="radio1" name="employment_type" value="1" checked> Permanent
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <label class="form-check-label" for="radio2">
                                                    <input type="radio" class="form-check-input" id="radio2" name="employment_type" value="2"> Contract
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <label class="form-check-label" for="radio3">
                                                    <input type="radio" class="form-check-input" id="radio3" name="employment_type" value="3"> Internship
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <label class="form-check-label" for="radio4">
                                                    <input type="radio" class="form-check-input" id="radio4" name="employment_type" value="4"> Outsource
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <label class="form-check-label" for="radio5">
                                                    <input type="radio" class="form-check-input" id="radio5" name="employment_type" value="5"> Freelance
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group hidden" id="container_duration">
                                    <label class="col-md-12">Duration</label>
                                    <div class="col-md-11">
                                        <input type="number" name="contract_duration" class="form-control"  placeholder="" autocomplete="off"  min="1"/>
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
                                                    <input type="checkbox" class="form-check-input" id="radio1" name="recruitment_type[]" value="1"> Internal
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check-inline">
                                                <label class="form-check-label" for="radio2">
                                                    <input type="checkbox" class="form-check-input" id="radio2" name="recruitment_type[]" value="2"> External
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Additional Information</label>
                                    <div class="col-md-12">
                                        <textarea name="additional_information" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br><br>
                        <div class="col-md-12">
                            <div class="col-md-12 text-center">
                                <a href="{{ route('karyawan.recruitment-request.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Back</a>
                                <button type="submit" class="btn btn-sm btn-success waves-effect waves-light m-r-10" id="btn_save"><i class="fa fa-save"></i> Save</button>
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
    <!-- /.container-fluid -->
    @include('layouts.footer')
</div>
@section('js')


    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script type="text/javascript">
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
    </script>
@endsection
@endsection
