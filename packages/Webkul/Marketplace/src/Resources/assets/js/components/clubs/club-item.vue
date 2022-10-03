<template>
        <div class="show-card h-100">
            <h3>{{clubItem.club_name}}</h3>
            <p>{{clubItem.address1}}, {{clubItem.city}}, {{clubItem.state}}, {{clubItem.zip}} </p>
            <a :href="clubUrl" class="btn btn-outline-primary">Learn more</a>
        </div>
</template>

<script>
    export default {
        props: {
            clubItem: {
                type: Object | Array,
            },
        },

        data: function () {
            return {}
        },

        computed: {
            clubUrl () {
                return '/marketplace/clubs-and-associations/' + this.clubItem.state_relation.default_name.replace(/([a-z])([A-Z])/g, "$1-$2")
                        .replace(/\s+/g, '-')
                        .toLowerCase() + '/' +
                    this.clubItem.club_name.replace(/([a-z])([A-Z])/g, "$1-$2")
                        .replace(/\s+/g, '-')
                        .toLowerCase();
            }
        },
    }
</script>
