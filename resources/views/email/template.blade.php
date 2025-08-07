@extends('email.layouts.main')
@section('content')
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ebebeb">
    <tr>
        <td class="p30-15-0" style="padding: 20px 30px 0px;" bgcolor="#ffffff">
            <div style="font-family: Helvetica, Arial, sans-serif; overflow: auto; line-height: 2; text-align: center;">
                <div style="margin: 0px auto; width: 70%; padding: 10px 0;">
                   
                    <div style="text-align: center; margin-top: 20px; font-size:20px; color: #384551;">
                        {!! $body !!}
                    </div>
                </div>
            </div>        
        </td>
    </tr>
</table>
@endsection
