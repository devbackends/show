
<booking-product-general-information></booking-product-general-information>

@push('scripts')

    <script type="text/x-template" id="booking-product-general-information-template">
        <!-- Form General Info -->
        <div class="create-product__box">
            <p class="create-product__box-title">General Information for Events</p>
            <p class="create-product__box-subtitle">Main Details</p>
{{--            <div class="row">
                <div class="col">
                    <div class="form-group" :class="[errors.has('booking[name]') ? 'has-error' : '' ]" >
                        <label for="productName" class="form-label-required">Name</label>
                        <input type="text" class="form-control" id="name" name="booking[name]" v-model="booking.name"
                               v-validate="'required'" placeholder="Give it a short, descriptive name" data-vv-as="&quot;Product Name&quot;" >
                        <span class="control-error" v-if="errors.has('booking[name]')">@{{ errors.first('booking[name]') }}</span>
                    </div>

                </div>
            </div>--}}
            <input type="hidden" name="booking[type]" :value="booking.type" />
    {{--        <div class="form-group" :class="[errors.has('booking[description]') ? 'has-error' : '' ]">
                <label for="booking_description">Description</label>
                <textarea rows="3" v-model="booking.description" name="booking[description]" id="booking_description" class="form-control"
                          v-validate="'required'"
                          placeholder="Describe your event, so bookers know what to expect"
                          data-vv-as="&quot;Description&quot;"></textarea>
                <span class="control-error" v-if="errors.has('booking[description]')">@{{ errors.first('booking[description]') }}</span>
            </div>--}}

            <div class="form-group" :class="[errors.has('booking[instructions]') ? 'has-error' : '' ]" >
                <label for="instructions">Attendee Instructions</label>
                <textarea rows="3" class="form-control" id="instructions"  v-model="booking.instructions" name="booking[instructions]"
                          placeholder="How to attend, what to bring, etc" data-vv-as="&quot;Attendee Instructions&quot;"></textarea>
                <span class="control-error" v-if="errors.has('booking[instructions]')">@{{ errors.first('booking[instructions]') }}</span>
            </div>
            {{--<image-uploader></image-uploader>--}}
            <div class="form-group" id="select2-leaders" :class="[errors.has('booking[leaders]') ? 'has-error' : '' ]" >
                <label for="leaders">Leaders
                    <i data-toggle="tooltip" data-placement="top" title="" class="fas fa-question-circle form-tooltip-icon" data-original-title="In addition to the primary organizer, list other attendees/organizers that will help organize, assist, and manage the event."></i>
                </label>
                <input type="hidden" name="booking[leaders]" v-model="booking.leaders">
                <Select2MultipleControl  v-model="booking.leaders" :options="booking.leaders" :settings="{tags: true, language: customNoResults }"></Select2MultipleControl>
                <span class="control-error" v-if="errors.has('booking[leaders]')">@{{ errors.first('booking[leaders]') }}</span>


            </div>
            <div class="form-group">
                <label for="booking_confirmation_message">Booking confirmation message</label>
                <textarea rows="3" type="text" class="form-control" id="booking_confirmation_message" name="booking[booking_confirmation_message]" v-model="booking.booking_confirmation_message"
                          placeholder="Information visible once the booking has been completed"></textarea>
            </div>
            <p class="create-product__box-subtitle">Additional Details</p>
            <div class="form-group" id="select2-tags">
                <label for="tags">Tags
                    <i data-toggle="tooltip" data-placement="top" title="" class="fas fa-question-circle form-tooltip-icon" data-original-title="Tags are your opportunity to include additional keywords that describe your unique product. Each tag you add is a chance to match with a shopper’s search, so spread them around and add some variety!"></i>
                </label>
                <input type="hidden" name="booking[tags]" v-model="booking.tags">
                <Select2MultipleControl  v-model="booking.tags" :options="booking.tags" :settings="{tags: true}" ></Select2MultipleControl>
            </div>
            <div class="row">
                <div class="col-12 col-lg">
                    <div class="form-group">
                        <label for="product-event-age-range">Age range</label>
                        <div class="row">
                            <div class="col"><input class="form-control" type="number" name="booking[min_age]" placeholder="Min." v-model="booking.min_age"></div>
                            <div class="col"><input class="form-control" type="number" name="booking[max_age]" placeholder="Max." v-model="booking.max_age"></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg">
                    <div class="form-group">
                        <div class="custom-control custom-checkbox custom-checkbox--no-label">
                            <input type="checkbox" class="custom-control-input" id="productEventAgeNoRestrictions" name="booking[age_restrictions]" v-model="booking.age_restrictions">
                            <label class="custom-control-label" for="productEventAgeNoRestrictions">No restrictions</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="productEventGenderRestrictions">Gender restrictions</label>
                <div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input" id="product-event-gender-restrictions-male" value="male" name="booking[gender]" v-model="booking.gender">
                        <label class="custom-control-label" for="product-event-gender-restrictions-male">Men Only</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input" id="product-event-gender-restrictions-female" value="female" name="booking[gender]"  v-model="booking.gender">
                        <label class="custom-control-label" for="product-event-gender-restrictions-female">Women Only</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input" id="product-event-gender-restrictions-no" value="no-restrictions" name="booking[gender]"  v-model="booking.gender">
                        <label class="custom-control-label" for="product-event-gender-restrictions-no">No restrictions</label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="levels">Levels</label>
                <input type="hidden" name="booking[levels]" v-model="booking.levels">
                <Select2MultipleControl  v-model="booking.levels" :options="levelsOptions"  ></Select2MultipleControl>
            </div>
        </div>
        <!-- END Form General Info -->
    </script>



    <script>

        var bookingProduct=@json($bookingProduct);
        Vue.component('booking-product-general-information',{
           template:'#booking-product-general-information-template',
           inject: ['$validator'],
           created(){

           } ,
           mounted(){


           } ,
           data: function(){
               return {
                   booking: bookingProduct ? bookingProduct: {
                   name: null,
                   description: null,
                   type: '<?=$product->attribute_family->code;?>',
                   instructions: null,
                   leaders: [],
                   booking_confirmation_message: null,
                   tags: [],
                   min_age: null,
                   max_age: null,
                   age_restrictions: null,
                   gender: null,
                   levels:[],
                   },
                   leadersOptions:[''],
                   tagsOptions:[''],
                   levelsOptions:['beginner','intermediate','advanced'],
                   customNoResults: function() { return 'No results found. Start typing to create a new one.'}
               }
           },
            methods: {
                checkValue: function(){

                }
            }
        });


    </script>



@endpush


