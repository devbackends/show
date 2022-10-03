<template>
    <form method="POST" data-vv-scope="contact-form" @submit.prevent="contactSeller('contact-form')">
        <div class="form-group" :class="[errors.has('contact-form.name') ? 'has-error' : '']">
            <input type="text" class="form-control" placeholder="Name" v-model="contact.name"
                name="name" v-validate="'required'" data-vv-as="&quot;Name&quot;">
            <span class="control-error" v-if="errors.has('contact-form.name')">@{{ errors.first('contact-form.name') }}</span>
        </div>
        <div class="form-group" :class="[errors.has('contact-form.email') ? 'has-error' : '']">
            <input type="email" class="form-control" placeholder="Email" v-model="contact.email"
                name="email" v-validate="'required|email'" data-vv-as="&quot;Email&quot;">
            <span class="control-error" v-if="errors.has('contact-form.email')">@{{ errors.first('contact-form.email') }}</span>
        </div>
        <div class="form-group"
             :class="[errors.has('contact-form.subject') ? 'has-error' : '']">
            <input type="text" class="form-control" placeholder="Subject"
                v-model="contact.subject" name="subject" v-validate="'required'" data-vv-as="&quot;Subject&quot;">
            <span class="control-error" v-if="errors.has('contact-form.subject')">@{{ errors.first('contact-form.subject') }}</span>
        </div>
        <div class="form-group" :class="[errors.has('contact-form.query') ? 'has-error' : '']">
            <textarea class="form-control" placeholder="Message" v-model="contact.query"
                name="query" v-validate="'required'" data-vv-as="&quot;Query&quot;"></textarea>
            <span class="control-error" v-if="errors.has('contact-form.query')">@{{ errors.first('contact-form.query') }}</span>
        </div>
        <input type="text" style="display: none" v-model="captcha">
        <div class="text-right">
            <submit-button :disabled="disable_button" text="Send message" :loading="isLoading"></submit-button>
        </div>
    </form>
</template>

<script>
export default {
    props: ['customer', 'url'],
    data: () => ({
        contact: {
            name: '',
            email: '',
            subject: '',
            query: ''
        },
        captcha: '',
        disable_button: false,
        isLoading: false
    }),

    mounted() {
        if (this.customer) {
            this.contact.email = this.customer.email;
            this.contact.name = this.customer.first_name + ' ' + this.customer.last_name;
        }
    },

    methods: {
        contactSeller(formScope) {
            this.isLoading = true;
            this.disable_button = true;
            if (this.captcha === '') {
                this.$validator.validateAll(formScope).then(result => {
                    if (result) {
                        this.$http.post(this.url, this.contact).then(response => {
                            this.disable_button = false;

                            $('#contactForm').modal('hide');
                            this.isLoading = false;

                            window.flashMessages = [{
                                'type': 'alert-success',
                                'message': response.data.message
                            }];

                            this.$root.addFlashMessages()
                        }).catch(error => {
                            this.disable_button = false;
                            this.handleErrorResponse(error.response, 'contact-form')
                        })
                    } else {
                        this.disable_button = false;
                    }
                });
            } else {
                $('#contactForm').modal('hide');
            }
        },

        handleErrorResponse(response, scope) {
            if (response.status == 422) {
                serverErrors = response.data.errors;
                this.$root.addServerErrors(scope)
                this.isLoading = false;
            }
        }
    }
}
</script>

<style scoped>

</style>