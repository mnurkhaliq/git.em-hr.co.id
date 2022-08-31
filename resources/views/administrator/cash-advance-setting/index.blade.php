@extends('layouts.administrator')

@section('title', 'Setting PIC Transfer Cash Advance, Payment Request, Business Trip & Medical')

@section('sidebar')

@endsection

@section('content')
<!-- ============================================================== -->
<!-- Page Content -->
<!-- ============================================================== -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">SETTING PIC TRANSFER CASH ADVANCE, BUSINESS TRIP, MEDICAL & PAYMENT REQUEST</h4> </div>
            <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12">
               
                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">SETTING PIC TRANSFER CASH ADVANCE, BUSINESS TRIP, MEDICAL & PAYMENT REQUEST</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title m-b-0 pull-left">Transfer Cash Advance, Business Trip, Medical & Payment Request</h3>
                    <a class="btn btn-info btn-xs pull-right add-accounting"><i class="fa fa-plus"></i> Add</a>
                    <div class="clearfix"></div>
                    <br />
                    <div class="table-responsive">
                        <table class="table display nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="30" class="text-center">NO</th>
                                    <th>NIK / NAMA</th>
                                    <th>POSITION</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $no => $item)
                                <tr>
                                    <td>{{ ($no + 1) }}</td>
                                    <td>{{ isset($item->user->name) ? $item->user->nik .'/'. $item->user->name : '' }}</td>
                                    <td>{{ $item->user->structure ? $item->user->structure->position->name.($item->user->structure->division ? ' - '.$item->user->structure->division->name : '').($item->user->structure->title ? ' - '.$item->user->structure->title->name : '') : "" }}</td>
                                    <td>
                                        <form action="{{ route('administrator.transfer-setting.destroy', $item->id) }}" onsubmit="return confirm('Delete this data?')" method="post" style="float: left;">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}                                               
                                            <button type="submit" class="btn btn-danger btn-xs m-r-5"><i class="ti-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr> 
                                    <td colspan="4" class="text-center">No data available in table</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>        
        </div>
        <!-- /.row -->
        <!-- ============================================================== -->
    </div>
    <!-- /.container-fluid -->
   @include('layouts.footer')
</div>
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->

<!-- sample modal content -->
<div id="modal_accounting" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Add Transfer</h4> </div>
                <div class="modal-body">
                   <form class="form-horizontal">
                       <div class="form-group">
                            <label class="col-md-3">Choose </label>
                            <div class="col-md-6">
                                <input type="text" class="form-control autocomplete-accounting" />
                                <input type="hidden" class="modal_accounting_id">
                            </div>
                       </div>
                   </form>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info btn-sm" id="add_modal_accounting">Add</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@section('footer-script')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<style type="text/css">
    .ui-menu.ui-widget.ui-widget-content.ui-autocomplete.ui-front {
        z-index: 9999;
    } 
</style>
<script type="text/javascript">
    $(".autocomplete-accounting" ).autocomplete({
        minLength:0,
        limit: 25,
        source: function( request, response ) {
            $.ajax({
              url: "{{ route('ajax.get-karyawan-transfer') }}",
              method : 'POST',
              data: {
                'name': request.term, '_token' : $("meta[name='csrf-token']").attr('content')
              },
              success: function( data ) {
                response( data );
              }
            });
        },
        select: function( event, ui ) {
            $( ".modal_accounting_id" ).val(ui.item.id);
        }
    }).on('focus', function () {
            $(this).autocomplete("search", "");
    });
    $('.add-accounting').click(function(){
        $('#modal_accounting').modal('show');
    });
    $('#add_modal_accounting').click(function(){
        $.ajax({
            type: 'POST',
            url: '{{ route('administrator.transfer-setting.store') }}',
            data: {'id' : $('.modal_accounting_id').val(), '_token' : $("meta[name='csrf-token']").attr('content')},
            dataType: 'json',
            success: function (data) {
                location.reload();
            }
        });
    });
</script>
@endsection
@endsection
