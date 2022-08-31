@extends('layouts.login')

@section('content')
<!--<img src="{{ asset('images/bg-left-bottom.png')}}?v=2" class="bg-left-bottom" style="position: absolute; bottom: 0;left: 0; width: 600px;z-index: 999;" />
  -->
  <img src="{{ asset('images/login-bg-left.gif')}}?v=2" class="bg-left-bottom" style="position: absolute;top: 98px;left: 246px;width:350px;z-index: 1010;"  />
  <img src="{{ asset('images/Komputer.png')}}?v=2" class="bg-left-bottom" style="position: absolute;bottom: 75px;left: 117px;width:350px;z-index: 999;" />

<div class="img-contact">
  <img src="{{ asset('images/exit-button.png')}}?v=1" class="close_contact" title="Close " style="cursor: pointer; position: absolute;top: 0;left: 17px;width: 40px;" />
  <img src="{{ asset('images/contact.png')}}?v=1" style="width: 200px;" />
</div>

<!-- Preloader -->
<div class="preloader">
  <div class="cssload-speeding-wheel"></div>
</div>
<section id="wrapper" class="new-login-register">
      <div class="lg-info-panel" style="z-index: 99;">
              <div class="inner-panel">
              </div>
      </div>
      <div class="new-login-box">
          <div class="white-box">
              <h3 class="box-title m-b-0">Reset Password  @if(isset($config)) {{$config->name}} @endif</h3>
              <small>Insert your NIK to reset your password</small>
              @if (count($errors) > 0)
                  <div class="alert alert-danger">
                      <ul>
                          @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                          @endforeach
                      </ul>
                  </div>
              @endif
            <form class="form-horizontal" method="POST" id="loginform" action="{{ route('request-reset') }}">
              {{ csrf_field() }}
                <input type="hidden" name="company" value="{{$company}}">
              <div class="form-group  m-t-20">
                <div class="col-xs-12">
                  <label>Nomor Induk Karyawan</label>
                  <input class="form-control" type="text" required="" name="nik" placeholder="Nomor Induk Karyawan" value="{{old('nik')}}">
                </div>
              </div>
              <div class="form-group text-center m-t-20">
                <div class="col-xs-12">
                  <button class="btn btn-info btn-lg btn-block btn-rounded text-uppercase waves-effect waves-light" type="submit">Submit</button>
                </div>

              </div>
            </form>
          </div>
      </div>
</section>
<footer style="position: absolute;right:20px; bottom: 0; ">
    <p style="color: #61c3d0;font-size: 12px; text-align: right;">Copyright &copy; PT Empore Hezer Tama</p>
</footer>
@if(Session::has('message-success'))
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script type="text/javascript">
        swal("Success!", "{{ Session::get('message-success') }}", "success");
    </script>
@endif
<style type="text/css">

  @media all and (min-width: 320px) and (max-width: 780px)
  {
    .bg-left-bottom {display: none;}
    .img-contact { display: none !important; }
  }

  .img-logo {
    position: absolute;
    bottom: 50px;
    left: 0;
    z-index: 999;
    display: none;
  }
  .img-contact {
    position: absolute;
    top: 50px;
    right: 0;
    z-index: 999;
    display: none;
  }
  .btn-info {
    background: #0d9a89 !important;
  }
  .btn-info:hover,.btn-info:active {
    background: #0d9a89 !important;
  }
  .field-icon {
    float: right;
    margin-right: 9px;
    margin-top: -28px;
    position: relative;
    z-index: 2;
  }
  .new-login-register {
    background: url({{asset('images/Background.png')}});
    background-repeat: no-repeat;
    background-size: cover;
  }
  .new-login-register .new-login-box {
    -margin-left: 730px !important;
    position: absolute !important;
  }
</style>
@endsection


