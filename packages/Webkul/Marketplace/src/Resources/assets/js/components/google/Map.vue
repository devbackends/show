<template>
    <gmap-map v-if="setCenterMap" :center="setCenterMap" :zoom="zoom" style="height: inherit; width: inherit">
        <gmap-marker
            :key="index"
            v-for="(m, index) in markers"
            :position="m.position"
            :icon= m.icon
            :clickable="true"
            @click="showByIndex = index; clickFflHandler(m.ffl);"
            @mouseover="showByIndex = index;">
        <gmap-info-window :options="infoOptions"
            :opened="showByIndex === index"
       >
                    <div class="row" v-if="typeof m.ffl !='undefined'">
                        <div class="col-12 col-md-7 order-1">
                            <h5 id="firstHeading" class="firstHeading" v-text="m.ffl.company_name"></h5>
                            <p>{{ m.ffl.street_address }}, {{ m.ffl.city }}, {{ m.ffl.state_name }} {{ m.ffl.zip_code }} </p>
                            Phone: <a href="tel:7083440008" tabindex="0" v-text="m.ffl.phone"></a>
                        </div>
                        <div class="col-12 col-md-5 order-2 text-center align-self-end">
                            <div class="row">
                                <div class="col-6 col-md-12 text-uppercase align-self-center align-self-md-end pb-1 pt-1 pt-lg-2 pb-lg-2 order-1 order-lg-2 text-left text-md-center">
                                    <strong v-text="m.ffl.distance.toFixed(1) + ' Miles'"></strong>
                                </div>
                                <div class="col-6 col-md-12 order-2 order-lg-1">
                                    <img src="/images/favicon/safari-pinned-tab.svg" class="dealer-map-image" style="visibility:hidden">
                                </div>
                                <div class="col-12 order-3">
                                    <button @click="SelectFfl(m.ffl)"  style="justify-content: center;" class="btn btn-primary w-100 p-1 ffl-select-dealer-btn" data-dealer="317" data-license="336031013G05948">Select</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" v-else>
                        <div class="col-12 col-md-7 order-1">
                            <h5 class="firstHeading" v-text="'My Location'"></h5>
                        </div>
                    </div>
        </gmap-info-window>
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
                showByIndex: null,
                //optional: offset infowindow so it visually sits nicely on top of our marker
                infoOptions: {
                    maxWidth: 260
                }
            };
        },
        computed: {
          setCenterMap: function () {
            return this.coordinates;
          },
        },
        methods: {
            SelectFfl(ffl) {
                this.$emit("select", ffl);
            },
            clickFflHandler(ffl){
                this.$emit("clickFflHandler",ffl)
            },
            setShowByIndex(index){
               this.showByIndex=index;
            }
        },
        watch: {

        }
    }
</script>

