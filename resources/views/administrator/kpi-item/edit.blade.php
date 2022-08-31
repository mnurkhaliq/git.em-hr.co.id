@extends('layouts.administrator')

@section('title', 'KPI Items')

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
                <h4 class="page-title">KPI Items Administrator</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">KPI Items Administrator</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title m-b-0">Form Setting KPI Items Admin</h3>
                    <hr />
                    <form id="form">
                    <div class="form-group col-md-12" style="padding: 0">
                        <div class="col-md-3" style="padding: 0">
                            <label class="col-md-12">Start Date</label>
                            <div class="col-md-12">
                                <input type="text" name="start_date" class="form-control datepicker" value="{{ date("Y/m/d", strtotime($period->start_date)) }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-3" style="padding: 0">
                            <label class="col-md-12">End Date</label>
                            <div class="col-md-12">
                                <input type="text" name="end_date" class="form-control datepicker" value="{{ date("Y/m/d", strtotime($period->end_date))}}" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-12" style="padding: 0">
                        <div class="col-md-3" style="padding: 0">
                            <label class="col-md-12">Total Weightage</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control" id="weight_setting" value="" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-12" style="padding: 0">
                        <div class="col-md-6" style="padding: 0">
                            <label class="col-md-12">Status KPI Items Admin</label>
                            <div class="col-md-6">
                                <select name="status" CLASS="form-control" id="status">
                                    <option value="0">DRAFT</option>
                                    <option value="1">PUBLISH</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="button" id="btn_item_manager" class="btn btn-warning hidden" onclick="showManager()">Show Manager Items</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <label class="col-md-12">KPI Items Setting</label>
                            <div class="table-responsive col-md-10" style="padding: 0">

                                    <table id="mytable" class="table table-striped table-bordered display nowrap" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th width="30" class="text-center">NO</th>
                                                <th>KPI ITEM</th>
                                                <th width="12%">MIN RATE</th>
                                                <th width="12%">MAX RATE</th>
                                                <th width="10%" id="weightage">WEIGHTAGE</th>
                                                <th width="10%">ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody id="data_item">
                                            <tr>
                                                <td colspan="6" class="text-center">Select KPI period first</td>
                                            </tr>
                                        </tbody>
                                        <thead>
                                        <tr style="background: white">
                                            <td colspan="4" class="text-center"><b>Total</b></td>
                                            <td class="text-center"><b id="total" style="color: red;">0 %</b></td>
                                            <td class="text-center"></td>
                                        </tr>
                                        </thead>
                                    </table>

                            </div>
                            <div class="col-md-2">
                                <div class="pull-right">
                                    <button type="button" id="btn_add" class="btn btn-info pull-right hidden">Add</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="text-center">
                                    @if($period->is_lock != 1)
                                        <button type="button" id="btn_save" class="btn btn-success hidden" onclick="submitItem()">Save</button>
                                    @else
                                        <span href="#" class="btn btn-sm btn-danger waves-effect waves-light m-r-10"><i class="fa fa-lock"></i> Locked</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <br/>
                        <div class="col-md-12">
                            <a href="{{ route('administrator.kpi-item.index') }}" class="btn btn-sm btn-default waves-effect waves-light"><i class="fa fa-arrow-left"></i> Back</a>
                            <br style="clear: both;" />
                        </div>
                    </div>
                    </form>
                </div>
            </div>                        
        </div>
        <!-- /.row -->
        <!-- ============================================================== -->
    </div>

    <!-- BEGIN MODAL -->
    <div  class="modal fade none-border" id="modal-manager">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><strong>KPI Item Managers</strong></h4>
                </div>
                <form>
                    <div class="modal-body" id="modal-add-body">
                        <div class="form-group">
                            @php($param = getKpiScoringStatus($period->id))
                            <div class="col-md-4">
                                <label>Weightage (%)</label>
                                <div>
                                    <input type="text" class="form-control" id="weight_manager" disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label>Total Position</label>
                                <div>
                                    <input type="text" class="form-control" value="{{$param['count_position']}}" disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label>Total Position Published</label>
                                <div>
                                    <input type="text" class="form-control" value="{{$param['count_manager_published']}}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label style="margin-top: 24px">Select Position</label>
                            <div>
                                <select name="position" CLASS="form-control" id="position">
                                    <option value="0">- Select Position -</option>
                                    @foreach(getKpiPositions() as $position)
                                        <option value="{{$position->id}}">{{ $position ? $position->position->name.($position->division ? ' - '.$position->division->name : '').($position->title ? ' - '.$position->title->name : '') : '' }}
                                            {{in_array($position->id,json_decode($param['manager_published']))?' (Published)':''}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <label>Status</label>
                                <div>
                                    <input type="text" id="status_manager" class="form-control" value="" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12" style="margin-top: 24px">KPI Items Setting</label>
                            <div class="table-responsive col-md-12">
                                <table class="table table-striped table-bordered display nowrap" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="30" class="text-center">NO</th>
                                        <th>KPI ITEM</th>
                                        <th width="12%">MIN RATE</th>
                                        <th width="12%">MAX RATE</th>
                                        <th width="10%" id="weightage_manager">WEIGHTAGE</th>
                                    </tr>
                                    </thead>
                                    <tbody id="data_item_manager">
                                    <tr>
                                        <td colspan="5" class="text-center">Select Position first</td>
                                    </tr>
                                    </tbody>
                                    <thead>
                                    <tr style="background: white">
                                        <td colspan="4" class="text-center"><b>Total</b></td>
                                        <td class="text-center"><b id="total_manager" style="color: red;">0 %</b></td>
                                    </tr>
                                    </thead>
                                </table>

                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">

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
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script>

        var period_id={{$period->id}}, item_count = 0, i = 0;
        var setting_scoring = null;
        var ids=[], dels=[];
        // $('#period').change(function(){
        //     period_id = $(this).val();
        //     getPeriod();
        // });
        getPeriod();

        function getPeriod(){
            ids=[];
            dels=[];
            if(period_id!==''){
                $.ajax({
                    type: 'GET',
                    url: '{{ route('ajax.get-kpi-item') }}',
                    dataType: 'json',
                    data:{'period_id':period_id,'module_id':1},
                    success: function (data) {
                        $('#weightage').html('WEIGHTAGE ('+data.weightage+'%)');
                        $('#weight_setting').val(data.weightage);
                        $('#weight_manager').val((100 - data.weightage));
                        $('#btn_item_manager').removeClass('hidden');
                        if(data.weightage == 0){
                            $('#btn_add').addClass('hidden');
                        }else {
                            $('#btn_add').removeClass('hidden');
                        }
                        i=0;
                        console.log(data.items);

                        var items = data.items;
                        item_count = items.length;
                        setting_scoring = data;
                        if(item_count == 0){
                            $('#data_item').html('<tr><td colspan="6" class="text-center">No item yet</td></tr>');
                        }
                        else{
                            $('#data_item').html('');
                            for(i = 0; i < item_count; i++){
                                ids.push(items[i].id);
                                var html = '<tr id="komponen'+i+'">' +
                                    '<td>'+(i+1)+'</td>' +
                                    '<td><input class="form-control" type="text" name="name[]" value="'+items[i].name+'" required></td>' +
                                    '<td><input class="form-control" type="text" value="{{$period->min_rate}}" disabled></td>' +
                                    '<td><input class="form-control" type="text" value="{{$period->max_rate}}" disabled></td>' +
                                    '<td><input class="form-control weightage" type="text" name="weightage[]" value="'+items[i].weightage+'" min="0" max="'+data.weightage+'" step="0.01" required></td>' +
                                    '<td><button type="button" onclick="remove('+items[i].id+','+i+')" class="btn btn-xs btn-danger btn_delete"><i class="fa fa-trash"></button></td>' +
                                    '</tr>';
                                $('#data_item').append(html);
                            }
                            console.log(ids);
                            checkTotal();

                        }
                        if(data.status.length == 0)
                        {
                            $('#status').val(0);
                        }
                        else{
                            $('#status').val(data.status[0].status);
                        }
                        updateTotal();

                        $('#view_status').removeClass("hidden");
                        // $('#btn_add').removeClass("hidden");
                        $('#btn_save').removeClass("hidden");
                        // $("#btn_add").attr("disabled", false);
                        // $("#btn_save").attr("disabled", false);
                        @if($period->is_lock == 1)
                            $("input[name='name[]']").prop("disabled", true);
                            $("input[name='weightage[]']").prop("disabled", true);
                            $("#status").prop("disabled", true);
                            $("#btn_add").prop("disabled", true);
                            $(".btn_delete").prop("disabled", true);
                            // $("select").prop("disabled", true);
                            // $("button").prop("disabled", true);

                        @endif
                    }
                });


            }
            else{
                $('#data_item').html('<tr><td colspan="6" class="text-center">Select KPI period first</td></tr>');
                $('#weightage').html('WEIGHTAGE');
                $('#view_status').addClass("hidden");
                $('#btn_add').addClass("hidden");
                $('#btn_save').addClass("hidden");
                // $("#btn_add").attr("disabled", true);
                // $("#btn_save").attr("disabled", true);
            }
        }
        $('#btn_add').on('click',function () {
            var min_rate = 0, max_rate = 0;
            if(setting_scoring!=null){
                min_rate = '{{$period->min_rate}}';
                max_rate = '{{$period->max_rate}}';
            }

            var html = '<tr id="komponen'+i+'">' +
                '<td>'+(i+1)+'</td>' +
                '<td><input class="form-control" type="text" name="name[]" value="" required></td>' +
                '<td><input class="form-control" type="text" value="'+min_rate+'" disabled></td>' +
                '<td><input class="form-control" type="text" value="'+max_rate+'" disabled></td>' +
                '<td><input class="form-control weightage" type="text" name="weightage[]" value="" min="0" max="'+setting_scoring.weightage+'" required></td>' +
                '<td><button type="button" onclick="remove(0,'+i+')" class="btn btn-xs btn-danger"><i class="fa fa-trash"></button></td>' +
                '</tr>';

            i++;
            if(item_count == 0){
                $('#data_item').html(html);
            }
            else {
                $('#data_item').append(html);
            }
            item_count++;
            checkTotal();
        });
        function checkTotal() {
            $(".weightage").keydown(function () {
                // Save old value.
                if (!$(this).val() || (parseFloat($(this).val()) <= parseFloat($(this).attr('max')) && parseFloat($(this).val()) >= 0))
                    $(this).data("old", $(this).val());
            });
            $('.weightage').on('keyup', function () {
                if (!(!$(this).val() || (parseFloat($(this).val()) <= $(this).attr('max') && parseFloat($(this).val()) >= 0))) {
                    $(this).val($(this).data("old"));
                    return;
                }
                updateTotal();
            });
            $('.weightage').on('change', function () {
                updateTotal();
            });
        }
        function updateTotal() {
            var total_weightage = 0;
            $(".weightage").each(function () {
                if ($(this).val() != "") {
                    total_weightage += parseFloat($(this).val());
                }
            });
            $('#total').html(total_weightage.toFixed(2) + " %");
            if(total_weightage == setting_scoring.weightage){
                $('#total').css('color','green');
            }
            else{
                $('#total').css('color','red');
            }
        }

        function remove(id,n) {
            $('#komponen'+n).remove();
            if(id!=0){
                const index = ids.indexOf(id);
                if (index !== -1) {
                    ids.splice(index, 1);
                }
                dels.push(id);
            }
            item_count--;
            if(item_count==0){
                $('#data_item').html('<tr><td colspan="6" class="text-center">No item</td></tr>');
            }
            updateTotal();
            console.log("dihapus : ");
            console.log(dels);

        }
        function submitItem(){
            swal({
                title: 'Are you sure?',
                text: 'The KPI items will be saved!',
                buttons: true,
                dangerMode: true,
            }).then((willSubmit) => {
                if (willSubmit) {
                    var form = $('#form')[0]; // You need to use standart javascript object here
                    var formData = new FormData(form);
                    for(var i=0; i<ids.length;i++){
                        formData.append("ids[]",ids[i]);
                    }
                    for(var i=0; i<dels.length;i++){
                        formData.append("dels[]",dels[i]);
                    }

                    formData.append('setting_id',setting_scoring.id);
                    formData.append('_token',"{{csrf_token()}}");
                    
                    // Display the key/value pairs
                    for (var pair of formData.entries()) {
                        console.log(pair[0]+ ', ' + pair[1]); 
                    }
                    $("#btn_save").attr("disabled", true);
                    $.ajax({
                        url: "{{route('administrator.kpi-item.store')}}",
                        type: "POST",
                        data:formData,
                        dataType: "JSON",
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            if (data.status == 'success') {
                                swal("Success!", data.message, "success");
                                getPeriod();
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
        }

        function showManager() {
            $('#modal-manager').modal('show');
        }
        var position_id = 0;
        $('#position').change(function(){
            position_id = $(this).val();
            getPeriodManager();
        });

        function getPeriodManager() {
            if(position_id!==''){
                $.ajax({
                    type: 'GET',
                    url: '{{ route('ajax.get-kpi-item-manager') }}',
                    dataType: 'json',
                    data:{'period_id':period_id,'module_id':2,'structure_organization_custom_id':position_id},
                    success: function (data) {
                        console.log(data);
                        data_admin = data[0];
                        data_manager = data[1];
                        var i=0;
                        var items = data_manager.items;
                        // item_count = items.length;
                        // setting_scoring = data_manager;
                        if(items.length == 0){
                            $('#data_item_manager').html('<tr><td colspan="5" class="text-center">No item yet</td></tr>');
                        }
                        else{
                            $('#data_item_manager').html('');
                            var total_weightage = 0;
                            for(i = 0; i < items.length; i++){
                                // ids.push(items[i].id);
                                total_weightage += parseFloat(items[i].weightage);
                                var html = '<tr>' +
                                    '<td>'+(i+1)+'</td>' +
                                    '<td><input class="form-control" title="'+items[i].name+'" type="text"  value="'+items[i].name+'" readonly></td>' +
                                    '<td><input class="form-control" type="text" value="{{$period->min_rate}}" disabled></td>' +
                                    '<td><input class="form-control" type="text" value="{{$period->max_rate}}" disabled></td>' +
                                    '<td><input class="form-control" type="text"  value="'+items[i].weightage+'" min="0" max="'+data_manager.weightage+'" step="0.01" disabled></td>' +
                                    '</tr>';
                                $('#data_item_manager').append(html);
                            }
                            $('#total_manager').html(total_weightage.toFixed(2) + " %");
                            if(total_weightage == $('#weight_manager').val()){
                                $('#total_manager').css('color','green');
                            }
                            else{
                                $('#total_manager').css('color','red');
                            }
                        }
                        if(data_manager.status.length == 0)
                        {
                            $('#status_manager').val('No Data!');
                        }
                        else{
                            var st = "Draft";
                            if(data_manager.status[0].status == 1)
                                st = "Published"
                            $('#status_manager').val(st);
                        }
                    }
                });


            }
            else{
                $('#data_item_manager').html('<tr><td colspan="5" class="text-center">Select position first</td></tr>');
            }
        }
    </script>
@endsection
@endsection
