<template>
    <div class="accordian" :class="[isActive ? 'active' : '', className, ! isActive && hasError ? 'error' : '']"
         :id="id">
        <div class="accordian-header" @click="toggleAccordian()">
            <slot name="header">
                {{ title }}
            </slot>
            <i class="icon" :class="iconClass"></i>
        </div>

        <div class="accordian-content" ref="controls">
            <slot name="body"></slot>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Accordian",
        props: {
            title: String,
            id: String,
            className: String,
            active: Boolean,
            disabled: {
                type: Boolean,
                default: false,
            }
        },

        inject: ['$validator'],

        data: function () {
            return {
                isActive: false,

                imageData: '',

                hasError: false
            }
        },

        mounted: function () {
            this.addHasErrorClass()

            eventBus.$on('onFormError', this.addHasErrorClass);

            this.isActive = this.active;
        },

        methods: {
            toggleAccordian: function () {
                if (!this.$props.disabled) {
                    this.isActive = !this.isActive;
                }
            },

            addHasErrorClass: function () {
                var this_this = this;

                setTimeout(function () {
                    $(this_this.$el).find('.control-group').each(function (index, element) {
                        if ($(element).hasClass('has-error')) {
                            this_this.hasError = true;
                        }
                    });
                }, 0);
            }
        },
        watch: {
           active: function () {
                this.isActive = this.active;
           }
        },
        computed: {
            iconClass() {
                return {
                    'accordian-up-icon': !this.isActive,
                    'accordian-down-icon': this.isActive,
                };
            }
        }
    }
</script>