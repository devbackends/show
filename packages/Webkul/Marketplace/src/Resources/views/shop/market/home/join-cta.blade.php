<?php
if (!isset($url) || empty($url)) {
    $url = route('customer.register.index');
}
?>
<div class="become-member__join-cta">
    <div class="container">
        <div class="row no-gutters">
            <div class="col-12 col-md-5 offset-md-1">
                <img src="/themes/market/assets/images/start-selling-join-cta.jpg" alt="">
            </div>
            <div class="col-12 col-md-5">
                    <div class="become-member__join-cta-content">
                        <h2>Join the never-ending gun show</h2>
                        <p class="font-paragraph-big-bold">Claim your own seller profile and get your stuff shown to thousands of people daily.</p>
                        <a href="{{ $url }}" class="btn btn-primary">Sign Up Now!</a>
                    </div>
            </div>
        </div>
    </div>
</div>