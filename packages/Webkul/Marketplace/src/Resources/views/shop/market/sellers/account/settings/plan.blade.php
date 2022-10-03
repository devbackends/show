<div class="row">
    <div class="col-12 col-md pr-md-0">
        <div id="basic-seller-plan" class="seller-plan-card @if($seller->type === 'basic') seller-plan-card--selected @endif">
            <h3 class="seller-plan-card__title">Basic Seller</h3>
            <div class="seller-plan-card__link">
                @if($seller->type === 'basic')
                    <button type="button" class="btn btn-primary btn-block">
                        This is your current plan
                    </button>
                @else
                    <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modalDowngrade">
                        Downgrade your account to this plan
                    </button>
                @endif
            </div>
            <ul class="seller-plan-card__features">
                <li>Accept Cash Payments</li>
                <li>$0.99 listing fee</li>
                <li>4% commission fee</li>
            </ul>
            <div class="seller-plan-card__price seller-plan-card__price--no-fee">
                <p class="seller-plan-card__price-no-fee">No monthly fee</p>
            </div>
            <p class="seller-plan-card__description">Recommended for casual sellers, gun show vendors, collectors, or any seller listing fewer than 10 products per month.</p>
        </div>
    </div>
    <div class="col-12 col-md-5">
        <div id="plus-seller-plan" class="seller-plan-card seller-plan-card--recommended @if($seller->type === 'plus') seller-plan-card--selected @endif">
            <img src="/themes/market/assets/images/seller-plan-recommended.svg" alt="" class="seller-plan-card--recommended-badge">
            <h2 class="seller-plan-card__title"><span>Plus</span> Seller</h2>
            <div class="seller-plan-card__link">
                @if($seller->type === 'plus')
                    <button type="button" class="btn btn-primary btn-block">
                        This is your current plan
                    </button>
                @else
                    <a>
                        <button type="button" class="btn btn-primary btn-block" onclick="if (confirm('Are you sure you want to upgrade your profile?')) { location.href = '{{route('marketplace.account.seller.upgrade.submit')}}'}">
                            Upgrade your account to this plan
                        </button>
                    </a>
                @endif
            </div>
            <ul class="seller-plan-card__features">
                <li>
                    <p>Accept Cash and <strong>Credit Card</strong> Payments</p>
                    <div>
                        <p class="mb-0 font-weight-bold">Powered by:</p>
                    </div>
                    <div class="mb-3">
                        <img src="https://www.2acommerce.com/themes/commerce/public/images/site-logo.svg" alt="" width="150px">
                    </div>
                    <div class="mb-2">
                        <button type="button" class="btn btn-outline-info-dark" data-toggle="modal" data-target="#plusSellerModal">
                        Learn More
                        </button>
                    </div>
                </li>
                <li>No listing fee</li>
                <li>2% commission fee</li>
            </ul>
            <!-- <div class="seller-plan-card__price">
                <p class="seller-plan-card__price-number">$9.99</p>
                <p class="seller-plan-card__price-description">Monthly subscription</p>
            </div> -->
            <p class="seller-plan-card__description">Recommended for sellers who plan to list more than 10 items per month or who would like to provide the option of paying with a credit card.</p>
        </div>
    </div>
    <div class="col-12 col-md pl-md-0">
        <div id="pro-seller-plan" class="seller-plan-card">
            <h3 class="seller-plan-card__title">Pro Seller</h3>
            <div class="seller-plan-card__link">
                <button type="button" class="btn btn-primary btn-block" disabled>Coming soon!</button>
            </div>
            <ul class="seller-plan-card__features">
                <li>Merchant sellers with their own custom ecommerce webstore</li>
                <li>Use your own merchant services account or register to use ours at 2A Commerce</li>
            </ul>
            <div class="seller-plan-card__price">
                <p class="seller-plan-card__price-number">$19.99</p>
                <p class="seller-plan-card__price-description">Monthly subscription</p>
            </div>
            <p class="seller-plan-card__description">Recommended for custom brands, gun stores, etc. who are operating their own ecommerce website but want to sell into 2A Gun Show and harness the power of our marketing channels.</p>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDowngrade" tabindex="-1" aria-labelledby="modalDowngradeLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-header-content">
                    <h5 class="modal-title">Downgrade to Basic Seller</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <iframe
                    id="JotFormIFrame-210666173439055"
                    title="Downgrade to Basic"
                    onload="window.parent.scrollTo(0,0)"
                    allowtransparency="true"
                    allowfullscreen="true"
                    allow="geolocation; microphone; camera"
                    src="https://form.jotform.com/210666173439055"
                    frameborder="0"
                    style="min-width: 100%;height:539px;border:none;"
                    scrolling="yes"
                >
                </iframe>
            </div>
        </div>
    </div>
</div>

<!-- Plus Seller Learn More Modal -->
<div class="modal fade" id="plusSellerModal" tabindex="-1" aria-labelledby="plusSellerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content seller-plus-modal">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i aria-hidden="true" class="far fa-times"></i>
            </button>
            <div class="modal-body">
                    <div class="row mx-0 align-items-md-stretch">
                        <div class="col-12 col-md-auto px-0">
                            <div class="seller-plus-modal__side-info">
                                <div class="seller-plus-modal__side-info-logo">
                                    <img src="https://www.2acommerce.com/themes/commerce/public/images/site-logo.svg" alt="" width="150px">
                                </div>
                                <div class="seller-plan-card__price">
                                    <div>
                                        <p class="seller-plan-card__price-number">$10</p>
                                        <p class="seller-plan-card__price-description">Monthly subscription</p>
                                    </div>
                                </div>
                                <div class="seller-plan-card__price">
                                    <div>
                                        <p class="seller-plan-card__price-number">2.8%</p>
                                        <p class="seller-plan-card__price-description">Credit card processing</p>
                                    </div>
                                </div>
                                <div class="seller-plan-card__price mb-0">
                                    <div>
                                        <p class="seller-plan-card__price-number">$0.30</p>
                                        <p class="seller-plan-card__price-description">Transaction fee</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md px-0">
                            <div class="h-100 seller-plus-modal__content">
                                <div>
                                    <p class="mb-0"><span class="font-weight-bold">2A Commerce</span> is veteran-owned, second amendment-friendly payment processing built for the firearms industry that gives you the freedom to conduct your business without fear of being shut down.</p>
                                    <div class="my-5">
                                        <a href="https://www.2acommerce.com/" target="_blank" class="btn btn-gray-darker mx-2">Learn more, visit 2acommerce.com</a>
                                    </div>
                                    <div>
                                        <ul class="text-left mb-0">
                                            <li>Free card reader for in-person transactions</li>
                                            <li>Multiple POS integration options</li>
                                            <li>Accept credit card payment on 2agunshow.com and reduce your commission fees</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>