@extends('layouts.administrator')

@section('title', 'Visit List')

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title" style="overflow: inherit;">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Manage Visit List</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <div class="col-md-12 pull-right" style="padding:0px;">
                    <form method="POST" action="{{ route('visit.index') }}" id="filter-form" autocomplete="off">
                        {{ csrf_field() }}
                        <input type="hidden" name="action" value="view">
                        <input type="hidden" name="reset" value="0">
                        <input type="hidden" name="eksport" value="0">
                        <div class="col-md-2 pull-right" style="padding:0px;">
                            <div class="btn-group pull-right">
                                <a href="javascript:void(0)" title="Show Hide Column" aria-expanded="false" data-toggle="dropdown" class="btn btn-default btn-sm btn-outline dropdown-toggle">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <ul role="menu" class="dropdown-menu" style="min-width: 10px; color: blue;">
                                    <li><a class="toggle-vis" data-column="1" style="color:blue;">NIK</a></li> 
                                    <li><a class="toggle-vis" data-column="2" style="color:blue;">Name</a></li>                                       
                                    <li><a class="toggle-vis" data-column="3" style="color:blue;">Visit Type</a></li>
                                    <li><a class="toggle-vis" data-column="4" style="color:blue;">Visit Category</a></li>
                                    <li><a class="toggle-vis" data-column="5" style="color:blue;">Date</a></li> 
                                    <li><a class="toggle-vis" data-column="6" style="color:blue;">Day</a></li>
                                    <li><a class="toggle-vis" data-column="7" style="color:blue;">Timezone</a></li>
                                    <li><a class="toggle-vis" data-column="8" style="color:blue;">Branch Name / Place Name</a></li>
                                    <li><a class="toggle-vis" data-column="9" style="color:blue;">Location Name</a></li> 
                                    <li><a class="toggle-vis" data-column="10" style="color:blue;">Activity Name</a></li>
                                    <li><a class="toggle-vis" data-column="11" style="color:blue;">PIC Name</a></li>
                                    <li><a class="toggle-vis" data-column="12" style="color:blue;">Visit Point</a></li>
                                </ul>
                            </div>
                            <div class="btn-group m-l-4 m-r-4 pull-right" style="padding-left:3px; padding-right:3px;">
                                <a href="javascript:void(0)" aria-expanded="false" data-toggle="dropdown" class="btn btn-info btn-sm dropdown-toggle">Action
                                    <i class="fa fa-gear"></i>
                                </a>
                                <ul role="menu" class="dropdown-menu">
                                    <li><a href="javascript:void(0)" onclick="reset_filter()"><i class="fa fa-refresh"></i> Reset Filter </a></li>
                                    <li><a href="javascript:void(0)" onclick="eksportVisit()"><i class="fa fa-download"></i> Export </a></li>
                                </ul>
                            </div>
                            <button id="filter_view" class="btn btn-default btn-sm btn-outline"> <i class="fa fa-search-plus"></i></button>
                        </div>
                        <div class="col-md-2 pull-right">
                            <select name="branch" class="form-control form-control-line" id="branch">
                                <option value="" selected>- Branch -</option>
                                @foreach(cabangvisit() as $item)
                                <option {{ $item->id == \Session::get('branch') ? 'selected' : '' }} value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 pull-right">
                            <select name="position" class="form-control form-control-line" id="position">
                                <option value="" selected>- Position -</option>
                                @foreach(getStructureName() as $item)
                                <option {{ $item['id'] == \Session::get('position') ? 'selected' : '' }} value="{{$item['id']}}">{{$item['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 pull-right">
                            <input type="text" name="filter_end" class="form-control datepicker form-control-line" id="filter_end" placeholder="End Date" value="{{ \Session::get('filter_end') }}">
                        </div>
                        <div class="col-md-2 pull-right">
                            <input type="text" name="filter_start" class="form-control datepicker form-control-line" id="filter_start" placeholder="Start Date" value="{{ \Session::get('filter_start') }}" />
                        </div>
                        <div class="col-md-2 pull-right">
                            <input type="text" name="visit_name" id="nama_nik" class="form-control form-control-line autocomplete-karyawan" placeholder="Nik / Name" value="{{ \Session::get('visit_name')}}">
                        </div>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 p-l-0 p-r-0">
                <div class="white-box">
                    <div class="table-responsive">
                        <table id="mytable" class="display nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th rowspan="1">No</th>
                                    <th rowspan="1">NIK</th>
                                    <th rowspan="1">Name</th>
                                    <th rowspan="1">Visit Type</th>
                                    <th rowspan="1">Visit Category</th>
                                    <th rowspan="1">Date</th>
                                    <th rowspan="1">Day</th>
                                    <th rowspan="1">Timezone</th>
                                    <th rowspan="1">Branch Name / Place Name</th>
                                    <th rowspan="1">Location Name</th>
                                    <th rowspan="1">Activity Name</th>
                                    <th rowspan="1">PIC Name</th>
                                    <th rowspan="1">Visit Point</th>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
    @include('layouts.footer')
</div>

<div id="modal_detail_visit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Visit</h4>
            </div>
            <div class="modal-body">
                <div><b style="font-size: large">Activity Name : </b>
                        <p id="Visit_activity_name"></p>
                    

                    <div><b style="font-size: medium">Description : </b>
                        <p id="description"></p>
                    </div>
                    <div>
                        <b style="font-size: medium" class="text-center">Location Name : </b>
                        <p id="location_name"></p>
                        <b style="font-size: medium">Visit Location Map</b>
                    </div>
                    <div id="map" style="height: 254px; width: 100%;">
                    </div>
                    <br>
                    <div class="form-group">
                        <label class="col-md-6">Latitude </label>
                        <label class="col-md-6">Longitude </label>
                        <div class="col-md-6">
                            <input type="text" class="form-control input-latitude" readonly="true">
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control input-longitude" readonly="true">
                        </div>
                        <br>
                    </div>
                    <div id="container_justification">
                        <br>
                        <br>
                        <b style="font-size: medium" id="title_justification">Note : </b>
                        <p id="justification"></p>
                    </div>
                    <div>
                        <b style="font-size: medium">Branch Name / Place Name : </b>
                        <p id="branch_name"></p>
                    </div>
                    <form class="form-horizontal frm-modal-inventaris">
                        <div class="mySlides">
                            <table class="table table-hover" id="tableListVisitPict">
                                <tr>
                                    <th class="text-center">Photo</th>
                                </tr>
                            </table>
                            <br>
                        </div>
                        <div>
                            <b style="font-size: medium">PIC Name : </b>
                            <p id="picname"></p>
                        </div>
                        <b style="font-size: medium">Signature : </b>
                        <div class="col-md-12 signature">
                        </div>


                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
        </div>
    </div>

    @section('js')

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBTctq_RFrwKOd84ZbvJYvU3MEcrLmPNJ8" async defer>

    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>

    <script type="text/javascript">
        var t;
        loadData();

        function loadData(){
            $('#mytable').DataTable().destroy();
            $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings)
            {
                return {
                    "iStart": oSettings._iDisplayStart,
                    "iEnd": oSettings.fnDisplayEnd(),
                    "iLength": oSettings._iDisplayLength,
                    "iTotal": oSettings.fnRecordsTotal(),
                    "iFilteredTotal": oSettings.fnRecordsDisplay(),
                    "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                    "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
                };
            };
            t = $("#mytable").DataTable({
                searching: false,
                ordering: true,
                lengthChange: true,
                pageLength: 50,
                initComplete: function() {
                    var api = this.api();
                    $('#mytable_filter input')
                        .off('.DT')
                        .on('keyup.DT', function(e) {
                            if (e.keyCode == 13) {
                                api.search(this.value).draw();
                            }
                        });
                },
                oLanguage: {
                    sProcessing: "loading..."
                },
                oSearch: { "bSmart": false, "bRegex": true },
                processing: true,
                serverSide: true,
                fixedHeader: true,
                scrollCollapse: true,
                scrollX: true,
                ajax: {
                    "url": "{{ route('visit.table') }}",
                    "type": "GET",
                    "data": {
                        "filter_start": $('input[name="filter_start"]').val(),
                        "filter_end": $('input[name="filter_end"]').val(),
                        "visit_name": $('input[name="visit_name"]').val(),
                        "branch": $('select[name="branch"]').val(),
                        "position": $('select[name="position"]').val(),
                    }
                },
                columns: [
                    { "data": "id", "orderable": false },
                    { "data": "nik" },
                    { "data": "username" },
                    { "data": "master_visit_type_name" },
                    { "data": "master_category_name" },
                    { "data": "column_date", "name": "visit_time" },
                    { "data": "column_day", "name": "timetable" },
                    { "data": "timezone" },
                    { "data": "place_name" },
                    { "data": "locationname" },
                    { "data": "activityname" },
                    { "data": "picname" },
                    { "data": "point" },
                ],
                order: [
                    [5, 'desc'],
                ],
                rowCallback: function(row, data, iDisplayIndex) {
                    var info = this.fnPagingInfo();
                    var page = info.iPage;
                    var length = info.iLength;
                    var index = page * length + (iDisplayIndex + 1);
                    $('td:eq(0)', row).html(index);
                }
            });
        };

        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });

        $('#tableVisit').ready(function() {
            var user_id = $('#idUser').val()
            $.ajax({
                url: '{{route("visit.ajax-holiday")}}',
                type: 'GET',
                dataType: 'JSON',
                contentType: 'application/json',
                processData: false,
                success: function(data) {
                    // console.log(data)
                    if (data.message == 'success') {
                        if (data.holidays.length > 0) {
                            $('.hariAbsen').each(function(i) {
                                console.log(i)
                                var baru = i + 1
                                if ($('#holiday' + baru).val() == 0) {
                                    for (var y = 0; y < data.holidays.length; y++) {
                                        if ($(this).text() == data.holidays[y].tanggal) {
                                            $(this).css('color', 'red')
                                            
                                        }
                                    }
                                }
                            })
                        }
                    } else {

                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            })
        })

        $("#filter_view").click(function() {
            if ($('#filter_start').val() > $('#filter_end').val()) {
                alert('Tanggal Tidak Boleh Backdate!');
            } else {
                $("#filter-form input[name='action']").val('view');
                $("#filter-form").submit();
            }
        });

        function reset_filter() {
            $("#filter-form input.form-control, #filter-form select").val("");
            $("input[name='reset']").val(1);
            $("#filter-form").submit();
        }

        function eksportVisit() {
            $("input[name='eksport']").val(1);
            $("#filter-form").submit();

            $("input[name='eksport']").val(0);
        }

        $('a.toggle-vis').on('click', function (e) {
            e.preventDefault();
            e.target.style.color == 'blue' ? $(this).addClass('change-toggle') : $(this).removeClass('change-toggle');
            e.target.style.color = e.target.style.color == 'blue' ? 'red' : 'blue';
            // console.log($(this).attr('href'))
            // $($(this).attr('href')).click(function(e) {
            //     e.stopPropagation();
            // })
            // $($(this).attr('href')).prop("checked", !$($(this).attr('href')).prop("checked"));
            // if((e.target).tagName == 'INPUT') return true; 
            
            // Get the column API object
            var column = t.column($(this).attr('data-column'));
    
            // Toggle the visibility
            column.visible(!column.visible());
        });
    </script>
    <script>
        function detail_visit(el) {
            var idlist = $(el).data('visitid');
            var pathsignature = $(el).data('signature');
            var visittype = $(el).data('visittype');
            var isoutbranch = $(el).data('isoutbranch');
            var img = '<img src="' + pathsignature + '" style="width:100%;" />';
            $('#modal_detail_visit .modal-title').html($(el).data('title'));
            $('.signature').html(img);
            $(".input-latitude").val($(el).data('latitude'));
            $(".input-longitude").val($(el).data('longitude'));
            $("#modal_detail_visit").modal("show");
            $('#idvisit').html($(el).data('visitid'));
            $('#Visit_activity_name').html($(el).data('activity-name'));
            $('#picname').html($(el).data('picname'));
            $('#justification').html($(el).data('justification'));
            $('#location_name').html($(el).data('location'));
            if (visittype==2 || ( visittype==1 && isoutbranch==1))
            {
                $('#branch_name').html($(el).data('placename'));
                
            }
            else
            {
                $('#branch_name').html($(el).data('cabang'));
            }
            $('#description').html($(el).data('description'));


            // The location of Uluru
            var userLoc = {
                lat: $(el).data('latitude'),
                lng: $(el).data('longitude')
            };
            var icon = "{{asset('images/icon/icon_man.png')}}";
            // The map, centered at Uluru
            setTimeout(function() {
                var map = new google.maps.Map(
                    document.getElementById('map'));
                // The marker, positioned at Uluru
                var userMarker = new google.maps.Marker({
                    position: userLoc,
                    map: map,
                    icon: icon
                });
                var bounds = new google.maps.LatLngBounds();
                bounds.extend(userMarker.getPosition());
                var padding = 0;

                if ($(el).data('lat-branch') != "" && $(el).data('long-branch') != "") {
                    var branchLoc = {
                        lat: $(el).data('lat-branch'),
                        lng: $(el).data('long-branch')
                    };
                    var radius = $(el).data('radius-branch');
                    var distance = getDistance(userLoc.lat, userLoc.lng, branchLoc.lat, branchLoc.lng);
                    var color;
                    if (distance > radius) {
                        color = "#FF0000";
                        padding = 0;
                    } else {
                        color = "#7cb342";
                        padding = 100;
                    }

                    var cityCircle = new google.maps.Circle({
                        strokeColor: color,
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: color,
                        fillOpacity: 0.35,
                        map: map,
                        center: branchLoc,
                        radius: radius
                    });
                    console.log("City Circle colored : " + color);

                    bounds.extend(branchLoc);
                }
                map.fitBounds(bounds, padding);
            }, 1000);

            $.ajax({
                url: "visit-pict/" + idlist,
                type: "GET",
                dataType: "JSON",
                contentType: "application/json",
                processData: false,
                success: function(data) {
                    if (data.message == 'success') {
                        $('#IdVisit').val(idlist)
                        $('#tableListVisitPict').find('tr:gt(0)').remove()
                        for (var i = 0; i < data.data.length; i++) {
                            var num = i + 1;
                            if (data.data[i].visit_list_id == idlist) {
                                $('#tableListVisitPict tr:last').after(
                                    '<tr>' +
                                    '<td width=100% style="text-align: center; vertical-align: middle;"><img src="' + data.data[i].photo + '" style="width:100%;height:70%;text-align: center;"></img> <br> <br> <p>' + data.data[i].photocaption + '</p> </td>' +
                                    '</tr>'
                                )
                            } else {
                                $('#tableListVisitPict tr:last').after(
                                    '<tr>' +
                                    '<td width=100% style="text-align: center; vertical-align: middle;"><img src="' + data.data[i].photo + '" style="width:100%;height:70%;text-align: center;"></img> <br> <br> <p>' + data.data[i].photocaption + '</p> </td>' +
                                    '</tr>'
                                )
                            }
                        }
                    } else {
                        $('#tableListVisitPict').find('tr:gt(0)').remove()
                        $('#tableListVisitPict tr:last').after(
                            '<tr>' +
                            '<td colspan="1">No data.</td>' +
                            '</tr>'
                        )
                        $('#modal_detail_visit .modal-title').html($(el).data('title'));
                    }
                }
            })
        }

        function getDistance(lat1, lon1, lat2, lon2) {

            var R = 6371000; // Radius of the earth in m
            var dLat = deg2rad(lat2 - lat1); // deg2rad below
            var dLon = deg2rad(lon2 - lon1);
            var a =
                Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            var d = R * c; // Distance in km
            return d;
        }

        function deg2rad(deg) {
            return deg * (Math.PI / 180)
        }

        $(".autocomplete-karyawan").autocomplete({
            minLength: 0,
            limit: 25,
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('ajax.get-karyawan') }}",
                    method: 'POST',
                    data: {
                        'name': request.term,
                        '_token': $("meta[name='csrf-token']").attr('content')
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            select: function(event, ui) {
                $("input[name='id']").val(ui.item.id);
            }
        }).on('focus', function() {
            $(this).autocomplete("search", "");
        });

        var slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("demo");
  var captionText = document.getElementById("caption");
  if (n > slides.length) {slideIndex = 1}
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
  if(slides){
    slides[slideIndex-1].style.display = "block";
  }
  if(dots.length > 0){
    dots[slideIndex-1].className += " active";
    captionText.innerHTML = dots[slideIndex-1].alt;
  }
}
    </script>
    @endsection
    @endsection