<template>
    <div class="show-card h-100">
<!--        <p>{{instructorItem.id}}</p>-->
        <h3>{{instructorItem.title}}</h3>
        <p class="font-weight-bold text-capitalize">by {{instructorItem.instructor.toLowerCase().replace(/mr /g,'')}}</p>
        <p>{{instructorItem.city_name}}, {{instructorItem.state}}</p>
        <!-- <p class="shows-list__item-date"><i class="far fa-calendar-alt mr-2 d-inline-block align-middle"></i><span class="d-inline-block align-middle">{{instructorItem.dates.replace(/,/g,', ')}}</span></p> -->
        <a :href="instructorUrl" class="btn btn-outline-primary">Learn more</a>
    </div>
</template>

<script>
    export default {
        props: {
            instructorItem: {
                type: Object | Array,
            },
        },

        data: function () {
            return {}
        },

        mounted() {

        },

        computed: {
            instructorUrl () {
                return '/marketplace/certified-firearm-instructor/' + this.instructorItem.state_relation.default_name.replace(/([a-z])([A-Z])/g, "$1-$2")
                    .replace(/\s+/g, '-')
                    .toLowerCase() + '/' +
                    this.instructorItem.instructor.replace(/([a-z])([A-Z])/g, "$1-$2")
                    .replace(/\s+/g, '-')
                    .toLowerCase();
            },
            compareWithToday(date){
                return this.instructorItem.dates.yymmdd(date) === this.todayIs;
            }
        },

        methods: {}
    }
</script>
