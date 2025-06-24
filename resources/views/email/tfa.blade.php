@extends('email.layouts.main')
@section('content')
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ebebeb">
    <tr>
        <td class="p30-15-0" style="padding: 50px 30px 0px;" bgcolor="#ffffff">
            <div style="font-family: Helvetica, Arial, sans-serif; overflow: auto; line-height: 2; text-align: center;">
                <div style="margin: 20px auto; width: 70%; padding: 10px 0;">
                    <div style="border-bottom: 1px solid #eee; padding-bottom: 10px;">
                        <a href="{{ url('/') }}" style="font-size: 18px; color: #00466a; text-decoration: none; font-weight: 600;">
                            Laravel Demo
                        </a>
                    </div>
                    <div style="text-align: left; margin-top: 20px; font-size:20px;">
                        {{ $otp }}
                    </div>
                </div>
            </div>        
        </td>
    </tr>
</table>
@endsection
