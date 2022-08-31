<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>{{ get_setting('title') }}</title>
</head>
<body style="margin:0px; background: #f8f8f8; ">
<div width="100%" style="background: #f8f8f8; padding: 0px 0px; font-family:arial; line-height:28px; height:100%;  width: 100%; color: #514d6a;">
    <div style="max-width: 700px; padding:30px 0;  margin: 0px auto; font-size: 14px">
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 10px">
            <tbody>
            <tr>
                <td style="vertical-align: top;">
                    @php($logo = isset($logo)?$logo:get_setting('logo'))
                    @if($logo != "")
                        <a href="#" target="_blank" style="text-decoration: none;color: #484848;"><img src="{{ url('/').$logo }}" style="border:none; height: 50px;" height="50"/></a>
                    @endif
                </td>
            </tr>
            </tbody>
        </table>
        <div style="padding: 40px; background: #fff;">
            @yield('content')
            <p>This email is sent automatically by the system, you cannot reply to this message, please log in to your account for more info
            </p>
            <br />
        <!--<b>{{ strip_tags(html_entity_decode (get_setting('mail_signature'))) }}</b>-->
            @php($mail_signature = isset($mail_signature)?$mail_signature:get_setting('mail_signature'))
            <b>{!! $mail_signature !!}</b>
        </div>
        {{-- <div style="text-align: center; font-size: 12px; color: #b2b2b5; margin-top: 20px">
            @php($title = isset($title)?$title:get_setting('title'))
            <p> {{ $title }}</p> --}}
        </div>
        <div style="text-align:center; font-size:12px; color:#b2b2b5; padding-bottom: 20px;">
            <a href="#" target="_blank" style="text-decoration: none;color: #484848;"><img src="{{ asset('images/Logo_EMHR_email_extra_small.png') }}" style="border:none; height: 50px;" height="50"/></a>
        </div>
    </div>
</div>
</body>
</html>
