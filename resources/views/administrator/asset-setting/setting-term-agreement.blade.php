@extends('layouts.administrator')

@section('title', 'Setting Term & Agreement of Asset')

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Setting Term & Agreement of Asset</h4> 
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Setting Term & Agreement of Asset</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 p-l-0 p-r-0">
                <div class="white-box">
                    <form class="form-horizontal" name="form_setting" enctype="multipart/form-data" action="{{ route('administrator.asset-setting.store') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="col-md-12">Term & Agreement of Asset</label>
                            <div class="col-md-12">
                                <textarea class="form-control" name="setting[term_and_agreement_asset]" style="height: 120px;" id="ckeditor1">
                                    @if(get_setting('term_and_agreement_asset') == '')
                                    <p> Example of Term & Agreement of Asset:</p>
                                    <p style=" text-align: justify; text-justify: inter-word;"> 1.	Maintain (cleanliness, integrity and security) of the company's assets as mentioned above as much as possible;</p>
                                    <p style=" text-align: justify; text-justify: inter-word;"> 2.	If there is damage or loss of the asset caused by my carelessness or negligence, bear the cost of repair and or replacement of spare parts or replacement;</p>
                                    <p><strong>Please delete it, before you change and save Term & Agreement of Asset</strong></p>
                                    @else
                                    {{ get_setting('term_and_agreement_asset') }}
                                    @endif
                                </textarea>
                            </div>
                        </div>
                    </form>
                    <button type="button" class="btn btn-info btn-sm hidden-sm waves-effect waves-light" onclick="form_setting.submit()"> <i class="fa fa-save"></i> Save Setting</button>
                    {{-- <hr style="margin-top:0;margin-bottom:6px;" /> --}}
                </div>
            </div> 
        </div>
        <!-- ============================================================== -->
    </div>
    @section('js')
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script type="text/javascript">
        CKEDITOR.replace('ckeditor1');
    </script>
    @endsection
    <!-- /.container-fluid -->
    @include('layouts.footer')
</div>
@endsection
