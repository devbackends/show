@extends('marketplace::shop.layouts.account')

@section('page_title')
    {{ __('marketplace::app.shop.sellers.account.messaging.title') }}
@endsection

@section('content')
    <div class="account-layout">
        <input type="hidden" id="customer_id" value="{{auth()->guard('customer')->user()->id}}"/>
        <messages :temp_message="{{ json_encode($temp_message) }}" base_url="{{ $base_url }}"></messages>
    </div>
@endsection

@push('scripts')
    <script type="text/x-template" id="messages-template">
        <div class="settings-page">
            <div class="settings-page__header">
                <div class="settings-page__header-title">
                    <p>{{ __('marketplace::app.shop.sellers.account.messaging.title') }}</p>
                </div>
                <div class="settings-page__header-actions">
                    <div class="messaging__search">
                        <input type="text" placeholder="Search your messages..." class="form-control"
                               v-on:keyup="filteredList()" v-model="search">
                        <a v-on:click="filteredList()" href="javascript:;" class="btn btn-outline-gray"><i
                                class="fas fa-search"></i></a>
                    </div>
                </div>
            </div>

            <div class="settings-page__body">
                <div class="messaging__wrapper">
                    <div class="row">
                        <div class="col-12 col-md-auto  pr-3 pr-md-0">
                            <div class="messaging__menu">
                                <div class="btn-group-vertical btn-group-toggle" data-toggle="buttons">
                                    <label v-on:click="getInboxMessages()" class="btn btn-link messaging__menu-item  active"  :class="[inboxCounter > 0  ? 'has-unread' : '']">
                                        <input type="radio" name="options" id="messagesInbox" checked><span
                                            class="messaging__menu-item-title">Inbox</span><span
                                            class="messaging__menu-item-quantity" v-if="inboxCounter > 0" v-text="inboxCounter"></span>
                                    </label>
                                    <label v-on:click="getSentMessages()"
                                           class="btn btn-link messaging__menu-item">
                                        <input type="radio" name="options" id="messagesSent"><span
                                            class="messaging__menu-item-title">Sent</span><span
                                            class="messaging__menu-item-quantity"></span>
                                    </label>
                                    <label v-on:click="getAllMessages()" class="btn btn-link messaging__menu-item">
                                        <input type="radio" name="options" id="messagesAll"> <span
                                            class="messaging__menu-item-title">All</span><span
                                            class="messaging__menu-item-quantity"></span>
                                    </label>
                                    {{--                                <label v-on:click="getUnreadMessages()" class="btn btn-link messaging__menu-item"
                                                                        id="messaging-menu-item-unread">
                                                                        <input type="radio" name="options" id="messagesUnread"> <span
                                                                            class="messaging__menu-item-title">Unread</span><span
                                                                            class="messaging__menu-item-quantity" v-text="unreadCounter"></span>
                                                                    </label>--}}
                                    <label v-on:click="getSpamMessages()" class="btn btn-link messaging__menu-item">
                                        <input type="radio" name="options" id="messagesSpam"> <span
                                            class="messaging__menu-item-title">Spam</span><span
                                            class="messaging__menu-item-quantity"></span>
                                    </label>
                                    {{--                                <label v-on:click="getTrashMessages()" class="btn btn-link messaging__menu-item"
                                                                        id="messaging-menu-item-trash">
                                                                        <input type="radio" name="options" id="messagesTrash"> <span
                                                                            class="messaging__menu-item-title">Trash</span><span
                                                                            class="messaging__menu-item-quantity" v-text="trashCounter"></span>
                                                                    </label>--}}
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md pl-3 pl-md-0">
                            <div class="messaging__actions">
                                <div class="row align-items-center">
                                    <div class="col-auto pr-0">
                                        <div class="custom-control custom-checkbox">
                                            <input v-on:change="selectAll()" type="checkbox" class="custom-control-input"
                                                   id="selectAll">
                                            <label class="custom-control-label" for="selectAll"></label>
                                        </div>
                                    </div>
                                    <div class="col pl-0">
                                        <div class="messaging__actions-buttons">
                                            <div>
                                                <a v-on:click="addToTrash()" href="javascript:;"
                                                   class="btn btn-link">Archive</a>
                                                <a v-on:click="markAsUnread();goToCurrentStage();" href="javascript:;"
                                                   class="btn btn-link">Mark
                                                    Unread</a>
                                                <a v-on:click="markAsread();goToCurrentStage();" href="javascript:;"
                                                   class="btn btn-link">Mark
                                                    Read</a>
                                                <a type="button" class="btn btn-link" data-toggle="modal"
                                                   data-target="#reportModal">Report</a>
                                                <a v-on:click="markAsSpam()" href="javascript:;" class="btn btn-link">Mark
                                                    as Spam</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-show="isLoading" class="messaging__loading py-4">
                                <loading-spinner />
                            </div>
                            <div v-show="showMessages==1" class="messaging__list">
                                <!-- Message item -->
                                <div>
                                    <div v-for="message in messages" class="messaging__list-item">
                                        <div class="row align-items-center">
                                            <div class="col-auto pr-0 pt-1 pt-md-2">
                                                <div class="custom-control custom-checkbox">
                                                    <input v-on:change="checkCheckbox(message.id)" type="checkbox"
                                                           class="custom-control-input" name="messages"
                                                           :data-messageid="message.id"
                                                           :value="message.id"
                                                           :id="`message-${message.id}`">
                                                    <label class="custom-control-label"
                                                           :for="`message-${message.id}`"></label>
                                                </div>
                                            </div>
                                            <div class="col-auto pr-0 pl-2 pl-md-3">
                                                <img v-if="message.customer_image"
                                                     :src="wassabi_storage+message.customer_image" alt=""
                                                     class="messaging__list-item-image">
                                                <img v-else="message.customer_image"
                                                     src="{{asset('images/user-avatar.png')}}" alt=""
                                                     class="messaging__list-item-image">
                                            </div>

                                            <div class="col pr-md-0 pl-2 pl-md-3">
                                                <a v-on:click="goToMessageDetails(message)" href="javascript:;"
                                                   class="messaging__list-item-name"
                                                   :class="[current_stage=='inbox' && message.read==0 && !message.temp && message.message_details[message.message_details.length - 1].to==<?=auth()->guard('customer')->user()->id?> ? 'bold' : '']"
                                                   v-text="message.customer_name"></a>
                                                <p class="messaging__list-item-text"
                                                   :class="[current_stage=='inbox' && message.read==0 && !message.temp && message.message_details[message.message_details.length - 1].to==<?=auth()->guard('customer')->user()->id?> ? 'bold' : '']"
                                                   v-text="message.subject"></p>
                                                <p class="messaging__list-item-time-mobile d-block d-md-none"
                                                   v-text="message.diff+' ago'"></p>
                                            </div>
                                            <div class="col-auto d-none d-md-block">
                                                <p class="messaging__list-item-time" v-text="message.diff+' ago'"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Message item END -->
                            </div>
                            <!-- Message item -->
                            <div v-show="showMessageDetails==1" class="messaging-thread">
                                <div class="row">
                                    <div class="col-12 col-md-9 pr-0 order-2 order-md-1">
                                        <div class="messaging-thread__subject">
                                            <a target="_blank" v-if="subject.includes(', order #')"
                                               :href="`/marketplace/account/sales/orders/view/${subject.split(', order #')[1].trim()}`"
                                               v-text="subject+', sent on '+placed_at"></a>
                                            <p v-else v-text="subject+', sent on '+placed_at"></p>
                                        </div>
                                        <div class="messaging-thread__chat d-flex flex-column">
                                            <div id="message-details-body" class="messaging-thread__chat-body flex-grow-1">
                                                <div v-for="(detail,index) in messageDetails">
                                                    <div v-if="index==0" class="messaging-thread__chat-date-separator">
                                                        <p v-text="getFormattedDate(detail.created_at)"></p>
                                                    </div>
                                                    <div
                                                        v-if="index > 0 && checkDate(detail.created_at,messageDetails[index -1].created_at)"
                                                        class="messaging-thread__chat-date-separator">
                                                        <p v-text="getFormattedDate(detail.created_at)"></p>
                                                    </div>
                                                    <!-- Message in -->
                                                    <div
                                                        :class="`messaging-thread__chat-message messaging-thread__chat-message--${detail.message_status} d-flex`">
                                                        <img v-if="detail.customer_image"
                                                             :src="wassabi_storage+detail.customer_image"
                                                             alt="" class="messaging-thread__chat-message-customer">
                                                        <div v-if="detail.message_type=='text'"
                                                             class="messaging-thread__chat-message-text"
                                                             v-text="detail.body"></div>
                                                        <div v-if="detail.message_type=='image'"
                                                             class="messaging-thread__chat-message-text messaging-thread__chat-message-image">
                                                            <a target="_blank" :href="wassabi_storage + detail.body">
                                                                <img style="max-width: 240px;max-height: 135px;" :src="wassabi_storage + detail.body"/>
                                                            </a>


                                                        </div>
                                                    </div>
                                                    <!-- Message in END -->
                                                </div>

                                            </div>
                                            <div class="messaging-thread__chat-field-container">
                                                <div class="messaging-thread__chat-field">
                                                    <textarea v-model="query" type="text" class="form-control" id="query_box"></textarea>
                                                    <div class="messaging-thread__chat-field-actions">
                                                        <a href="#" class="btn btn-link"><i class="far fa-image"></i></a>
                                                        <input type="file" accept="image/x-png,image/gif,image/jpeg"
                                                               multiple v-on:change="doSomethingWithFiles($event)"
                                                               id="file-input"
                                                               style="width: 100%;height: 30px;position: absolute;top: 0;bottom: 0;right: 0;left: 0;opacity: 0;cursor: pointer;z-index: 10;">
                                                        <a v-if="isMobileView" href="#" class="btn btn-link"><i
                                                                class="far fa-camera"></i></a>
                                                        <input v-if="isMobileView" type="file"
                                                               accept="image/x-png,image/gif,image/jpeg"
                                                               v-on:change="doSomethingWithFilesFromCamera($event)" multiple
                                                               capture="environment" id="file-input-camera"
                                                               style="width: 100%;height: 30px;position: absolute;top: 30px;bottom: 0;right: 0;left: 0;opacity: 0;cursor: pointer;z-index: 10;">
                                                    </div>
                                                    <div class="row pt-1">
                                                        <div class="col">
                                                            <div v-for="(photo,index) in photos" :key="index"
                                                                 class="messaging-thread__chat-field-uploaded">
                                                                <div class="badge badge-light">
                                                                    <i class='far fa-paperclip mr-2'></i>
                                                                    <span v-text="photo.name"></span>
                                                                    <i class='far fa-times ml-2'
                                                                       v-on:click='removePhoto(photo)'></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-auto">
                                                            <div class="messaging-thread__chat-field-send">
                                                                <a v-on:click="sendMessage" href="javascript:;"
                                                                   class="btn btn-primary mr-auto"><i
                                                                        class="far fa-paper-plane mr-2"></i>Send</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3 order-1 order-md-2">
                                        <div v-if="message" class="messaging-thread__sender">
                                            <div class="row align-items-center">
                                                <div class="col-auto col-md-12 pr-0 pr-md-3">
                                                    <img v-if="message.customer_image"
                                                         :src="wassabi_storage+message.customer_image" alt="">
                                                    <img v-else
                                                         src="{{asset('images/user-avatar.png')}}" alt="">
                                                </div>
                                                <div class="col col-md-12">
                                                    <a v-if="message.shop_url" :href="`/`+message.shop_url"
                                                       class="messaging-thread__sender-name"
                                                       v-text="message.customer_name"></a>
                                                    <p v-else class="" v-text="message.customer_name"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Message item END -->

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="reportModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
                 aria-labelledby="reportModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="POST" @submit.prevent="submitReason">
                            <div class="modal-header">
                                <h5 class="modal-title">Report</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <div class="form-group control-group"
                                     :class="[errors.has('reportReason') ? 'has-error' : '']">
                                    <label for="exampleInputEmail1">Reason</label>
                                    <select class="custom-select" id="reportReason" name="reportReason"
                                            v-model="reportReason" v-validate="'required'"
                                            data-vv-as="&quot;Reason&quot;">

                                        <option value="shipment damage">shipment damage</option>
                                        <option value="shipment not received">shipment not received</option>
                                    </select>
                                </div>
                                <div class="form-group mb-0 control-group"
                                     :class="[errors.has('reportDetail') ? 'has-error' : '']">
                                    <label for="exampleInputEmail1">More details about the issue</label>
                                    <textarea type="text" class="form-control" id="reportDetail"
                                              aria-describedby="emailHelp" name="reportDetail" v-model="reportDetail"
                                              v-validate="'required'" data-vv-as="&quot;Details&quot;"></textarea>
                                    <small id="emailHelp" class="form-text text-muted">Ut amet non Lorem aliquip eu et
                                        sunt fugiat qui deserunt.</small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="text/javascript">
        (() => {
            Vue.component('messages', {
                template: '#messages-template',
                props: ['temp_message', 'base_url'],
                data: function () {
                    return {
                        messages: [],
                        message_id: '',
                        message: '',
                        inboxCounter: 0,
                        sentCounter: 0,
                        allCounter: 0,
                        unreadCounter: 0,
                        spamCounter: 0,
                        trashCounter: 0,
                        showMessages: 0,
                        showMessageDetails: 0,
                        messageDetails: [],
                        subject: '',
                        placed_at: '',
                        selectedMessages: [],
                        search: '',
                        current_stage: '',
                        photos: [],
                        query: '',
                        receiver_cutomer_id: '',
                        wassabi_storage: '<?= getenv("WASSABI_STORAGE") . "/"?>',
                        isMobileView: this.$root.isMobile(),
                        reportReason: null,
                        reportDetail: '',
                        isLoading: true
                    }
                },
                async mounted() {
                    await this.getInboxMessages().catch(console.log);
                    if(this.temp_message){
                        this.createNewMessage();
                    }
                    @if(isset($message_id))
                    var i;
                    for (i = 0; i < this.messages.length; i++) {
                        if(this.messages[i].id==<?=$message_id?>){
                            this.goToMessageDetails(this.messages[i])
                            break;
                        }
                    }
                    @endif
                },
                computed: {

                },
                methods: {
                    createNewMessage(){
                        this.messages.push(this.temp_message);
                        this.goToMessageDetails(this.messages[this.messages.length -1]);
                        this.isLoading = false;
                        this.query = this.temp_message.query;
                        setTimeout(() => {
                            $('#query_box').focus();
                        }, 200);
                    },
                    async getInboxMessages() {
                        this.current_stage = 'inbox';
                        var url = this.base_url + "/inbox"
                        var error = null;
                        this.isLoading = true;
                        var response = await axios.get(url).catch(e => error = e);
                        this.showMessages = 1;
                        this.showMessageDetails = 0;
                        if (error) {
                            this.showError(error);
                        }
                        if (response.status == 200) {
                            if (this.showMessages == 1) {
                                this.messages = response.data.messages;

                                //   $('.messaging__menu-item').removeClass('active');

                            }
                            this.isLoading = false;
                            this.inboxCounter = response.data.unread;
                        }



                    },
                    async getSentMessages() {
                        this.current_stage = 'sent';
                        var url = this.base_url + "/sent";
                        var error = null;
                        var response = await axios.get(url).catch(e => error = e);
                        this.showMessageDetails = 0;
                        this.showMessages = 1;
                        if (error) {
                            this.showError(error);
                        }
                        if (response.status == 200) {
                            if (this.showMessages == 1) {
                                this.messages = response.data;
                            }
                            this.sentCounter = response.data.length;
                        }



                    },
                    async getAllMessages() {
                        this.current_stage = 'spam';
                        this.showMessageDetails = 0;
                        this.showMessages = 1;
                        var url = this.base_url + "/all";
                        var error = null;
                        var response = await axios.get(url).catch(e => error = e);
                        if (error) {
                            this.showError(error);
                        }
                        if (response.status == 200) {
                            if (this.showMessages == 1) {
                                this.messages = response.data;
                            }
                            this.allCounter = response.data.length;
                        }



                    },
                    async getSpamMessages() {
                        this.current_stage = 'all';
                        this.showMessageDetails = 0;
                        this.showMessages = 1;
                        var url = this.base_url + "/spam";
                        var error = null;
                        var response = await axios.get(url).catch(e => error = e);
                        if (error) {
                            this.showError(error);
                        }
                        if (response.status == 200) {
                            if (this.showMessages == 1) {
                                this.messages = response.data;
                            }
                            this.spamCounter = response.data.length;
                        }


                    },
                    async getTrashMessages() {
                        this.current_stage = 'trash';
                        this.showMessageDetails = 0;
                        this.showMessages = 1;
                        var url = this.base_url + "/trash";
                        var error = null;
                        var response = await axios.get(url).catch(e => error = e);
                        if (error) {
                            this.showError(error);
                        }
                        if (response.status == 200) {
                            if (this.showMessages == 1) {
                                this.messages = response.data;
                            }
                            this.trashCounter = response.data.length;
                        }


                    },
                    async getUnreadMessages() {
                        this.current_stage = 'unread';
                        this.showMessageDetails = 0;
                        this.showMessages = 1;
                        var url = this.base_url + "/unread";
                        var error = null;
                        var response = await axios.get(url).catch(e => error = e);
                        if (error) {
                            this.showError(error);
                        }
                        if (response.status == 200) {
                            if (this.showMessages == 1) {
                                this.messages = response.data;
                            }
                            this.unreadCounter = response.data.length;
                        }


                    },
                    goToMessageDetails(message) {
                        this.showMessageDetails = 1;
                        this.showMessages = 0;
                        this.messageDetails = message.message_details;
                        this.message = message;
                        this.subject = message.subject;
                        this.placed_at = this.getFormattedDate(message.created_at);
                        this.receiver_cutomer_id = message.customer_id;
                        this.message_id = message.id;
                        // when you click on the message we have to put as read
                        this.selectedMessages.splice(0, this.selectedMessages.length)
                        this.selectedMessages.push(message.id);
                        this.markAsread();
                        //this is to clean the selected message array after mark it as read
                        this.selectedMessages.splice(0, this.selectedMessages.length)
                        this.checkCheckbox(message.id);
                        this.updateScroll();


                    },
                    getFormattedDate(date) {

                        var myDate = new Date(date);
                        var day = myDate.getDate();
                        var month = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"][myDate.getMonth()];
                        return month + ' ' + day + ', ' + myDate.getFullYear();
                    },
                    checkDate(date1, date2) {
                        if (this.getFormattedDate(date1) !== this.getFormattedDate(date2)) {

                            return true;
                        }
                        return false;
                    },
                    selectAll() {
                        var checkboxes = document.getElementsByName('messages');
                        var selectAll = document.getElementById('selectAll');


                        if (selectAll.checked) {
                            for (var checkbox of checkboxes) {

                                checkbox.checked = 'checked';
                                this.checkCheckbox(checkbox.value);
                            }

                        } else {
                            for (var checkbox of checkboxes) {
                                checkbox.checked = false;
                                this.checkCheckbox(checkbox.value);
                            }
                        }


                    },
                    async addToTrash() {
                        if (this.selectedMessages.length > 0) {
                            var url = this.base_url + "/add-to-trash";
                            var error = null;
                            data = {
                                selected_messages: this.selectedMessages
                            };
                            var response = await axios.post(url, data).catch(e => error = e);
                            if (error) {
                                this.showError(error);
                            }
                            if (response.status == 200) {
                                if (response.data.status == 'success') {
                                    this.goToCurrentStage();
                                    this.getTrashMessages();
                                    $('.messaging__menu-item').removeClass('active');
                                    $('#messaging-menu-item-trash').addClass('active');
                                }
                            }


                        } else {
                            this.$toast.error('Select a message you want to add to Archive', {
                                position: 'top-right',
                                duration: 3000,
                            });
                        }
                    },
                    async markAsUnread() {
                        if (this.selectedMessages.length > 0) {
                            var url = this.base_url + "/mark-as-unread";
                            var error = null;
                            data = {
                                selected_messages: this.selectedMessages
                            };
                            var response = await axios.post(url, data).catch(e => error = e);
                            if (error) {
                                this.showError(error);
                            }
                            if (response.status == 200) {
                                if (response.data.status == 'success') {
                                    /*     this.getUnreadMessages();
                                         $('.messaging__menu-item').removeClass('active');
                                         $('#messaging-menu-item-unread').addClass('active');*/
                                }
                            }


                        } else {
                            this.$toast.error('Select a message you want to add to unread', {
                                position: 'top-right',
                                duration: 3000,
                            });
                        }
                    },
                    async markAsread() {
                        if (this.selectedMessages.length > 0) {
                            var url = this.base_url + "/mark-as-read";
                            var error = null;
                            data = {
                                selected_messages: this.selectedMessages
                            };
                            var response = await axios.post(url, data).catch(e => error = e);
                            if (error) {
                                this.showError(error);
                            }
                            if (response.status == 200) {
                                if (response.data.status == 'success') {
                                    // this.goToCurrentStage();
                                    /*                   $('.messaging__menu-item').removeClass('active');
                                                       $('#messaging-menu-item-unread').addClass('active');*/
                                }
                            }


                        } else {
                            this.$toast.error('Select a message you want to add to read', {
                                position: 'top-right',
                                duration: 3000,
                            });

                        }
                    },
                    async markAsSpam() {
                        if (this.selectedMessages.length > 0) {
                            var url = this.base_url + "/mark-as-spam";
                            var error = null;
                            data = {
                                selected_messages: this.selectedMessages
                            };
                            var response = await axios.post(url, data).catch(e => error = e);
                            if (error) {
                                this.showError(error);
                            }
                            if (response.status == 200) {
                                if (response.data.status == 'success') {
                                    this.getSpamMessages();
                                    $('.messaging__menu-item').removeClass('active');
                                    $('#messaging-menu-item-unread').addClass('active');
                                }
                            }


                        } else {
                            this.$toast.error('Select a message you want to add to spam', {
                                position: 'top-right',
                                duration: 3000,
                            });

                        }
                    },
                    checkCheckbox(message_id) {
                        if (this.selectedMessages.includes(message_id)) {
                            const index = this.selectedMessages.indexOf(message_id);
                            if (index > -1) {
                                this.selectedMessages.splice(index, 1);
                            }
                        } else {
                            this.selectedMessages.push(message_id);
                        }


                    },
                    filteredList() {

                        var result = this.messages.filter(message => {

                            $customer_result = message.customer_name.toLowerCase().includes(this.search.toLowerCase());
                            if ($customer_result) {
                                return $customer_result;
                            }
                            $subject_result = message.subject.toLowerCase().includes(this.search.toLowerCase());
                            if ($subject_result) {
                                return $subject_result;
                            }
                        });


                        if (this.search == '') {
                            this.goToCurrentStage();
                        } else {

                            this.messages = result;


                        }


                    },
                    goToCurrentStage() {
                        if (this.current_stage == 'inbox') {
                            this.getInboxMessages();
                        }
                        if (this.current_stage == 'sent') {
                            this.getSentMessages();
                        }
                        if (this.current_stage == 'all') {
                            this.getAllMessages();
                        }
                        /* if (this.current_stage == 'unread') {
                             this.getUnreadMessages();
                         }*/
                        if (this.current_stage == 'spam') {
                            this.getSpamMessages();
                        }
                        if (this.current_stage == 'trash') {
                            this.getTrashMessages();
                        }
                    },
                    capitalizeFirstLetter(string) {
                        return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
                    },
                    doSomethingWithFiles() {
                        const validImageTypes = ["image/jpg", "image/gif", "image/jpeg", "image/png"];
                        var files = $('#file-input')[0].files; //where files would be the id of your multi file input
                        for (var i = 0, f; f = files[i]; i++) {
                            const fileType = f["type"];
                            if (validImageTypes.includes(fileType)) {
                                this.photos.push(f);
                            } else {
                                this.$toast.error('Please try to upload a valid image', {
                                    position: 'top-right',
                                    duration: 3000,
                                });
                            }
                        }
                    },
                    doSomethingWithFilesFromCamera() {
                        const validImageTypes = ["image/jpg", "image/gif", "image/jpeg", "image/png"];
                        var files = $('#file-input-camera')[0].files; //where files would be the id of your multi file input
                        for (var i = 0, f; f = files[i]; i++) {
                            const fileType = f["type"];
                            if (validImageTypes.includes(fileType)) {
                                this.photos.push(f);
                            } else {
                                this.$toast.error('Please try to upload a valid image', {
                                    position: 'top-right',
                                    duration: 3000,
                                });
                            }
                        }
                    },
                    removePhoto(photo) {
                        const index = this.photos.indexOf(photo);
                        if (index > -1) {
                            this.photos.splice(index, 1);
                        }
                    },
                    async sendMessage() {
                        if (this.query.length > 1) {
                            var url = this.base_url + "/send-message";
                            var error = null;
                            data = {
                                'subject': this.subject,
                                'query': this.query,
                                'from': <?= auth()->guard('customer')->user()->id ?>,
                                'to': this.receiver_cutomer_id
                            };
                            this.isLoading = true;
                            var response = await axios.post(url, data).catch(e => error = e);
                            if (error) {
                                this.showError(error);
                            }
                            if (response.status == 200) {
                                this.query = '';
                                this.$toast.success('Message Sent Successfuly', {
                                    position: 'top-right',
                                    duration: 3000,
                                });
                                this.isLoading = false;
                            }


                        }
                        if (this.photos) {
                            var url = this.base_url + "/upload-photos";
                            var error = null;
                            const config = {headers: {'Content-Type': 'multipart/form-data'}};
                            const form_data = new FormData();

                            for (var i = 0; i <= this.photos.length; i++) {
                                form_data.append('photo-' + i, this.photos[i]);
                            }
                            form_data.append('number-of-images', this.photos.length + 1);
                            form_data.append('from', <?= auth()->guard('customer')->user()->id ?>);
                            form_data.append('to', this.receiver_cutomer_id);

                            var response = await axios.post(url, form_data, config).catch(e => error = e);
                            if (error) {
                                this.showError(error);
                            }
                            if (response.status == 200) {
                                this.photos = [];
                                this.getMessageDetails(this.message_id);
                            }


                        }
                    },
                    async getMessageDetails(message_id) {

                        var url = this.base_url + "/get-message-details/" + message_id;
                        var error = null;
                        var response = await axios.get(url).catch(e => error = e);
                        if (error) {
                            this.showError(error);
                        }
                        if (response.status == 200) {
                            this.messageDetails = response.data;
                        }


                    },
                    updateScroll() {

                        var element = document.getElementById("message-details-body");

                        setTimeout(function () {
                            element.scrollTop = element.scrollHeight;
                        }, 100);
                    },
                    submitReason() {
                        if (this.selectedMessages.length > 0) {
                            this.$validator.validateAll().then((result) => {
                                if (result) {
                                    var thisthis = this;
                                    thisthis.formAction = 'messages/report';
                                    this.$http.post(thisthis.formAction, {
                                        reportReason: thisthis.reportReason,
                                        reportDetail: thisthis.reportDetail,
                                        selected_messages: this.selectedMessages

                                    })
                                        .then((response) => {
                                            if (response.status == 200) {
                                                $('#reportModal').modal('hide');
                                                this.$toast.success('Message reported successfully', {
                                                    position: 'top-right',
                                                    duration: 5000,
                                                });
                                                this.reportReason = '';
                                                this.reportDetail = '';

                                            }

                                        })
                                        .catch(function (error) {
                                        });
                                }
                            });
                        } else {
                            this.$toast.error('Select a message you want to report', {
                                position: 'top-right',
                                duration: 3000,
                            });
                        }
                    }
                }
            });
        })()
    </script>
@endpush
