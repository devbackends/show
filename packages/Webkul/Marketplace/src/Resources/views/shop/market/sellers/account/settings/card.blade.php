<p style="display: none"><input type="text" name="card[token]"></p>
<p style="display: none"><input type="text" name="card[billingInfo]"></p>

<div class="row">
    @php $paymentMethodCustomer=null; @endphp

    @if($fluidCustomer)
        @php  $paymentMethodCustomer=$fluidCustomer; @endphp
    @endif

    @if($sellerFluidCustomer)
      @php  $paymentMethodCustomer=$sellerFluidCustomer; @endphp
    @endif

    <div class="col-12 col-md-auto">
    @if($paymentMethodCustomer)
        <div class="fluid-cards__list">
            <div class="fluid-cards__list-item">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <i class="far fa-credit-card fluid-cards__list-item-icon"></i>
                    </div>
                    <div class="col">
                        <p>Ending in <strong>{{$paymentMethodCustomer->card_details}}</strong></p>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-link fluid-cards__list-item-edit" data-toggle="modal" data-target="#modalCard">
                            <i class="far fa-pencil"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @else
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCard">
            <i class="far fa-credit-card mr-2"></i>Add Card
        </button>
    @endif
    </div>
    <div class="col-12 col-md"></div>

</div>

<div class="modal fade" id="modalCard" tabindex="-1" aria-labelledby="modalCardLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-header-content">
                    <i class="fal fa-credit-card"></i>
                    <h5 class="modal-title">@if($paymentMethodCustomer) Edit Card @else Add Card @endif</h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="fluid-form" class="mb-3"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-gray" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" id="cardModalSubmitBtn">Save</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{config('services.2acommerce.gateway_url') . '/tokenizer/tokenizer.js'}}"></script>
    <script>
        $(document).ready(function () {
            const tokenizer = new Tokenizer({
                url: '{{config('services.2acommerce.gateway_url')}}',
                apikey: '{{core()->getConfigData('sales.paymentmethods.fluid.public_key')}}',
                container: document.querySelector('#fluid-form'),
                settings: {
                    payment: {
                        showTitle: true,
                        placeholderCreditCard: '0000 0000 0000 0000',
                        showExpDate: true,
                        showCVV: true
                    },
                    @if(!$paymentMethodCustomer)
                        user: {
                            showInline: true,
                            showName: true,
                            showEmail: true,
                            showTitle: true
                        },
                        billing: {
                            show: true,
                            showTitle: true
                        }
                    @endif
                },
                submission: (res) => {
                    if (res.status === 'success') {
                        $('input[name="card[token]"]').val(res.token);
                        @if(!$paymentMethodCustomer)
                            $('input[name="card[billingInfo]"]').val(JSON.stringify({
                                ...res.user,
                                ...res.billing,
                            }));
                        @endif
                    }
                    $('#main-submit-btn').trigger('click');
                },
            })
            $('#cardModalSubmitBtn').click(function () {
                tokenizer.submit();
            })
        })
    </script>
@endpush