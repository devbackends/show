@if ($product->type == 'booking')

    @if ($bookingProduct = app('\Webkul\BookingProduct\Repositories\BookingProductRepository')->findOneByField('product_id', $product->product_id))

        @push('css')
            <link rel="stylesheet" href="{{ bagisto_asset('css/velocity-booking.css') }}">
        @endpush

        <booking-product-view :booking-product="{{$bookingProduct}}"></booking-product-view>

        @push('scripts')
            <script src="{{ bagisto_asset('js/booking.js') }}"></script>

            <script type="text/x-template" id="booking-information-template">
                <div class="booking-information">

                    @if ($bookingProduct->location != '')
                        <div class="booking-info-row">
                            <span class="icon bp-location-icon"></span>
                            <span class="title">{{ __('bookingproduct::app.shop.products.location') }}</span>
                            <span class="value">{{ $bookingProduct->location }}</span>
                            <a href="https://maps.google.com/maps?q={{ $bookingProduct->location }}" target="_blank">View
                                on Map</a>
                        </div>
                    @endif

                    @if(isset($bookingProduct->available_from) and $bookingProduct->available_from != null)
                        <p>Booking from: <span>{{ DATE_FORMAT($bookingProduct->available_from,"d M Y H:i ")}}</span></p>
                    @endif
                    @if(isset($bookingProduct->available_to) and $bookingProduct->available_to != null)
                        <p>Booking to: <span>{{ DATE_FORMAT($bookingProduct->available_to,"d M Y H:i")}}</span></p>
                    @endif
                    @include ('bookingproduct::shop.products.view.booking.' . $bookingProduct->type, ['bookingProduct' => $bookingProduct])

                </div>
            </script>

            <script>
                Vue.component('booking-information', {
                    template: '#booking-information-template',

                    inject: ['$validator'],

                    data: function () {
                        return {
                            showDaysAvailability: false
                        }
                    }
                });
            </script>

        @endpush

    @endif

@endif