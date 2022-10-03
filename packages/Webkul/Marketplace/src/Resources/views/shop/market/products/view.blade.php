@extends('shop::layouts.master')
@inject ('wishListHelper', 'Webkul\Marketplace\Helpers\Marketplace')
@php
    $customer = auth('customer')->user();

    $isWished = false;
    $linkTitle = '';

    if ($customer) {
        $isWished = $wishListHelper->isProductWished($product->product_id, $product->marketplace_seller_id);
        $linkTitle = ($isWished) ? __('velocity::app.shop.wishlist.remove-wishlist-text') : __('velocity::app.shop.wishlist.add-wishlist-text');
    }

    $isEvent= ( $product->type == 'booking') ?  true : false ;
    $videos=[];
    if($product->product->videos()->get()->count() > 0){
        $videos=$product->product->videos()->get()->toArray();
     }

@endphp

@section('page_title')
    {{ trim($product->meta_title) != "" ? $product->meta_title : $product->name }}
@stop

@section('seo')
    <meta name="description"
          content="{{ trim($product->meta_description) != "" ? $product->meta_description : str_limit(strip_tags($product->description), 120, '') }}"/>
    <meta name="keywords" content="{{ $product->meta_keywords }}"/>


    <meta property="og:title" content="{{ $product->name }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="2A Gun Show"/>
    <meta property="og:image" content="{{ isset($product->productImages[0]) ? $product->productImages[0]['large_image_url'] : '' }}"/>
    <meta property="og:image:url" content="{{ isset($product->productImages[0]) ? $product->productImages[0]['large_image_url']  : '' }}"/>
    <meta property="og:description" content="{{ str_limit(strip_tags($product->description), 120, '') }}"/>

    <meta property="twitter:card" content="summary_large_image"/>
    <meta property="twitter:image" content="{{ isset($product->productImages[0]) ? $product->productImages[0]['large_image_url'] : '' }}"/>
    <meta property="twitter:image:src" content="{{ isset($product->productImages[0]) ? $product->productImages[0]['large_image_url'] : '' }}"/>
    <meta property="twitter:title" content="{{ $product->name }}"/>
    <meta property="twitter:type" content="website"/>
    <meta property="twitter:url" content="2A Gun Show"/>
    <meta property="twitter:description" content="{{ str_limit(strip_tags($product->description), 120, '') }}"/>

@stop

@section('content-wrapper')

    {!! view_render_event('bagisto.shop.products.view.before', ['product' => $product]) !!}
    <div>
        <product-view :products="{{$products}}" token="{{csrf_token()}}">
            <template v-slot:attributes>
                @include ('shop::products.view.attributes', ['active' => true])
            </template>
            <template v-slot:quantity>
                {!! view_render_event('bagisto.shop.products.view.quantity.before', ['product' => $product]) !!}
                <div>
                    <quantity-changer/>
                </div>
                {!! view_render_event('bagisto.shop.products.view.quantity.after', ['product' => $product]) !!}
            </template>
        </product-view>
    </div>
    {!! view_render_event('bagisto.shop.products.view.after', ['product' => $product]) !!}
@endsection

@push('scripts')
    <script type='text/javascript' src='https://unpkg.com/spritespin@4.1.0/release/spritespin.js'></script>

    <script type="text/x-template" id="product-view-template">
        @if($product->type == 'booking')

        <div class="event-product"  id="event-product-wrapper">
            <div class="event-product__hero" :style="(images.length == 0 ) ? `background:linear-gradient(0deg, rgba(0, 0, 0, 0.90), rgba(0, 0, 0, 0.25)), url(/themes/market/assets/images/event-default-hero-image.jpg` : `background:linear-gradient(0deg, rgba(0, 0, 0, 0.90), rgba(0, 0, 0, 0.25)), url(${images[0].large_image_url})`">
                <div class="event-product__hero-content">
                    <div class="container">
                        <div class="row align-items-center event-product__hero-category">
                            <div class="col-auto pr-0">
                                <p class="event-product__hero-category-name" v-text="getEventRangeDate()" ></p> {{--{{ucwords($product->attribute_family->name)}}--}}
                            </div>
                            @if($bookingProduct['tags'])
                            <div class="col">
                                @foreach(json_decode($bookingProduct['tags']) as $tag )
                                <span class="badge badge-info-dark">{{$tag}}</span>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        <div class="event-product__hero-title-container">
                            <h2 class="event-product__hero-title">
                                <span>@{{ mainProduct.name }}</span>
                                <a class="event-product__hero-wishlink">
                                    <i class="far fa-heart"></i>
                                </a>
                            </h2>
                            <p class="event-product__hero-by">by @{{mainProduct.seller.shop_title}}</p>
                        </div>

                    </div>
                </div>


            </div>
            <div class="event-product__body">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-lg-5 order-2 order-lg-1">
                            <div class="event-product__description">
                                <p class="event-product__description-title">Event Details</p>
                                <p>
                                    {{isset($bookingProduct->description) ? $bookingProduct->description : '' }}
                                </p>
                            </div>

                            <div class="event-product__info">

                                <div  class="event-product__info-item">
                                    <div class="row">
                                        <div class="col-auto pr-0">
                                            <i class="far fa-calendar-alt"></i>
                                        </div>
                                        <div class="col">
                                            <p class="event-product__info-item-title">Dates</p>
                                            <div v-if="sessions.length > 0" class="row"
                                                 v-for="(session, session_index) in sessions" :key="session_index">
                                                <div class="col">
                                                    <div class="row" v-for="(slot, i) in session.slots" :key="i">
                                                        <div class="col">
                                                            <p class="event-product__info-item-title"
                                                               v-text="getFormattedDate(slot.day)"></p>
                                                        </div>
                                                        <div class="col">
                                                            <p class="mb-0"
                                                               v-for="(duration, key) in defaultSlots.slots[i].durations"
                                                               :key="key" v-text="duration.from+' - '+duration.to"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div v-if="sessions.length==0" class="row" v-for="(slot, i) in defaultSlots.slots" :key="i">
                                                <div class="col">
                                                    <p class="event-product__info-item-title" v-text="getFormattedDate(slot.day)" ></p>
                                                </div>
                                                <div class="col">
                                                    <p class="mb-0" v-for="(duration, key) in defaultSlots.slots[i].durations" :key="key" v-text="duration.from+' - '+duration.to" ></p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>




                                <div class="event-product__info-item">
                                    <div class="row">
                                        <div class="col-auto pr-0">
                                            <i class="far fa-map-marker-alt"></i>
                                        </div>
                                        <div class="col">
                                            <p class="event-product__info-item-title">Location</p>
                                            @if($bookingProduct->location)<p class="mb-0">{{$bookingProduct->location}}</p>@endif
                                            @if($bookingProduct->address_line1)<p class="mb-0">{{$bookingProduct->address_line1}} @if($bookingProduct->address_line2)<br>{{$bookingProduct->address_line2}} @endif @if($bookingProduct->city)<br>{{$bookingProduct->city}}, @endif @if($bookingProduct->state) {{ app('Webkul\Core\Repositories\CountryStateRepository')
                    ->find($bookingProduct->state)->default_name }} @endif  @if($bookingProduct->postal_code) {{$bookingProduct->postal_code}} @endif</p>@endif
                                            @if($bookingProduct->location_additional_information)<p class="mb-0">{{$bookingProduct->location_additional_information}}</p>@endif
                                        </div>
                                    </div>
                                </div>
                                @if($bookingProduct->instructions)
                                <div class="event-product__info-item">
                                    <div class="row">
                                        <div class="col-auto pr-0">
                                            <i class="far fa-info-circle"></i>
                                        </div>
                                        <div class="col">
                                            <p class="event-product__info-item-title">Attendee Instructions</p>
                                            <p class="mb-0">{{$bookingProduct->instructions}}</p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @if($bookingProduct['leaders'])
                                <div class="event-product__info-item">
                                    <div class="row">
                                        <div class="col-auto pr-0">
                                            <i class="far fa-user"></i>
                                        </div>
                                        <div class="col">
                                            <p class="event-product__info-item-title">Leaders</p>
                                            <ul>
                                                @foreach(json_decode($bookingProduct['leaders']) as $leader )
                                                    <li>{{$leader}}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @if($bookingProduct['levels'])
                                    <div class="event-product__info-item">
                                        <div class="row">
                                            <div class="col-auto pr-0">
                                                <i class="far fa-user"></i>
                                            </div>
                                            <div class="col">
                                                <p class="event-product__info-item-title">Levels</p>
                                                <ul>
                                                    @foreach(json_decode($bookingProduct['levels']) as $level )
                                                        <li>{{$level}}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if($bookingProduct['age_restrictions']==0)
                                <div class="event-product__info-item">
                                    <div class="row">
                                        <div class="col-auto pr-0">
                                            <i class="far fa-exclamation-triangle"></i>
                                        </div>
                                        <div class="col">
                                            <p class="event-product__info-item-title">Restrictions</p>
                                            <ul>
                                                @if($bookingProduct['gender'] == 'male')
                                                    <li>Just for men</li>
                                                @endif
                                                @if($bookingProduct['gender'] == 'female')
                                                    <li>Just for women</li>
                                                @endif
                                                @if($bookingProduct['min_age'] != 0)
                                                    <li>Minimum age : {{$bookingProduct['min_age']}} years</li>
                                                @endif
                                                @if($bookingProduct['max_age'] != 0)
                                                    <li>Maximum age : {{$bookingProduct['max_age']}} years</li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-12 col-lg order-1 order-lg-2 mb-5 mb-lg-0">
                            <div class="event-product__booking-info">
                                <!-- If event_tickets -->
                                <div v-if="bookingProduct.type=='event'">
                                    <div class="event-product__ticket-list">
                                        <!-- Ticket Item TICKET -->
                                        <div class="event-product__ticket" :class="isTicketNotAvailable ? 'event-product__ticket--not-available' : ''"
                                             v-for="(item, index) in event_tickets.tickets" :key="index">
                                             <div class="row" v-if="item.showTicket">
                                                <div class="col-auto">
                                                    <div class="event-product__ticket-icon">
                                                        <i class="far fa-ticket"></i>
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="event-product__ticket-content">
                                                        <div v-if="bookingProduct.default_slot.type_of_event=='repeating' && sessions.length > 0">
                                                            <div class="row align-items-center">
                                                                <div class="col-12 col-md mb-2 mb-md-0">
                                                                    <p class="event-product__ticket-title" v-html="item.productTicketName"></p>
                                                                    <p class="event-product__ticket-description" v-html="item.ticketDetails"></p>
                                                                    <p v-if="validateTicketRestriction(item.restrictWhenAvailable,item.restrictionDate)" v-text="`Ticket Will Be Available on ${getFormattedDate(item.restrictionDate)}`"></p>
                                                                </div>
                                                                <div class="col-12 col-md-auto mb-2 mb-md-0" v-show="!isTicketNotAvailable">
                                                                    <quantity-changer name="quantity" :slotindex="index"></quantity-changer>
                                                                </div>
                                                                <div class="col-12 col-md-auto mb-2 mb-md-0" v-if="typeof item.note == 'undefined'">
                                                                    <add-to-cart
                                                                        v-if="(item.nbOfAvailableTickets==null || item.nbOfAvailableTickets > 0) && !isTicketNotAvailable"
                                                                        :csrf-token="token"
                                                                        :product-flat-id="mainProduct.product_id"
                                                                        :product-id="mainProduct.product_id"
                                                                        :reload-page="false"
                                                                        :product="mainProduct.product"
                                                                        :move-to-cart="false"
                                                                        :isDisabled="validateTicketRestriction(item.restrictWhenAvailable,item.restrictionDate)"
                                                                        btn-text="{{__('shop::app.products.add-to-cart')}}"
                                                                        ui="primary"
                                                                        :seller-info="getSellerInfo(mainProduct)"
                                                                        :selected-slot="sessions.length > 1 ? selectedSlot[index]  : sessions[0].slots[0].durations[0]"
                                                                        :default-slots="bookingProduct.default_slot"
                                                                        :selected-ticket="item"
                                                                        :booking-product-type="bookingProduct.type"
                                                                        :booking-product-id="bookingProduct.id"
                                                                        >
                                                                    </add-to-cart>
                                                                    <button class="btn btn-outline-black" disabled v-else><i class="far fa-cart-plus mr-2"></i>Available on Aug 24th 2021</button>
                                                                </div>
                                                                <div v-else class="col-12 col-md-auto mb-2 mb-md-0" >
                                                                    <button class="btn btn-outline-black" disabled ><i class="far fa-cart-plus mr-2"></i>Sold out</button>
                                                                </div>
                                                                <div class="col-12 col-md-auto">
                                                                    <p class="event-product__ticket-price mb-0" v-html="`$ ${item.ticketPrice}`"></p>
                                                                </div>
                                                            </div>
                                                            <div class="row align-items-center mt-3" v-if="bookingProduct.default_slot.type_of_event=='repeating' && sessions.length > 0">
                                                                <div class="col-12 col-md-auto">
                                                                    <p class="mb-0 text-right">Select date and time</p>
                                                                </div>
                                                                <div class="col-12 col-md">
                                                                    <select v-if="sessions.length > 1" class="form-control"  v-model="selectedSlot[index]"
                                                                            placeholder="Select time slot">
                                                                        <option v-for="(session, key) in sessions" :key="key"
                                                                                v-html="getFormattedDate(sessions[key].slots[0].day)+' '+ sessions[key].slots[0].durations[0].from+' - '+sessions[key].slots[0].durations[0].to"></option>
                                                                    </select>
                                                                    <p class="event-product__ticket-title" v-else v-text="sessions[0].slots[0].durations[0].from +' to '+sessions[0].slots[0].durations[0].to" ></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div v-if="bookingProduct.default_slot.type_of_event=='single-day' || bookingProduct.default_slot.type_of_event=='multi-day'" class="row align-items-center">
                                                            <div class="col-12 col-md mb-2 mb-md-0">
                                                                <p class="event-product__ticket-title" v-html="item.productTicketName"></p>
                                                                <p class="event-product__ticket-description" v-html="item.ticketDetails"></p>
                                                                <p v-if="validateTicketRestriction(item.restrictWhenAvailable,item.restrictionDate)" v-text="`Ticket Will Be Available on ${getFormattedDate(item.restrictionDate)}`"></p>
                                                            </div>
                                                            <div class="col-12 col-md-auto mb-2 mb-md-0" v-show="!isTicketNotAvailable">
                                                                <quantity-changer name="quantity" :slotindex="index"></quantity-changer>
                                                            </div>
                                                            <div class="col-12 col-md-auto mb-2 mb-md-0" v-if="typeof item.note == 'undefined'">
                                                                <add-to-cart
                                                                    v-if="(item.nbOfAvailableTickets==null || item.nbOfAvailableTickets > 0) && !isTicketNotAvailable"
                                                                    :csrf-token="token"
                                                                    :product-flat-id="mainProduct.product_id"
                                                                    :product-id="mainProduct.product_id"
                                                                    :reload-page="false"
                                                                    :product="mainProduct.product"
                                                                    :move-to-cart="false"
                                                                    :isDisabled="validateTicketRestriction(item.restrictWhenAvailable,item.restrictionDate)"
                                                                    btn-text="{{__('shop::app.products.add-to-cart')}}"
                                                                    ui="primary"
                                                                    :seller-info="getSellerInfo(mainProduct)"
                                                                    :default-slots="bookingProduct.default_slot"
                                                                    :selected-ticket="item"
                                                                    :booking-product-type="bookingProduct.type"
                                                                    :booking-product-id="bookingProduct.id"
                                                                >
                                                                </add-to-cart>
                                                            </div>
                                                            <div v-else class="col-12 col-md-auto mb-2 mb-md-0">
                                                                <button class="btn btn-outline-black" disabled><i class="far fa-cart-plus mr-2"></i>Sold Out</button>
                                                            </div>
                                                            <div class="col-12 col-md-auto">
                                                                <p class="event-product__ticket-price mb-0" v-html="`$ ${item.ticketPrice}`"></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                             </div>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="bookingProduct.type=='training'">
                                    <p class="event-product__ticket-price" v-html="getMainProductPrice+ '/class'"></p>
                                    <p class="event-product__booking-info-title">Select Date & Time</p>
                                    <div class="event-product__date-list">
                                        <!-- If there are NO TICKETS -->
                                        <!-- Date and Time Item -->
                                        <div class="event-product__date" v-if="sessions.length > 0" v-for="(session, index) in sessions" :key="index">
                                            <div v-for="(slot,slotindex) in sessions[index].slots" :key="slotindex" class="row align-items-center">
                                                <div class="col-12 col-md pr-0 mb-2 mb-md-0">
                                                    <p class="event-product__ticket-title"
                                                       v-html="getFormattedDate(sessions[index].date)"></p>
                                                </div>
                                                <div class="col col-md-auto pr-0">
                                                    <select v-if="sessions[index].slots[slotindex].durations.length > 1" class="form-control" id="eventTime" v-model="selectedSlot[index]"
                                                            placeholder="Select time slot">
                                                        <option v-for="(duration, key) in sessions[index].slots[slotindex].durations"
                                                                v-html="duration.from+' - '+duration.to" :key="key"
                                                                :value="duration"></option>
                                                    </select>
                                                    <p class="event-product__ticket-title" v-else v-text="sessions[index].slots[slotindex].durations[0].from +' to '+sessions[index].slots[slotindex].durations[0].to" ></p>
                                                </div>

                                                <div class="col-auto">
                                                    <add-to-cart
                                                        v-if="sessions[index].slots[slotindex].durations.length > 0"
                                                        :csrf-token="token"
                                                        :product-flat-id="mainProduct.product_id"
                                                        :product-id="mainProduct.product_id"
                                                        :reload-page="false"
                                                        :product="mainProduct.product"
                                                        :move-to-cart="false"
                                                        :is-enable="mainProduct.visible_individually"
                                                        btn-text="{{__('shop::app.products.add-to-cart')}}"
                                                        ui="primary"
                                                        :seller-info="getSellerInfo(mainProduct)"
                                                        :selected-date="sessions[index].slots[slotindex].day"
                                                        :selected-slot="sessions[index].slots[slotindex].durations.length ==1 ? sessions[index].slots[slotindex].durations[0] : selectedSlot[index]"
                                                        :booking-product-type="bookingProduct.type"
                                                        :booking-product-id="bookingProduct.id"
                                                        :default-slots="bookingProduct.default_slot">
                                                    </add-to-cart>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="event-product__date" v-if="sessions.length == 0" v-for="(slot, index) in defaultSlots.slots" :key="index">
                                            <div class="row align-items-center">
                                                <div class="col-12 col-md pr-0 mb-2 mb-md-0">
                                                    <p class="event-product__ticket-title"
                                                       v-html="getFormattedDate(defaultSlots.slots[index].day)"></p>
                                                </div>
                                                <div class="col col-md-auto pr-0">
                                                    <select v-if="defaultSlots.slots[index].durations.length > 1" class="form-control" id="eventTime" v-model="selectedSlot[index]"
                                                            placeholder="Select time slot">
                                                        <option v-for="(duration, key) in defaultSlots.slots[index].durations"
                                                                v-html="duration.from+' - '+duration.to" :key="key"
                                                                :value="duration"></option>
                                                    </select>
                                                    <p class="event-product__ticket-title" v-else v-text="defaultSlots.slots[index].durations[0].from +' to '+defaultSlots.slots[index].durations[0].to" ></p>
                                                </div>
                                                <div class="col-auto">
                                                    <add-to-cart
                                                        v-if="defaultSlots.slots[index].durations.length > 0"
                                                        :csrf-token="token"
                                                        :product-flat-id="mainProduct.product_id"
                                                        :product-id="mainProduct.product_id"
                                                        :reload-page="false"
                                                        :product="mainProduct.product"
                                                        :move-to-cart="false"
                                                        :is-enable="mainProduct.visible_individually"
                                                        btn-text="{{__('shop::app.products.add-to-cart')}}"
                                                        ui="primary"
                                                        :seller-info="getSellerInfo(mainProduct)"
                                                        :selected-slot="defaultSlots.slots[index].durations.length==1 ? defaultSlots.slots[index].durations[0] : selectedSlot[index]"
                                                        :booking-product-type="bookingProduct.type"
                                                        :booking-product-id="bookingProduct.id"
                                                        :default-slots="bookingProduct.default_slot">
                                                    </add-to-cart>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- END Date and Time Item -->
                                        <!-- END If there are NO TICKETS -->
                                    </div>
                                </div>



                                <div v-if="bookingProduct.type=='default_event'">
                                    <p class="event-product__ticket-price" v-html="getMainProductPrice+ '/class'"></p>
                                    <p class="event-product__booking-info-title">Select Date & Time</p>
                                    <div class="event-product__date-list">
                                        <!-- If there are NO TICKETS -->
                                        <!-- Date and Time Item -->
                                        <div class="event-product__date" v-if="bookingProduct.default_slot.type_of_event=='repeating' && sessions.length > 0" v-for="(session, index) in sessions" :key="index">
                                                <div v-for="(slot,slotindex) in sessions[index].slots" :key="slotindex" class="row align-items-center">
                                                    <div class="col-12 col-md pr-0 mb-2 mb-md-0">
                                                        <p class="event-product__ticket-title"
                                                           v-html="getFormattedDate(sessions[index].date)"></p>
                                                    </div>
                                                    <div class="col col-md-auto pr-0">
                                                        <select v-if="sessions[index].slots[slotindex].durations.length > 1" class="form-control" id="eventTime" v-model="selectedSlot[index]"
                                                                placeholder="Select time slot">
                                                            <option v-for="(duration, key) in sessions[index].slots[slotindex].durations"
                                                                    v-html="duration.from+' - '+duration.to" :key="key"
                                                                    :value="duration"></option>
                                                        </select>
                                                        <p class="event-product__ticket-title" v-else v-text="sessions[index].slots[slotindex].durations[0].from +' to '+sessions[index].slots[slotindex].durations[0].to" ></p>
                                                    </div>

                                                    <div class="col-auto">
                                                        <add-to-cart
                                                            v-if="sessions[index].slots[slotindex].durations.length > 0"
                                                            :csrf-token="token"
                                                            :product-flat-id="mainProduct.product_id"
                                                            :product-id="mainProduct.product_id"
                                                            :reload-page="false"
                                                            :product="mainProduct.product"
                                                            :move-to-cart="false"
                                                            :is-enable="mainProduct.visible_individually"
                                                            btn-text="{{__('shop::app.products.add-to-cart')}}"
                                                            ui="primary"
                                                            :seller-info="getSellerInfo(mainProduct)"
                                                            :selected-slot="sessions[index].slots[0]"
                                                            :booking-product-type="bookingProduct.type"
                                                            :booking-product-id="bookingProduct.id"
                                                            :default-slots="bookingProduct.default_slot">
                                                        </add-to-cart>
                                                    </div>
                                                </div>
                                        </div>

                                        <div class="event-product__date" v-if="bookingProduct.default_slot.type_of_event=='multi-day'">
                                           <div class="row">
                                               <div class="col-8">
                                                   <div v-for="(slot, index) in defaultSlots.slots" :key="index" class="row align-items-center">
                                                       <div class="col-12 col-md pr-0 mb-2 mb-md-0">
                                                           <p class="event-product__ticket-title"
                                                              v-html="getFormattedDate(defaultSlots.slots[index].day)"></p>
                                                       </div>
                                                       <div class="col col-md-auto pr-0">
                                                           <p class="event-product__ticket-title" v-text="defaultSlots.slots[index].durations[0].from +' to '+defaultSlots.slots[index].durations[0].to" ></p>
                                                       </div>
                                                   </div>
                                               </div>
                                               <div class="col-4">
                                                   <add-to-cart
                                                       v-if="defaultSlots.slots.length > 0"
                                                       :csrf-token="token"
                                                       :product-flat-id="mainProduct.product_id"
                                                       :product-id="mainProduct.product_id"
                                                       :reload-page="false"
                                                       :product="mainProduct.product"
                                                       :move-to-cart="false"
                                                       :is-enable="mainProduct.visible_individually"
                                                       btn-text="{{__('shop::app.products.add-to-cart')}}"
                                                       ui="primary"
                                                       :seller-info="getSellerInfo(mainProduct)"
                                                       :selected-slot="defaultSlots.slots"
                                                       :booking-product-type="bookingProduct.type"
                                                       :booking-product-id="bookingProduct.id"
                                                       :default-slots="bookingProduct.default_slot">
                                                   </add-to-cart>
                                               </div>
                                           </div>
                                        </div>

                                        <div class="event-product__date" v-if="bookingProduct.default_slot.type_of_event=='single-day'">
                                            <div class="row align-items-center">
                                                <div class="col-12 col-md pr-0 mb-2 mb-md-0">
                                                    <p class="event-product__ticket-title"
                                                       v-html="getFormattedDate(defaultSlots.slots[0].day)"></p>
                                                </div>
                                                <div class="col col-md-auto pr-0">
                                                    <p class="event-product__ticket-title"  v-text="defaultSlots.slots[0].durations[0].from +' to '+defaultSlots.slots[0].durations[0].to" ></p>
                                                </div>
                                                <div class="col-auto">
                                                    <add-to-cart
                                                        v-if="defaultSlots.slots[0].durations.length > 0"
                                                        :csrf-token="token"
                                                        :product-flat-id="mainProduct.product_id"
                                                        :product-id="mainProduct.product_id"
                                                        :reload-page="false"
                                                        :product="mainProduct.product"
                                                        :move-to-cart="false"
                                                        :is-enable="mainProduct.visible_individually"
                                                        btn-text="{{__('shop::app.products.add-to-cart')}}"
                                                        ui="primary"
                                                        :seller-info="getSellerInfo(mainProduct)"
                                                        :selected-slot="defaultSlots.slots[0].durations.length==1 ? defaultSlots.slots[0].durations[0] : selectedSlot[0]"
                                                        :booking-product-type="bookingProduct.type"
                                                        :booking-product-id="bookingProduct.id"
                                                        :default-slots="bookingProduct.default_slot">
                                                    </add-to-cart>

                                                </div>
                                            </div>
                                        </div>
                                        <!-- END Date and Time Item -->
                                        <!-- END If there are NO TICKETS -->
                                    </div>
                                </div>

                                <!-- If is a RENTAL product -->
                      {{--          <div v-if="rentalSlots">
                                    --}}{{--<p class="event-product__booking-info-price">$100 <span class="event-product__booking-info-price-unit"> / Class</span></p>--}}{{--
                                    <p class="event-product__booking-info-title">Select Date & Time</p>

                                    <!-- Date and Time Item -->
                                    <div v-if="rentalSlots.renting_type=='hourly' || rentalSlots.renting_type=='both'">
                                        <div v-if="rentalSlots.same_slot_all_days" class="">
                                            <div class="row align-items-center">
                                                <div class="col-6 col-md pr-0 mb-2 mb-md-0">
                                                    <p><span v-text="`Renting type`" class="font-weight-bold mr-2"></span><span v-text="rentalSlots.renting_type"></span></p>
                                                </div>
                                                <div class="col-6 col-md">
                                                    <p class="event-product__ticket-price text-right" style="font-size: 1rem;" v-if="rentalSlots.renting_type=='both' || rentalSlots.renting_type=='hourly'" v-text="'$ '+parseFloat(rentalSlots.hourly_price).toFixed(2)+' /hr'"></p>
                                                </div>
                                            </div>
                                            <div class="event-product__rental">
                                                <div class="row">
                                                    <div class="col col-md pr-0">
                                                        <select class="form-control" id="eventTime" v-model="selectedSlot"
                                                                placeholder="Select time slot">
                                                            <option v-for="(slot, key) in rentalSlots.slots"
                                                                    v-html="(typeof slot.from !='undefined' ) ? slot.from+' '+slot.to  : slot.durations.from+' '+slot.durations.to  " :key="key"
                                                                    :value="slot"></option>
                                                        </select>
                                                    </div>
                                                    <div class="col-auto">
                                                        --}}{{--   <button class="btn btn-primary">
                                                                <i class="far fa-cart-plus mr-md-2"></i><span class="d-none d-md-inline-block">Add to cart</span>
                                                            </button>--}}{{--
                                                        <add-to-cart
                                                            v-if="rentalSlots.slots.length > 0"
                                                            :csrf-token="token"
                                                            :product-flat-id="mainProduct.product_id"
                                                            :product-id="mainProduct.product_id"
                                                            :reload-page="false"
                                                            :product="mainProduct.product"
                                                            :move-to-cart="false"
                                                            :is-enable="mainProduct.visible_individually"
                                                            btn-text="{{__('shop::app.products.add-to-cart')}}"
                                                            ui="primary"
                                                            :seller-info="getSellerInfo(mainProduct)"
                                                            :selected-slot="selectedSlot"
                                                            :selected-date="selectedSlot    ? selectedSlot.from : '' "
                                                            :booking-product-type="bookingProduct.type"
                                                            :booking-product-id="bookingProduct.id">
                                                        </add-to-cart>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-else>
                                            <div class="row align-items-center">
                                                <div class="col-6 col-md pr-0 mb-2 mb-md-0">
                                                    <p><span v-text="`Renting type`" class="font-weight-bold mr-2"></span><span v-text="rentalSlots.renting_type"></span></p>
                                                </div>
                                                <div class="col-6 col-md">
                                                <p class="event-product__ticket-price text-right" style="font-size: 1rem;" v-if="rentalSlots.renting_type=='both' || rentalSlots.renting_type=='hourly'" v-text="'$ '+parseFloat(rentalSlots.hourly_price).toFixed(2)+' /hr'"></p>
                                                </div>
                                            </div>
                                            <div class="event-product__rental" v-for="(slot,index) in rentalSlots.slots" :key="index">
                                                <div class="row">
                                                    <div class="col col-md pr-0">
                                                        <p class="event-product__ticket-title"
                                                                v-text="`Day of the week`"></p>
                                                        <p v-text="slot.day" class="mb-0"></p>

                                                    </div>
                                                    <div class="col col-md pr-0">
                                                        <select class="form-control" id="eventTime" v-model="selectedSlot"
                                                                    placeholder="Select time slot">
                                                                <option v-for="(daily_slot, key) in rentalSlots.slots[index].durations"
                                                                        v-html="daily_slot.from+' '+daily_slot.to" :key="key"
                                                                        :value="daily_slot"></option>
                                                            </select>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div>
                                                            <add-to-cart
                                                                v-if="rentalSlots.slots[index].durations.length > 0"
                                                                :csrf-token="token"
                                                                :product-flat-id="mainProduct.product_id"
                                                                :product-id="mainProduct.product_id"
                                                                :reload-page="false"
                                                                :product="mainProduct.product"
                                                                :move-to-cart="false"
                                                                :is-enable="mainProduct.visible_individually"
                                                                btn-text="{{__('shop::app.products.add-to-cart')}}"
                                                                ui="primary"
                                                                :seller-info="getSellerInfo(mainProduct)"
                                                                :selected-slot="selectedSlot"
                                                                :selected-date="selectedSlot    ? selectedSlot.from : '' "
                                                                :booking-product-type="bookingProduct.type"
                                                                :booking-product-id="bookingProduct.id">
                                                            </add-to-cart>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-else class="event-product__rental">
                                        <div class="row align-items-center">
                                            <div class="col-6 col-md pr-0 mb-2 mb-md-0">
                                                <p><span v-text="`Renting type`" class="font-weight-bold mr-2"></span><span v-text="rentalSlots.renting_type"></span></p>
                                            </div>
                                            <div class="col-6 col-md">
                                                <p class="event-product__ticket-price text-right" style="font-size: 1rem;" v-if="rentalSlots.renting_type=='both' || rentalSlots.renting_type=='daily'" v-text="'$ '+parseFloat(rentalSlots.daily_price).toFixed(2)+' /day'"></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col col-md pr-0">
                                                <div class="form-group mb-0"  :class="[errors.has('booking[start_date]') ? 'has-error' : '']">
                                                    <label for="productEventStartDate">Start date</label>
                                                    <date>
                                                        <input type="text" v-validate="'required'"  name="booking[start_date]" v-model="startDate" class="form-control control" data-vv-as="&quot;Start Date&quot;">
                                                    </date>
                                                    <span class="control-error" v-if="errors.has('booking[start_date]')">@{{ errors.first('booking[start_date]') }}</span>
                                                </div>
                                            </div>
                                            <div class="col col-md-auto pr-0">
                                                <div class="form-group mb-0"  :class="[errors.has('booking[nb_of_days]') ? 'has-error' : '']">
                                                    <label for="productEventStartDate">For how many days</label>
                                                    <input type="text" v-validate="'required'"  name="booking[nb_of_days]" v-model="nbOfDays" class="form-control control" data-vv-as="&quot;# Of Days&quot;">
                                                    <span class="control-error" v-if="errors.has('booking[nb_of_days]')">@{{ errors.first('booking[nb_of_days]') }}</span>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                --}}{{--   <button class="btn btn-primary">
                                                        <i class="far fa-cart-plus mr-md-2"></i><span class="d-none d-md-inline-block">Add to cart</span>
                                                    </button>--}}{{--
                                                <div class="event-product__rental-add-to-cart">
                                                    <add-to-cart
                                                        v-if=""
                                                        :csrf-token="token"
                                                        :product-flat-id="mainProduct.product_id"
                                                        :product-id="mainProduct.product_id"
                                                        :reload-page="false"
                                                        :product="mainProduct.product"
                                                        :move-to-cart="false"
                                                        :isDisabled="!startDate || !nbOfDays"
                                                        btn-text="{{__('shop::app.products.add-to-cart')}}"
                                                        ui="primary"
                                                        :seller-info="getSellerInfo(mainProduct)"
                                                        :selected-slot="{'from': startDate, 'nbOfDays':nbOfDays}"
                                                        :booking-product-type="bookingProduct.type"
                                                        :booking-product-id="bookingProduct.id">
                                                    </add-to-cart>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END Date and Time Item -->
                                </div>--}}
                                <!-- END If is a RENTAL product -->
                                <div class="product-thumb mt-4" v-if="mainProduct">
                                    <div class="product-thumb__image">
                                        <a target="_blank" :href="`${baseUrl}/`+mainProduct.seller.url">
                                            <img v-if="mainProduct.seller.logo"
                                                 :src="`{{getenv('WASSABI_STORAGE')}}/`+mainProduct.seller.logo"
                                                 alt="Product image">
                                            <img v-else :src="`${baseUrl}/images/product-thumb-empty.jpg`"
                                                 alt="Product image"></a>
                                    </div>
                                    <div class="product-thumb__info">
                                        <a v-if="mainProduct.seller.url!== undefined" target="_blank"
                                           :href="`${baseUrl}/`+mainProduct.seller.url" class="name">@{{mainProduct.seller.shop_title}}</a>
                                        <a v-else target="_blank"
                                           :href="`${baseUrl}/reviews/`+mainProduct.product.url_key" class="name">@{{mainProduct.seller.shop_title}}</a>
                                        <div class="rate">
                                                <span class="stars"
                                                      v-html="getReviewHtml(mainProduct.review.average)"></span>
                                            <span>@{{mainProduct.review.average}} (@{{mainProduct.review.total}} ratings)</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row pt-3">
                                    <div class="col-md-12">
                                        <div class="product-detail__description">
                                            <div class="group">
                                                <h5>Description</h5>
                                                <p v-html="mainProduct.description"></p>
                                            </div>
                                        </div>
                                        <div class="product-detail__description">
                                            <div class="group">
                                                <h5>Product information & Specs</h5>
                                                <p v-html="mainProduct.short_description"></p>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        @else
        <div class="product-detail py-5" v-else>
            <div class="container">

                <div class="row d-flex flex-column flex-sm-row" v-if="mainProduct">
                    @auth('admin')
                        <div class="col-12 pb-5">
                            <a target="_blank" :href="`${baseUrl}/admin/catalog/products/edit/`+mainProduct.product_id"
                               class="btn btn-primary">
                                Edit Product
                            </a>
                        </div>
                    @endif

                    <div class="col-md-12 col-lg-5 product-detail__intro order-1 order-sm-0">
                        <div class="product-detail__images d-none d-sm-block">
                            <product-gallery :videos="videos" :images="images"></product-gallery>
                        </div>
                        <div class="product-detail__description">
                            <div class="group">
                                <h5>Product information & Specs</h5>
                                <p v-html="mainProduct.short_description"></p>
                            </div>
                            <div class="product-detail__attributes">
                                <slot name="attributes"></slot>
                            </div>

                        </div>

                        <!-- Simple info box for product warnings -->
                        <div v-if="typeof mainProduct.product.prop65!='undefined'" class="simple-info-box mt-4" v-show="mainProduct.product.prop65">
                            <div class="row mx-n2 align-items-center">
                                <div class="col-auto px-2"><i class="far fa-exclamation-triangle"></i></div>
                                <div class="col px-2">
                                    <p class="mb-0" v-text="mainProduct.product.prop65"></p>
                                </div>
                            </div>
                        </div>
                        <!-- END Simple info box for product warnings -->

                        <!-- Disclaimer Modal -->
                        <div class="product-detail__disclaimer mt-4">
                            <i class="far fa-info-circle"></i> * <a href="#" data-toggle="modal" data-target="#disclaimerModal">Firearms and Ammo Disclaimer</a>
                        </div>
                        <div class="modal fade" id="disclaimerModal" tabindex="-1" aria-labelledby="disclaimerModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <i class="fal fa-times"></i>
                                        </button>

                                        <div class="modal-head">
                                            <!-- <h5 class="modal-title" id="disclaimerModalLabel">Firearms and Ammo Disclaimer</h5> -->
                                            <i class="far fa-3x fa-exclamation-triangle"></i>
                                            <h3 class="modal-title" id="deleteProfileLabel">Firearms and Ammo Disclaimer</h3>
                                        </div>

                                        <p>Due to individual municipal or state laws, a seller may not be able to sell or ship some items to your area. Ultimately it is the customers responsibility to be aware of their federal, state and local laws.</p>
                                        <p>Such restrictions may include, but are by no means limited to, the following examples:</p>
                                        <ul class="pl-4 mb-0">
                                            <li>Ammo: NY, DC, HI, MA, IL* (see note below), CA</li>
                                            <li>Blanks: NY</li>
                                            <li>Bows: MA, DC, New York, NY</li>
                                            <li>Crossbows: MA, DC, New York, NY</li>
                                            <li>Crossbow Bolts: CT, MA, DC</li>
                                            <li>Folding & Collapsible Stocks: CT, CA, DC, HI, MA, NJ, Chicago, IL, Cicero, IL, Columbus, OH, Dubin, OH, Cincinnati, OH, Cleveland, OH, Dayton, OH, Toledo, OH</li>
                                            <li>Muzzleloaders: IL, MA, NJ, DC, RI, HI</li>
                                            <li>Pepper Spray: MA, NJ, RI, HI, IN, MI, NY, PA, WI</li>
                                            <li>Reloading Items: MA</li>
                                            <li>Slingshots: IL, NJ, DC, NY, NM, CT, DE</li>
                                            <li>Certain Solvents: CA - VOC Restrictions</li>
                                            <li>Stun Guns: MA, NJ, NY, CA</li>
                                            <li>Magazines > 10: NY, CT, DC, HI, MD, MA, Oak Park, IL</li>
                                            <li>Magazines > 12: NY, CT, DC, HI, MD, MA, CA, Oak Park, IL, Chicago, IL</li>
                                            <li>Magazines > 15: NY, CT, DC, HI, MD, MA, CA, CO, NJ, Oak Park, IL, Chicago, IL, Aurora, IL, Cicero, IL, Cincinnati, OH</li>
                                            <li>Magazines > 16: NY, CT, DC, HI, MD, MA, CO, CA, NJ, Oak Park, IL, Chicago, IL, Aurora, IL, Cicero, IL, Cincinnati, OH, Franklin Park, IL</li>
                                            <li>Magazines > 21: NY, CT, DC, HI, MD, MA, CO, CA,NJ, Oak Park, IL, Chicago, IL, Aurora, IL, Cicero, IL, Cincinnati, OH, Franklin Park, IL</li>
                                            <li>Magazines > 31: NY, CT, DC, HI, MD, MA, CO, CA,NJ, OH, Oak Park, IL, Chicago, IL, NJ, Aurora, IL, Cicero, IL, Franklin Park, IL</li>
                                            <li>Air Rifles & Pistols: IL, CA, MA, NJ, New York, NY, Buffalo, NY, Philadelphia, PA</li>
                                            <li>Illinois Residents: For zip codes starting with 606, 607 and 608, before orders containing ammunition can be processed, you must contact the seller and provide a valid/current copy of your F.O.I.D. card, and state issued ID or Drivers License. Click here to go to FOID applicaiton.</li>
                                        </ul>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Ok</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END Disclaimer Modal -->

                        {{-- @if($customer and $customer_is_seller)
                            <div class="mt-3">
                                <strong >Have one to sell ?</strong>
                                <a target="_blank" :href="`${baseUrl}/marketplace/account/catalog/products/assign/`+mainProduct.product_id" class="btn btn-primary ml-3">
                                    Sell yours
                                </a>
                            </div>
                        @endif --}}

                    </div>

                    <div class="col-md-12 col-lg-7 product-detail__info order-0 order-sm-1">
                        <h2 class="h3">@{{mainProduct.name}}</h2>
                        <div class="meta">

                            <span
                                v-if="mainProduct.quantity < 1  && mainProduct.product.type!='configurable' && mainProduct.product.type!='booking'"
                                class="badge badge-danger">Out of stock</span>
                            <span class="badge badge-gray-dark" v-if="typeof mainProduct.isWithinThirtyDays != 'undefined' && mainProduct.isWithinThirtyDays==1">Recently added</span>
                            <span class="text-gray-dark h4">@{{mainProduct.condition.join(': ')}}</span>
                        </div>

                        @include ('shop::sellers.products.price', ['product' => $product])
                        @include ('shop::products.view.configurable-options')

                        <div class="mb-3">
                            <slot v-if="showMainQuantityChanger" name="quantity"></slot>
                        </div>

                        <div class="action mt-3">
                            {{-- please note that the below condition and logic should be changed , there is a logic created on the initail theme from bagisto , we should grabb this logic to here ,
                            when we change the attribute variants , we should change the price , images and stock depending on the selected variant from drop doen menu.--}}
                            <add-to-cart
                                v-if="checkAddToCart"
                                :csrf-token="token"
                                :product-flat-id="mainProduct.product_id"
                                :product-id="mainProduct.product_id"
                                :reload-page="false"
                                :product="mainProduct.product"
                                :move-to-cart="false"
                                :is-disabled="isDisabledAddToCart"
                                :disable-reason="isDisabledAddToCart ? 'Please select an option to add to cart' : '' "
                                btn-text="{{__('shop::app.products.add-to-cart')}}"
                                ui="primary"
                                :seller-info="getSellerInfo(mainProduct)"
                                >
                            </add-to-cart>
                            <div v-if="checkOutOfStock">
                                <button
                                    type="submit"
                                    disabled="true"
                                    class="btn btn-outline-dark">
                                    <i class="far fa-cart-plus"></i>
                                    <span>Out Of Stock</span>
                                </button>
                            </div>
                            <wishlist-component
                                :active="{{$isWished ? 'true' : 'false'}}"
                                :is-customer="{{($customer) ? 'true' : 'false'}}"
                                :product-id="mainProduct.product_id"
                                :seller-id="mainProduct.marketplace_seller_id ? mainProduct.marketplace_seller_id : 0"
                                add-url="{{route('marketplace.wishlist.add')}}"
                                remove-url="{{route('marketplace.wishlist.delete')}}"
                                add-class-to-link="{{ $addWishlistClass ?? '' }}"
                                linkTitle="{{$linkTitle}}">
                            </wishlist-component>
                            <compare-component
                                token="{{csrf_token()}}"
                                :customer="{{auth()->guard('customer')->user() ? 'true' : 'false'}}"
                                :product-id="mainProduct.product_id"
                                :marketplace-seller-id="mainProduct.marketplace_seller_id">
                            </compare-component>
                        </div>

                        {{-- do not remove the below it should be implemented at a later stage

                        @include ('shop::products.view.downloadable')

                        @include ('shop::products.view.grouped-products')

                        @include ('shop::products.view.bundle-options')--}}

                        <div class="product-detail__images d-block d-sm-none my-3">
                            <product-gallery :videos="videos" :images="images"></product-gallery>
                        </div>
                        <div class="product-thumb" v-if="mainProduct">
                            <div class="product-thumb__image">
                                <a target="_blank" :href="`${baseUrl}/`+mainProduct.seller.url">
                                    <img v-if="mainProduct.seller.logo"
                                         :src="`{{getenv('WASSABI_STORAGE')}}/`+mainProduct.seller.logo"
                                         alt="Product image">
                                    <img v-else :src="`${baseUrl}/images/product-thumb-empty.jpg`" alt="Product image"></a>
                            </div>
                            <div class="product-thumb__info">
                                <a v-if="mainProduct.seller.url!== undefined" target="_blank"
                                   :href="`${baseUrl}/`+mainProduct.seller.url" class="name">@{{mainProduct.seller.shop_title}}</a>
                                <a v-else target="_blank" :href="`${baseUrl}/reviews/`+mainProduct.product.url_key"
                                   class="name">@{{mainProduct.seller.shop_title}}</a>
                                {{--<div class="rate">
                                    <span class="stars" v-html="getReviewHtml(mainProduct.review.average)"></span>
                                    <span>@{{mainProduct.review.average}} (@{{mainProduct.review.total}} ratings)</span>
                                </div>--}}
                                <div>
                                    <p class="font-weight-bold" v-text="mainProduct.seller.city+', '+mainProduct.seller.state"></p>
                                </div>
                            </div>
                        </div>
                        <div class="description">
                            <p v-html="mainProduct.description"></p>
                        </div>

<!--                        <div v-if="otherProducts.length > 0" class="product-thumb__list-head">
                            <b>Sold by other sellers</b>

                        </div>
                        <div v-if="otherProducts.length > 0" class="related-products">
                            <seller-products :token="token" :products="otherProducts"
                                             images-path="{{getenv('WASSABI_STORAGE')}}"></seller-products>
                        </div>-->

                    </div>
                </div>
            </div>
        </div>
        @endif
    </script>

    <script>

        Vue.component('product-view', {
            inject: ['$validator'],
            props: ['products', 'token'],
            template: '#product-view-template',
            data: function () {
                return {
                    is_buy_now: 0,
                    mainProduct: this.products[0],
                    otherProducts: [],
                    images: [],
                    videos: @json($videos),
                    isEvent: @json($isEvent),
                    bookingProduct:   @json($bookingProduct) ,
                    defaultSlots: @json($bookingProductDefaultSlot) ,
                    rentalSlots: @json($bookingProductRental) ,
                    event_tickets: @json($event_tickets),
                    selectedSlot: [],
                    isTicketNotAvailable: false,
                    nbOfDays: 1,
                    startDate: null,
                    sessions:[],
                    isDisabledAddToCart: false
                }
            },
            created: function () {

                eventBus.$on('configurable-variant-update-images-event', this.updateImages);
                window.addEventListener('scroll', this.handleSCroll);
            },

            computed: {
                getMainProductPrice() {
                    let currentDate = this.getCurrentDate();
                    let price = parseFloat(this.mainProduct.price).toFixed(2);
                    let special = '';

                    if (this.mainProduct.special_price && parseFloat(this.mainProduct.special_price) > 0 && this.mainProduct.special_price.indexOf('$0.') === -1) {
                        if (this.mainProduct.special_price_from && this.mainProduct.special_price_to) {
                            if (currentDate >= this.mainProduct.special_price_from && currentDate <= this.mainProduct.special_price_to) {
                                price = parseFloat(this.mainProduct.special_price).toFixed(2);
                                special = `
                            <div class="price__list">
                                <span class="before">Before $ ${ parseFloat(this.mainProduct.price).toFixed(2)}</span>
                                <span class="after">You save $ ${ parseFloat(this.mainProduct.price_save).toFixed(2)}</span>
                            </div>`;
                            }
                        } else {
                            price = parseFloat(this.mainProduct.special_price).toFixed(2);
                            special = `
                            <div class="price__list">
                                <span class="before">Before ${ parseFloat(this.mainProduct.price).toFixed(2)}</span>
                                <span class="after">You save ${ parseFloat(this.mainProduct.price_save).toFixed(2)}</span>
                            </div>`;
                        }
                    }

                    return `<span>$${price}</span>${special}`;
                },

                showMainQuantityChanger() {
                    return this.mainProduct.product.type !== 'booking';
                },
                checkAddToCart: function (){
                    if(this.mainProduct.product.type=='simple'){
                        if( typeof this.mainProduct.isInStock != 'undefined'){
                            return  this.mainProduct.isInStock;
                        }else{
                            if(this.mainProduct.quantity > 0){
                                return true;
                            }
                        }
                    }
                    if(this.mainProduct.product.type=='configurable'){
                        this.isDisabledAddToCart=true;
                        return true;
                    }
                    if(this.mainProduct.product.type=='booking'){
                        return true;
                    }
                    return false;
                },
                checkOutOfStock: function(){
                    if(this.mainProduct.product.type=='simple'){
                        if(this.mainProduct.quantity == 0){
                            return true;
                        }
                    }
                    if(this.mainProduct.product.type=='configurable'){
                        return false;
                    }
                    if(this.mainProduct.product.type=='booking'){
                        return false;
                    }
                    return false;
                }
            },

            mounted: function () {

                let mainProductKey;
                for (let key in this.products) {
                    this.products[key].isRecentlyAdded = this.isRecentlyAdded(this.products[key].created_at);
                    if (key == 0) {
                        this.images = this.products[key].productImages.sort((a, b) => (a.sort_order > b.sort_order) ? -1 : 1) /*(a.sort_order > b.sort_order) ? 1 : -1*/
                        mainProductKey = key;
                    }
                }
                this.products.splice(mainProductKey, 1);
                this.setRecentlyViewed(this.mainProduct)
                this.images = this.mainProduct.productImages.sort((a, b) => -1);
                this.otherProducts = [...this.products].sort((a, b) => {
                    return parseInt(a.price.substring(1)) - parseInt(b.price.substring(1));
                });
                this.$root.$on('onUpdateTicketQuantity', ({quantity,index}) => {
                    this.event_tickets.tickets[index].qty=quantity;
                });
                this.getSessions();
                this.$root.$on('updateProductId', (text) => {
                      for (let i=0 ; i < this.mainProduct.child.length; i++){
                          if(this.mainProduct.child[i].product_id== text){

                              if(this.mainProduct.child[i].quantity > 0){
                                  this.isDisabledAddToCart=false;
                              }
                          }
                      }
                });
                this.$root.$on('disableAddtoCart', (text) => {
                    this.isDisabledAddToCart=true;
                });
            },
            methods: {
                updateImages: function (galleryImages) {
                    this.images = galleryImages;
                },
                onSubmit(event) {
                    if (event.target.getAttribute('data-type') != 'submit')
                        return;

                    event.preventDefault();

                    this.$validator.validateAll().then(result => {
                        if (result) {
                            this.is_buy_now = event.target.classList.contains('buynow') ? 1 : 0;

                            setTimeout(function () {
                                document.getElementById('product-form').submit();
                            }, 0);
                        }
                    });
                },

                setRecentlyViewed(product) {
                    let currentProductId = product.url_key;
                    let existingViewed = window.localStorage.getItem('recentlyViewed');

                    if (!existingViewed) {
                        existingViewed = [];
                    } else {
                        existingViewed = JSON.parse(existingViewed);
                    }

                    if (existingViewed.indexOf(currentProductId) == -1) {
                        existingViewed.push(currentProductId);

                        if (existingViewed.length > 3)
                            existingViewed = existingViewed.slice(Math.max(existingViewed.length - 4, 1));

                        window.localStorage.setItem('recentlyViewed', JSON.stringify(existingViewed));
                    } else {
                        var uniqueNames = [];

                        $.each(existingViewed, function (i, el) {
                            if ($.inArray(el, uniqueNames) === -1) uniqueNames.push(el);
                        });

                        uniqueNames.push(currentProductId);

                        uniqueNames.splice(uniqueNames.indexOf(currentProductId), 1);

                        window.localStorage.setItem('recentlyViewed', JSON.stringify(uniqueNames));
                    }
                },

                getReviewHtml(review) {
                    let html = ``;
                    for (let i = 0; i < review; i++) {
                        html += `<i class="fas fa-star"></i>`;
                    }
                    for (let i = 0; i < (5 - review); i++) {
                        html += `<i class="fal fa-star"></i>`;
                    }
                    return html;
                },

                getCurrentDate() {
                    const today = new Date();
                    let dd = today.getDate(),
                        mm = today.getMonth() + 1,
                        yyyy = today.getFullYear();
                    if (dd < 10) dd = '0' + dd;
                    if (mm < 10) mm = '0' + mm;
                    return `${yyyy}-${mm}-${dd}`
                },

                getSellerInfo(product) {
                    if (product.seller && product.seller.id > 0) {
                        let options = {
                            sellerId: product.seller.id,
                        }
                        options.productId = product.product_id;
                        options.isOwner = 1;
                        return options;
                    }
                    return null;
                },
                isRecentlyAdded(date) {
                    let now = new Date();
                    let productDate = new Date(date);
                    return (30 - Math.floor((now.getTime() - productDate.getTime()) / (1000 * 3600 * 24))) >= 0;
                },

                //adding sticky on scroll
                handleSCroll(event) {
                    let eventWrapper = document.querySelector("#event-product-wrapper");
                    if (window.scrollY > 120) {
                        eventWrapper.classList.add('event-product--sticky');
                    } else {
                        eventWrapper.classList.remove('event-product--sticky');
                    }
                },
                getFormattedDate(date) {
                    var myDate = new Date(date);
                    var day = myDate.getDate();
                    var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                    var dayName = days[myDate.getDay()];
                    var month = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"][myDate.getMonth()];
                    return dayName+', ' +month + '. ' + day + ', ' + myDate.getFullYear();
                },
                formatAMPM: function (date) {
                    var date = new Date(date);
                    var hours = date.getHours();
                    var minutes = date.getMinutes();
                    var ampm = hours >= 12 ? 'pm' : 'am';
                    hours = hours % 12;
                    hours = hours ? hours : 12; // the hour '0' should be '12'
                    minutes = minutes < 10 ? '0' + minutes : minutes;
                    var strTime = hours + ':' + minutes + ' ' + ampm;
                    return strTime;
                },
                validateTicketRestriction: function (restrictWhenAvailable,restrictionDate){
                    if(restrictWhenAvailable){
                        if (restrictionDate) {
                            const restrictDate = new Date(restrictionDate);
                            const currentDate = new Date('<?php echo date('Y-m-d') ?>');
                            if (restrictDate > currentDate) {
                                return true;
                            }
                        }
                    }
                   return false;
                },
                getSessions: function(){
                    this.sessions=[];
                  if(typeof this.bookingProduct.default_slot !='undefined'){
                      if(this.bookingProduct.default_slot.repetition_type=='weekly' ){
                          var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                          let dayName='';

                          if(this.bookingProduct.default_slot.repeat_until_value){
                              let i=0;
                              for (var d = new Date(this.bookingProduct.default_slot.start_date); d <= new Date(this.bookingProduct.default_slot.repeat_until_value); d.setDate(d.getDate() + 1)) {
                                  dayName = days[d.getDay()];
                                  if(this.bookingProduct.default_slot.repetition_sequence.indexOf(dayName) > -1 ){
                                      this.sessions.push({'day':dayName,'date':d.toString(), 'slots': JSON.parse(JSON.stringify(this.bookingProduct.default_slot.slots))});
                                      for( let j=0; j < this.sessions[i].slots.length; j++){
                                          this.sessions[i].slots[j].day=d.toString();
                                      }
                                      i+=1;
                                  }
                              }
                          }
                          if(this.bookingProduct.default_slot.repeat_until_number){
                              let counter=0;
                              let i=0;
                              let slots=this.bookingProduct.default_slot.slots;

                              for (let d = new Date(this.bookingProduct.default_slot.start_date); d <= new Date(this.bookingProduct.default_slot.start_date).setDate(d.getDate() + 200) ; d.setDate(d.getDate() + 1)) {
                                  if(counter ==this.bookingProduct.default_slot.repeat_until_number){
                                      break;
                                  }
                                  dayName = days[d.getDay()];
                                  if(this.bookingProduct.default_slot.repetition_sequence.indexOf(dayName) > -1 ){
                                      for( let j=0; j < slots.length; j++){
                                          slots[j].day=d.toString();
                                      }
                                      this.sessions.push({'day':dayName,'date':d.toString(), 'slots': JSON.parse(JSON.stringify(this.bookingProduct.default_slot.slots))});

                                      for( let j=0; j < this.sessions[i].slots.length; j++){
                                          this.sessions[i].slots[j].day=d.toString();
                                      }
                                      i+=1;
                                      counter+=1;
                                  }
                              }
                          }
                      }
                      if(this.bookingProduct.default_slot.repetition_type=='monthly' ) {
                          var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                          var months = ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];
                          let dayName = '';
                          let monthName = '';

                          if (this.bookingProduct.default_slot.repeat_until_value) {
                              let i = 0;
                              let counter = 0;
                              for (var d = new Date(this.bookingProduct.default_slot.start_date); d <= new Date(this.bookingProduct.default_slot.repeat_until_value); d.setMonth(d.getMonth() + 1)) {
                                  dayName = days[d.getDay()];
                                  monthName = months[d.getMonth()];
                                  if (this.bookingProduct.default_slot.repetition_sequence.indexOf(monthName) > -1) {
                                      this.sessions.push({
                                          'day': dayName,
                                          'date': d.toString(),
                                          'slots': JSON.parse(JSON.stringify(this.bookingProduct.default_slot.slots))
                                      });
                                      for (let j = 0; j < this.sessions[i].slots.length; j++) {
                                          this.sessions[i].slots[j].day = d.toString();
                                      }
                                      i += 1;
                                      counter += 1;
                                  }
                              }
                          }

                          if (this.bookingProduct.default_slot.repeat_until_number) {
                              let i = 0;
                              let counter = 0;
                              const currentDay = new Date(this.bookingProduct.default_slot.start_date);
                              for (var d = new Date(this.bookingProduct.default_slot.start_date); d <= new Date(currentDay.setMonth(currentDay.getMonth() + 500)); d.setMonth(d.getMonth() + 1)) {
                                  if (counter == this.bookingProduct.default_slot.repeat_until_number) {
                                      break;
                                  }

                                  dayName = days[d.getDay()];
                                  monthName = months[d.getMonth()];
                                  if (this.bookingProduct.default_slot.repetition_sequence.indexOf(monthName) > -1) {
                                      this.sessions.push({
                                          'day': dayName,
                                          'date': d.toString(),
                                          'slots': JSON.parse(JSON.stringify(this.bookingProduct.default_slot.slots))
                                      });
                                      for (let j = 0; j < this.sessions[i].slots.length; j++) {
                                          this.sessions[i].slots[j].day = d.toString();
                                      }
                                      i += 1;
                                      counter += 1;
                                  }
                              }
                          }
                      }
                  }
                },
                getEventRangeDate: function(){
                    var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                    var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                    let start='';
                    let end='';
                    if (this.sessions.length > 0) {
                        start=new Date(this.sessions[0].slots[0].day);
                        end=new Date(this.sessions[this.sessions.length - 1].slots[this.sessions[this.sessions.length - 1].slots.length - 1].day);
                        if(start.getMonth()==end.getMonth() && start.getFullYear()==end.getFullYear()){
                            if(start.getDate()!=end.getDate()){
                                return months[start.getMonth()]+' '+start.getDate()+'-'+end.getDate()+', '+start.getFullYear();
                            }else{
                                return months[start.getMonth()]+' '+start.getDate()+', '+start.getFullYear();
                            }
                        }else{
                            return months[start.getMonth()]+' '+start.getDate()+', '+start.getFullYear() +' - ' +months[end.getMonth()]+' '+end.getDate()+', '+end.getFullYear();
                        }
                    }
                    if(this.sessions.length == 0){
                        start=new Date(this.defaultSlots.slots[0].day);
                        end=new Date(this.defaultSlots.slots[this.defaultSlots.slots.length - 1].day);
                        if(start.getMonth()==end.getMonth() && start.getFullYear()==end.getFullYear()){
                            if(start.getDate()!=end.getDate()){
                                return months[start.getMonth()]+' '+start.getDate()+'-'+end.getDate()+', '+start.getFullYear();
                            }else{
                                return months[start.getMonth()]+' '+start.getDate()+', '+start.getFullYear();
                            }
                        }else{
                            return months[start.getMonth()]+' '+start.getDate()+', '+start.getFullYear() +' - ' +months[end.getMonth()]+' '+end.getDate()+', '+end.getFullYear();
                        }

                    }
                }
            },
            watch: {

            }
        });

        window.onload = function () {
            var thumbList = document.getElementsByClassName('thumb-list')[0];
            var thumbFrame = document.getElementsByClassName('thumb-frame');
            var productHeroImage = document.getElementsByClassName('product-hero-image')[0];

            if (thumbList && productHeroImage) {
                for (let i = 0; i < thumbFrame.length; i++) {
                    thumbFrame[i].style.height = (productHeroImage.offsetHeight / 4) + "px";
                    thumbFrame[i].style.width = (productHeroImage.offsetHeight / 4) + "px";
                }

                if (screen.width > 720) {
                    thumbList.style.width = (productHeroImage.offsetHeight / 4) + "px";
                    thumbList.style.minWidth = (productHeroImage.offsetHeight / 4) + "px";
                    thumbList.style.height = productHeroImage.offsetHeight + "px";
                }
            }

            window.onresize = function () {
                if (thumbList && productHeroImage) {
                    for (let i = 0; i < thumbFrame.length; i++) {
                        thumbFrame[i].style.height = (productHeroImage.offsetHeight / 4) + "px";
                        thumbFrame[i].style.width = (productHeroImage.offsetHeight / 4) + "px";
                    }
                    if (screen.width > 720) {
                        thumbList.style.width = (productHeroImage.offsetHeight / 4) + "px";
                        thumbList.style.minWidth = (productHeroImage.offsetHeight / 4) + "px";
                        thumbList.style.height = productHeroImage.offsetHeight + "px";
                    }
                }
            }
        };
    </script>

@endpush