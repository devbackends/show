@php

    $ffl_types=['01'=>'Firearm Dealer and Gunsmith',
                '02' =>'Pawnbroker and Firearm Dealer',
                '03' => 'C&R Collector',
                '06' => 'Ammunition Manufacturer',
                '07' => 'Firearm Manufacturer',
                '08' => 'Importer of Firearms',
                '09' => 'Dealer of Destructive Devices',
                '10' => 'Manufacturer of Destructive Devices',
                '11' => 'Importer of Destructive Devices'];

    $ffl_expirations_month=['A' => 'January',
                      'B' => 'February',
                      'C' => 'March',
                      'D' => 'April',
                      'E' => 'May',
                      'F' => 'June',
                      'G' => 'July',
                      'H' => 'August',
                      'J' => 'September',
                      'K' => 'October',
                      'L' => 'November',
                      'M' => 'December'];

    $ffl_expirations_year=['1' => '2021',
                      '2' => '2022',
                      '3' => '2023',
                      '4' => '2024',
                      '5' => '2025',
                      '6' => '2016',
                      '7' => '2017',
                      '8' => '2018',
                      '9' => '2019',
                      '0' => '2020']

@endphp
@extends('marketplace::shop.layouts.master')

@section('page_title')
    FFL
@stop

@section('seo')
    <meta name="description" content="FFL"/>
    <meta name="keywords" content="FFL"/>
@stop

@section('content-wrapper')
    @if($item)
        <div class="ffl-detail">
            <!-- HERO SECTION -->
            <div class="hero-ffl" style="background-image: url(/themes/market/assets/images/hero_show.jpg);">
                <div class="container">

                    @php $isPreferred = ($item->is_approved==1 && $item->source=='on_boarding_form')? TRUE : FALSE @endphp
                    @if($isPreferred)
                        <div class="hero-ffl__preferred">
                            <div class="hero-ffl__preferred-label"><img
                                        src="/themes/market/assets/images/ffl-preferred-icon-alt.svg" alt=""><span>Preferred FFL</span>
                            </div>
                        </div>
                    @endif
                    <div>
                        @if($item->businessInfo->company_name)
                            <h1 class="h1 hero-section__title">{{$item->businessInfo->company_name}}</h1>
                        @else
                            <h1 class="h1 hero-section__title">{{$item->license->license_name}}</h1>
                        @endif
                    </div>
                    <a href="{{route('marketplace.ffl.index')}}" class="hero-section__link"><i
                                class="fal fa-angle-left"></i>Back
                        to all FFLs</a>
                </div>
            </div>
            <!-- END HERO SECTION -->
            <div class="ffl-detail__license-info py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-auto">
                            <div class="form-group">
                                <label>Licence type</label>
                                <p>{{ isset($ffl_types[$item->license->license_type]) ? $ffl_types[$item->license->license_type] : '' }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-auto">
                            <div class="form-group">
                                <label>Licence expiration date</label>
                                @php $ffl_expirations=$item->license->license_expire_date  @endphp
                                <p>{{ isset($ffl_expirations_month[$item->license->license_expire_date[1]]) ? $ffl_expirations_month[$item->license->license_expire_date[1]] : '' }}
                                    {{ isset($ffl_expirations_year[$item->license->license_expire_date[0]]) ? $ffl_expirations_year[$item->license->license_expire_date[0]] : '' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ffl-detail__address-info py-5">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-lg-3">
                            @php $stateCode = app('Webkul\Core\Repositories\CountryStateRepository')->where(['id' => $item->businessInfo->state])->first() @endphp
                            <div class="form-group">
                                <label>Address</label>
                                <p>{{$item['location']}}</p>
                                <p>{{$item->businessInfo->city}}
                                    , {{$stateCode->code}}  {{$item->businessInfo->zip_code}}</p>
                            </div>
                            @if($item->businessInfo->website)
                                <div class="form-group">
                                    <label>Voice Phone</label>
                                    <p>{{$item->businessInfo->phone}}</p>
                                </div>
                            @endif
                            @if($item->businessInfo->email)
                                <div class="form-group">
                                    <label>Email</label>
                                    <p>{{$item->businessInfo->email}}</p>
                                </div>
                            @endif
                            @if( isset($item->businessInfo->social))
                                @php $socials=json_decode($item->businessInfo->social)@endphp
                                @if($socials)
                                    <div class="ffl-detail__social">
                                        @foreach($socials as $social_link)
                                            @if(strpos($social_link, 'facebook') !== FALSE)
                                                <a class="ffl-detail__social-icon" href="{{$social_link}}">
                                                    <i class="fab fa-facebook-f"></i>
                                                </a>
                                            @endif
                                            @if(strpos($social_link, 'twitter') !== FALSE)
                                                <a class="ffl-detail__social-icon" href="{{$social_link}}">
                                                    <i class="fab fa-twitter"></i>
                                                </a>
                                            @endif
                                            @if(strpos($social_link, 'gab') !== FALSE)
                                                <a class="ffl-detail__social-icon" href="{{$social_link}}">
                                                    <img src="/themes/market/assets/images/mp-gab-alt.svg" width="30"
                                                         alt="">
                                                </a>
                                            @endif
                                            @if(strpos($social_link, 'parler') !== FALSE)
                                                <a class="ffl-detail__social-icon" href="{{$social_link}}">
                                                    <img src="/themes/market/assets/images/mp-parler-alt.svg" width="20"
                                                         alt="">
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            @endif
                            @if($item->businessInfo->website)
                                <a href="{{$item->businessInfo->website}}" target="_blank"
                                   class="btn btn-outline-primary">Visit website</a>
                            @endif
                        </div>
                        <div class="col-12 col-lg-6">
                            <div id="map" style="height:400px;"></div>
                        </div>
                        <div class="col-12 col-lg-3">
                            <a href="/marketplace/start-selling">
                                <img src="/themes/market/assets/images/2a_2020_nov_promo_6.jpg" alt="" class="w-100">
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @if($item->transferFees->long_gun || $item->transferFees->hand_gun || $item->transferFees->nics || $item->transferFees->other)
                <div class="ffl-detail__fees pb-5">
                    <div class="container">
                        <div class="ffl-detail__fees-content">
                            <p class="font-paragraph-big-bold">Transfer Fees</p>
                            <div class="row">
                                @if($item->transferFees->long_gun)
                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label for="">Long Gun</label>
                                            <p>${{$item->transferFees->long_gun}}</p>
                                            <small>{{$item->transferFees->long_gun_description}}</small>
                                        </div>
                                    </div>
                                @endif
                                @if($item->transferFees->hand_gun)
                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label for="">Hand Gun</label>
                                            <p>${{$item->transferFees->hand_gun}}</p>
                                            <small>{{$item->transferFees->hand_gun_description}}</small>
                                        </div>
                                    </div>
                                @endif
                                @if($item->transferFees->nics)
                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label for="">Nics</label>
                                            <p>${{$item->transferFees->nics}}</p>
                                            <small>{{$item->transferFees->nics_description}}</small>
                                        </div>
                                    </div>
                                @endif
                                @if($item->transferFees->other)
                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label for="">Other</label>
                                            <p>${{$item->transferFees->other}}</p>
                                            <small>{{$item->transferFees->other_description}}</small>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="modal fade" id="contactSeller" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <i class="fal fa-times"></i>
                            </button>
                            <div class="modal-head">
                                <i class="fal fa-comment-alt-lines"></i>
                                <h3>Contact </h3>
                            </div>
                            <contact-seller-form></contact-seller-form>
                        </div>
                    </div>
                </div>
            </div>

            @if($isPreferred)
                @include('shop::home.featured-products')
            @endif

            @include('shop::home.uscca-link')
        </div>
        @push('scripts')
            <script
                    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDnUKjS6BAjGhL8XL8SacAYmDQMpWEchXs&callback=initMap&libraries=&v=weekly"
                    async
            ></script>
            <script>
                // Initialize and add the map
                function initMap() {
                    // The location of Uluru
                    const uluru = { lat:<?=$item->businessInfo->latitude?>, lng: <?=$item->businessInfo->longitude?> };
                    // The map, centered at Uluru
                    const map = new google.maps.Map(document.getElementById("map"), {
                        zoom: 7,
                        center: uluru,
                    });
                    // The marker, positioned at Uluru
                    const marker = new google.maps.Marker({
                        position: uluru,
                        map: map,
                    });
                }

            </script>
        @endpush


        @include('shop::home.recent-products')

        @if($isPreferred)
            @include('shop::home.featured-products')

        @endif
    @endif
@endsection