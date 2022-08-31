@extends('layouts.administrator')

@section('title', 'Title')

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
                <h4 class="page-title">Form Title</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Title</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <form class="form-horizontal" enctype="multipart/form-data" action="{{ route('administrator.title.update', $data->id) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <div class="col-md-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Data Title</h3>
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
                        
                        <div class="col-md-6">
                        <input name="idTitle" type="hidden" id="idTitle" value="{{$data->id}}">
                            <div class="form-group">
                                <label class="col-md-12">Title Name</label>
                                <div class="col-md-10">
                                    <input id="titleName" type="text" name="name" class="form-control form-control-line nameCan" value="{{ $data->name }}">
                                </div>
                                <span style="display:none;margin-top:5px;color:blue;font-size:12px" id="canUseName">Name is available</span>
                                <span style="display:none;margin-top:5px;color:red;font-size:12px" id="cannotUseName">Name is existed</span>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Title Code</label>
                                <div class="col-md-10">
                                    <input id="titleCode" type="text" name="code" class="form-control form-control-line codeCan" value="{{ $data->code }}">
                                </div>
                                <span style="display:none;margin-top:5px;color:blue;font-size:12px" id="canUse">Code is available</span>
                                <span style="display:none;margin-top:5px;color:red;font-size:12px" id="cannotUse">Code is existed</span>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    
                        <a href="{{ route('administrator.title.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Cancel</a>
                        <button type="submit" class="btn btn-sm btn-success waves-effect waves-light m-r-10"><i class="fa fa-save"></i> Save</button>
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
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script>
    $(document).ready(function(){
        $(".codeCan").keyup(function(){
            var code = $('#titleCode').val();
            var id = $('#idTitle').val();
            var type = 'update';
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                },
                url: '/administrator/check-title-code',
                type: 'POST',
                dataType: 'json',
                processData: false,
                data: "&code=" + code + "&id=" + id + "&type=" + type,
                success: function(response){
                    // console.log(response)
                    if(response == true){
                        $('#canUse').show();
                        $('#cannotUse').hide();
                    }
                    if(response == false){
                        $('#cannotUse').show();
                        $('#canUse').hide();
                    }
                    if(code == ''){
                        $('#cannotUse').hide();
                        $('#canUse').hide();
                    }
                }
            });
        });

        $(".nameCan").keyup(function(){
            var name = $('#titleName').val();
            var id = $('#idTitle').val();
            var type = 'update';
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                },
                url: '/administrator/check-title-name',
                type: 'POST',
                dataType: 'json',
                processData: false,
                data: "&name=" + name + "&id=" + id + "&type=" + type,
                success: function(response){
                    // console.log(response)
                    if(response == true){
                        $('#canUseName').show();
                        $('#cannotUseName').hide();
                    }
                    if(response == false){
                        $('#cannotUseName').show();
                        $('#canUseName').hide();
                    }
                    if(name == ''){
                        $('#cannotUseName').hide();
                        $('#canUseName').hide();
                    }
                }
            });
        });
    })
</script>
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@endsection
