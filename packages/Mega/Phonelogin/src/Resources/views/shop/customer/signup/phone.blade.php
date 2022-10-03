<?php
    $helper = app('Mega\Phonelogin\Helper\PhoneloginHelper');
?>
@if($helper->isEnabled())
    @include('megaPhoneLogin::shop.customer.signup.phone-velocity')
@endif
