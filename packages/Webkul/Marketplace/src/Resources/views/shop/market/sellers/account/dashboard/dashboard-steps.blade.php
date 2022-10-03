<p class="font-paragraph-big-bold text-center text-info dashboard__tips-title">Get ready to sell online. <span>Follow these steps to get started.</span></p>
<div class="dashboard__tips">
    <div class="row">
        <div class="col-12">
            <a id="close-tips" class="dashboard__tips-close">
                <i class="far fa-times"></i>
            </a>
            <div class="dashboard__tips-content p-3 p-lg-4 pb-lg-5 h-100">
                <ul class="nav nav-tabs mb-1" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link @if($is_seller_profile_complete) nav-link__success @else active @endif" id="set-profile-tab" data-toggle="tab" href="#set-profile" role="tab" aria-controls="home" aria-selected="true">Set Your Profile<i class="far fa-check ml-2"></i></a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link @if($check_shipping_methods_is_configured) nav-link__success @elseif($is_seller_profile_complete && !$check_shipping_methods_is_configured) active @else  @endif" id="setup-shipping-tab" data-toggle="tab" href="#setup-shipping" role="tab" aria-controls="profile" aria-selected="false">Setup Shipping Methods<i class="far fa-check ml-2"></i></a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link @if($is_seller_profile_complete && $check_shipping_methods_is_configured && $seller_products_count == 0) active @elseif($check_shipping_methods_is_configured && $seller_products_count > 0) nav-link__success @else disabled  @endif" id="add-product-tab" data-toggle="tab" href="#add-product" role="tab" aria-controls="contact" aria-selected="false">Add Product<i class="far fa-check ml-2"></i></a>

                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link  @if($check_shipping_methods_is_configured && $seller_products_count > 0) active  @else disabled  @endif" id="share-store-tab" data-toggle="tab" href="#share-store" role="tab" aria-controls="contact" aria-selected="false">Share Your Store<i class="far fa-check ml-2"></i></a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade @if($is_seller_profile_complete)  @else show active @endif" id="set-profile" role="tabpanel" aria-labelledby="set-profile">
                        <div class="row">
                            <div class="col-auto">
                                <div class="dashboard__tips-content-icon d-flex justify-content-center align-items-center">
                                    <i class="fal fa-address-card"></i>
                                </div>
                            </div>
                            <div class="col">
                                <h3 class="dashboard__tips-content-big">Let’s Get Your Profile Set Up!</h3>
                                <p class="dashboard__tips-content-big-gray">Personalize your profile by adding your store banner, profile image, location information and more!</p>
                                <a class="btn btn-primary" href="/marketplace/account/edit">Setup your profile</a>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade @if($is_seller_profile_complete && !$check_shipping_methods_is_configured) show active @endif" id="setup-shipping" role="tabpanel" aria-labelledby="setup-shipping">
                        <div class="row">
                            <div class="col-auto">
                                <div class="dashboard__tips-content-icon d-flex justify-content-center align-items-center">
                                    <i class="fal fa-shipping-fast"></i>
                                </div>
                            </div>
                            <div class="col">
                                <h3 class="dashboard__tips-content-big">Choose Your Shipping Rates And Services.</h3>
                                <p class="dashboard__tips-content-big-gray">These will be your default rates.</p>
                                <a class="btn btn-primary" href="/customer/marketplace/marketplace/multi-shippping">Setup shipping methods</a>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade @if($is_seller_profile_complete && $check_shipping_methods_is_configured && $seller_products_count == 0) show active @else  @endif" id="add-product" role="tabpanel" aria-labelledby="add-product">
                        <div class="row">
                            <div class="col-auto">
                                <div class="dashboard__tips-content-icon d-flex justify-content-center align-items-center">
                                    <i class="fal fa-tags"></i>
                                </div>
                            </div>
                            <div class="col">
                                <h3 class="dashboard__tips-content-big">Start Adding Products</h3>
                                <p class="dashboard__tips-content-big-gray">Your payment processing is set up, now the fun begins!</p>
                                <a class="btn btn-primary" href="/marketplace/account/catalog/products/create">Add A Product</a>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade @if($is_seller_profile_complete && $seller_products_count > 0) show active  @else  @endif" id="share-store" role="tabpanel" aria-labelledby="share-store">
                        <div class="row">
                            <div class="col-auto">
                                <div class="dashboard__tips-content-icon d-flex justify-content-center align-items-center">
                                    <i class="fal fa-share-alt"></i>
                                </div>
                            </div>
                            <div class="col">
                                <h3 class="dashboard__tips-content-big">Share Your 2A Store</h3>
                                <p class="dashboard__tips-content-big-gray">Welcome to the best online firearm marketplace on the planet. Share the good news with your customers and start making money today.</p>

                                <!-- Here the store url -->
                                <p class="store-url d-none">{{ route('marketplace.seller.show',$seller->url) }}</p>
                                <button class="btn btn-primary dashboard__tips-copy-url"><i class="far fa-copy mr-2"></i><i class="far fa-check mr-2"></i><span class="dashboard__tips-copy-url--text">Copy the store url and share</span><span class="dashboard__tips-copy-url--message">Store url is copied to clipboard</span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).on('click', '#close-tips', function(e) {
        $('.dashboard__tips').remove();
        $('.dashboard__tips-title').remove();
    });

    $(document).ready(function(){
        $(".dashboard__tips-copy-url").click(function(event){
            var $tempElement = $("<input>");
            $("body").append($tempElement);
            $tempElement.val($(this).closest(".tab-pane").find(".store-url").text()).select();
            document.execCommand("Copy");
            $tempElement.remove();
            copyStoreUrl();
        });
    });


    function copyStoreUrl() {
        $('.dashboard__tips-copy-url').addClass('btn-success');
        $('.dashboard__tips-copy-url').removeClass('btn-primary');
        setTimeout(function() {
            $('.dashboard__tips-copy-url').removeClass('btn-success');
            $('.dashboard__tips-copy-url').addClass('btn-primary');
        }, 3000);
    }

</script>
@endpush