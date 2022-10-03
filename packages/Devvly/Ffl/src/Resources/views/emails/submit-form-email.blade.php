@component('shop::emails.layouts.master')

    <div style="margin-bottom: 32px; text-align: center;">
        <a href="https://www.2agunshow.com/" style="display: inline-block;">
            @include ('shop::emails.layouts.logo')
        </a>
    </div>
    <div>

        <p style="color: #111111;">A New Ffl Form Has Been submitted</p>
        <p style="color: #111111;">Please find below Some Information to follow Up:</p>
        <p style="color: #111111;"><strong>Company Name: </strong> {{$company_name}}</p>
        <p style="color: #111111;"><strong>Contact Name: </strong> {{$contact_name}}</p>
        <p style="color: #111111;"><strong>Email: </strong> {{$email}}</p>
        <p style="color: #111111;"><strong>Phone: </strong> {{$phone}}</p>
        <p style="color: #111111;"><strong>Business Hours: </strong> {{$business_hours}}</p>
        <p style="color: #111111;"><strong>City: </strong> {{$city}}</p>
        <p style="color: #111111;"><strong>Street Address: </strong> {{$street_address}}</p>

        <p style="color: #111111;">In order to check the new ffl submitted form you have to check the below link:</p>
        {{--<p>{{ route('super.session.index') }}</p>--}}
        <p>{{ getenv('APP_URL').'/super/ffl/review/'.$ffl_id }}</p>

        <p style="color: #111111;">{{ __('shop::app.mail.forget-password.thanks') }}</p>
    </div>

@endcomponent