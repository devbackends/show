<template>
    <div style="height: inherit; width: inherit">
        <div class="google-map" ref="googleMap"></div>
        <template v-if="Boolean(this.google) && Boolean(this.map)">
            <slot
                :google="google"
                :map="map"
            >
                <google-map-marker v-for="marker in markers" :key="marker.id" :google="google" :map="map"
                                   :marker="marker"/>
            </slot>
        </template>
    </div>
</template>

<script>
    import GoogleMapsApiLoader from 'google-maps-api-loader';
    import * as VueGoogleMaps from 'vue2-google-maps';
    import GoogleMapMarker from "./googleMapMarker";

    export default {
        name: "googleMap",
        components: {GoogleMapMarker},
        props: {
            mapConfig: Object,
            apiKey: String,
            markers: {
                default: function () {
                    return [];
                },
                type: Array,
            }
        },
        data() {
            return {
                google: null,
                map: null
            }
        },
        async mounted() {
            this.google = await GoogleMapsApiLoader({
                apiKey: this.apiKey,
                libraries: ['geocode']
            })
            this.initializeMap()
        },
        methods: {
            initializeMap() {
                const mapContainer = this.$refs.googleMap
                if (this.address) {
                    // this.google.
                }
                this.map = new this.google.maps.Map(
                    mapContainer, this.mapConfig
                )
            }
        },
    }
</script>

<style scoped>

</style>
