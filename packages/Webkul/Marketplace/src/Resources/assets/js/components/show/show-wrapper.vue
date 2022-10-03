<template>
    <div class="shows-list__items row mx-n1">
        <div class="shows-list__item col-12 col-md-6 col-lg-4 px-1 mb-2" v-for='(show, index) in shows' :key="index">
            <show-item
                :showItem="show"
            ></show-item>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            shows: {
                type: Array | String,
                required: false,
                default: () => ([])
            }
        },

        data: function () {
            return {
                items: this.shows
            }
        },

    }
</script>
