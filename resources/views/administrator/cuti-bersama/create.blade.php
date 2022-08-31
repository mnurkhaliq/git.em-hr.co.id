@extends('layouts.administrator')

@section('title', 'Collective Leave')

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
                <h4 class="page-title">Form Collective Leave</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Collective Leave</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <form class="form-horizontal" enctype="multipart/form-data" action="{{ route('administrator.cuti-bersama.store') }}" method="POST" id="form-cuti-bersama">
                <div class="col-md-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Collective Leave</h3>
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
                        
                        <div class="form-group">
                            <label class="col-md-12">Date<span class="text-danger">*</span></label>
                            <div class="col-md-3">
                               <input type="text" name="dari_tanggal" id="from" placeholder="From Date" class="form-control">
                            </div>
                            <div class="col-md-3">
                               <input type="text" name="sampai_tanggal" id="to" placeholder="To Date" class="form-control">
                            </div>

                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Description<span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <input type="text" name="description" class="form-control" value="{{ old('description') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Deduct The Annual Leave</label>
                            <div class="col-md-6">
                            <input type="checkbox" id="impacttoleave" name="impacttoleave" onclick="isimpact()" class="switch-input"  value="1" {{ old('impacttoleave') ? 'checked="checked"' : '' }}/>
                        </div>
                        
                        </div> 
                        
                        <div class="clearfix"></div>
                        <br />
                        <div class="col-md-12">
                            <a href="{{ route('administrator.cuti-bersama.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Cancel</a>
                            <a class="btn btn-sm btn-success waves-effect waves-light m-r-10" id="submit-cuti"><i class="fa fa-save"></i> Submit Collective Leave</a>
                            <br style="clear: both;" />
                        </div>
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
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@section('footer-script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script type="text/javascript">

        $.ajax({
            type: 'GET',
            url: '{{ route('ajax.get-collective-calendar') }}',
            success: function (data) {
                $("#from, #to").datepicker({
                    dateFormat: "yy-mm-dd",
                    minDate: moment().add('d', 1).toDate(),
                    changeMonth: true,
                    beforeShowDay: function(date){
                        let loopDate = null
                        let loopMonth = '' + (date.getMonth() + 1)
                        let loopDay = '' + date.getDate()
                        let loopYear = date.getFullYear()
        
                        if (loopMonth.length < 2) 
                            loopMonth = '0' + loopMonth;
                        if (loopDay.length < 2) 
                            loopDay = '0' + loopDay;
        
                        loopDate = [loopYear, loopMonth, loopDay].join('-');
        
                        return [data['holiday'].filter(i => loopDate == i.tanggal).length == 0 && data['leave'].filter(i => loopDate == i.dari_tanggal).length == 0]
                    },
                    onSelect: function(selectedDate) {
                        if(this.id == 'from'){
                            let dateMin = $('#from').datepicker("getDate");
                            let rMin = new Date(dateMin.getFullYear(), dateMin.getMonth(), dateMin.getDate()); // Min Date = Selected
                            $('#to').datepicker("option", "minDate", rMin);
                        } else {
                            let dateMin = $('#to').datepicker("getDate");
                            let rMax = new Date(dateMin.getFullYear(), dateMin.getMonth(), dateMin.getDate()); // Max Date = Selected
                            $('#from').datepicker("option", "maxDate", rMax);
                        }
                    }
                });
            }
        });


        $("#submit-cuti").click(function(){ 

            if($("input[name='dari_tanggal']").val() == "" || $("input[name='sampai_tanggal']").val() == "" || $("input[name='description']").val() == "")
            {

                bootbox.alert('Complete all form!');
                return false;
            }
            if($("input[name='dari_tanggal']").val() >  $("input[name='sampai_tanggal']").val() )
            {

                bootbox.alert('To Date Must Greater Than From Date');
                return false;
            }
            bootbox.confirm('<label style="color: red;"></label><br ><br > Do you want process this collective leave?', function(result){
            
                if(result)
                {
                    $("#form-cuti-bersama").submit();
                }
            });

        });

        jQuery(function($) {
        $("#impacttoleave").click(function () {
            if ($(this).is(":checked")) {
                bootbox.alert('<label style="color: red;">This Option Will deduct the annual leave of all employees </label>');
            } 
        });
    });
    </script>
@endsection
    
@endsection
