
<booking-product-date-info></booking-product-date-info>


@push('scripts')

    <script type="text/x-template" id="booking-product-date-info-template">
        <!-- Form Date And Time -->
        <div class="create-product__box" id="booking-product-date-info">
            <p class="create-product__box-title">Date & Time</p>
            <div class="create-product__box-start-date">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="productEventType">What type of event is?</label>
                            <div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" v-validate="'required'" v-on:change="changeTypeOfEvent" v-model="bookingProductDefaultSlot.type_of_event" name="booking[type_of_event]"  value="single-day" id="productEventTypeSingleDay" class="custom-control-input">
                                    <label class="custom-control-label" for="productEventTypeSingleDay">Single Day</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio"  v-validate="'required'" v-on:change="changeTypeOfEvent" v-model="bookingProductDefaultSlot.type_of_event" name="booking[type_of_event]"  value="multi-day" id="productEventTypeMultiDay" class="custom-control-input">
                                    <label class="custom-control-label" for="productEventTypeMultiDay">Multi-day</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio"  v-validate="'required'" v-on:change="changeTypeOfEvent" v-model="bookingProductDefaultSlot.type_of_event" name="booking[type_of_event]"  value="repeating" id="productEventTypeRepeating" class="custom-control-input">
                                    <label class="custom-control-label" for="productEventTypeRepeating">Repeating</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div v-if="booking_type!='event'"  class="form-group" :class="[errors.has('booking[qty]') ? 'has-error' : '' ]">
                <label for="booking_description"># of spots available</label>
                <input type="number" v-model="booking.qty" name="booking[qty]" id="booking_qty" class="form-control"
                        v-validate="'required'"
                        placeholder="# of spot available"
                        data-vv-as="&quot;# of spot available&quot;"/>
                <span class="control-error" v-if="errors.has('booking[quantity]')">@{{ errors.first('booking[quantity]') }}</span>
            </div>
            <input type="hidden" :value="start_date"  name="booking[start_date]" />
            <input type="hidden" v-model="JSON.stringify(sessions)"  name="booking[sessions]" />
            <div  class="row" v-for="(slot,index) in bookingProductDefaultSlot.slots" :key="index" >
                <div class="col-12 col-lg-4" >
                    <div class="form-group mb-3"  :class="[errors.has('booking[slots][' + index + '][day]') ? 'has-error' : '']">
                        <label v-if="index==0" for="productEventStartDate">Start date</label>
                        <label v-else for="productEventStartDate">Additional day</label>
                        <input type="date" v-validate="'required'" class="form-control" :name="'booking[slots][' + index + '][day]'" v-model="bookingProductDefaultSlot.slots[index].day" data-vv-as="&quot;Day&quot;" @change="getSessions">
                        <span class="control-error" v-if="errors.has('booking[slots][' + index + '][day]')">@{{ errors.first('booking[slots][' + index + '][day]') }}</span>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group" v-for="(durationSlot,key) in bookingProductDefaultSlot.slots[index].durations" :key="key" >
                        <label for="productEventStartDate">Duration<i class="fas fa-question-circle form-tooltip-icon" data-toggle="tooltip" data-placement="top" title="How long will your event run for? Select a start and end time."></i></label>
                        <!-- Time slot -->
                        <div class="row mb-3" >
                            <input type="hidden" :name="'booking[slots][' + index + '][durations]['+key+'][slotId]'" v-model="bookingProductDefaultSlot.slots[index].durations[key].slotId" />
                            <div class="col">

                                <div class="form-group mb-1"  :class="[errors.has('booking[slots][' + index + '][durations]['+key+'][from]') ? 'has-error' : '']">
                                    <vue-timepicker @change="changeTime($event,'from' ,index, key)" v-validate="'required'" format="h:mm a" placeholder="Start Time" :name="'booking[slots][' + index + '][durations]['+key+'][from]'" v-model="bookingProductDefaultSlot.slots[index].durations[key].from" data-vv-as="&quot;{{ __('bookingproduct::app.admin.catalog.products.from') }}&quot;" :minute-interval="5"></vue-timepicker>
                                    <span class="control-error" v-if="errors.has('booking[slots][' + index + '][durations]['+key+'][from]')">
                                                        @{{ errors.first('booking[slots][' + index + '][durations]['+key+'][from]') }}

                                    </span>
                                </div>
                            </div>
                            <div class="col">

                                <div class="form-group mb-1" :class="[errors.has('booking[slots][' + index + '][durations]['+key+'][to]') ? 'has-error' : '']" >
                                    <vue-timepicker @change="changeTime($event,'to' ,index, key)" v-validate="'required'" format="h:mm a" placeholder="End Time"  :name="'booking[slots][' + index + '][durations]['+key+'][to]'"    v-model="bookingProductDefaultSlot.slots[index].durations[key].to" data-vv-as="&quot;{{ __('bookingproduct::app.admin.catalog.products.to') }}&quot;" :minute-interval="5"></vue-timepicker>
                                    <span class="control-error" v-if="errors.has('booking[slots][' + index + '][durations]['+key+'][to]')">
                                                        @{{ errors.first('booking[slots][' + index + '][durations]['+key+'][to]') }}

                                    </span>
                                </div>
                            </div>
                            <div  class="col-auto pl-0" v-if="key > 0">
                                <a v-on:click="removeSlot(index,key)" href="javascript:;" class="create-product__delete-icon-button"><i class="far fa-trash-alt"></i></a>
                            </div>
                        </div>
                        <!-- END Time slot -->
                    </div>
                    <div class="col-12">
                        <a v-if="booking_type=='training' && bookingProductDefaultSlot.type_of_event!='multi-day' " v-on:click="addSlot(index)" href="javascript:;" class="create-product__add-button"><i class="far fa-plus"></i>Add Slot</a>
                    </div>
                </div>
                <div  class="col-auto pt-4 px-0" v-if="index > 0">
                    <a v-on:click="removeDay(index)" href="javascript:;" class="create-product__delete-icon-button"><i class="far fa-trash-alt"></i></a>
                </div>

            </div>

            <div class="row" v-if="bookingProductDefaultSlot.type_of_event=='multi-day'">
                <div class="col-12">
                    <a v-on:click="addDate" href="javascript:;" class="create-product__add-button"><i class="far fa-plus"></i>Add Date</a>
                </div>
            </div>


            <!-- Show if it's a repeating event -->
            <div v-if="bookingProductDefaultSlot.type_of_event=='repeating'">

                <div class="form-group">
                    <label for="productEventHowRepeat">How often does it repeat?</label>
                    <div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input v-on:change="getSessions"  type="radio" v-validate="'required'" v-model="bookingProductDefaultSlot.repetition_type" name="booking[repetition_type]" value="weekly" id="productEventHowRepeatWeekly" class="custom-control-input">
                            <label class="custom-control-label" for="productEventHowRepeatWeekly">Repeat weekly</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input v-on:change="getSessions" type="radio" v-validate="'required'" v-model="bookingProductDefaultSlot.repetition_type" name="booking[repetition_type]"  value="monthly"  id="productEventHowRepeatMonthly" class="custom-control-input">
                            <label class="custom-control-label" for="productEventHowRepeatMonthly">Repeat monthly</label>
                        </div>
                    </div>
                </div>
                <div class="form-group" v-if="bookingProductDefaultSlot.repetition_type=='weekly'">
                    <label for="productEventRepeatWeekDay" >Repeat on days of week</label>
                    <div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" id="productEventRepeatWeekDayMon" v-on:change="getSessions" v-model="bookingProductDefaultSlot.repetition_sequence" name="booking[repetition_sequence][]" value="Monday" class="custom-control-input">
                            <label class="custom-control-label" for="productEventRepeatWeekDayMon">Mon</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" id="productEventRepeatWeekDayTue" v-on:change="getSessions" v-model="bookingProductDefaultSlot.repetition_sequence" name="booking[repetition_sequence][]" value="Tuesday" class="custom-control-input">
                            <label class="custom-control-label" for="productEventRepeatWeekDayTue">Tue</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" id="productEventRepeatWeekDayWed" v-on:change="getSessions" v-model="bookingProductDefaultSlot.repetition_sequence" name="booking[repetition_sequence][]" value="Wednesday"  class="custom-control-input">
                            <label class="custom-control-label" for="productEventRepeatWeekDayWed">Wed</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" id="productEventRepeatWeekDayThu" v-on:change="getSessions" v-model="bookingProductDefaultSlot.repetition_sequence" name="booking[repetition_sequence][]" value="Thursday" class="custom-control-input">
                            <label class="custom-control-label" for="productEventRepeatWeekDayThu">Thu</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" id="productEventRepeatWeekDayFri" v-on:change="getSessions" v-model="bookingProductDefaultSlot.repetition_sequence" name="booking[repetition_sequence][]" value="Friday" class="custom-control-input">
                            <label class="custom-control-label" for="productEventRepeatWeekDayFri">Fri</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" id="productEventRepeatWeekDaySat" v-on:change="getSessions" v-model="bookingProductDefaultSlot.repetition_sequence" name="booking[repetition_sequence][]" value="Saturday" class="custom-control-input">
                            <label class="custom-control-label" for="productEventRepeatWeekDaySat">Sat</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" id="productEventRepeatWeekDaySun" v-on:change="getSessions" v-model="bookingProductDefaultSlot.repetition_sequence" name="booking[repetition_sequence][]" value="Sunday"  class="custom-control-input">
                            <label class="custom-control-label" for="productEventRepeatWeekDaySun">Sun</label>
                        </div>
                    </div>
                </div>
                <div class="form-group" v-if="bookingProductDefaultSlot.repetition_type=='monthly'">
                    <label for="productEventRepeatWeekDay" >Repeat on months</label>
                    <div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" v-model="bookingProductDefaultSlot.repetition_sequence" v-on:change="getSessions" name="booking[repetition_sequence][]" value="jan" class="custom-control-input">
                            <label class="custom-control-label" for="productEventRepeatWeekDayMon">Jan</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" v-model="bookingProductDefaultSlot.repetition_sequence" v-on:change="getSessions" name="booking[repetition_sequence][]" value="feb" class="custom-control-input">
                            <label class="custom-control-label" for="productEventRepeatWeekDayTue">Feb</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" v-model="bookingProductDefaultSlot.repetition_sequence" v-on:change="getSessions" name="booking[repetition_sequence][]" value="mar"  class="custom-control-input">
                            <label class="custom-control-label" for="productEventRepeatWeekDayWed">Mar</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" v-model="bookingProductDefaultSlot.repetition_sequence" v-on:change="getSessions" name="booking[repetition_sequence][]" value="apr" class="custom-control-input">
                            <label class="custom-control-label" for="productEventRepeatWeekDayThu">Apr</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" v-model="bookingProductDefaultSlot.repetition_sequence" v-on:change="getSessions" name="booking[repetition_sequence][]" value="may" class="custom-control-input">
                            <label class="custom-control-label" for="productEventRepeatWeekDayFri">May</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" v-model="bookingProductDefaultSlot.repetition_sequence" v-on:change="getSessions" name="booking[repetition_sequence][]" value="jun" class="custom-control-input">
                            <label class="custom-control-label" for="productEventRepeatWeekDaySat">Jun</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" v-model="bookingProductDefaultSlot.repetition_sequence" v-on:change="getSessions" name="booking[repetition_sequence][]" value="jul"  class="custom-control-input">
                            <label class="custom-control-label" for="productEventRepeatWeekDaySun">Jul</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" v-model="bookingProductDefaultSlot.repetition_sequence" v-on:change="getSessions" name="booking[repetition_sequence][]" value="aug"  class="custom-control-input">
                            <label class="custom-control-label" for="productEventRepeatWeekDaySun">Aug</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" v-model="bookingProductDefaultSlot.repetition_sequence" v-on:change="getSessions" name="booking[repetition_sequence][]" value="sep"  class="custom-control-input">
                            <label class="custom-control-label" for="productEventRepeatWeekDaySun">Sep</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" v-model="bookingProductDefaultSlot.repetition_sequence" v-on:change="getSessions" name="booking[repetition_sequence][]" value="oct"  class="custom-control-input">
                            <label class="custom-control-label" for="productEventRepeatWeekDaySun">Oct</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" v-model="bookingProductDefaultSlot.repetition_sequence" v-on:change="getSessions" name="booking[repetition_sequence][]" value="nov"  class="custom-control-input">
                            <label class="custom-control-label" for="productEventRepeatWeekDaySun">Nov</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" v-model="bookingProductDefaultSlot.repetition_sequence" v-on:change="getSessions" name="booking[repetition_sequence][]" value="dec"  class="custom-control-input">
                            <label class="custom-control-label" for="productEventRepeatWeekDaySun">Dec</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="productEventRepeatUntil">Repeat until</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <select v-model="bookingProductDefaultSlot.repeat_until_type" @change="changeRepeatUntilType" name="booking[repeat_until_type]" class="form-control" id="productEventRepeatUntil">
                                        <option value="day">Until set day</option>
                                        <option value="numberOfEvents">For a number of events</option>
                                    </select>
                                </div>
                                <div class="col-md-6"v-if="bookingProductDefaultSlot.repeat_until_type=='day'" :class="[errors.has('booking[repeat_until_value]') ? 'has-error' : '']" >
                                    <!-- If value is setDay -->
                                    <input type="date" v-if="bookingProductDefaultSlot.repeat_until_type=='day'"  v-validate="'required'"  name="booking[repeat_until_value]" v-model="bookingProductDefaultSlot.repeat_until_value" v-on:change="validateDate($event); getSessions();" class="form-control" data-vv-as="&quot;Repeat until date&quot;">
                                    <span class="control-error" v-if="errors.has('booking[repeat_until_value]')">@{{ errors.first('booking[repeat_until_value]') }}</span>
                                </div>
                                <div class="col-md-6" v-if="bookingProductDefaultSlot.repeat_until_type=='numberOfEvents'"  :class="[errors.has('booking[repeat_until_number]') ? 'has-error' : '']" >
                                    <!-- If value is numberEvents -->
                                    <input v-if="bookingProductDefaultSlot.repeat_until_type=='numberOfEvents'" @input="getSessions" @change=" getSessions" v-model="bookingProductDefaultSlot.repeat_until_number" name="booking[repeat_until_number]" v-validate="'required'" class="form-control" type="number" placeholder="5" value="5">
                                    <span class="control-error" v-if="errors.has('booking[repeat_until_number]')">@{{ errors.first('booking[repeat_until_number]') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive" style="max-height: 300px;" v-if="sessions.length > 0">
                    <table class="table table-sm my-3">
                        <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Time</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- for each date -->
                        <tr v-for="(session,i) in sessions" :key="i">
                            <th scope="row" v-text="getFormattedDate(session.date)"></th>
                            <td v-for="(slot,index) in sessions[i].slots" :key="index">
                                <div v-for="(duration,key) in sessions[i].slots[index].durations" :key="key"  v-text="duration.from+' to '+ duration.to"></div>
                            </td>
                            {{--<td class="text-right">--}}
                            {{--<a href="#" class="table-icon-button table-icon-button--edit"><i class="far fa-edit"></i></a>--}}
                            {{--</td>--}}
                        </tr>
                        <!-- END for each date -->
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END Show if it's a repeating event -->
        </div>
        <!-- END Form Date And Time -->
    </script>
    <script>
        var bookingProductInfo=@json($bookingProductDefaultSlot);
        if(!bookingProductInfo.repetition_sequence){
            bookingProductInfo.repetition_sequence=[];
        }
		var bookingProduct=@json($bookingProduct);
        Vue.component('booking-product-date-info', {
            template: '#booking-product-date-info-template',
            inject: ['$validator'],
            data: function() {
                return {
                        bookingProductDefaultSlot: bookingProductInfo ? bookingProductInfo : {

                        end_date: null,
                        type_of_event: 'single-day',
                        repetition_type: null,
                        repetition_sequence: [],
                        repeat_until_type: null,
                        repeat_until_value: null,
                        repeat_until_number: null,
                        slots:[
                            {'day':'',
                            'durations':   [
                                              {'from':"", 'to': "",'slotId': Math.floor(Math.random() * 10000000000)}
                                            ]
                           }
                        ]
                    } ,
                    booking: bookingProduct ? bookingProduct: {
                        qty: null
                    },
                    sessions: [],
                    booking_type: @json(strtolower(app('Webkul\Attribute\Repositories\AttributeFamilyRepository')->find($product->attribute_family_id)->code))

                }
            },
            created: function() {
                this.getSessions();
            },
            computed:{
                start_date(){
                    return this.bookingProductDefaultSlot.slots[0].day;
                }
            },
            methods: {
                addSlot: function (index) {
                    this.bookingProductDefaultSlot.slots[index].durations.push( {'from':"", 'to': "",'slotId': Math.floor(Math.random() * 10000000000) });
                },
                addDate: function(){
                    this.bookingProductDefaultSlot.slots.push({'day':'',
                        'durations':   [
                            {'from':"", 'to': "",'slotId': Math.floor(Math.random() * 10000000000)}
                        ]
                    });
                },
               removeSlot: function(index,key){
                   this.bookingProductDefaultSlot.slots[index].durations.splice(key, 1);
              },
                removeDay: function(index){
                    this.bookingProductDefaultSlot.slots.splice(index, 1);
                },
                arrayRemove: function (arr, value) {

                    return arr.filter(function (ele) {
                        return ele != value;
                    });
                },
                getSessions: function(){
                        this.sessions=[];
                        if(! this.bookingProductDefaultSlot.slots[0].day){
                            return [];
                        }
                        if(this.bookingProductDefaultSlot.repetition_type=='weekly' ){
                            var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                            let dayName='';

                                if(this.bookingProductDefaultSlot.repeat_until_value){
                                     let i=0;
                                    for (var d = new Date(this.start_date); d <= new Date(this.bookingProductDefaultSlot.repeat_until_value); d.setDate(d.getDate() + 1)) {
                                        dayName = days[d.getDay()];
                                        if(this.bookingProductDefaultSlot.repetition_sequence.indexOf(dayName) > -1 ){
                                            this.sessions.push({'day':dayName,'date':d.toString(), 'slots': JSON.parse(JSON.stringify(this.bookingProductDefaultSlot.slots))});
                                            for( let j=0; j < this.sessions[i].slots.length; j++){
                                                this.sessions[i].slots[j].day=d.toString();
                                            }
                                            i+=1;
                                        }
                                    }
                                }
                                if(this.bookingProductDefaultSlot.repeat_until_number){
                                    let i=0;
                                    let counter=0;
                                    for (var d = new Date(this.start_date); d <= new Date(this.start_date).setDate(d.getDate() + 200) ; d.setDate(d.getDate() + 1)) {
                                        if(counter ==this.bookingProductDefaultSlot.repeat_until_number){
                                            break;
                                        }

                                        dayName = days[d.getDay()];
                                        if(this.bookingProductDefaultSlot.repetition_sequence.indexOf(dayName) > -1 ){
                                            this.sessions.push({'day':dayName,'date':d.toString(), 'slots': JSON.parse(JSON.stringify(this.bookingProductDefaultSlot.slots))});
                                            for( let j=0; j < this.sessions[i].slots.length; j++){
                                                this.sessions[i].slots[j].day=d.toString();
                                            }
                                            i+=1;
                                            counter+=1;
                                        }
                                    }
                                }
                        }


                    if(this.bookingProductDefaultSlot.repetition_type=='monthly' ){
                        var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                        var months = ['jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec'];
                        let dayName='';
                        let monthName='';

                        if(this.bookingProductDefaultSlot.repeat_until_value){
                            let i=0;
                            let counter=0;
                            for (var d = new Date(this.start_date); d <= new Date(this.bookingProductDefaultSlot.repeat_until_value); d.setMonth(d.getMonth()+1)) {
                                dayName = days[d.getDay()];
                                monthName = months[d.getMonth()];
                                if(this.bookingProductDefaultSlot.repetition_sequence.indexOf(monthName) > -1 ){
                                    this.sessions.push({'day':dayName,'date':d.toString(), 'slots': JSON.parse(JSON.stringify(this.bookingProductDefaultSlot.slots))});
                                    for( let j=0; j < this.sessions[i].slots.length; j++){
                                        this.sessions[i].slots[j].day=d.toString();
                                    }
                                    i+=1;
                                    counter+=1;
                                }
                                }
                            }

                        if(this.bookingProductDefaultSlot.repeat_until_number){
                            let i=0;
                            let counter=0;
                            const currentDay=new Date(this.start_date);

                            for (var d = new Date(this.start_date); d <= new Date(currentDay.setMonth(currentDay.getMonth()+500)) ; d.setMonth(d.getMonth()+1)) {
                                if(counter ==this.bookingProductDefaultSlot.repeat_until_number){
                                    break;
                                }

                                dayName = days[d.getDay()];
                                monthName = months[d.getMonth()];
                                if(this.bookingProductDefaultSlot.repetition_sequence.indexOf(monthName) > -1 ){
                                    this.sessions.push({'day':dayName,'date':d.toString(), 'slots': JSON.parse(JSON.stringify(this.bookingProductDefaultSlot.slots))});
                                    for( let j=0; j < this.sessions[i].slots.length; j++){
                                        this.sessions[i].slots[j].day=d.toString();
                                    }
                                    i+=1;
                                    counter+=1;
                                }
                            }
                        }

                    }
                    },
                getDayDate: function (d,day) {
                    d = new Date(d);
                    var diff = d.getDate() + day; // adjust when day is sunday
                    return new Date(d.setDate(diff));
                },
                getSunday: function (d) {
                    d = new Date(d);
                    var day = d.getDay();
                        diff = d.getDate() - day; // adjust when day is sunday
                    return new Date(d.setDate(diff));
                },
                validateDate(e){
                    this.bookingProductDefaultSlot.repeat_until_value=e.target.value;
                },
                getFormattedDate(date) {
                    var myDate = new Date(date);
                    var day = myDate.getDate();
                    var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                    var dayName = days[myDate.getDay()];
                    var month = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"][myDate.getMonth()];
                    return dayName+', ' +month + '. ' + day + ', ' + myDate.getFullYear();
                },
                formatAMPM: function (date) {
                    var date = new Date(date);
                    var hours = date.getHours();
                    var minutes = date.getMinutes();
                    var ampm = hours >= 12 ? 'pm' : 'am';
                    hours = hours % 12;
                    hours = hours ? hours : 12; // the hour '0' should be '12'
                    minutes = minutes < 10 ? '0' + minutes : minutes;
                    var strTime = hours + ':' + minutes + ' ' + ampm;
                    return strTime;
                },
                changeTypeOfEvent: function(){

                        this.bookingProductDefaultSlot.slots=[];
                    this.$set(this.bookingProductDefaultSlot.slots, 0,{'day':'',
                        'durations':   [
                            {'from':"", 'to': "",'slotId': Math.floor(Math.random() * 10000000000)}
                        ]
                    });
                    $('.char').trigger( "click" );
/*                        this.bookingProductDefaultSlot.slots[0]= {'day':'',
                            'durations':   [
                                {'from':"", 'to': "",'slotId': Math.floor(Math.random() * 10000000000)},
                                {'from':"", 'to': "",'slotId': Math.floor(Math.random() * 10000000000)},
                          ]
                        };*/

                },
                changeRepeatUntilType: function(){

                    if(this.bookingProductDefaultSlot.repeat_until_type=='day'){
                        this.bookingProductDefaultSlot.repeat_until_number=null;
                    }
                    if(this.bookingProductDefaultSlot.repeat_until_type=='numberOfEvents'){
                        this.bookingProductDefaultSlot.repeat_until_value=null;
                    }
                },
                changeTime: function($event,source ,index, key){
                    this.getSessions();
                }
            }
        });

    </script>
@endpush


