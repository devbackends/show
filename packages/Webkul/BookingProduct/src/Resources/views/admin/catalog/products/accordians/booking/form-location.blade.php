@php
    $states = app('Webkul\Core\Repositories\CountryStateRepository')->findByField('country_code', 'US');
@endphp
<booking-product-location></booking-product-location>

@push('scripts')
    <script type="text/x-template" id="booking-product-location-template">
        <div class="create-product__box" id="booking-product-location">
            <p class="create-product__box-title">Location</p>
            <div class="mb-3" :class="[errors.has('booking[location_type]') ? 'has-error' : '' ]">
                <div class="custom-control custom-radio custom-control-inline">
                    <input  type="radio" id="productEventLocationPhysical" v-model="booking.location_type" value="physical" name="booking[location_type]" class="custom-control-input"  data-vv-as="&quot;Location Type&quot;" >
                    <label class="custom-control-label" for="productEventLocationPhysical">Physical location</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input  type="radio" id="productEventLocationOnline" v-model="booking.location_type" value="online" name="booking[location_type]" class="custom-control-input"  data-vv-as="&quot;Location Type&quot;" >
                    <label class="custom-control-label" for="productEventLocationOnline">Online event</label>
                </div>
                <span class="control-error" v-if="errors.has('booking[location_name]')">@{{ errors.first('booking[location_name]') }}</span>
            </div>
            <div class="form-group" :class="[errors.has('booking[location]') ? 'has-error' : '' ]">
                <label for="productEventLocationName" class="form-label-required">Location name</label>
                <input type="text" v-validate="'required'" class="form-control" v-model="booking.location" name="booking[location]" id="productEventLocationName" placeholder="Enter the location name"  data-vv-as="&quot;Location Name&quot;" >
                <span class="control-error" v-if="errors.has('booking[location]')">@{{ errors.first('booking[location]') }}</span>
            </div>





            <div class="form-group" v-if="booking.location_type=='physical'" :class="[errors.has('booking[address_line1]') ? 'has-error' : '' ]">
                <label for="productEventAddressLine1" class="form-label-required">Address Line 1</label>
                <input type="text" v-validate="'required'" class="form-control" v-model="booking.address_line1" name="booking[address_line1]" id="productEventAddressLine1" placeholder="Address Line 1"  data-vv-as="&quot;Address Line 1&quot;" >
                <span class="control-error" v-if="errors.has('booking[address_line1]')">@{{ errors.first('booking[address_line1]') }}</span>
            </div>

            <div class="form-group"  v-if="booking.location_type=='physical'" :class="[errors.has('booking[address_line2]') ? 'has-error' : '' ]">
                <label for="productEventAddressLine2" class="">Address Line 2</label>
                <input type="text"  class="form-control" v-model="booking.address_line2" name="booking[address_line2]" id="productEventAddressLine2" placeholder="Address Line 2"  data-vv-as="&quot;Address Line 2&quot;" >
                <span class="control-error" v-if="errors.has('booking[address_line2]')">@{{ errors.first('booking[address_line2]') }}</span>
            </div>

            <div class="form-group"  v-if="booking.location_type=='physical'" :class="[errors.has('booking[city]') ? 'has-error' : '' ]">
                <label for="productEventCity" class="form-label-required">City</label>
                <input type="text" v-validate="'required'" class="form-control" v-model="booking.city" name="booking[city]" id="productEventCity" placeholder="City"  data-vv-as="&quot;City&quot;" >
                <span class="control-error" v-if="errors.has('booking[city]')">@{{ errors.first('booking[city]') }}</span>
            </div>

            <div class="form-group"  v-if="booking.location_type=='physical'" :class="[errors.has('booking[state]') ? 'has-error' : '' ]">
                <label for="productEventState" class="form-label-required">State</label>
                {{--<input type="text" v-validate="'required'" class="form-control" v-model="booking.state" name="booking[state]" id="productEventState" placeholder="State"  data-vv-as="&quot;State&quot;" >--}}
{{--                <select id="productEventState" class="form-control" required v-model="booking.state" name="booking[state]"  data-vv-as="&quot;State&quot;">
                    <option selected>Select State</option>
                    <option v-for="(state, index) in states" :value="state.default_name" :key="index">
                        @{{ state.default_name }}
                    </option>
                </select>--}}
                <Select2 id="productEventState" v-model="booking.state" :options="states" name="booking[state]" ></Select2>
                <span class="control-error" v-if="errors.has('booking[state]')">@{{ errors.first('booking[state]') }}</span>
            </div>

            <div class="form-group" v-if="booking.location_type=='physical'" :class="[errors.has('booking[postal_code]') ? 'has-error' : '' ]">
                <label for="productEventPostalCode" class="form-label-required">Postal Code</label>
                <input type="text" v-validate="'required'" class="form-control" v-model="booking.postal_code" name="booking[postal_code]" id="productEventPostalCode" placeholder="Postal Code"  data-vv-as="&quot;Postal Code&quot;" >
                <span class="control-error" v-if="errors.has('booking[postal_code]')">@{{ errors.first('booking[postal_code]') }}</span>
            </div>





            <div class="form-group mb-0" :class="[errors.has('booking[location_additional_information]') ? 'has-error' : '' ]">
                <label for="productEventLocationAdditionalInfo" >Additional information</label>
                <textarea rows="4" class="form-control" v-model="booking.location_additional_information" name="booking[location_additional_information]" id="productEventLocationAdditionalInfo" placeholder="Enter any details that may be helpful for your attendees."  data-vv-as="&quot;Location Information&quot;" ></textarea>
                <span class="control-error" v-if="errors.has('booking[location_additional_information]')">@{{ errors.first('booking[location_additional_information]') }}</span>
            </div>
        </div>
    </script>
    <script>
        var fetched_states=@json($states);
        var bookingProduct=@json($bookingProduct);
        Vue.component('booking-product-location',{
            template:'#booking-product-location-template',
            inject: ['$validator'],
            computed:{
                states(){
                    for (let i=0;i<fetched_states.length;i++){
                        fetched_states[i].text=fetched_states[i].default_name;
                    }
                    return fetched_states;
                }
            },
            mounted(){

            },
            data: function(){
               return {
                       booking: bookingProduct ? bookingProduct: {
                           location_type: 'physical',
                           location: null,
                           address_line1: null,
                           address_line2: null,
                           city: null,
                           state: null,
                           postal_code: null,
                           location_additional_information: null
                   }
               }
            }
        });
    </script>
@endpush

