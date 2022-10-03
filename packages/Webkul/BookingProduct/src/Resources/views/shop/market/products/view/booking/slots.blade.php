<book-slots></book-slots>

@push('scripts')
    <script type="text/x-template" id="book-slots-template">

        <div class="book-slots">
            <label>{{ $title ?? __('bookingproduct::app.shop.products.book-an-appointment') }} :</label>

            <div class="control-group-container">
                <div class="form-group date" :class="[errors.has('booking[date]') ? 'has-error' : '']">
                    <date @onChange="dateSelected($event)"
                          @if(isset($bookingProduct->available_from) and $bookingProduct->available_from != null)
                          :minDate="new Date('{{ DATE_FORMAT($bookingProduct->available_from,"Y-m-d")}}')"
                          @endif
                          @if(isset($bookingProduct->available_to) and $bookingProduct->available_to != null)
                          :maxDate="new Date('{{ DATE_FORMAT($bookingProduct->available_to,"Y-m-d")}}')"
                        @endif
                    >
                        <input type="text" v-validate="'required'" name="booking[date]" class="form-style" data-vv-as="&quot;{{ __('bookingproduct::app.shop.products.date') }}&quot;"/>
                    </date>

                    <span class="control-error" v-if="errors.has('booking[date]')">@{{ errors.first('booking[date]') }}</span>
                </div>

                <div class="form-group slots" :class="[errors.has('booking[slot]') ? 'has-error' : '']">
                    <select v-validate="'required'" name="booking[slot]" class="form-style" data-vv-as="&quot;{{ __('bookingproduct::app.shop.products.slot') }}&quot;">
                        <option v-for="slot in slots" :value="slot.timestamp">@{{ slot.from + ' - ' + slot.to }}</option>
                    </select>

                    <span class="control-error" v-if="errors.has('booking[slot]')">@{{ errors.first('booking[slot]') }}</span>
                </div>
            </div>
        </div>

    </script>

    <script>

        Vue.component('book-slots', {
            template: '#book-slots-template',

            inject: ['$validator'],

            data: function() {
                return {
                    slots: []
                }
            },

            methods: {
                dateSelected: function(date) {
                    var this_this = this;

                    this.$http.get("{{ route('booking_product.slots.index', $bookingProduct->id) }}", {params: {date: date}})
                        .then (function(response) {
                            this_this.slots = response.data.data;

                            this_this.errors.clear();
                        })
                        .catch (function (error) {})
                }
            }
        });

    </script>
@endpush