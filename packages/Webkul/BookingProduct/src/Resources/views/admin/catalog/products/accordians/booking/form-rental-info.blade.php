<booking-product-rental-information></booking-product-rental-information>

@push('scripts')
<script type="text/x-template"  id="booking-product-rental-information-template">

    <div class="create-product__box" id="booking-product-rental-information">
        <p class="create-product__box-title">Rental Information</p>
        <div class="mb-3"  :class="[errors.has('booking[renting_type]') ? 'has-error' : '' ]">
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="productRentalDaily" name="booking[renting_type]" class="custom-control-input" value="daily" v-validate="'required'" v-model="rental.renting_type">
                <label class="custom-control-label" for="productRentalDaily">Daily basis</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="productRentalHourly" name="booking[renting_type]" class="custom-control-input" value="hourly" v-validate="'required'"  v-model="rental.renting_type">
                <label class="custom-control-label" for="productRentalHourly">Hourly basis</label>
            </div>
        {{--    <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="productRentalBoth" name="booking[renting_type]" class="custom-control-input" value="both" v-validate="'required'" v-model="rental.renting_type">
                <label class="custom-control-label" for="productRentalBoth">Both (daily and hourly basis)</label>
            </div>--}}
            <span class="control-error" v-if="errors.has('booking[renting_type]')">@{{ errors.first('booking[renting_type]') }}</span>
        </div>
        <div class="form-group" :class="[errors.has('booking[daily_price]') ? 'has-error' : '' ]" v-if="rental.renting_type=='daily' || rental.renting_type=='both'">
            <label for="productRentalDailyPrice">Price per day</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="productRentalDailyPrice">$</span>
                </div>
                <input type="text" class="form-control" placeholder="0.00" aria-label="Price per day" aria-describedby="productRentalDailyPrice" name="booking[daily_price]" v-validate="'required'" v-model="rental.daily_price">
            </div>
            <span class="control-error" v-if="errors.has('booking[daily_price]')">@{{ errors.first('booking[daily_price]') }}</span>
        </div>
        <div class="row"  v-if="rental.renting_type=='hourly' || rental.renting_type=='both'">
            <div class="col-12 col-lg">
                <div class="form-group"  :class="[errors.has('booking[hourly_price]') ? 'has-error' : '' ]">
                    <label for="productRentalHourlyPrice">Price per hour</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="productRentalHourlyPrice">$</span>
                        </div>
                        <input type="text" class="form-control" placeholder="0.00" aria-label="Price per hour" aria-describedby="productRentalHourlyPrice"  name="booking[hourly_price]" v-validate="'required'" v-model="rental.hourly_price">
                    </div>
                    <span class="control-error" v-if="errors.has('booking[hourly_price]')">@{{ errors.first('booking[hourly_price]') }}</span>
                </div>
            </div>
            <div class="col-12 col-lg-auto">
                <div class="custom-control custom-checkbox custom-checkbox--no-label custom-control-inline">
                    <input type="checkbox" id="productRentalSameSlot" class="custom-control-input" name="booking[same_slot_all_days]" v-on:change="resetSlots" v-model="rental.same_slot_all_days" value="1">
                    <label class="custom-control-label" for="productRentalSameSlot">Same slot all days</label>
                </div>
            </div>
        </div>
        <!-- table for different slots every day -->
        <div class="table-responsive" v-if="(rental.renting_type=='hourly' || rental.renting_type=='both') && !rental.same_slot_all_days">
            <table class="table table-sm my-3"  v-if="rental.slots && rental.slots.length > 0">
                <thead>
                <tr>
                    <th scope="col">Day</th>
                    <th scope="col">Time</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(day_slots,index) in rental.slots" :key="index">
                    <th scope="row">
                        <select class="custom-select" v-model="rental.slots[index].day" :name="'booking[slots]['+index+'][day]'">
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                        </select>
                    </th>
                    <td>
                        <!-- Time slot -->
                        <div class="row mb-2" v-for="(slot,i) in rental.slots[index].durations" :key="i">
                            <div class="col pr-0"  :class="[errors.has('booking[slots]['+index+'][durations]['+i+'][from]') ? 'has-error' : '']">
                                <vue-timepicker v-validate="'required'" format="h:mm a"  placeholder="Start Time" :name="'booking[slots]['+index+'][durations]['+i+'][from]'" v-model="rental.slots[index].durations[ i ].from" data-vv-as="&quot;{{ __('bookingproduct::app.admin.catalog.products.from') }}&quot;"></vue-timepicker>
                                <span class="control-error" v-if="errors.has('booking[slots]['+index+'][durations]['+i+'][from]')">@{{ errors.first('booking[slots]['+index+'][durations]['+i+'][from]') }}</span>
                            </div>
                            <div class="col"  :class="[errors.has('booking[slots][' + index + '][durations]['+i+'][to]') ? 'has-error' : '']">
                                <vue-timepicker v-validate="'required'" format="h:mm a"  placeholder="End Time" :name="'booking[slots]['+index+'][durations]['+i+'][to]'" v-model="rental.slots[index].durations[ i ].to" data-vv-as="&quot;{{ __('bookingproduct::app.admin.catalog.products.from') }}&quot;"></vue-timepicker>
                                <span class="control-error" v-if="errors.has('booking[slots][' + index + '][durations]['+i+'][to]')">@{{ errors.first('booking[slots][' + index + '][durations]['+i+'][to]') }}</span>
                            </div>
                            <div>
                                <a v-on:click="removeTimeSlotToDailyDiff(index,i)" href="javascript:;" class="table-icon-button table-icon-button--delete"><i class="far fa-trash-alt"></i></a>
                            </div>
                        </div>
                        <!-- END Time slot -->
                        <a v-on:click="addTimeSlotToDailyDiff(index)" href="javascript:;" class="create-product__add-button"><i class="far fa-plus"></i>Add time slot</a>
                    </td>



                </tr>
                </tbody>
            </table>
            <a v-on:click="addDay()" href="javascript:;" class="create-product__add-button mt-3"><i class="far fa-plus"></i>Add day</a>
        </div>

        <!-- table for different slots every day -->
        <!-- table for same slot for all days -->
        <div class="table-responsive"  v-if="rental.same_slot_all_days">
            <table class="table table-sm my-3">
                <thead>
                <tr>
                    <th scope="col">From</th>
                    <th scope="col">To</th>
                    <th scope="col col-actions"></th>
                </tr>
                </thead>
                <tbody>
                <tr  v-for="(slot,index) in rental.slots" :key="index" >
                    <td>
                        <div class="col pr-0" :class="[errors.has('booking[slots][' + index + '][from]') ? 'has-error' : '']">
                            <vue-timepicker v-validate="'required'" format="h:mm a"  placeholder="Start Time" :name="'booking[slots]['+index+'][from]'" v-model="rental.slots[ index ].from" data-vv-as="&quot;{{ __('bookingproduct::app.admin.catalog.products.from') }}&quot;"></vue-timepicker>
                            <span class="control-error" v-if="errors.has('booking[slots][' + index + '][from]')">@{{ errors.first('booking[slots][' + index + '][from]') }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="col pr-0"
                             :class="[errors.has('booking[slots][' + index + '][to]') ? 'has-error' : '']">
                            <vue-timepicker v-validate="'required'" format="h:mm a"  placeholder="End Time" :name="'booking[slots]['+index+'][to]'" v-model="rental.slots[ index ].to" data-vv-as="&quot;{{ __('bookingproduct::app.admin.catalog.products.from') }}&quot;"></vue-timepicker>
                            <span class="control-error" v-if="errors.has('booking[slots][' + index + '][to]')">@{{ errors.first('booking[slots][' + index + '][to]') }}</span>
                        </div>
                    </td>
                    <td class="text-right">
                        <a href="#" class="table-icon-button table-icon-button--delete"><i class="far fa-trash-alt"></i></a>
                    </td>
                </tr>
                </tbody>
            </table>
            <a v-on:click="addTimeSlot()" href="javascript:;" class="create-product__add-button mt-3"><i class="far fa-plus"></i>Add time slot</a>
        </div>
        <!-- table for same slot for all days -->
    </div>

</script>
<script>
    const rental=@json($rental);
    Vue.component('booking-product-rental-information',{
        template:'#booking-product-rental-information-template',
        inject: ['$validator'],
        mounted() {
        if(this.rental.slots == null){
            this.rental.slots=[];
            this.rental.daily_price=parseFloat(this.rental.daily_price).toFixed(2);
            this.rental.hourly_price=parseFloat(this.rental.hourly_price).toFixed(2);
        }
            },
        data: function(){
            return {
               rental: rental ? rental : {
                   renting_type: 'daily',
                   daily_price: null,
                   hourly_price: null,
                       same_slot_all_days: false,
                   slots:[
                       {'day':'Monday',
                           'durations':   [
                               {'from':"", 'to': "",'slotId': Math.floor(Math.random() * 10000000000)},
                               {'from':"", 'to': "",'slotId': Math.floor(Math.random() * 10000000000)},

                           ]
                       }
                   ]
               }
            }
        },
        methods:{
            addTimeSlotToDailyDiff: function(index){
                this.rental.slots[index].durations.push( {'from':"", 'to': "",'slotId': Math.floor(Math.random() * 10000000000)});
            },
            addTimeSlot(){
                this.rental.slots.push( {'from':"", 'to': "",'slotId': Math.floor(Math.random() * 10000000000)});
            },
            removeTimeSlotToDailyDiff: function(index,i){
                this.rental.slots[index].durations.splice(i, 1);
            },
            addDay(){
              this.rental.slots.push(
                  {'day':'Monday',
                  'durations':   [
                      {'from':"", 'to': "",'slotId': Math.floor(Math.random() * 10000000000)},
                      {'from':"", 'to': "",'slotId': Math.floor(Math.random() * 10000000000)},

                  ]
              })
            },
            resetSlots(){
                if(this.rental.same_slot_all_days){
                    this.rental.slots=[];
                    this.rental.slots.push( {'from':"", 'to': "",'slotId': Math.floor(Math.random() * 10000000000)});
                }else{
                 this.rental.slots=[];
                    this.rental.slots.push(
                        {'day':'Monday',
                            'durations':   [
                                {'from':"", 'to': "",'slotId': Math.floor(Math.random() * 10000000000)},
                                {'from':"", 'to': "",'slotId': Math.floor(Math.random() * 10000000000)},

                            ]
                        });
                }
            }
        }
    });
</script>
@endpush
