<template>
    <gmap-map v-if="setCenterMap" :center="setCenterMap" :zoom="zoom" style="height: inherit; width: inherit">
        <gmap-marker
            :key="index"
            v-for="(m, index) in markers"
            :position="m.position">
        </gmap-marker>
    </gmap-map>
</template>

<script>
    export default {
        name: "Map",
        props: {
            coordinates: {
                type: Object,
                default: null,
            },
            markers: {
                type: Array,
                default: []
            }
        },
        data: function () {
            return {
                zoom: 8,
            };
        },
        computed: {
          setCenterMap: function () {
            return this.coordinates;
          }
        },
        methods: {},
    }
</script>