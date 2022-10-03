{!! view_render_event('bagisto.shop.layout.header.account-item.before') !!}
<login-header></login-header>
{!! view_render_event('bagisto.shop.layout.header.account-item.after') !!}

<script type="text/x-template" id="login-header-template">
    <div class="header-top__user-content">
        <div id="account">
            <div @click="togglePopup">
                <i class="material-icons header-top__user-icon">perm_identity</i>
                <p class="header-top__user-name">
                    @guest('customer')
                        {{ __('velocity::app.header.welcome-message', ['customer_name' => trans('velocity::app.header.guest')]) }}!
                    @endguest

                    @auth('customer')
                        {{ __('velocity::app.header.welcome-message', ['customer_name' => auth()->guard('customer')->user()->first_name]) }}
                    @endauth
                </p>
            </div>
        </div>

        <div class="account-modal sensitive-modal hide">
            <!--Content-->
                @guest('customer')
                    <div class="modal-content">
                        <!--Header-->
{{--                        <div class="modal-header no-border pb0">
                            <label class="">{{ __('shop::app.header.title') }}</label>

                            <button type="button" class="close disable-box-shadow" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" class="white-text fs20" @click="togglePopup">×</span>
                            </button>
                        </div>--}}

                        <!--Body-->
                            <p class="p-4 mb-0">{{ __('shop::app.header.dropdown-text') }}</p>

                        <!--Footer-->
                        <div class="modal-footer d-flex justify-content-end pb-4 px-4">
                        <a href="{{ route('customer.register.index') }}" class="btn btn-outline-primary">
                                    {{ __('shop::app.header.sign-up') }}
                                </a>
                                <a href="{{ route('customer.session.index') }}" class="btn btn-primary">
                                {{ __('shop::app.header.sign-in') }}
                                </a>
                        </div>
                    </div>
                @endguest

                @auth('customer')
                    <div class="modal-content customer-options">
                        <div class="customer-session">
                        <p>{{ auth()->guard('customer')->user()->first_name }}</p>
                        </div>

                        <ul type="none">
                            <li>
                                <a href="{{ route('customer.profile.index') }}" class="unset">{{ __('shop::app.header.profile') }}</a>
                            </li>

                            <li>
                                <a href="{{ route('customer.orders.index') }}" class="unset">{{ __('velocity::app.shop.general.orders') }}</a>
                            </li>

                            <li>
                                <a href="{{ route('customer.wishlist.index') }}" class="unset">{{ __('shop::app.header.wishlist') }}</a>
                            </li>

                            <li>
                                <a href="{{ route('velocity.customer.product.compare') }}" class="unset">{{ __('velocity::app.customer.compare.text') }}</a>
                            </li>

                            <li>
                                <a href="{{ route('customer.session.destroy') }}" class="unset">{{ __('shop::app.header.logout') }}</a>
                            </li>
                        </ul>
                    </div>
                @endauth
            <!--/.Content-->
        </div>
    </div>
</script>

@push('scripts')
<script type="text/javascript">
    Vue.component('login-header', {
        template: '#login-header-template',

        methods: {
            togglePopup: function(event) {
                let accountModal = this.$el.querySelector('.account-modal');
                let modal = $('#cart-modal-content')[0];

                if (modal)
                    modal.classList.add('hide');

                accountModal.classList.toggle('hide');

                event.stopPropagation();
            }
        }
    })
</script>
@endpush