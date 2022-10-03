<template>
    <div class="h-100" v-bind:class="{ 'shows-list__item--cancelled' : showItem.is_cancelled===1}">
            <div class="show-card h-100">
                <p>
                    <strong><span class="shows-list__item-date">{{showItem.dates.join()}}</span><span class="shows-list__item-cancelled-label">Canceled</span></strong>
                </p>
                <h3>{{showItem.title}}</h3>
                <p>{{showItem.location}}</p>
                <a :href="showUrl" class="btn btn-outline-primary">Learn more</a>
            </div>
    </div>
</template>

<script>
    export default {
        props: {
            showItem: {
                type: Object | Array,
            }
        },

        data: function () {
            return {}
        },

        computed: {
            showUrl () {
                return '/marketplace/gun-show/' + this.showItem.state_relation.default_name.replace(/([a-z])([A-Z])/g, "$1-$2")
                    .replace(/\s+/g, '-')
                    .toLowerCase() + '/' +
                    this.showItem.title
                    .replace(/\s+/g, '-')
                    .toLowerCase();
            }
        },

        methods: {}
    }
</script>
