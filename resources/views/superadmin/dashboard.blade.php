@extends('layouts.superadmin')

@section('title', 'Dashboard')

@section('content')
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">Client Area</h4> </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="javascript:void(0)">Dashboard</a></li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <form class="form-horizontal">
                    {{ csrf_field() }}
                    <div class="col-md-6">
                        <div class="white-box">
                            <div class="form-group">
                                <label class="col-md-12">List Module</label>
                                @if(isset($product))
                                    @foreach($product as $no => $item)
                                        @php($item->id = $item->emhr_id)
                                        @php($check='')
                                        @php($limit='No Limit')
                                        @foreach($data as $key => $items)
                                            @if($items->crm_product_id == $item->id)
                                                @php($check='checked')
                                                @if($items->limit_user > 0 )
                                                    @php($limit=$items->limit_user)
                                                @endif
                                            @endif
                                        @endforeach
                                        <div class="col-md-6">
                                            <label>
                                                <input type="checkbox" {{$check}}  disabled style="margin-right: 10px; margin-bottom: 10px" class="project_product_id item_product[]" name="project_product_id[{{$item->id}}]" value="{{$item->id}}"> {{$item->name}}
                                            </label>
                                            @if($item->is_user_limit)
                                                <input type="text" style="margin-bottom: 10px" disabled value="{{$limit}}" class="form-control limit_user" name="limit_user[{{$item->id}}]" placeholder="User Limit">
                                            @endif
                                        </div>
                                        <div class="clearfix"></div>
                                    @endforeach
                                @endif
                            </div>
                            @if(isset($project))
                                @foreach($project as $no => $itemProject)
                                    <div class="form-group">
                                        <label class="col-md-12">Project Name</label>
                                        <div class="col-md-6">
                                            <input type="text" name="name" readonly="true" class="form-control" value="{{$itemProject->name}}">
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Due Date</label>
                                        <div class="col-md-6">
                                            <input type="text" name="name" readonly="true" class="form-control" value="{{$itemProject->due_date}}">
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                @endforeach
                            @endif
                            <div class="clearfix"></div>
                            <div class="col-md-12">
                                <button type="button" onclick="editModule()" class="btn btn-info btn-sm m-r-5" style="display: none; float: left; margin-right:5px" ><i class="fa fa-edit"></i> edit</button>
                                <button type="submit" class="btn btn-sm btn-success waves-effect waves-light m-r-10" id="btnUpdate" style="display: none; margin-left: 5px;"><i class="fa fa-save"></i> Update</button>
                                <br style="clear: both;" />
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
        @include('layouts.footer')
    </div>

    <style type="text/css">
        .col-in h3 {
            font-size: 20px;
        }
    </style>
@section('js')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script type="text/javascript">
        // $(document).ready(function () {

        //     //var el = $("input[name='project_type_id']").val();
        //     var projectType = $("select[name='project_type_id'] :selected");
        //     var el = projectType.val();

        //     if(el == 1)
        //     {
        //         document.getElementById('divLabelDuration').style.display = "none";
        //         document.getElementById('divLabelExpired').style.display = "none";
        //         document.getElementById('divLabelLicense').style.display = "block";
        //     }
        //     if(el == 2)
        //     {
        //         document.getElementById('divLabelDuration').style.display = "block";
        //         document.getElementById('divLabelExpired').style.display = "block";
        //         document.getElementById('divLabelLicense').style.display = "none";
        //     }
        // });

        function editModule() {
            $(".project_product_id").removeAttr("disabled", "disabled");
            $(".limit_user").removeAttr("disabled", "disabled");

            //document.getElementById('project_type_id').removeAttribute('readonly');
            //$(".project_type_id").removeAttr("disabled", "disabled");
            document.getElementById('btnUpdate').style.display = "block";

            var arr = document.getElementsByName('price');
            var tot=0;
            for(var i=0;i<arr.length;i++){
                if(parseInt(arr[i].value))
                    tot += parseInt(arr[i].value);
            }
            document.getElementById('total').value = tot;
        }
        function handleClick(cb) {
            //di cek semua yang tercentang nilainya

            //alert("Clicked, new value = " + cb.checked);
        }


        // $(".project_product_id").click(function(){
        //     var getcheck = $(this).val();
        //     alert(getcheck);

        // });

        //function calculateprice(id){
        // if($("input[name='project_product_id["+id+"]']:checked")){

        //     var totalharga = [];
        //     $.each($("input[name^='price']"), function(){
        //         totalharga.push($(this).val());
        //     });
        //     var val = $("input[name='project_product_id["+id+"]']").val();
        //     console.log(val);

        //     var array = totalharga,
        //     s = 0,
        //     p = 1,
        //     i;

        //     for(i = 0; i < array.length; i += 1){
        //         s += parseInt(array[i]);
        //     }
        //     $("input[name='total']").val(s);
        // }


        $('input[type="checkbox"]').click(function(){
            var val = $(".price"+ $(this).val()).val();
            if($(this).is(":checked")){
                $("input[name='total']").val(parseInt($("input[name='total']").val()) + parseInt(val));
            }
            else if($(this).is(":not(:checked)")){
                $("input[name='total']").val(parseInt($("input[name='total']").val()) - parseInt(val));
            }
        });
        //}
    </script>
@endsection
@endsection
