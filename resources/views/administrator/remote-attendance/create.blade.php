@extends('layouts.administrator')

@section('title', 'Remote Attendance')

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
                    <h4 class="page-title">Form Remote Attendance</h4> </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                    <ol class="breadcrumb">
                        <li><a href="javascript:void(0)">Dashboard</a></li>
                        <li class="active">Remote Attendance</li>
                    </ol>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- .row -->
            <div class="row">
                <form id="form" class="form-horizontal" enctype="multipart/form-data" action="{{ route('administrator.remote-attendance.store') }}" method="POST">
                    <div class="col-md-12">
                        <div class="white-box">
                            <h3 class="box-title m-b-0">Add Remote Attendance</h3>
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
                                <label class="col-md-12">Employee</label>
                                <div class="col-md-6">
                                    <input type="text" name="user" class="form-control autocomplete-user"  value="{{ old('user') }}">
                                    <input type="hidden" name="user_id" class="form-control" value="{{ old('user_id') }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Location Name</label>
                                <div class="col-md-6">
                                    <input type="text" name="location_name" class="form-control" value="{{ old('location_name') }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Time Zone</label>
                                <div class="col-md-6">
                                    <select name="timezone" id="timezone" class="form-control">
                                        <option value="">- Select Time Zone -</option>
                                        <option value="WIB">WIB (GMT +7)</option>
                                        <option value="WITA">WITA (GMT +8)</option>
                                        <option value="WIT">WIT (GMT +9)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Remote Date</label>
                                <div class="col-md-3">
                                    <input type="text" name="start_date" class="form-control" id="from" placeholder="Start Date" value="{{ old('start_date') }}" autocomplete="off"/>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="end_date" class="form-control" id="to" placeholder="End Date" value="{{ old('end_date') }}" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6"  style="padding: 0">
                                <div class="form-group col-md-6"  style="padding: 0">
                                    <label class="col-md-12">Latitude</label>
                                    <div class="col-md-12">
                                        <input id="latitude" name="latitude" type="number" step="any" class="form-control" value="{{ old('latitude') }}">
                                    </div>
                                </div>
                                <div class="form-group col-md-6"  style="padding-right: 0">
                                    <label class="col-md-12">Longitude</label>
                                    <div class="col-md-12">
                                        <input id="longitude" name="longitude" type="number" step="any" class="form-control" value="{{ old('longitude') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Lokasi</label>
                                <div class="col-md-6">
                                    <div id="map" style="height: 400px; width: 100%;"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-12">Radius (M)</label>
                                <div class="col-md-6">
                                    <input type="number" id="radius" name="radius" class="form-control" value="{{ old('radius') }}">
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <br />
                            <div class="col-md-12">
                                <a href="{{ route('administrator.remote-attendance.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Cancel</a>
                                <button type="submit" class="btn btn-sm btn-success waves-effect waves-light m-r-10"><i class="fa fa-save"></i> Save</button>
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
@section('js')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBTctq_RFrwKOd84ZbvJYvU3MEcrLmPNJ8"
            async defer></script>
    <script>
        @if(old('timezone'))
        $('#timezone').val("{{old('timezone')}}");
        @endif
        var list_anggota = [];

        @foreach(get_all_user() as $item)
        list_anggota.push({id : {{ $item->id }}, value : "{{ $item->nik .' - '. $item->name }}" });
        @endforeach

        $(".autocomplete-user").autocomplete({
            source: list_anggota,
            minLength:0,
            select: function( event, ui ) {
                $( "input[name='user_id']" ).val(ui.item.id);
            }
        }).on('focus', function () {
            $(this).autocomplete("search", "");
        });
        $( "#from, #to" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 2
        });
        setTimeout(function() {



            var currentRadius = '0';
            $('#radius').on('change paste keyup',function () {

                if(typeof marker.getPosition()=== "undefined" && currentRadius != $(this).val()) {
                    alert("Pilih lokasi terlebih dahulu!");
                }

                currentRadius = $(this).val();
                cityCircle.setRadius(parseInt(currentRadius));
                console.log($(this).val());
            });
            var jakarta = {lat: -6.21462, lng: 106.84513};



            var map = new google.maps.Map(
                document.getElementById('map'), {zoom: 10, center: jakarta});
            var marker = new google.maps.Marker({map : map});
            var cityCircle = new google.maps.Circle({
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#FF0000',
                fillOpacity: 0.35,
                map : map
            });
            @if(old('latitude') && old('longitude'))
            cityCircle.setCenter({lat: {{old('latitude')}}, lng: {{old('longitude')}}});
            marker.setPosition({lat: {{old('latitude')}}, lng: {{old('longitude')}}});
            map.setCenter({lat: {{old('latitude')}}, lng: {{old('longitude')}}});
            cityCircle.setRadius({{old('radius')}});
            map.setZoom(16);
            console.log("Data lat/lng : {{old('latitude')}}");
            @else
            console.log("Tidak ada data lama");
            @endif
            console.log(marker.getPosition());
            map.addListener('click', function(e) {
                marker.setPosition(e.latLng);
                cityCircle.setCenter(e.latLng);
                console.log(marker.getPosition());
                map.setCenter(e.latLng);
                // map.setZoom(16);
                updateLatLng();
            });
            cityCircle.addListener('click', function(e) {
                marker.setPosition(e.latLng);
                cityCircle.setCenter(e.latLng);
                console.log(marker.getPosition());
                map.setCenter(e.latLng);
                updateLatLng();
                // map.setZoom(16);
            });

            function updateLatLng(){
                $('#latitude').val(marker.getPosition().lat());
                $('#longitude').val(marker.getPosition().lng());
            }
            $('#latitude').on('keyup change input',function () {
                marker.setPosition({lat: parseFloat($(this).val()), lng: marker.getPosition().lng()});
                cityCircle.setCenter({lat: parseFloat($(this).val()), lng: marker.getPosition().lng()});
                console.log(marker.getPosition());
            });
            $('#longitude').on('keyup change input',function () {
                marker.setPosition({lat: marker.getPosition().lat(), lng: parseFloat($(this).val())});
                cityCircle.setCenter({lat: marker.getPosition().lat(), lng: parseFloat($(this).val())});
            });
            // The marker, positioned at Uluru
            // var marker = new google.maps.Marker({position: uluru, map: map});
            //

            $('#form').submit(function(eventObj) {
                // if(typeof marker.getPosition()!== "undefined") {
                //     $(this).append("<input type='hidden' name='latitude' value='"+marker.getPosition().lat()+"' /> ");
                //     $(this).append("<input type='hidden' name='longitude' value='"+marker.getPosition().lng()+"' /> ");
                // }
                return true;
            });
        },1000);

    </script>
@endsection
@endsection
