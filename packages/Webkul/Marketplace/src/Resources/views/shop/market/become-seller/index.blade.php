@extends('shop::layouts.master')

@section('content-wrapper')

    <div class="promo-section promo-section--low" style="background-image: url(./images/marketplace/bg-promo-become-a-seller.jpg);">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h1 class="promo-section__title">Become a seller!</h1>
                    <p class="head">Join the community</p>
                </div>
            </div>
        </div>
    </div>

    <div class="become-member-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10 col-xl-9">
                    <p class="head">2A Gun Show is the world's largest online marketplace site for shooting accessories, ammunition, hunting gear, gun safety classes, training, collectibles, and much more! Our low rates rival anyone in the industry, and when you decide to sell with us you get your very own seller profile page to list your goods and services.</p>

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

@endsection