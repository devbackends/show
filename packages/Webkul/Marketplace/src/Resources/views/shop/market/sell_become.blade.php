<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <title>@yield('page_title')</title>
    <link rel="stylesheet" href="{{ asset('themes/market/assets/css/market.css') }}" />
    @if (core()->getCurrentLocale()->direction == 'rtl')

    @endif

    @if ($favicon = core()->getCurrentChannel()->favicon_url)
        <link rel="icon" sizes="16x16" href="{{ $favicon }}"/>
    @else
        <link rel="icon" sizes="16x16" href="{{ asset('images/favicon.png') }}"/>
    @endif

    @yield('head')

    @section('seo')
        <meta name="description" content="{{ core()->getCurrentChannel()->description }}"/>
    @show

    @stack('css')

    {!! view_render_event('bagisto.shop.layout.head') !!}


</head>

<body @if (core()->getCurrentLocale()->direction == 'rtl') class="rtl" @endif>
{!! view_render_event('bagisto.shop.layout.body.before') !!}

<div id="app">
    <div class="main-container-wrapper">

        <!-- HEADER -->
        <!-- HEADER TopBar -->
        <nav id="top" class="header-topbar">
            <div class="row no-margin">
                <div class="col-sm-6 ">
                    <div class="pull-left header-topbar__locale">
                        <div class="dropdown">
                            <div class="locale-icon">
                                <span class="icon american-english-icon"></span>
                            </div>
                            <select class="locale-switcher styled-select">
                                <option value="en" selected="selected">English</option>
                            </select>
                            <div class="select-icon-container">
                                <i class="select-icon language-arrow-icon fal fa-angle-down"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 header-topbar__login">
                    <div class="dropdown float-right">
                        <div id="account">
                            <div class="welcome-content dropdown-toggle" id="profileMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fal fa-user-circle"></i>
                                <span class="title">Welcome, Guest!</span>
                                <span class="select-icon fal fa-angle-down"></span>
                            </div>
                            <div class="dropdown-menu dropdown-menu-right text-right" aria-labelledby="profileMenuButton">
                                <a class="dropdown-item" href="user-account_profile.html">My Account</a>
                                <a class="dropdown-item" href="marketplace_profile.html">Marketplace</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="home-out.html">Sign Out</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <!-- END HEADER TopBar -->
        <!-- HEADER MiddleBar -->
        <header id="sticky-header" class="sticky-header header-middle">
            <div id="header-container" class="row col-12 remove-padding-margin velocity-divide-page">
                <a href="home-in.html" class="brand-logo"></a>
                <div class="row no-margin right searchbar">
                    <div class="col-7 no-padding input-group">
                        <form role="search" id="search-form" action="/">
                            <div role="toolbar" class="btn-toolbar full-width">
                                <div class="row header-search-container">
                                    <div class="geolocation-container col-md-4 no-padding">
                                        <input id="geolocation_search" name="geolocation_search" type="search" placeholder="City, State or Zip code" class="form-control geolocation_search">
                                        <a href="#" class="link"><i class="far fa-location-arrow"></i>Current location</a>
                                    </div>
                                    <div class="selectdiv col-md-3  no-padding">
                                        <select name="category" class="form-control fs13 styled-select">
                                            <option value="">All Categories</option>
                                            <option value="44">Booster</option>
                                        </select>
                                        <div class="select-icon-container">
                                            <span class="select-icon fal fa-angle-down"></span>
                                        </div>
                                    </div>
                                    <div class="full-width col-md-5 no-padding">
                                        <input id="search_products" required="required" name="term" type="search" placeholder="Search for items or shows" class="form-control ui-autocomplete-input" autocomplete="off">
                                        <button type="submit" id="header-search-icon" class="btn">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-5">
                        <a href="#" class="wishlist-btn">
                            <i class="far fa-heart"></i>
                            <span>Wishlist</span>
                            <!-- <span class="badge">2</span> -->
                        </a>
                        <a href="#" class="compare-btn">
                            <i class="far fa-compress-alt"></i>
                            <span>Compare</span>
                            <!-- <span class="badge">2</span> -->
                        </a>
                        <div class="mini-cart-container pull-right">
                            <div class="dropdown disable-active">
                                <button type="button" class="btn btn-link disable-box-shadow cursor-not-allowed" id="mini-cart" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <div class="mini-cart-content">
                                        <i class="far fa-shopping-cart"></i>
                                        <span class="badge">2</span>
                                        <span class="cart-text">Cart</span>
                                    </div>
                                    <div class="down-arrow-container">
                                        <span class="fal fa-angle-down"></span>
                                    </div>
                                </button>
                                <div class="dropdown-menu dropdown-menu--cart dropdown-menu-right" aria-labelledby="mini-cart">
                                    <div class="cart-dropdown__content">
                                        <div class="cart-dropdown__list">
                                            <div class="cart-dropdown__content-head">Products from Seller name one</div>
                                            <div class="cart-dropdown__list-item">
                                                <div class="image">
                                                    <a href="#">
                                                        <img src="../images/list-item.jpg" alt="Product image">
                                                    </a>
                                                </div>
                                                <div>3 x</div>
                                                <div>
                                                    <p>22 LR 10+1 5 BARREL 2.2 LBS</p>
                                                    <p><b>$4567.00</b></p>
                                                </div>
                                                <div class="ml-auto mr-3">
                                                    <a href="#">
                                                        <i class="far fa-trash-alt"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="cart-dropdown__list-item">
                                                <div class="image">
                                                    <a href="#">
                                                        <img src="../images/list-item.jpg" alt="Product image">
                                                    </a>
                                                </div>
                                                <div>3 x</div>
                                                <div>
                                                    <p>22 LR 10+1 5 BARREL 2.2 LBS</p>
                                                    <p><b>$4567.00</b></p>
                                                </div>
                                                <div class="ml-auto mr-3">
                                                    <a href="#">
                                                        <i class="far fa-trash-alt"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="cart-dropdown__content-bottom">
                                                <small>Subtotal from this seller</small>
                                                <span>$999999.00</span>
                                                <a href="#" class="btn btn-primary">Checkout</a>
                                            </div>
                                        </div>
                                        <div class="cart-dropdown__list">
                                            <div class="cart-dropdown__content-head">Products from Seller name two</div>
                                            <div class="cart-dropdown__list-item">
                                                <div class="image">
                                                    <a href="#">
                                                        <img src="../images/list-item.jpg" alt="Product image">
                                                    </a>
                                                </div>
                                                <div>3 x</div>
                                                <div>
                                                    <p>22 LR 10+1 5 BARREL 2.2 LBS</p>
                                                    <p><b>$4567.00</b></p>
                                                </div>
                                                <div class="ml-auto mr-3">
                                                    <a href="#">
                                                        <i class="far fa-trash-alt"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="cart-dropdown__list-item">
                                                <div class="image">
                                                    <a href="#">
                                                        <img src="../images/list-item.jpg" alt="Product image">
                                                    </a>
                                                </div>
                                                <div>3 x</div>
                                                <div>
                                                    <p>22 LR 10+1 5 BARREL 2.2 LBS</p>
                                                    <p><b>$4567.00</b></p>
                                                </div>
                                                <div class="ml-auto mr-3">
                                                    <a href="#">
                                                        <i class="far fa-trash-alt"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="cart-dropdown__content-bottom">
                                                <small>Subtotal from this seller</small>
                                                <span>$999999.00</span>
                                                <a href="#" class="btn btn-primary">Checkout</a>
                                            </div>
                                        </div>
                                        <div class="cart-dropdown__list">
                                            <div class="cart-dropdown__content-head">Products from Seller name three</div>
                                            <div class="cart-dropdown__list-item">
                                                <div class="image">
                                                    <a href="#">
                                                        <img src="../images/list-item.jpg" alt="Product image">
                                                    </a>
                                                </div>
                                                <div>3 x</div>
                                                <div>
                                                    <p>22 LR 10+1 5 BARREL 2.2 LBS</p>
                                                    <p><b>$4567.00</b></p>
                                                </div>
                                                <div class="ml-auto mr-3">
                                                    <a href="#">
                                                        <i class="far fa-trash-alt"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="cart-dropdown__list-item">
                                                <div class="image">
                                                    <a href="#">
                                                        <img src="../images/list-item.jpg" alt="Product image">
                                                    </a>
                                                </div>
                                                <div>3 x</div>
                                                <div>
                                                    <p>22 LR 10+1 5 BARREL 2.2 LBS</p>
                                                    <p><b>$4567.00</b></p>
                                                </div>
                                                <div class="ml-auto mr-3">
                                                    <a href="#">
                                                        <i class="far fa-trash-alt"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="cart-dropdown__content-bottom">
                                                <small>Subtotal from this seller</small>
                                                <span>$999999.00</span>
                                                <a href="#" class="btn btn-primary">Checkout</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer__actions cart-dropdown__footer">
                                        <a href="#">View Shopping Cart</a>
                                        <span>Total: $999999.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- END HEADER MiddleBar -->
        <!-- END HEADER -->

        <!-- MODALS -->
        <!-- MODAL CART -->
        <div class="modal modal--cart fade" id="cartModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="modal-head">
                            <i class="far fa-cart-plus"></i>
                            <h3>Added  to cart</h3>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <a href="#" class="btn btn-outline-gray" data-dismiss="modal">Continue shopping</a>
                            </div>
                            <div class="col-6">
                                <a href="#" class="btn btn-primary">Proceed to checkout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END MODAL CART -->

        <!-- MODAL DELETE IMAGE -->
        <div class="modal normal fade" id="imageDelete" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="modal-head">
                            <i class="far fa-trash-alt"></i>
                            <h3>Are you sure you want to delete this image?</h3>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="#" class="btn btn-outline-gray" data-dismiss="modal">No, cancel</a>
                            <a href="#" class="btn btn-primary">Yes, delete</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END MODAL DELETE IMAGE -->

        <!-- MODAL EDIT REVIEW -->
        <div class="modal modal--review fade" id="editReview" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="fal fa-times"></i>
                        </button>
                        <div class="modal-head">
                            <i class="far fa-star"></i>
                            <small>Edit review for</small>
                            <h3></h3> {{--here set  seller shop title--}}
                            <p>Product bought: <b></b></p> {{--here set product description--}}
                        </div>
                        <form>
                            <div class="form-group text-center">
                                <div class="rate">
              <span class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="far fa-star"></i>
                <i class="far fa-star"></i>
              </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" value="Good product">
                            </div>
                            <div class="form-group">
                                <textarea class="form-control">The most highly evolved plants use seeds to reproduce and spread. Seeds have tough outer coatings and carry a large food store to help the new individual to grow, More primitive plants use simpler mechanisms. These usually involve spores which have a seed-like role but are very small and usually consist of just a cell.</textarea>
                            </div>
                            <div class="text-right">
                                <input type="submit" class="btn btn-primary" value="Save review">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END MODAL EDIT REVIEW -->

        <!-- MODAL CONTACT SELLER -->
        <div class="modal fade" id="contactSeller" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="fal fa-times"></i>
                        </button>
                        <div class="modal-head">
                            <i class="fal fa-comment-alt-lines"></i>
                            <h3>Contact </h3> {{--here set seller shop title--}}
                        </div>
                        <form>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Name">
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Subject">
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" placeholder="Message"></textarea>
                            </div>
                            <div class="text-right">
                                <input type="submit" class="btn btn-primary" value="Send message">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL CONTACT SELLER -->

        <!-- MODAL ADD NEW CREDIT CARD -->
        <div id="newCardPopup" tabindex="-1" role="dialog" aria-labelledby="credit-card" class="modal fade credit-card-modal modal--add-card" aria-hidden="true">
            <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="fal fa-times"></i>
                        </button>
                        <div class="modal-head">
                            <h3>Add a new credit card</h3>
                        </div>
                        <form action="/">
                            <div class="save-card-controls">
                                <div class="row no-gutters">
                                    <div class="col-12 save-card-controls-col">
                                        <input type="text" class="form-control" placeholder="Card Number">
                                        <i class="far fa-credit-card"></i>
                                    </div>
                                </div>
                                <div class="row no-gutters">
                                    <div class="col-6 save-card-controls-col">
                                        <input type="text" class="form-control" placeholder="MM/YY">
                                        <i class="far fa-calendar-alt"></i>
                                    </div>
                                    <div class="col-6 save-card-controls-col">
                                        <input type="text" class="form-control" placeholder="CVC">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="save-card-checkbox">
            <span class="form-check">
              <input type="checkbox" id="save_credit_card" name="save_credit_card" class="ml0">
              <label for="save_credit_card" class="custom-checkbox-view form-check-label">Save card</label>
            </span>
                            </div>
                            <div class="col-12 text-right">
                                <button type="submit" id="submit_btn" class="btn btn-primary">Add a new credit card</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END MODAL ADD NEW CREDIT CARD -->

        <!-- MODAL ADD NEW ADDRESS -->
        <div id="newAddressPopup" tabindex="-1" role="dialog" aria-labelledby="select-ffl" class="modal fade modal--add-address" aria-hidden="true">
            <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close shipping-modal">
                            <i aria-hidden="true" class="far fa-times"></i>
                        </button>
                        <div class="modal-head">
                            <h3>Add new address</h3>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group form-field">
                                    <label for="first_name" class="mandatory">First name</label>
                                    <input placeholder="First name" type="text" id="first_name" name="first_name" class="form-control" aria-required="true" aria-invalid="false">
                                    <span class="control-error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-field">
                                    <label for="last_name" class="mandatory">Last name</label>
                                    <input placeholder="Last name" type="text" id="last_name" name="last_name" class="form-control" aria-required="true" aria-invalid="false">
                                    <span class="control-error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-field">
                                    <label for="email" class="mandatory">Email</label>
                                    <input placeholder="Email" type="text" id="email" name="email" class="form-control" aria-required="true" aria-invalid="false"> <span class="control-error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-field">
                                    <label for="phone" class="mandatory">Phone</label>
                                    <input placeholder="Phone" type="text" id="phone" name="phone" class="form-control" aria-required="true" aria-invalid="false"> <span class="control-error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h5 class="modal-section-title">Address</h3>
                            </div>
                            <div class="col-12">
                                <div class="form-field form-group">
                                    <label for="shipping_address_0" class="mandatory">Street</label>
                                    <input type="text" placeholder="Street" id="shipping_address_0" name="shipping[address1][]" class="form-control" aria-required="true" aria-invalid="false">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-field form-group">
                                    <label for="shipping[city]" class="mandatory">City</label>
                                    <input type="text" placeholder="City" id="shipping[city]" name="shipping[city]" class="form-control" aria-required="true" aria-invalid="false">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-field form-group">
                                    <label for="shipping[state]" class="mandatory">State</label>
                                    <select id="shipping[state]" name="shipping[state]" class="form-control" aria-required="true" aria-invalid="false">
                                        <option value="">State</option>
                                        <option value="AL">Alabama</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-field form-group">
                                    <label for="shipping[postcode]" class="mandatory">Zip/Postcode</label>
                                    <input type="text" placeholder="Zip/Postcode" id="shipping[postcode]" name="shipping[postcode]" class="form-control" aria-required="true" aria-invalid="false">
                                </div>
                            </div>
                        </div>
                        <div class="modal-actions d-flex flex-row-reverse align-center">
                            <div class="col-sm-3 text-right p-0 mt-1">
                                <button type="button" class="btn btn-primary ml-auto">Add new address</button>
                            </div>
                            <div class="text-right col-sm-9">
                                <p class="text-danger mb-0">You have entered an identical address to one already on your profile, please select the existing address or enter a new one.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END MODAL ADD NEW ADDRESS -->

        <!-- MODAL SELECT ADDRESS -->
        <div id="selectAddressPopup" tabindex="-1" role="dialog" class="modal fade modal--select-address" aria-hidden="true">
            <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close shipping-modal">
                            <i aria-hidden="true" class="far fa-times"></i>
                        </button>
                        <div class="modal-head">
                            <h3>Select shipping address</h3>
                        </div>
                        <div class="row d-flex align-items-stretch">
                            <div class="col-lg-6 holder_item checkout__form-customer-item">
                                <div class="card checkout__form-customer-card active">
                                    <div class="card-body">
                                        <div class="checkout__form-customer-card-radio">
                                            <div class="form-group form-check">
                                                <div class="radio-button">
                                                    <div class="radio-button__input">
                                                        <input type="radio" name="billing" value="12" id="billing-21">
                                                        <label for="billing-21" class="form-check-label"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="checkout__form-customer-card-info">
                                            <p class="card-title checkout__form-customer-card-name">Justin Phelan,</p>
                                            <ul type="none" class="checkout__form-customer-card-address">
                                                <li>1234 Campbellsville Pike,</li>
                                                <li>Culleoka, TN, 38451</li>
                                                <li>Contact: 123-456-7890</li>
                                            </ul>
                                        </div>
                                        <div>
                                            <span class="control-error"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 holder_item checkout__form-customer-item">
                                <div class="card checkout__form-customer-card active">
                                    <div class="card-body">
                                        <div class="checkout__form-customer-card-radio">
                                            <div class="form-group form-check">
                                                <div class="radio-button">
                                                    <div class="radio-button__input">
                                                        <input type="radio" name="billing" value="12" id="billing-22">
                                                        <label for="billing-22" class="form-check-label"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="checkout__form-customer-card-info">
                                            <p class="card-title checkout__form-customer-card-name">Justin Phelan,</p>
                                            <ul type="none" class="checkout__form-customer-card-address">
                                                <li>1234 Campbellsville Pike,</li>
                                                <li>Culleoka, TN, 38451</li>
                                                <li>Contact: 123-456-7890</li>
                                            </ul>
                                        </div>
                                        <div>
                                            <span class="control-error"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 holder_item checkout__form-customer-item">
                                <div class="card checkout__form-customer-card active">
                                    <div class="card-body">
                                        <div class="checkout__form-customer-card-radio">
                                            <div class="form-group form-check">
                                                <div class="radio-button">
                                                    <div class="radio-button__input">
                                                        <input type="radio" name="billing" value="12" id="billing-23">
                                                        <label for="billing-23" class="form-check-label"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="checkout__form-customer-card-info">
                                            <p class="card-title checkout__form-customer-card-name">Justin Phelan,</p>
                                            <ul type="none" class="checkout__form-customer-card-address">
                                                <li>1234 Campbellsville Pike,</li>
                                                <li>Culleoka, TN, 38451</li>
                                                <li>Contact: 123-456-7890</li>
                                            </ul>
                                        </div>
                                        <div>
                                            <span class="control-error"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 checkout__form-customer-item">
                                <div data-toggle="modal" data-target="#newAddressPopup" data-dismiss="modal" aria-label="Close" class="card h-100 cursor-pointer">
                                    <div class="card-body add-address-button text-center d-flex align-items-center justify-content-center">
                                        <div>
                                            <i class="far fa-plus"></i>
                                            <p>Add a new address</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="button" class="btn btn-primary">Select shipping address</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END MODAL SELECT ADDRESS -->

        <!-- MODAL FFL SELECT -->
        <div id="fflModal" tabindex="-1" role="dialog" aria-labelledby="select-ffl" aria-hidden="true" class="modal fade modal--select-ffl">
            <div role="document" class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-body modal__ffl">
                        <div class="row">
                            <div class="col-md-5 pr-md-0">
                                <div class="modal__ffl-left">
                                    <button type="button" data-dismiss="modal" aria-label="Close" class="close shipping-modal">
                                        <i aria-hidden="true" class="far fa-times"></i>
                                    </button>
                                    <h3 id="select-ffl" class="modal-head">Choose ffl pickup location</h3>
                                    <h4 class="modal__ffl-preferred-text">2AGunshow preferred FFL</h4>
                                    <div class="modal__ffl-search input-group">
                                        <input type="text" placeholder="Filter location by name" aria-describedby="searchIcon" class="form-control filter-ffl">
                                        <div class="input-group-append">
                  <span id="searchIcon" class="input-group-text">
                    <i aria-hidden="true" class="fa fa-search"></i>
                  </span>
                                        </div>
                                    </div>
                                    <div class="modal__ffl-list">
                                        <div class="modal__ffl-list-item recommended">
                                            <div class="modal__ffl-list-item-overlay justify-content-center align-items-center">
                                                <button data-dismiss="modal" class="btn btn-sm btn-primary">Select</button>
                                            </div>
                                            <div>
                                                <div class="modal__ffl-list-item-content">
                                                    <p class="modal__ffl-list-item-name">Red Dot Arms., INC.</p>
                                                    <p class="modal__ffl-list-item-distance">23.2 MILES | <a href="#">You are buying from this seller</a></p>
                                                    <p class="modal__ffl-list-item-address">1600 N MILWAUKEE AVE STES 305, 306, 307, 308. LAKE VILLA, IL 60046</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal__ffl-list-item">
                                            <div class="modal__ffl-list-item-overlay justify-content-center align-items-center">
                                                <button data-dismiss="modal" class="btn btn-sm btn-primary">Select</button>
                                            </div>
                                            <div>
                                                <div class="modal__ffl-list-item-content">
                                                    <p class="modal__ffl-list-item-name">Red Dot Arms., INC.</p>
                                                    <p class="modal__ffl-list-item-distance">2.5 MILES</p>
                                                    <p class="modal__ffl-list-item-address">1600 N MILWAUKEE AVE STES 305, 306, 307, 308. LAKE VILLA, IL 60046</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal__ffl-list-item">
                                            <div class="modal__ffl-list-item-overlay justify-content-center align-items-center">
                                                <button data-dismiss="modal" class="btn btn-sm btn-primary">Select</button>
                                            </div>
                                            <div>
                                                <div class="modal__ffl-list-item-content">
                                                    <p class="modal__ffl-list-item-name">Red Dot Arms., INC.</p>
                                                    <p class="modal__ffl-list-item-distance">12.0 MILES</p>
                                                    <p class="modal__ffl-list-item-address">1600 N MILWAUKEE AVE STES 305, 306, 307, 308. LAKE VILLA, IL 60046</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal__ffl-list-item">
                                            <div class="modal__ffl-list-item-overlay justify-content-center align-items-center">
                                                <button data-dismiss="modal" class="btn btn-sm btn-primary">Select</button>
                                            </div>
                                            <div>
                                                <div class="modal__ffl-list-item-content">
                                                    <p class="modal__ffl-list-item-name">Red Dot Arms., INC.</p>
                                                    <p class="modal__ffl-list-item-distance">17.6 MILES</p>
                                                    <p class="modal__ffl-list-item-address">1600 N MILWAUKEE AVE STES 305, 306, 307, 308. LAKE VILLA, IL 60046</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="map" class="col-md-7 d-none d-md-block">
                                <div class="dummy-content">
                                    <h1>Map</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END MODAL FFL SELECT -->
        <!-- END MODALS -->

        <div class="main-content-wrapper">
            <!-- HEADER CategoryNav -->
            <div id="category-menu-header" class="row vc-header header-shadow active">
                <div class="vc-small-screen container">
                    <div class="row">
                        <div class="col-6">
                            <div class="nav-container">
                                <div class="wrapper">
                                    <div class="greeting drawer-section">
                                        <i class="fal fa-user-circle profile"></i>
                                        <span>
                <a href="#">Welcome, Guest !</a>
                <i class="far fa-times close js-close-nav"></i>
              </span>
                                    </div>
                                    <ul class="list-unstyled category-wrapper">
                                        <li>
                                            <a href="#">
                                                <span>Handguns</span>
                                            </a>
                                            <i class="far fa-angle-right arrow-right"></i>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span>Rifles</span>
                                            </a>
                                            <i class="far fa-angle-right arrow-right"></i>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span>Ammo</span>
                                            </a>
                                            <i class="far fa-angle-right arrow-right"></i>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span>Single shot</span>
                                            </a>
                                            <i class="far fa-angle-right arrow-right"></i>
                                        </li>
                                    </ul>
                                    <ul class="list-unstyled meta-wrapper">
                                        <li>
                                            <div class="language-logo-wrapper">
                                                <span class="icon american-english-icon"></span>
                                            </div>
                                            <span>English</span>
                                            <i class="far fa-angle-right arrow-right"></i>
                                        </li>
                                        <li>
                                            <a href="sign-up.html" class="btn btn-outline-primary">
                                                <span>Sign up</span>
                                            </a>
                                            <a href="sign-in.html" class="btn btn-primary">
                                                <span>Sign In</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="close-wrapper"></div>
                            </div>

                            <a class="hamburger-wrapper js-toggle-nav" href="#">
                                <i class="far fa-bars"></i>
                            </a>
                            <a href="#" class="left brand-logo"></a>
                        </div>
                        <div class="right-vc-header col-6 dropdown">
                            <a href="#" class="js-toggle-search">
                                <i class="far fa-search"></i>
                            </a>
                            <a href="#" class="wishlist-btn">
                                <i class="far fa-heart"></i>
                            </a>
                            <a href="#" class="compare-btn">
                                <i class="far fa-compress-alt"></i>
                            </a>
                            <a href="#" id="mini-cart-mobile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <div class="badge-wrapper">
                                    <span class="badge">0</span>
                                </div>
                                <i class="far fa-shopping-cart"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu--cart dropdown-menu-right" aria-labelledby="mini-cart-mobile">
                                <div class="cart-dropdown__content">
                                    <div class="cart-dropdown__list">
                                        <div class="cart-dropdown__content-head">Products from Seller name one</div>
                                        <div class="cart-dropdown__list-item">
                                            <div class="image">
                                                <a href="#">
                                                    <img src="../images/list-item.jpg" alt="Product image">
                                                </a>
                                            </div>
                                            <div>3 x</div>
                                            <div>
                                                <p>22 LR 10+1 5 BARREL 2.2 LBS</p>
                                                <p><b>$4567.00</b></p>
                                            </div>
                                            <div class="ml-auto mr-3">
                                                <a href="#">
                                                    <i class="far fa-trash-alt"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="cart-dropdown__list-item">
                                            <div class="image">
                                                <a href="#">
                                                    <img src="../images/list-item.jpg" alt="Product image">
                                                </a>
                                            </div>
                                            <div>3 x</div>
                                            <div>
                                                <p>22 LR 10+1 5 BARREL 2.2 LBS</p>
                                                <p><b>$4567.00</b></p>
                                            </div>
                                            <div class="ml-auto mr-3">
                                                <a href="#">
                                                    <i class="far fa-trash-alt"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="cart-dropdown__content-bottom">
                                            <small>Subtotal from this seller</small>
                                            <span>$999999.00</span>
                                            <a href="#" class="btn btn-primary">Checkout</a>
                                        </div>
                                    </div>
                                    <div class="cart-dropdown__list">
                                        <div class="cart-dropdown__content-head">Products from Seller name two</div>
                                        <div class="cart-dropdown__list-item">
                                            <div class="image">
                                                <a href="#">
                                                    <img src="../images/list-item.jpg" alt="Product image">
                                                </a>
                                            </div>
                                            <div>3 x</div>
                                            <div>
                                                <p>22 LR 10+1 5 BARREL 2.2 LBS</p>
                                                <p><b>$4567.00</b></p>
                                            </div>
                                            <div class="ml-auto mr-3">
                                                <a href="#">
                                                    <i class="far fa-trash-alt"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="cart-dropdown__list-item">
                                            <div class="image">
                                                <a href="#">
                                                    <img src="../images/list-item.jpg" alt="Product image">
                                                </a>
                                            </div>
                                            <div>3 x</div>
                                            <div>
                                                <p>22 LR 10+1 5 BARREL 2.2 LBS</p>
                                                <p><b>$4567.00</b></p>
                                            </div>
                                            <div class="ml-auto mr-3">
                                                <a href="#">
                                                    <i class="far fa-trash-alt"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="cart-dropdown__content-bottom">
                                            <small>Subtotal from this seller</small>
                                            <span>$999999.00</span>
                                            <a href="#" class="btn btn-primary">Checkout</a>
                                        </div>
                                    </div>
                                    <div class="cart-dropdown__list">
                                        <div class="cart-dropdown__content-head">Products from Seller name three</div>
                                        <div class="cart-dropdown__list-item">
                                            <div class="image">
                                                <a href="#">
                                                    <img src="../images/list-item.jpg" alt="Product image">
                                                </a>
                                            </div>
                                            <div>3 x</div>
                                            <div>
                                                <p>22 LR 10+1 5 BARREL 2.2 LBS</p>
                                                <p><b>$4567.00</b></p>
                                            </div>
                                            <div class="ml-auto mr-3">
                                                <a href="#">
                                                    <i class="far fa-trash-alt"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="cart-dropdown__list-item">
                                            <div class="image">
                                                <a href="#">
                                                    <img src="../images/list-item.jpg" alt="Product image">
                                                </a>
                                            </div>
                                            <div>3 x</div>
                                            <div>
                                                <p>22 LR 10+1 5 BARREL 2.2 LBS</p>
                                                <p><b>$4567.00</b></p>
                                            </div>
                                            <div class="ml-auto mr-3">
                                                <a href="#">
                                                    <i class="far fa-trash-alt"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="cart-dropdown__content-bottom">
                                            <small>Subtotal from this seller</small>
                                            <span>$999999.00</span>
                                            <a href="#" class="btn btn-primary">Checkout</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer__actions cart-dropdown__footer">
                                    <a href="#">View Shopping Cart</a>
                                    <span>Total: $999999.00</span>
                                </div>
                            </div>
                        </div>
                        <div class="row no-margin right searchbar">
                            <div class="col-7 no-padding input-group">
                                <form role="search" id="search-form" action="/">
                                    <div role="toolbar" class="btn-toolbar full-width">
                                        <div class="row header-search-container">
                                            <div class="geolocation-container col-md-4 no-padding">
                                                <input id="geolocation_search" name="geolocation_search" type="search" placeholder="City, State or Zip code" class="form-control geolocation_search">
                                            </div>
                                            <div class="selectdiv col-md-3  no-padding">
                                                <select name="category" class="form-control fs13 styled-select">
                                                    <option value="">All Categories</option>
                                                    <option value="44">Booster</option>
                                                </select>
                                                <div class="select-icon-container">
                                                    <span class="select-icon fal fa-angle-down"></span>
                                                </div>
                                            </div>
                                            <div class="full-width col-md-5 no-padding">
                                                <input id="search_products" required="required" name="term" type="search" placeholder="Search for items or shows" class="form-control ui-autocomplete-input" autocomplete="off">
                                                <button type="submit" id="header-search-icon" class="btn">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-5">
                                <a href="#" class="wishlist-btn">
                                    <i class="far fa-heart"></i>
                                    <span>Wishlist</span>
                                </a>
                                <a href="#" class="compare-btn">
                                    <i class="far fa-compress-alt"></i>
                                    <span>Compare</span>
                                </a>
                                <div class="mini-cart-container pull-right">
                                    <div class="dropdown disable-active">
                                        <button type="button" id="mini-cart" class="btn btn-link disable-box-shadow cursor-not-allowed">
                                            <div class="mini-cart-content">
                                                <i class="far fa-shopping-cart"></i>
                                                <span class="cart-text">Cart</span>
                                            </div>
                                            <div class="down-arrow-container">
                                                <span class="fal fa-angle-down"></span>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="buy_category.html" class="main-category left">
                    <i class="far fa-list-ul"></i>
                    <span class="pl5">Shop by Category</span>
                </a>
                <div class="content-list right">
                    <ul class="list-unstyled">
                        <li>
                            <a href="buy.html">Buy</a>
                        </li>
                        <li>
                            <a href="sell_home.html">Sell</a>
                        </li>
                        <li>
                            <a href="shows_browse.html">Gun Shows</a>
                        </li>
                        <li>
                            <a href="#">About</a>
                        </li>
                        <li>
                            <a href="#">Articles</a>
                        </li>
                    </ul>
                </div>
                <a href="sell_become.html" class="link">Start selling on 2AGunShow</a>
            </div>
            <!-- END HEADER CategoryNav -->

            <div class="promo-section promo-section--low" style="background-image: url(../images/bg-promo-become-a-seller.jpg);">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <h1 class="promo-section__title">Become a seller!</h1>
                            <p class="head">Join the community</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SELLER TYPES SECTION -->
            <div class="become-member-section">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12 col-lg-10 col-xl-9">
                            <p class="head">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum est lorem, dictum eleifend justo et, varius suscipit urna. Nullam fermentum velit felis, in lobortis magna vestibulum vel.</p>

                            <ul class="list-unstyled become-member-section__list">
                                <li class="become-member-section__list-item">
                                    <h2>Marketplace <br> vendor</h2>
                                    <p>Typical marketplace sellers include gunshow vendors, individuals who are selling firearm accesories, or unique items and collectibles.</p>
                                    <ol class="list-unstyled">
                                        <li>
                                            <span>Monthly fee</span>
                                            <b>$0</b>
                                            <i class="line"></i>
                                        </li>
                                        <li>
                                            <span>Processing fee</span>
                                            <b>2.9%</b>
                                            <i class="line"></i>
                                        </li>
                                        <li>
                                            <span>Transaction fee</span>
                                            <b>$0.30</b>
                                            <i class="line"></i>
                                        </li>
                                        <li>
                                            <span>Listing fee</span>
                                            <b>$0.99</b>
                                            <i class="line"></i>
                                        </li>
                                        <li>
                                            <span>Commission</span>
                                            <b>2%</b>
                                            <i class="line"></i>
                                        </li>
                                    </ol>
                                    <a href="#" class="btn btn-primary">Learn more!</a>
                                </li>
                                <li class="become-member-section__list-item">
                                    <h2>Merchant <br> seller</h2>
                                    <p>Typical merchant sellers includes gunstores, shooting ranges, influencers who have their own products, etc.</p>
                                    <ol class="list-unstyled">
                                        <li>
                                            <span>Monthly fee</span>
                                            <b>$0</b>
                                        </li>
                                        <li>
                                            <span>Processing fee</span>
                                            <b>2.9%</b>
                                        </li>
                                        <li>
                                            <span>Transaction fee</span>
                                            <b>$0.30</b>
                                        </li>
                                        <li>
                                            <span>Listing fee</span>
                                            <b>$0.99</b>
                                        </li>
                                        <li>
                                            <span>Commission</span>
                                            <b>0%</b>
                                        </li>
                                    </ol>
                                    <a href="#" class="btn btn-primary">Learn more!</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END SELLER TYPES SECTION -->
            <!-- SUBSCRIBE SECTION -->
            <div class="subscribe-section">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-xl-4 col-lg-6">
                            <h4>Stay in the know. <br> Get <span class="text-primary">2agunshow.com</span> offers and news</h4>
                        </div>
                        <div class="col-12 col-xl-4 col-lg-6">
                            <div class="subscribe__form">
                                <div class="subscribe__form-input control-group">
                                    <input class="form-control" type="email" placeholder="Enter your email" name="email" value="" required="required">
                                </div>
                                <a href="#" class="subscribe__form-submit btn btn-outline-primary">Sign up!</a>
                            </div>
                        </div>
                        <div class="col-12 col-xl-4 col-lg-12">
                            <div class="social float-xl-right">
                                <h4 class="social__title">Follow Us On</h4>
                                <ul class="list-unstyled social__menu">
                                    <li class="social__menu-item">
                                        <a href="#" class="social__link">
                                            <i class="fab fa-youtube-square"></i>
                                        </a>
                                    </li>
                                    <li class="social__menu-item">
                                        <a href="#" class="social__link">
                                            <i class="fab fa-facebook-square"></i>
                                        </a>
                                    </li>
                                    <li class="social__menu-item">
                                        <a href="#" class="social__link">
                                            <i class="fab fa-instagram"></i>
                                        </a>
                                    </li>
                                    <li class="social__menu-item">
                                        <a href="#" class="social__link">
                                            <i class="fab fa-twitter-square"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END SUBSCRIBE SECTION -->        </div>

        <!-- FOOTER -->
        <div class="footer">
            <div class="container">
                <div class="footer__top">
                    <div class="row">
                        <div class="col-12 col-md-3">
                            <h3>Customer Resources</h3>
                            <ul class="list-unstyled">
                                <li>
                                    <a href="#">Check On My Order</a>
                                </li>
                                <li>
                                    <a href="#">Contact Customer Service</a>
                                </li>
                                <li>
                                    <a href="#">Shipping</a>
                                </li>
                                <li>
                                    <a href="#">Return and Warranty Policy</a>
                                </li>
                                <li>
                                    <a href="#">Fees</a>
                                </li>
                                <li>
                                    <a href="#">Make an Offer</a>
                                </li>
                                <li>
                                    <a href="#">Guns for Sale</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-md-3">
                            <h3>Seller Resources</h3>
                            <ul class="list-unstyled">
                                <li>
                                    <a href="#">Join the 2agunshow.com Network</a>
                                </li>
                                <li>
                                    <a href="#">Login to My Seller Dashboard</a>
                                </li>
                                <li>
                                    <a href="#">Contact a Rangemaster</a>
                                </li>
                                <li>
                                    <a href="#">Affiliates</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-md-3">
                            <h3>Who we are</h3>
                            <ul class="list-unstyled">
                                <li>
                                    <a href="#">The 2agunshow.com Story</a>
                                </li>
                                <li>
                                    <a href="#">The 2agunshow.com Promise</a>
                                </li>
                                <li>
                                    <a href="#">2agunshow.com Careers</a>
                                </li>
                                <li>
                                    <a href="#">Our Values</a>
                                </li>
                                <li>
                                    <a href="#">Ambassador Program</a>
                                </li>
                                <li>
                                    <a href="#">Customer Reviews</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-md-3 text-center text-md-right">
                            <div class="footer__links float-md-right">
                                <a href="#">
                                    <i class="far fa-question-circle"></i>
                                    <h4>FAQs</h4>
                                </a>
                                <a href="404.html">
                                    <i class="far fa-envelope"></i>
                                    <h4>Email</h4>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer__bottom">
                    <div class="row">
                        <div class="col-md-5 col-lg-4 order-2 order-md-1">
                            <ul class="list-unstyled">
                                <li>
                                    <a href="#">Privacy policy</a>
                                </li>
                                <li>
                                    <a href="#">Terms and conditions</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-2 col-lg-4 text-center order-1 order-md-2">
                            <a href="#" class="brand-logo brand-logo--white"></a>
                        </div>
                        <div class="col-md-5 col-lg-4 text-center text-md-right order-3">
                            <p>©2020 2AGunShow.com. All Rights Reserved</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END FOOTER -->      </div>
</div>

<!-- JS FILES -->

<!-- jquery -->
<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<!-- END jquery -->

<!-- flexslider -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/flexslider/2.7.2/jquery.flexslider-min.js" integrity="sha512-BmoWLYENsSaAfQfHszJM7cLiy9Ml4I0n1YtBQKfx8PaYpZ3SoTXfj3YiDNn0GAdveOCNbK8WqQQYaSb0CMjTHQ==" crossorigin="anonymous"></script>
<!-- END flexslider -->

<!-- Bootstrap js -->
<script src="../js/vendors/bootstrap.bundle.min.js"></script>
<!-- End Bootstrap js -->

<!-- custom js scripts -->
<script src="../js/main.js"></script>
<!-- custom js scripts -->

<!-- END JS FILES -->


</body>
</html>
