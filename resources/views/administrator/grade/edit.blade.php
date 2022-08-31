@extends('layouts.administrator')

@section('title', 'Grade')

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
                <h4 class="page-title">Form Grade</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Grade</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <form class="form-inline" enctype="multipart/form-data" action="{{ route('administrator.grade.update', $data->id) }}" method="POST">
            <input type="hidden" name="_method" value="PUT">
                <div class="col-md-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Data Grade</h3>
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
                        <div class="col-md-8" id="gradeDiv">
                        <input type="hidden" id="idGrade" name="idGrade" value="{{$data->id}}">
                            <table class="table" id="gradeTable">
                                <tr>
                                    <th>No</th>
                                    <th>Grade Name</th>
                                    <th colspan="2">Salary Range</th>
                                    <th>Enable Sub Grade</th>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td><input autocomplete="off" value="{{$data->name}}" required name="gradeName" id="gradeName" type="text" class="form-control gradeName"></td>
                                    @if($is_sub == 1)
                                    <td><input autocomplete="off" onkeypress="checkAngkaLow(1)" disabled value="{{$low}}" required name="salaryLow" id="salaryLow" type="text" class="form-control spanLow1" placeholder="Lowest"></br><span id="alertLow1" style="display:none;color:red;font-style:italic;">This value has to be numeric</span></td>
                                    <td><input autocomplete="off" onkeypress="checkAngkaHigh(1)" disabled value="{{$high}}" required name="salaryHigh" id="salaryHigh" type="text" class="form-control spanHigh1" placeholder="Highest"></br><span id="alertHigh1" style="display:none;color:red;font-style:italic;">This value has to be numeric</span></td>
                                    <td><input id="enSub" type="checkbox" class="form-control" checked></td>
                                    @else
                                    <td><input autocomplete="off" onkeypress="checkAngkaLow(1)" value="{{$low}}" required name="salaryLow" id="salaryLow" type="text" class="form-control spanLow1" placeholder="Lowest"></br><span id="alertLow1" style="display:none;color:red;font-style:italic;">This value has to be numeric</span></td>
                                    <td><input autocomplete="off" onkeypress="checkAngkaHigh(1)" value="{{$high}}" required name="salaryHigh" id="salaryHigh" type="text" class="form-control spanHigh1" placeholder="Highest"></br><span id="alertLow1" style="display:none;color:red;font-style:italic;">This value has to be numeric</span></td>
                                    <td><input id="enSub" type="checkbox" class="form-control"></td>
                                    @endif
                                </tr>
                                @if($is_sub == 1)
                                <tr id="subGrade">
                                    <td></b></td>
                                    <td><b>Sub Grade Name</b></th>
                                    <td colspan="2"><b>Salary Range</b></td>
                                    <td><b>Action</b></td>
                                </tr>
                                <tr id="subGradeInput">
                                    <td>2</td>
                                    <td><input autocomplete="off" value="{{$first->name}}" required name="subGradeName[]" id="subGradeName" type="text" class="form-control"></td>
                                    <td><input autocomplete="off" onkeypress="checkAngkaLow(2)" value="{{explode(' - ',$first->salary_range)[0]}}" required onkeyup="rangeLow()" name="subSalaryLow[]" id="subSalaryLow" type="text" class="form-control subSalaryLow spanLow2 valLow2" placeholder="Lowest"></br><span id="alertLow2" style="display:none;color:red;font-style:italic;">This value has to be numeric</span><span id="valLow2" style="display:none;color:red;font-style:italic;"></span></td>
                                    <td><input autocomplete="off" onkeypress="checkAngkaHigh(2)" value="{{explode(' - ',$first->salary_range)[1]}}" required onkeyup="rangeHigh()" name="subSalaryHigh[]" id="subSalaryHigh" type="text" class="form-control subSalaryHigh spanHigh2 valHigh2" placeholder="Highest"></br><span id="alertHigh2" style="display:none;color:red;font-style:italic;">This value has to be numeric</span><span id="valLow2" style="display:none;color:red;font-style:italic;"></span></td>
                                    <td><button id="addSub" type="button" class="btn btn-sm btn-success waves-effect waves-light m-r-10 addSub"><i class="fa fa-plus"></i></button> <button id="delSub" type="button" class="btn btn-sm btn-danger"><i class="fa fa-minus"></i></button></td>
                                </tr>
                                <?php $i = 3; ?>
                                @if($after)
                                @foreach($after as $s)
                                <tr id="subGradeInput" class="newRow">
                                    <td>{{$i}}</td>
                                    <td><input autocomplete="off" value="{{$s['name']}}" required name="subGradeName[]" id="subGradeName" type="text" class="form-control"></td>
                                    <td><input autocomplete="off" onkeypress="checkAngkaLow({{$i}})" value="{{explode(' - ',$s['salary_range'])[0]}}" required onkeyup="rangeLow()" name="subSalaryLow[]" id="subSalaryLow" type="text" class="form-control subSalaryLow spanLow{{$i}} valLow{{$i}}" placeholder="Lowest"></br><span id="alertLow{{$i}}" style="display:none;color:red;font-style:italic;">This value has to be numeric</span><span id="valLow{{$i}}" style="display:none;color:red;font-style:italic;"></span></td>
                                    <td><input autocomplete="off" onkeypress="checkAngkaHigh({{$i}})" value="{{explode(' - ',$s['salary_range'])[1]}}" required onkeyup="rangeHigh()" name="subSalaryHigh[]" id="subSalaryHigh" type="text" class="form-control subSalaryHigh spanHigh{{$i}} valHigh{{$i}}" placeholder="Highest"></br><span id="alertHigh{{$i}}" style="display:none;color:red;font-style:italic;">This value has to be numeric</span><span id="valHigh{{$i}}" style="display:none;color:red;font-style:italic;"></span></td>
                                    <td></td>
                                </tr>
                                <?php $i++; ?>
                                @endforeach
                                @endif
                                @else
                                <tr id="subGrade" style="display:none">
                                    <td></b></td>
                                    <td><b>Sub Grade Name</b></th>
                                    <td colspan="2"><b>Salary Range</b></td>
                                    <td><b>Action</b></td>
                                </tr>
                                <tr id="subGradeInput" style="display:none">
                                    <td>2</td>
                                    <td><input autocomplete="off" required disabled name="subGradeName[]" id="subGradeName" type="text" class="form-control"></td>
                                    <td><input autocomplete="off" onkeypress="checkAngkaLow(2)" required disabled onkeyup="rangeLow()" name="subSalaryLow[]" id="subSalaryLow" type="text" class="form-control subSalaryLow spanLow2 valLow2" placeholder="Lowest"></br><span id="alertLow2" style="display:none;color:red;font-style:italic;">This value has to be numeric</span><span id="valLow2" style="display:none;color:red;font-style:italic;"></span></td>
                                    <td><input autocomplete="off" onkeypress="checkAngkaHigh(2)" required disabled onkeyup="rangeHigh()" name="subSalaryHigh[]" id="subSalaryHigh" type="text" class="form-control subSalaryHigh spanHigh2 valHigh2" placeholder="Highest"></br><span id="alertHigh2" style="display:none;color:red;font-style:italic;">This value has to be numeric</span><span id="valHigh2" style="display:none;color:red;font-style:italic;"></span></td>
                                    <td><button id="addSub" type="button" class="btn btn-sm btn-success waves-effect waves-light m-r-10 addSub"><i class="fa fa-plus"></i></button> <button id="delSub" type="button" class="btn btn-sm btn-danger"><i class="fa fa-minus"></i></button></td>
                                </tr>
                                @endif
                            </table>
                            <table class="table" id="gradeBenefit">
                                <tr>
                                    <th colspan="5">Grade Benefit (Optional)</th>
                                </tr>
                                <tr>
                                    <td colspan="5"><textarea name="benefit" id="benefit" cols="30" rows="10" class="form-control">{{$benefit}}</textarea></td>
                                </tr>
                            </table>
                        </div>
                        <div class="clearfix"></div>
                        <br style="clear: both;" />
                        <a href="{{ route('administrator.grade.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Cancel</a>
                        <button id="submitGrade" type="submit" class="btn btn-sm btn-success waves-effect waves-light m-r-10"><i class="fa fa-save"></i> Save</button>
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
    @extends('layouts.footer')
</div>
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">
    CKEDITOR.replace( 'benefit' );
</script>
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script>
    function rangeLow(){
        var tB = document.querySelectorAll("[id^=subSalaryLow]");
        var sum = 0;
        for(var i = 0; i < tB.length; i++){
            sum = parseInt(tB[0].value)
        }
        document.getElementById("salaryLow").value = sum
    }

    function rangeHigh(){
        var tB = document.querySelectorAll("[id^=subSalaryHigh]");
        var sum = 0;
        for(var i = 0; i < tB.length; i++){
            sum = parseInt(tB[tB.length-1].value)
        }
        document.getElementById("salaryHigh").value = sum
    }

    function checkAngkaLow(q){
        $(".spanLow"+q).keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57) && e.which != 13) {
                //display error message
                // return false;
                $("#alertLow"+q).show();
            }
            else if(e.which == 13){
                var inputs = $(this).parents("form").eq(0).find(":input");
                var idx = inputs.index(this);

                if (idx == inputs.length - 1) {
                    inputs[0].select()
                } else {
                    inputs[idx + 1].focus(); //  handles submit buttons
                    inputs[idx + 1].select();
                }
                return false;
            }
            else{
                $("#alertLow"+q).hide();
            }
        });

        $(".spanLow"+q).focusout(function (e) {
            var qu = parseInt(q) - 1
            if($(".valLow"+qu) && parseInt($(".valLow"+q).val()) <= parseInt($(".valLow"+qu).val())){
                $("#valLow"+q).html('Has to be greater than the above');
                $("#valLow"+q).show();
                $("#alertLow"+q).hide();
                $(".spanLow"+q).focus();
                $("#submitGrade").attr("disabled", true);
            }
            else{
                $("#valLow"+q).hide();
                $("#submitGrade").attr("disabled", false);
            }
        });
    }

    function checkAngkaHigh(q){
        $(".spanHigh"+q).keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57) && e.which != 13) {
                //display error message
                // return false;
                $("#alertHigh"+q).show();
            }
            else if(e.which == 13){
                var inputs = $(this).parents("form").eq(0).find(":input");
                var idx = inputs.index(this);

                if (idx == inputs.length - 1) {
                    inputs[0].select()
                } else {
                    inputs[idx + 1].focus(); //  handles submit buttons
                    inputs[idx + 1].select();
                }
                return false;
            }
            else{
                $("#alertHigh"+q).hide();
            }
        });

        $(".spanHigh"+q).focusout(function (e) {
            var qu = parseInt(q) - 1
            if(parseInt($(".spanHigh"+q).val()) <= parseInt($(".spanLow"+q).val())){
                $("#valHigh"+q).html('Has to be greater than the left');
                $("#valHigh"+q).show();
                $("#alertHigh"+q).hide();
                $(".spanHigh"+q).focus();
                $("#submitGrade").attr("disabled", true);
            }
            else if($(".valHigh"+qu) && parseInt($(".valHigh"+q).val()) <= parseInt($(".valHigh"+qu).val())){
                $("#valHigh"+q).html('Has to be greater than the above');
                $("#valHigh"+q).show();
                $("#alertHigh"+q).hide();
                $(".spanHigh"+q).focus();
                $("#submitGrade").attr("disabled", true);
            }
            else{
                $("#valHigh"+q).hide();
                $("#submitGrade").attr("disabled", false);
                $(".addSub").attr("disabled", false);
                $("#delSub").attr("disabled", false);
            }
        });
    }

    function allowNumber(){
        $(".subSalaryLow").keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                //display error message
                return false;
            }
        });

        $(".subSalaryHigh").keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                //display error message
                return false;
            }
        });
    }

    let lineNo = $('#gradeTable tr').length - 1;
    // alert(lineNo);
    $(document).ready(function(){
        allowNumber();
        $("#salaryLow").keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                //display error message
                return false;
            }
        });

        $("#salaryHigh").keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                //display error message
                return false;
            }
        });

        $('.addSub').click(function(){
            var newRow = '<tr id="subGradeInput" class="newRow">';
            newRow += '<td>' + lineNo + '</td>';
            newRow += '<td><input required name="subGradeName[]" id="subGradeName" type="text" class="form-control"></td>';
            newRow += '<td><input required onkeypress="checkAngkaLow('+lineNo+')" onkeyup="rangeLow()" name="subSalaryLow[]" id="subSalaryLow" type="text" class="form-control subSalaryLow spanLow'+lineNo+' valLow'+lineNo+'" placeholder="Lowest"></br><span id="alertLow'+lineNo+'" style="display:none;color:red;font-style:italic;">This value has to be numeric</span><span id="valLow'+lineNo+'" style="display:none;color:red;font-style:italic;"></span></td>';
            newRow += '<td><input required onkeypress="checkAngkaHigh('+lineNo+')" onkeyup="rangeHigh()" name="subSalaryHigh[]" id="subSalaryHigh" type="text" class="form-control subSalaryHigh spanHigh'+lineNo+' valHigh'+lineNo+'" placeholder="Highest"></br><span id="alertLow'+lineNo+'" style="display:none;color:red;font-style:italic;">This value has to be numeric</span><span id="valHigh'+lineNo+'" style="display:none;color:red;font-style:italic;"></span></td>';
            newRow += '<td></td>';
            newRow += '</tr>';

            $('#gradeTable').append(newRow);
            lineNo++;
            $('#delSub').show();
            allowNumber();
        })

        $('#delSub').click(function(){
            $('#gradeTable tr:last').remove();
            lineNo--;
            var tB = document.querySelectorAll("[id^=subSalaryLow]");
            var sum = 0;
            for(var i = 0; i < tB.length; i++){
                sum = parseInt(tB[0].value)
            }
            document.getElementById("salaryLow").value = sum
            var tH = document.querySelectorAll("[id^=subSalaryHigh]");
            var sumH = 0;
            for(var i = 0; i < tH.length; i++){
                sumH = parseInt(tH[tH.length-1].value)
            }
            document.getElementById("salaryHigh").value = sumH
            if($('#gradeTable tr').length == 4){
                $('#delSub').hide();
            }
        })
        
        $('#enSub').change(function(){
            if($('#enSub').is(':checked')){
                $('#subGrade').show();
                $('#subGradeInput').show();
                $('#salaryLow').prop('disabled', true);
                $('#salaryHigh').prop('disabled', true);
                $('#subGradeName').prop('disabled', false);
                $('#subSalaryLow').prop('disabled', false);
                $('#subSalaryHigh').prop('disabled', false);
                $('#salaryLow').val(0);
                $('#salaryHigh').val(0);
            }
            else{
                $('#subGrade').hide();
                $('#subGradeInput').hide();
                $('#salaryLow').prop('disabled', false);
                $('#salaryHigh').prop('disabled', false);
                $('#subGradeName').prop('disabled', true);
                $('#subGradeName').val('');
                $('#subSalaryLow').prop('disabled', true);
                $('#subSalaryLow').val(0);
                $('#subSalaryHigh').prop('disabled', true);
                $('#subSalaryHigh').val(0);
                if($('#gradeTable tr').length > 4){
                    $('.newRow').each(function(){
                        this.remove();
                        lineNo--;
                    })
                }
                // alert($('#gradeTable tr').length)
            }
        })

        $("#gradeName").keyup(function(){
            var name = $('#gradeName').val();
            var type = 'store';
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                },
                url: '/administrator/check-grade-name',
                type: 'POST',
                dataType: 'json',
                processData: false,
                data: "&name=" + name + "&type=" + type,
                success: function(response){
                    // console.log(response)
                    if(response == true){
                        $('#gradeName').css("color", "blue");
                    }
                    if(response == false){
                        $('#gradeName').css("color", "red");
                    }
                    if(name == ''){
                        $('#gradeName').css("color", "black");
                    }
                }
            });
        });
    })
</script>
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->

@section('footer-script')
@endsection

@endsection
