@extends('admin::layouts.content')

@section('page_title')
    {{'Review'}}
@stop
@section('css')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
@stop
@section('content')
    <?php /** @var Devvly\Ffl\Models\Ffl $ffl */ ?>
    <div class="content">
        <div class="page-header">
            <div class="page-action">
                <form method="POST" action="{{route('ffl.review.approve', ['id' => $ffl->id])}}">
                    @csrf
                    <button type="submit" class="btn btn-primary"> @if($ffl->is_approved) Don't Approve @else Approve  @endif</button>
                </form>
            </div>
        </div>
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12 mb-5">
                        <h3 class="bold mb-5">Business info</h3>
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-hover table-responsive mb-5">
                                    <tbody>
                                    <tr>
                                        <td>Company name</td>
                                        <td> {{$ffl->businessInfo->company_name}}</td>
                                    </tr>
                                    <tr>
                                        <td>Contact name</td>
                                        <td>{{$ffl->businessInfo->company_name}}</td>
                                    </tr>
                                    <tr>
                                        <td>Retail store</td>
                                        <td>{{!empty($ffl->businessInfo->retail_store_front) ? 'Yes' : 'No'}}</td>
                                    </tr>
                                    <tr>
                                        <td>Importer/exporter</td>
                                        <td>{{!empty($ffl->businessInfo->importer_exporter) ? 'Yes' : 'No'}}</td>
                                    </tr>
                                    <tr>
                                        <td>Website</td>
                                        <td>{{!empty($ffl->businessInfo->website) ? $ffl->businessInfo->website : 'No'}}</td>
                                    </tr>
                                    <tr>
                                        <td><h3>Shipping address</h3></td>
                                    </tr>
                                    <tr>
                                        <td>FFL Street address</td>
                                        <td>{{$ffl->businessInfo->street_address}}</td>
                                    </tr>
                                    <tr>
                                        <td>City</td>
                                        <td>{{$ffl->businessInfo->city}}</td>
                                    </tr>
                                    <tr>
                                        <td>State</td>
                                        <td>{{$ffl->businessInfo->countryState->default_name}}</td>
                                    </tr>
                                    <tr>
                                        <td>Zip code</td>
                                        <td>{{$ffl->businessInfo->zip_code}}</td>
                                    </tr>
                                    <tr>
                                        <td>Coordinates</td>
                                        <td> Latitude: {{$ffl->businessInfo->latitude}},
                                            Longitude: {{$ffl->businessInfo->longitude}}</td>
                                    </tr>
                                    <tr>
                                        <td><h3>Contact information</h3></td>
                                    </tr>
                                    <tr>
                                        <td>Phone</td>
                                        <td>{{$ffl->businessInfo->phone}}</td>
                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <td>{{$ffl->businessInfo->email}}</td>
                                    </tr>
                                    <tr>
                                        <td>Business hour</td>
                                        <td>{{$ffl->businessInfo->business_hours}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                                <h3 class="bold mb-5">License</h3>
                                <table class="table table-hover table-responsive mb-5">
                                    <tbody>
                                    <tr>
                                        <td>License number</td>
                                        <td>{{$ffl->license->license_number}}</td>
                                    </tr>
                                    <tr>
                                        <td>License copy</td>
                                        <td><a href="{{ $licenseUrl}}">License</a></td>
                                    </tr>
                                    </tbody>
                                </table>
                                <h3 class="bold mb-5">Transfer fees</h3>
                                <table class="table table-hover table-responsive mb-5">
                                    <tbody>
                                    <tr>
                                        <td>Long gun</td>
                                        <td>{{$ffl->transferFees->long_gun}}</td>
                                        <td>{{$ffl->transferFees->long_gun_description}}</td>
                                    </tr>
                                    <tr>
                                        <td>Hand gun</td>
                                        <td>{{$ffl->transferFees->hand_gun}}</td>
                                        <td>{{$ffl->transferFees->hand_gun_description}}</td>
                                    </tr>
                                    <tr>
                                        <td>NICS</td>
                                        <td>{{$ffl->transferFees->nics}}</td>
                                        <td>{{$ffl->transferFees->nics_description}}</td>
                                    </tr>
                                    <tr>
                                        <td>Other</td>
                                        <td>{{$ffl->transferFees->other}}</td>
                                        <td>{{$ffl->transferFees->other_description}}</td>
                                    </tr>
                                    <tr>
                                        <td><h4>Accepted payment method</h4></td>
                                    </tr>
                                    <tr>
                                        <td>Payment</td>
                                        <td>{{$payment}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                                @if($ffl->transferFees->comments)
                                    <div class="col-sm-12">
                                        <p><span class="bold">Question/feedback:</span> {{$ffl->transferFees->comments}}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
