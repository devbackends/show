
<?php
$bookingProduct= app('\Webkul\BookingProduct\Repositories\BookingProductRepository')->findWhere(['product_id'=> $product->id])->first();

if(strtolower($product->attribute_family->code)=='default_event'){
    array_push($GLOBALS['groups'],['id'=>'idbooking','title'=>'General Information for Events']);
    array_push($GLOBALS['groups'],['id'=>'booking-product-date-info','title'=>'Date & Time']);
    array_push($GLOBALS['groups'],['id'=>'booking-product-location','title'=>'Location']);
}
if(strtolower($product->attribute_family->code)=='event'){
    array_push($GLOBALS['groups'],['id'=>'idbooking','title'=>'General Information for Events']);
    array_push($GLOBALS['groups'],['id'=>'booking-product-date-info','title'=>'Date & Time']);
    array_push($GLOBALS['groups'],['id'=>'booking-product-location','title'=>'Location']);
    array_push($GLOBALS['groups'],['id'=>'booking-product-tickets','title'=>'Tickets']);
}
if(strtolower($product->attribute_family->code)=='training'){
    array_push($GLOBALS['groups'],['id'=>'idbooking','title'=>'General Information for Events']);
    array_push($GLOBALS['groups'],['id'=>'booking-product-date-info','title'=>'Date & Time']);
    array_push($GLOBALS['groups'],['id'=>'booking-product-location','title'=>'Location']);
}
if(strtolower($product->attribute_family->code)=='rental'){
    array_push($GLOBALS['groups'],['id'=>'idbooking','title'=>'General Information for Events']);
    array_push($GLOBALS['groups'],['id'=>'booking-product-location','title'=>'Location']);
    array_push($GLOBALS['groups'],['id'=>'booking-product-rental-information','title'=>'Rental Information']);
}
?>
    <booking-information></booking-information>


@push('scripts')
    @php

        $bookingProductDefaultSlot='';
        $event_tickets='';
        $rental='';
           if($bookingProduct){
            $bookingProduct->leaders=json_decode($bookingProduct->leaders);
            $bookingProduct->tags=json_decode($bookingProduct->tags);
            $bookingProduct->levels=json_decode($bookingProduct->levels);
            $bookingProductDefaultSlot=$bookingProduct->default_slot;
            if($bookingProductDefaultSlot){
                $bookingProductDefaultSlot->slots=json_decode($bookingProductDefaultSlot->slots);
                $bookingProductDefaultSlot->repetition_sequence=json_decode($bookingProductDefaultSlot->repetition_sequence);
            }
            $event_tickets=$bookingProduct->event_tickets;
            if($event_tickets){
                $event_tickets->tickets=json_decode($event_tickets->tickets);
            }
            $rental=$bookingProduct->rental_slot;
            if($rental){
                $rental->slots=json_decode($rental->slots);
            }
    }

    @endphp
    @parent

    <script type="text/x-template" id="booking-information-template">
        <div id="idbooking">
                    <div class="default-booking-section" v-if="booking.type.toLowerCase() == 'default_event'">
                        @include('bookingproduct::admin.catalog.products.accordians.booking.form-general-info-for-events', ['bookingProduct' => $bookingProduct])
                        @include('bookingproduct::admin.catalog.products.accordians.booking.form-date-and-time', ['bookingProduct' => $bookingProduct,'bookingProductDefaultSlot'=>$bookingProductDefaultSlot])
                        @include('bookingproduct::admin.catalog.products.accordians.booking.form-location', ['bookingProduct' => $bookingProduct])
                    </div>

                    <div class="appointment-booking-section" v-if="booking.type.toLowerCase() == 'event'">
                        @include('bookingproduct::admin.catalog.products.accordians.booking.form-general-info-for-events', ['bookingProduct' => $bookingProduct])
                        @include('bookingproduct::admin.catalog.products.accordians.booking.form-date-and-time', ['bookingProduct' => $bookingProduct,'bookingProductDefaultSlot'=>$bookingProductDefaultSlot])
                        @include('bookingproduct::admin.catalog.products.accordians.booking.form-location', ['bookingProduct' => $bookingProduct])
                        @include('bookingproduct::admin.catalog.products.accordians.booking.form-tickets', ['bookingProduct' => $bookingProduct,'event_tickets'=>$event_tickets])
                    </div>

                    <div class="event-booking-section" v-if="booking.type.toLowerCase() == 'training'">
                        @include('bookingproduct::admin.catalog.products.accordians.booking.form-general-info-for-events', ['bookingProduct' => $bookingProduct])
                        @include('bookingproduct::admin.catalog.products.accordians.booking.form-date-and-time', ['bookingProduct' => $bookingProduct])
                        @include('bookingproduct::admin.catalog.products.accordians.booking.form-location', ['bookingProduct' => $bookingProduct])
                    </div>

                    <div class="rental-booking-section" v-if="booking.type.toLowerCase() == 'rental'">
                        @include('bookingproduct::admin.catalog.products.accordians.booking.form-general-info-for-events', ['bookingProduct' => $bookingProduct])
                        @include('bookingproduct::admin.catalog.products.accordians.booking.form-location', ['bookingProduct' => $bookingProduct])
                        @include('bookingproduct::admin.catalog.products.accordians.booking.form-rental-info', ['bookingProduct' => $bookingProduct,'rental'=>$rental])
                    </div>

        </div>
    </script>

    <script>
        var bookingProduct = @json($bookingProduct);

        Vue.component('booking-information', {

            template: '#booking-information-template',

            inject: ['$validator'],

            data: function() {
                return {
                    booking: bookingProduct ? bookingProduct : {

                        type: '<?=$product->attribute_family->code;?>',

                        location: '',

                        qty: 0,

                        available_every_week: '',

                        available_from: '',

                        available_to: ''
                    }
                }
            }
        });
    </script>

    @include ('bookingproduct::admin.catalog.products.accordians.booking.slots')

@endpush