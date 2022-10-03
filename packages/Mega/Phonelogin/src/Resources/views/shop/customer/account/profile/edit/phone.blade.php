<?php
$helper = app('Mega\Phonelogin\Helper\PhoneloginHelper');
?>
@if($helper->isEnabled())
    <div class="account-table-content10" style="width: 50%;">
        <h2>{{ __('megaPhoneLogin::app.customer.additional-details') }}</h2>
        <table style="color: rgb(94, 94, 94);">
            <tbody>
            <tr>
                <td>{{ __('megaPhoneLogin::app.customer.signup-form.phone') }}</td>
                <td>{{$customer->phone}}<a href="{{ route('mega.phonelogin.verifyphone') }}"> {{ __('megaPhoneLogin::app.customer.profile.edit-phone') }}</a></td>
            </tr>
            </tbody>
        </table>
    </div>
@endif