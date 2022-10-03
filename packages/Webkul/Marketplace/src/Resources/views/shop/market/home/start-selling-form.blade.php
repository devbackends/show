<div class="become-member-section__custom-website py-5">
    <user-help-form send-message-url="{{route('marketplace.userHelpForm')}}" token="{{csrf_token()}}"></user-help-form>
</div>
@push('scripts')
    <script type="text/x-template" id="user-help-form-template">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-4 offset-md-2">
                    <p class="font-paragraph-big">Looking for a custom website for your gun store or gun range?<br><span class="font-paragraph-big-bold">We can help with that too!</span></p>
                    <p>The team at 2A Gunshow are not just Second Amendment enthusiasts, but expert developers as well. We can build powerful fully custom e-commerce websites for your business, with the huge bonus of your products automatically listed in our 2A Gun Show marketplace too! Drop us a line to find out more.</p>
                </div>
                <div class="col-12 col-md-4">
                    <form action="" id="marketplace-vendor-info-form">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Your full name" v-model="message.name"/>
                            <div class="invalid-feedback">
                                What's your name?
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Your email" v-model="message.email"/>
                            <div class="invalid-feedback">
                                What's your email?
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea type="text" class="form-control" placeholder="Your message for us" v-model="message.text"></textarea>
                            <div class="invalid-feedback">
                                Please tell us more about you...
                            </div>
                        </div>
                        <button type="submit" class="btn btn-outline-primary" @click="send"><i class="far fa-paper-plane mr-2"></i>Send</button>
                    </form>
                </div>
            </div>
        </div>
    </script>

    <script type="text/javascript">
        (() => {
            Vue.component('user-help-form', {
                template: '#user-help-form-template',
                props: ['sendMessageUrl', 'token'],
                data: () => ({
                    message: {
                        name: '',
                        email: '',
                        text: '',
                    },
                }),
                methods: {
                    send(e) {
                        e.preventDefault();
                        if (this.message.name === '' || this.message.email === '' || this.message.text === '') {
                            this.error('All fields are required')
                            return false
                        }
                        $.ajax(this.sendMessageUrl, {
                            method: 'POST',
                            data: {
                                _token: this.token,
                                message: this.message,
                            },
                            success: () => {
                                window.showAlert('alert-success', 'Success', 'Request sent successfuly');
                                this.message.name = '';
                                this.message.email = '';
                                this.message.text = '';
                            },
                            error: err => {
                                if (err.responseJSON.errors) {
                                    for (let key in err.responseJSON.errors) {
                                        this.error(err.responseJSON.errors[key])
                                    }
                                }
                            }
                        })
                    },
                    error(message) {
                        window.showAlert('alert-danger', 'Error', message);
                    }
                },
            })
        })()
    </script>
@endpush