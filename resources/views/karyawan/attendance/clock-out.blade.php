@extends('layouts.karyawan')

@section('title', 'Clock Out')

@section('sidebar')

@endsection

@section('content')
<style>
    #results  {
        max-width: 400px;
        max-height: 300px;

    }

    @media (max-width: 576px) {
        #my_camera  {
            max-width: 300px;
            max-height: 200px;
        }
        #my_camera video {
            max-width: 290px;
            max-height: 190px;
        }

        #results img {
            max-width: 290px;
            max-height: 190px;
        }
    }

    @media (max-width: 320px) {
        #my_camera  {
            max-width: 220px;
            max-height: 120px;
        }
        #my_camera video {
            max-width: 210px;
            max-height: 110px;
        }

        #results img {
            max-width: 210px;
            max-height: 110px;
        }
    }
</style>

<!-- ============================================================== -->
<!-- Page Content -->
<!-- ============================================================== -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Form Clock Out</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Clock Out</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <form class="form-horizontal" enctype="multipart/form-data" id="form-clock-out" action="{{route('karyawan.clock')}}" method="POST">
                <div class="col-md-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Clock Out</h3>
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
                        <div class="form-group">
                            <div>
                                <input type="hidden" name="latitude" id="latitude">
                                <input type="hidden" name="longitude" id="longitude">
                                <input type="hidden" name="date_shift" value="{{$date_shift}}">
                                <input type="hidden" name="time" id="time">
                                <input type="hidden" name="company" value="{{$settings['title']}}">
                                <input type="hidden" name="type" value="1">
                                {{--<input type="hidden" name="status" value="out_of_office">--}}
                            </div>
                            {{-- <input type="text" class="form-control" value="time" id="MyClockDisplay"> --}}
                            <h3 id="MyClockDisplay" class="clock text-center" onload="startTime()"></h3>
                        </div>
                        <div id="map_attendance" style="height: 254px; width: 100%;">

                        </div>
                        <br>
                        <div class="form-group">
                            <div class="col-md-6">
                                <div id="my_camera"></div>
                                <br/>
                                <input type=button class="btn btn-sm btn-primary" value="Take a Selfie" onClick="take_snapshot()">
                                <input type="hidden" name="image" id="selfie" class="image-tag" required>
                            </div>
                            <div class="col-md-6">
                                <div id="results"><h2> <span style="color:blue">Please Take a Selfie </span>and your selfie image will appear here...</h2></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <div class="col-md-12">
                                <input type="checkbox" {{$out_of_office==1 && $absensi->attendance_type_in=='out_of_office' ? '' : ''}} name="status" id="out_of_office" value="out_of_office">Out of Office
                                @if($absensi->shift_type=='other_shift')
                                <input type="hidden" name="shift_status" value="other_shift">
                                <input type="checkbox" {{$absensi->shift_type=='other_shift' ? 'checked disabled' : ''}}  name="shift_status" id="other_shift" value="other_shift"> Other Shift
                                @endif
                            </div>
                        </div>
                        <div class="form-group" id="div_shift_id" style="display: none;">
                            <label>Shift</label>
                            <div class="col-md-12">
                                <select name="shift_id" class="form-control" id="shift_id" {{$absensi->shift_id!= NULL ? 'readonly="true"' : ''}}>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Justification</label>
                            <div class="col-md-12">
                                <textarea class="form-control" name="justification" id="justification"></textarea>
                            </div>
                        </div>
                        <a href="{{ route('karyawan.detail.clock-out') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Back</a>
                        <button  class="btn" style="background:#bd332b; color: white;" id="btn_save">Clock Out</button>
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
@endsection
@section('footer-script')
<style type="text/css">
    /* #results { padding:10px; border:1px solid; background:#ccc; } */
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
{{-- <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script> --}}
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBTctq_RFrwKOd84ZbvJYvU3MEcrLmPNJ8"
    async defer></script>
    <script language="JavaScript">
        var jarak = 0;
        function detectWebcam(callback) {
            let md = navigator.mediaDevices;
            if (!md || !md.enumerateDevices) return callback(false);
            md.enumerateDevices().then(devices => {
                callback(devices.some(device => 'videoinput' === device.kind));
            })
        }

        detectWebcam(function(hasWebcam) {
            if(hasWebcam!=true){
                window.alert("The device doesn't have a webcam.");
                window.history.back();
            }
        })

        if($("#other_shift").is(':checked')){
            console.log('{{$absensi->shift_id}}')
            // Code in the case checkbox is checked.
            $("#div_shift_id").show();
            $.ajax({
            url: "{{ route('ajax.get-karyawan-shift') }}",
            method : 'get',
            data: {
                'shift':'other_shift' ,'_token' : $("meta[name='csrf-token']").attr('content')
            },
            success: function(data) {
                // $('#shift_id').html(`
                //     <option value="" selected disabled>--Select Shift--</option>
                // `);    
                data.forEach(data => {  
                    if(data.id == '{{$absensi->shift_id}}'){
                        $('#shift_id').append(`
                            <option value="${data.id}" selected>`+data.name+`</option>
                        `);
                    }  
                });
            },
            error:function(XMLHttpRequest, textStatus, errorThrown) { 
                alert("Status: " + textStatus); alert("Error: " + errorThrown); 
            }    
        });
        }

    Webcam.set({
        width: 400,
        height: 300,
        image_format: 'jpg',
        jpeg_quality: 90
    });
    Webcam.attach( '#my_camera' );
    function take_snapshot() {
        Webcam.snap( function(data_uri) {
            $(".image-tag").val(data_uri);
            document.getElementById('results').innerHTML = '<img src="'+data_uri+'"/>';
        } );
    }

    var div  = document.getElementById("map_attendance");
    function getLocation() {
        if (navigator.geolocation) {
          navigator.geolocation.watchPosition(showPosition, showError);
        } else {
          div.innerHTML = "The Browser Does not Support Geolocation";
        }
    }

    function showPosition(position) {
        //div.innerHTML = "Latitude: " + position.coords.latitude + "<br>Longitude: " + position.coords.longitude;
        $('#latitude').val(position.coords.latitude)
        $('#longitude').val(position.coords.longitude)
        var userLoc = {lat: position.coords.latitude, lng: position.coords.longitude};
        var officeLoc = {lat:  parseFloat('{{$lat_office}}'), lng: parseFloat('{{$long_office}}')};
        var icon = "{{asset('images/icon/icon_man.png')}}";
        // The map, centered at Uluru
        setTimeout(function(){
            map = new google.maps.Map(document.getElementById('map_attendance'), {
                center: {lat: position.coords.latitude, lng: position.coords.longitude},
                zoom: 12
            });
            var marker = new google.maps.Marker({
                position: userLoc,
                map: map,
                label: "User Location"
            });
            var marker = new google.maps.Marker({
                position: officeLoc,
                map: map,
                label: "Office Location"
            });
            // var map = new google.maps.Map(
            //     document.getElementById('map_attendance'));
            // // The marker, positioned at Uluru
            // var userMarker = new google.maps.Marker({position: userLoc, map: map,icon: icon});
            // var bounds = new google.maps.LatLngBounds();
            // bounds.extend(userMarker.getPosition());
            // var padding = 0;
            // map.fitBounds(bounds,padding);
        }, 1000);

        jarak = calcCrow(position.coords.latitude, position.coords.longitude, '{{$lat_office}}', '{{$long_office}}')
        //console.log(jarak)
        if(jarak*1000 > '{{$radius}}'){
            $("#justification").prop('required',true);
        }
    }

    function showError(error) {
        switch(error.code) {
            case error.PERMISSION_DENIED:
                div.innerHTML = "<h3 class='text-center'>Your denied the request for location. Please allow location to check your location!</h3>"
                window.alert("Please allow location to check your location!");
                $("#btn_save").prop("disabled", true);
                break;
            case error.POSITION_UNAVAILABLE:
                div.innerHTML = "Location information is unavailable."
                break;
            case error.TIMEOUT:
                div.innerHTML = "The request to get user location timed out."
                break;
            case error.UNKNOWN_ERROR:
                div.innerHTML = "An unknown error occurred."
                break;
        }
    }
    
    getLocation();

    function startTime() {
        const today = new Date();
        let h = today.getHours();
        let m = today.getMinutes();
        let s = today.getSeconds();
        m = checkTime(m);
        s = checkTime(s);
        document.getElementById('MyClockDisplay').innerHTML =  today.toDateString() + ' ' + h + ":" + m + ":" + s;
        $("#time").val(h + ":" + m + ":" + s)
        setTimeout(startTime, 1000);
    }

    function checkTime(i) {
        if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
        return i;
    }

    startTime();

    function calcCrow(lat1, lon1, lat2, lon2) 
    {
      var R = 6371; // km
      var dLat = toRad(lat2-lat1);
      var dLon = toRad(lon2-lon1);
      var lat1 = toRad(lat1);
      var lat2 = toRad(lat2);

      var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.sin(dLon/2) * Math.sin(dLon/2) * Math.cos(lat1) * Math.cos(lat2); 
      var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
      var d = R * c;
      return d;
    }

    // Converts numeric degrees to radians
    function toRad(Value) 
    {
        return Value * Math.PI / 180;
    }

    $("#btn_save").click(function(){
        if(document.getElementById('selfie').value  == ''){
            window.alert("Photo selfie/attendance not available, please take a selfie!");
        }
        else if(jarak*1000 > '{{$radius}}'){
            if(!$("#out_of_office").is(':checked')){
                window.alert("Your location must be less than {{$radius}} meter(s) from the office location.");
            }
            else if(document.getElementById('justification').value  == ''){
                window.alert("justification can't be empty!");
            }
            else{
                $('#form-clock-out').submit();
            }
        }
        else{
            $('#form-clock-out').submit();
        }
    });
</script>

@endsection
