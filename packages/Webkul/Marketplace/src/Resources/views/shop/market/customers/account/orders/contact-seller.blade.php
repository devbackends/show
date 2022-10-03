
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
                    @if(strpos(request()->route()->getName(), 'marketplace') !== false)
                    <h3>Contact buyer</h3>
                    @else
                    <h3>Contact seller</h3>
                    @endif
                </div>
                <contact-seller-form></contact-seller-form>
            </div>
        </div>
    </div>
</div>

<!-- MODAL CONTACT SELLER -->

@push('scripts')

    <script type="text/x-template" id="contact-form-template">
        <form id="contact-form" method="POST" data-vv-scope="contact-form"
              @submit.prevent="contactSeller('contact-form')">
            <input type="hidden" id="from" name="from" />
            <input type="hidden" id="to" name="to" />
            <input type="hidden" id="order_id" name="order_id" />
            <div class="form-group" :class="[errors.has('contact-form.name') ? 'has-error' : '']">
                <input type="text" class="form-control" placeholder="{{ __('marketplace::app.shop.sellers.profile.name') }}" v-model="contact.name"
                       name="name" v-validate="'required'"
                       data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.profile.name') }}&quot;">
                <span class="control-error" v-if="errors.has('contact-form.name')">@{{ errors.first('contact-form.name') }}</span>
            </div>
            <div class="form-group" :class="[errors.has('contact-form.email') ? 'has-error' : '']">
                <input type="email" class="form-control" placeholder="{{ __('marketplace::app.shop.sellers.profile.email') }}" v-model="contact.email"
                       name="email" v-validate="'required|email'"
                       data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.profile.email') }}&quot;">
                <span class="control-error" v-if="errors.has('contact-form.email')">@{{ errors.first('contact-form.email') }}</span>
            </div>
            <div class="form-group"
                 :class="[errors.has('contact-form.subject') ? 'has-error' : '']">
                <Select type="text" class="form-control" id="subject"
                       v-model="contact.subject" name="subject" v-validate="'required'"
                        data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.profile.subject') }}&quot;">
                    <option value="General Inquiry">General Inquiry</option>
                    <option value="Product damaged">Product damaged</option>
                    <option value="Parts missing or broken">Parts missing or broken</option>
                    <option value="Item defective or doesn't work">Item defective or doesn't work</option>
                    <option value="Wrong Item was sent">Wrong Item was sent</option>
                    <option value="Inaccurate product description">Inaccurate product description</option>
                    <option value="Item has not arrived">Item has not arrived</option>x
                </Select>
                <span class="control-error" v-if="errors.has('contact-form.subject')">@{{ errors.first('contact-form.subject') }}</span>
            </div>
            <div class="form-group" :class="[errors.has('contact-form.query') ? 'has-error' : '']">
                                    <textarea class="form-control" placeholder="{{ __('marketplace::app.shop.sellers.profile.query') }}" v-model="contact.query"
                                              name="query" v-validate="'required'"
                                              data-vv-as="&quot;{{ __('marketplace::app.shop.sellers.profile.query') }}&quot;"></textarea>
                <span class="control-error" v-if="errors.has('contact-form.query')">@{{ errors.first('contact-form.query') }}</span>
            </div>
            <input type="text" style="display: none" v-model="captcha">
            <div class="text-right">
                <input type="submit" class="btn btn-primary" value="Send message" :disabled="disable_button">
            </div>
        </form>
    </script>

    <script>
        Vue.component('contact-seller-form', {

            data: () => ({
                contact: {
                    'name': '',
                    'email': '',
                    'subject': '',
                    'query': '',
                    'from': '',
                    'to':  ''
                },
                captcha: '',

                disable_button: false,
            }),

            template: '#contact-form-template',

            created() {

                @auth('customer')
                    @if(auth('customer')->user())
                    this.contact.email = "{{ auth('customer')->user()->email }}";
                this.contact.name = "{{ auth('customer')->user()->first_name }} {{ auth('customer')->user()->last_name }}";
                @endif
                @endauth

            },
            mounted() {
            },
            methods: {
                contactSeller(formScope) {
                    var this_this = this;

                    this_this.contact.subject=$('#subject').val()+' , order # '+$('#order_id').val();
                    this_this.disable_button = true;

                    if (this.captcha === '') {
                        this.$validator.validateAll(formScope).then((result) => {
                            if (result) {
                                this.contact.from=$('#from').val();
                                this.contact.to=$('#to').val();
                                if($('#from').val() && $('#to').val()){
                                    this.$http.post("{{route('marketplace.account.messages.send-message')}}" , this.contact)
                                  .then(function (response) {
                                      this_this.disable_button = false;
                                      this_this.$toast.success('Message Sent successfully', {
                                          position: 'top-right',
                                          duration: 5000,
                                      });
                                      $('#contactSeller').modal('hide');

                                      this_this.contact.subject='';
                                      this_this.contact.query='';

                                  })

                                  .catch(function (error) {
                                      this_this.disable_button = false;

                                      this_this.handleErrorResponse(error.response, 'contact-form')
                                  })
                                }

                            } else {
                                this_this.disable_button = false;
                            }
                        });
                    } else {
                        $('#contactSeller').modal('hide');
                    }
                },

                handleErrorResponse(response, scope) {
                    if (response.status == 422) {
                        serverErrors = response.data.errors;
                        this.$root.addServerErrors(scope)
                    }
                }
            }
        });

    </script>
@endpush