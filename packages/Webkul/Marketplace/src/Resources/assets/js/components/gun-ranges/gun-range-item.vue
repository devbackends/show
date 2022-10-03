<template>
    <div class="show-card h-100">
        <h3>{{gunRangeItem.name}}</h3>
        <p>{{gunRangeItem.hours}}</p>
        <a :href="gunRangeUrl" class="btn btn-outline-primary">Learn more</a>
    </div>
</template>

<script>
    export default {
        props: {
            gunRangeItem: {
                type: Object | Array,
            },
        },

        data: function () {
            return {}
        },

        mounted() {

        },

        computed: {
            gunRangeUrl () {
                return '/marketplace/gun-range/' + this.gunRangeItem.state_relation.default_name.replace(/([a-z])([A-Z])/g, "$1-$2")
                    .replace(/\s+/g, '-')
                    .toLowerCase() + '/' +
                    this.gunRangeItem.name.replace(/([a-z])([A-Z])/g, "$1-$2")
                    .replace(/\s+/g, '-')
                    .toLowerCase();
            }
        },

        methods: {}
    }
</script>
