@extends('marketplace::shop.layouts.master')

@section('page_title')
Contact us
@stop

@section('seo')
<meta name="description" content="Community" />
<meta name="keywords" content="Community" />
@stop

@section('content-wrapper')

<div class="container py-5">
    <div class="row">
        <div class="col-12 col-md-8 offset-md-2">
            <div class="mb-4">
                <h2>Questions? Comments?</h2>
                <p>
                    The 2A team has worked hard to create an online community that takes pride in gun ownership. We will continue to work hard to improve our firearms community and our merchant sellers’ and buyers’ Marketplace experience. Transparency and communication are vital to us, so if you feel we have not delivered, please feel free to reach out.
                </p>
            </div>
            <user-help-form send-message-url="{{route('marketplace.userHelpForm')}}" token="{{csrf_token()}}"></user-help-form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script type="text/x-template" id="user-help-form-template">
    <form action="" id="marketplace-vendor-info-form">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Your full name" v-model="message.name"/>
                    <div class="invalid-feedback">
                        What's your name?
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Your email" v-model="message.email"/>
                    <div class="invalid-feedback">
                        What's your email?
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <textarea type="text" class="form-control" placeholder="Your message for us" v-model="message.text"></textarea>
                    <div class="invalid-feedback">
                        Please tell us more about you...
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-outline-primary" @click="send"><i class="far fa-paper-plane mr-2"></i>Send</button>
    </form>
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