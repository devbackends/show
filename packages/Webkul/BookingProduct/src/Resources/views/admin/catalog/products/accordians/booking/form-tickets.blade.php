<booking-product-tickets></booking-product-tickets>

@push('scripts')
<script type="text/x-template" id="booking-product-tickets-template">
    <!-- Form Booking -->
    <div class="create-product__box" id="booking-product-tickets">
        <p class="create-product__box-title">Tickets & Costs</p>
        <div class="row">
            <div class="col-12 col-lg">
                <div class="form-group" :class="[errors.has('booking[maximum_event_size]') ? 'has-error' : '' ]">
                    <label for="product-tickets-max-size">Maximum event size</label>
                    <input type="number" class="form-control" id="product-tickets-max-size" name="booking[maximum_event_size]" v-model="productTicketsMaxSize"   data-vv-as="&quot;Maximum event size&quot;" placeholder="Set maximum event size">
                    <span class="control-error" v-if="errors.has('booking[maximum_event_size]')">@{{ errors.first('booking[maximum_event_size]') }}</span>
                </div>
            </div>
            <div class="col-12 col-lg">
                <div class="form-group" :class="[errors.has('booking[maximum_ticket_per_booking]') ? 'has-error' : '' ]">
                    <label for="product-tickets-max-tickets-per-booking">Maximum tickets one user can purchase</label>
                    <input type="number" class="form-control" id="product-tickets-max-tickets-per-booking" name="booking[maximum_ticket_per_booking]" v-model="maxTicketsPerBooking"   data-vv-as="&quot;Maximum tickets one user can purchase&quot;" placeholder="Set Maximum tickets one user can purchase">
                    <span class="control-error" v-if="errors.has('booking[maximum_ticket_per_booking]')">@{{ errors.first('booking[maximum_ticket_per_booking]') }}</span>
                </div>
            </div>
        </div>
        <input type="hidden" :value="getLowestPrice" name="price">
        <input type="hidden" v-model="JSON.stringify(tickets)" name="booking[tickets]">
        <div class="create-product__ticket-list" v-if="tickets && tickets.length > 0">
            <div class="create-product__ticket-item" v-for="(ticket,index) in tickets" :key="index">

                <div class="row align-items-stretch">

                    <div class="col-auto create-product__ticket-drag-icon"><i class="fal fa-bars"></i></div>
                    <div class="col-auto create-product__ticket-icon"><i class="far fa-ticket-alt"></i></div>
                    <div class="col">
                        <p class="create-product__ticket-name" v-text="ticket.productTicketName"></p>
                        <p class="create-product__ticket-description"  v-text="ticket.ticketDetails"></p>
                    </div>
                    {{--<div class="col-auto"><p class="create-product__ticket-price">$20.00</p></div>--}}
                    <!-- When clicking the edit button it should open the "create product" box with the ticket information and the option to delete the ticket -->
                    <div class="col-auto"><a v-on:click="ShowEditTicketForm(index)" href="javascript:;" class="create-product__ticket-edit"><i class="far fa-edit"></i></a></div>
                </div>
            </div>
        </div>
        <!-- The "create new ticket" box should be show when there's no tickets created. Then should be opened when the "edit ticket" or the "add ticket" buttons are clicked -->

        <!-- edit-ticket box -->
        <div class="create-product__inner-box" v-if="showEditTicket">
            <div class="row">
                <div class="col">
                    <!-- The "New ticket" title should be replaced with the ticket name when user fill that field -->
                    <p class="create-product__inner-box-title">Edit Ticket</p>
                </div>
                <div class="col-auto">
                    <a v-on:click="showEditTicket=false" href="javascript:;" class="create-product__inner-box-close"><i class="far fa-times"></i></a>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg">
                    <div class="form-group" :class="[errors.has('update-product-ticket-name') ? 'has-error' : '' ]">
                        <label for="update-product-ticket-name" class="form-label-required">Ticket name</label>
                        <input type="text" id="update-product-ticket-name" name="update-product-ticket-name" v-model="tickets[editIndex].productTicketName" class="form-control" placeholder="Enter ticket name"   v-validate="'required'"  data-vv-as="&quot;Product Ticket Name&quot;" >
                        <span class="control-error" v-if="errors.has('update-product-ticket-name')">@{{ errors.first('update-product-ticket-name') }}</span>
                    </div>
                </div>
                <div class="col-12 col-lg-auto">
                    <div class="form-group">
                        <div class="custom-control custom-switch custom-switch--no-label">
                            <input type="checkbox" class="custom-control-input" id="update-show-ticket" v-model="tickets[editIndex].showTicket" name="update-show-ticket" >
                            <label class="custom-control-label" for="update-show-ticket">Active</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group" :class="[errors.has('update-product-ticket-details') ? 'has-error' : '' ]">
                <label for="update-product-ticket-details">Ticket details</label>
                <input type="text" id="update-product-ticket-details" name="update-product-ticket-details" v-model="tickets[editIndex].ticketDetails"  class="form-control" placeholder="Describe your event, so bookers know what to expect">
                <span class="control-error" v-if="errors.has('update-product-ticket-details')">@{{ errors.first('update-product-ticket-details') }}</span>
            </div>
            <div class="form-group">
                <label for="update-product-ticket-price" class="form-label-required">Ticket price</label>
                <input type="text" id="update-product-ticket-price"  name="update-product-ticket-price" v-model="tickets[editIndex].ticketPrice"  class="form-control" placeholder="Price" v-validate="'required'">
                <span class="control-error" v-if="errors.has('update-product-ticket-price')">@{{ errors.first('update-product-ticket-price') }}</span>
            </div>
            <div class="row">
                <div class="col-12 col-lg">
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="update-restrict-when-available" v-model="tickets[editIndex].restrictWhenAvailable" name="update-restrict-when-available" >
                            <label class="custom-control-label" for="update-restrict-when-available">Restrict when this ticket will be available?</label>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-auto">
                    <input type="date" id="update-restriction-date" class="form-control" name="update-restriction-date" v-model="tickets[editIndex].restrictionDate" data-vv-as="&quot;Restriction Date&quot;">
                </div>
            </div>
            <div class="form-group" :class="[errors.has('update-nb-of-available-tickets') ? 'has-error' : '' ]">
                <label for="update-nb-of-available-tickets" class="">Number of tickets available</label>
                <input type="number" id="update-nb-of-available-tickets" name="update-nb-of-available-tickets" class="form-control" v-model="tickets[editIndex].nbOfAvailableTickets"  data-vv-as="&quot;Number of tickets available&quot;" placeholder="Enter how many tickets are available">
                <span class="control-error" v-if="errors.has('update-nb-of-available-tickets')">@{{ errors.first('update-nb-of-available-tickets') }}</span>
            </div>
            <div class="row">
                <div class="col">
                    <a v-on:click="editTicket(editIndex)" href="javascript:;" class="btn btn-primary">Update ticket</a>
                </div>
                <!-- The delete ticket button should just be shown just when user is editing any existing ticket -->
                <div class="col text-right">
                    <a v-on:click="showEditTicket=false" href="javascript:;" class="btn btn-link create-product__inner-box-delete">Delete ticket</a>
                </div>
            </div>
        </div>
        <!-- END edit ticket box -->



        <!-- Create new ticket box -->
        <div class="create-product__inner-box" v-if="tickets.length==0 || showAddTicket">
            <div class="row">
                <div class="col">
                    <!-- The "New ticket" title should be replaced with the ticket name when user fill that field -->
                    <p class="create-product__inner-box-title">New Ticket</p>
                </div>
                <div class="col-auto">
                    <a href="#" class="create-product__inner-box-close"><i class="far fa-times"></i></a>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg">
                    <div class="form-group" :class="[errors.has('product-ticket-name') ? 'has-error' : '' ]">
                        <label for="product-ticket-name" class="form-label-required">Ticket name</label>
                        <input type="text" id="product-ticket-name" name="product-ticket-name" v-model="productTicketName" class="form-control" placeholder="Enter ticket name"   v-validate="'required'"  data-vv-as="&quot;Product Ticket Name&quot;" >
                        <span class="control-error" v-if="errors.has('product-ticket-name')">@{{ errors.first('product-ticket-name') }}</span>
                    </div>
                </div>
                <div class="col-12 col-lg-auto">
                    <div class="form-group">
                        <div class="custom-control custom-switch custom-switch--no-label">
                            <input type="checkbox" class="custom-control-input" id="show-ticket" name="show-ticket" v-model="showTicket">
                            <label class="custom-control-label" for="show-ticket">Active</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="product-ticket-details">Ticket details</label>
                <input type="text" id="product-ticket-details" name="product-ticket-details" v-model="ticketDetails" class="form-control" placeholder="Describe your event, so bookers know what to expect">
            </div>
            <div class="form-group" :class="[errors.has('product-ticket-price') ? 'has-error' : '' ]">
            <label for="product-ticket-price" class="form-label-required">Ticket price</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">$</span>
                </div>
                <input type="text" id="product-ticket-price" name="product-ticket-price"  class="form-control" placeholder="Price"  v-model="ticketPrice" v-validate="'required'">
            </div>
                <span class="control-error" v-if="errors.has('product-ticket-price')">@{{ errors.first('product-ticket-price') }}</span>


            </div>
            <div class="row">
                <div class="col-12 col-lg">
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="restrict-when-available" value="1" name="restrict-when-available" v-model="restrictWhenAvailable">
                            <label class="custom-control-label" for="restrict-when-available">Restrict when this ticket will be available?</label>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-auto">
                    {{--<date>--}}
                        <input type="date" id="restriction-date" name="restriction-date" v-model="restrictionDate" class="form-control control" data-vv-as="&quot;Restriction Date&quot;">
                    {{--</date>--}}
                </div>
            </div>
            <div class="form-group" :class="[errors.has('nb-of-available-tickets') ? 'has-error' : '' ]">
                <label for="nb-of-available-tickets" class="">Number of tickets available</label>
                <input type="number" id="nb-of-available-tickets" name="nb-of-available-tickets" class="form-control" v-model="nbOfAvailableTickets" data-vv-as="&quot;Number of tickets available&quot;" placeholder="Enter how many tickets are available">
                <span class="control-error" v-if="errors.has('nb-of-available-tickets')">@{{ errors.first('nb-of-available-tickets') }}</span>
            </div>
            <div class="row">
                <div class="col">
                    <a v-on:click="addNewTicket" href="javascript:;" class="btn btn-primary">Save ticket</a>
                </div>
                <!-- The delete ticket button should just be shown just when user is editing any existing ticket -->
                <div class="col text-right">
                    <a v-on:click="showAddTicket=false" href="javascript:;" class="btn btn-link create-product__inner-box-delete">Delete ticket</a>
                </div>
            </div>
        </div>
        <!-- END Create new ticket box -->
        <!-- Hide the "Add ticket" button when the create box is open -->
        <a v-on:click="showAddTicket=true" href="javascript:;" class="create-product__add-button mt-3"><i class="far fa-plus"></i>Add ticket</a>
        <!-- END Hide the "Add ticket" button when the create box is open -->
    </div>
    <!-- END Form Booking -->
</script>
    <script>
        const event_tickets=@json($event_tickets);
        Vue.component('booking-product-tickets',{
            template: '#booking-product-tickets-template',
            inject: ['$validator'],
            data: function(){
                return {
                    productTicketsMaxSize: event_tickets ? event_tickets.maximum_event_size : null,
                    maxTicketsPerBooking: event_tickets ? event_tickets.maximum_ticket_per_booking : null,
                    tickets: event_tickets ? event_tickets.tickets ? event_tickets.tickets : []  : [],
                    productTicketName: '',
                    showTicket: true,
                    ticketDetails: null,
                    restrictWhenAvailable: false,
                    restrictionDate: null,
                    nbOfAvailableTickets: null,
                    courseTicket: false,
                    groupOrFamilyTicket: false,
                    showAddTicket: false,
                    showEditTicket: false,
                    ticketPrice: 0,
                    ticketId:Math.floor(Math.random() * 10000000000),
                    editIndex:null
                }
            },
            computed: {
                getLowestPrice(){
                    let price =0;
                    let myArray=this.tickets;
                    myArray.sort(function (a, b) {
                        return a.ticketPrice - b.ticketPrice
                    });
                    if(myArray.length > 0){
                        price = myArray[0].ticketPrice;
                    }
                    return price;
                }
            },
            methods: {
                addNewTicket: function(){
                   if(this.productTicketName && this.ticketPrice){
                        this.tickets.push({
                            'productTicketName': this.productTicketName,
                            'showTicket': true,
                            'ticketDetails': this.ticketDetails,
                            'restrictWhenAvailable': this.restrictWhenAvailable,
                            'restrictionDate': this.restrictionDate,
                            'nbOfAvailableTickets': this.nbOfAvailableTickets,
                            'courseTicket': this.courseTicket,
                            'ticketPrice':this.ticketPrice,
                            'groupOrFamilyTicket': this.groupOrFamilyTicket,
                            'ticketId': Math.floor(Math.random() * 10000000000) });

                       this.showAddTicket=false;
                       this.productTicketName= '';
                       this.showTicket= true;
                       this.ticketDetails= null;
                       this.restrictWhenAvailable= false;
                       this.restrictionDate= null;
                       this.nbOfAvailableTickets= null;
                       this.courseTicket= false;
                       this.groupOrFamilyTicket= false;

                   }else{
                       window.showAlert(`alert-danger`, 'Error', 'You have to fill all the fields in ticket form');
                   }
                },
                editTicket: function(index){
                   window.showAlert(`alert-success`, 'Success', 'Your ticket is updated succesfuly');
                    this.showEditTicket=false;
                },
                ShowEditTicketForm: function(index){
                    this.showEditTicket=true;
                    this.editIndex=index;

                }
            }
        });
    </script>
@endpush


