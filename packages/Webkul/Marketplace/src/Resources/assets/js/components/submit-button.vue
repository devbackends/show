<template>
    <button @click="onClickButton" :type="type" :class="[{'submit-button btn': true}, cssClass ? cssClass : '', color ? 'btn-' + color : 'btn-primary', size ? 'btn-' + size : '', loading ? 'disabled' : '']" :disabled="disabled" :id="id">
        <i style="position: absolute;width:100%;height:100%" v-if="disabled" data-toggle="tooltip" data-placement="top" title="" :data-original-title="disableReason" class="form-tooltip-icon"></i>
        <div class="spinner-border spinner-border-sm" role="status" v-show="loading">
            <span class="sr-only">Loading...</span>
        </div>
        <span :class="loading ? 'invisible' : ''"><i v-if="faIconLeft" :class="'submit-button__icon--left far fa-' + faIconLeft"></i>{{ text }}<i v-if="faIconRight" :class="'submit-button__icon--right far fa-' + faIconRight"></i></span>
    </button>
</template>

<script>
export default {
    props: {
        text: {
                default: "Submit",
            },
        disabled: {
                default: false,
            },
        disableReason: {
            default: "",
        },
        loading: {
                default: false,
            },
        type:  {
                default: "submit",
            },
        cssClass: {
                default: "",
            },
        color: {
            default: ""
        },
        size: {
                default: "",
            },
        id: {
                default: "",
            },
        faIconLeft: {
            default: null
        },
        faIconRight: {
            default: null
        }
    },

    data: () => ({}),

    mounted() {},

    methods: {
      onClickButton() {
        this.$emit('clickEvent')
      }
    }
};
</script>