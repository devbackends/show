<template>
    <div class="modal fade bd-example-modal-xl" id="fflModal" tabindex="-1" role="dialog" aria-labelledby="select-ffl" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-body modal__ffl">
                    <div class="row">
                        <div class="col-md-5 pr-0">
                            <div class="modal__ffl-left">
                                <button
                                    ref="closePopup"
                                    type="button"
                                    class="close shipping-modal"
                                    data-dismiss="modal"
                                    aria-label="Close"
                                >
                                    <i class="far fa-times" aria-hidden="true"></i>
                                </button>
                                <h3 class="modal-title" id="select-ffl">Choose ffl pickup location</h3>
                                <h4 class="modal__ffl-preferred-text">2AGunshow preferred FFL</h4>
                                <div class="modal__ffl-search input-group">
                                    <input
                                        type="text"
                                        placeholder="Filter by zip code"
                                        class="form-control filter-ffl"
                                        v-model="zipFilter"
                                        aria-describedby="searchIcon"
                                    />
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="searchIcon">
                                          <i class="fa fa-search" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                                <div ref="fflContainer" class="modal__ffl-list" :scroll="onFflScrollHandler">
                                    <div class="container px-0" id="modal-ffl-list">
                                    <div
                                        class="modal__ffl-list-item"
                                        v-for="ffl in ffls"
                                        :key="ffl.id"
                                        :id="'ffl-item-' + ffl.id"
                                    >
                                        <a @click="onClickFflHandler(ffl)" class="modal__ffl-list-item-link">
                                            <div class="row">
                                                <div class="col">
                                                    <p class="modal__ffl-list-item-name">{{ ffl.company_name }}</p>
                                                    <p class="modal__ffl-list-item-address">{{ ffl.street_address }}, {{ ffl.city }}, {{ ffl.state_name }} {{ ffl.zip_code }}</p>
                                                </div>
                                                <div class="col-auto">
                                                    <p class="modal__ffl-list-item-distance">{{ Number((ffl.distance).toFixed(1)) }} miles</p>
                                                    <p class="modal__ffl-list-item-fee"></p>
                                                </div>
                                            </div>

                                            <div class="modal__ffl-list-item-more" :id="'#collapse' + ffl.id">
                                                <div class="modal__ffl-list-item-fees" v-show="ffl.long_gun || ffl.hand_gun || ffl.nics || ffl.other">
                                                    <p class="font-weight-bold">Fees</p>
                                                    <div class="row">
                                                        <div class="col-3 modal__ffl-list-item-fee" v-show="ffl.long_gun">
                                                            <p>Long Gun</p>
                                                            <p class="modal__ffl-list-item-fee-value">$ {{ ffl.long_gun }}</p>
                                                        </div>
                                                        <div class="col-3 modal__ffl-list-item-fee" v-show="ffl.hand_gun">
                                                            <p>Hand Gun</p>
                                                            <p class="modal__ffl-list-item-fee-value">$ {{ ffl.hand_gun }}</p>
                                                        </div>
                                                        <div class="col-3 modal__ffl-list-item-fee" v-show="ffl.nics">
                                                            <p>NICS</p>
                                                            <p class="modal__ffl-list-item-fee-value">$ {{ ffl.nics }}</p>
                                                        </div>
                                                        <div class="col-3 modal__ffl-list-item-fee" v-show="ffl.other">
                                                            <p>Other</p>
                                                            <p class="modal__ffl-list-item-fee-value">$ {{ ffl.other }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="btn btn-primary btn-sm" @click="onSelectFflHandler(ffl)">Select</button>
                                            </div>
                                        </a>
                                            <img
                                                v-if="ffl.source === preferred_source"
                                                src="/images/ffl-preferred-icon.svg"
                                                class="modal__ffl-list-item--preferred"
                                            />
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7" id="map">
                            <Map ref="map" @select="onSelectFflHandler" @clickFflHandler="onClickFflHandler" :coordinates="coordinates" :markers="markers"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Map from "./../google/Map";
import {
    API_ENDPOINTS,
    FFL_PREFERRED_SOURCE,
    GOOGLE_GEOCODE_API_URL,
    GOOGLE_MAPS_API_KEY,
} from "../../constant";
import geocoding from "../../utility/geocoding";

export default {
    name: "fflPopup",
    components: {
        Map,
    },
    data: function () {
        return {
            address: null,
            state: null,
            coordinates: null,
            preferred_source: FFL_PREFERRED_SOURCE,
            ffls: [],
            markers: [],
            offset: 0,
            selectedFfl: null,
            clickedFfl: null,
            fflItem: null,
            clickedFflItem: null,
            zipFilter: null,
            filtered: false,
            coordinatesFromAddr: null,
        };
    },
    created() {
        let billingAddr = this.$store.getters.getCart.billing_address;
        if (billingAddr) {
            this.address = billingAddr.postcode;
            this.state= billingAddr.state;
            this.setCoordinatesFromAddr();
            this.coordinates = this.coordinatesFromAddr;
        }
    },
    computed: {
        billingPostcode() {
            let addr = this.$store.getters.getCart.billing_address;
            return addr ? addr.postcode : '';
        },
    },
    methods: {
        setCoordinatesFromAddr: function () {
            geocoding(this.address)
                .then((res) => {
                    this.coordinatesFromAddr = this.coordinates = {
                        lat: res.data.results[0].geometry.location.lat,
                        lng: res.data.results[0].geometry.location.lng,
                    };
                    this.markers.push({
                        id: 0,
                        position: this.coordinatesFromAddr,
                        icon: "http://maps.google.com/mapfiles/ms/icons/green-dot.png"
                    });
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        getClosestFfl: function (coordinates, offset) {
            this.$http
                .post(API_ENDPOINTS.getClosestFfls, {
                    coordinates: coordinates,
                    offset: offset,
                })
                .then((res) => {
                    res.data.ffls.map((ffl) => {
                        this.ffls.push(ffl);
                        this.markers.push({
                            id: ffl.ffl_id,
                            position: {
                                lat: parseFloat(ffl.latitude),
                                lng: parseFloat(ffl.longitude),
                            },
                            ffl: ffl
                        });
                    });
                    this.ffls = res.data.ffls;
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        getFflByZip: function () {
            this.$http
                .get(
                    API_ENDPOINTS.getFflByZip +
                    "/" +
                    this.zipFilter +
                    "/" +
                    this.coordinates.lat +
                    "/" +
                    this.coordinates.lng +
                    "/" +
                    this.offset+
                    "/" +
                    this.state
                )
                .then((res) => {
                    this.ffls = res.data.ffls;
                    this.markers = [];
                    this.offset = 0;
                    res.data.ffls.map((ffl) => {
                        this.markers.push({
                            id: ffl.ffl_id,
                            position: {
                                lat: parseFloat(ffl.latitude),
                                lng: parseFloat(ffl.longitude),
                            },
                            ffl: ffl
                        });
                        this.markers.push({
                            id: 0,
                            position: this.coordinatesFromAddr,
                            icon: "http://maps.google.com/mapfiles/ms/icons/green-dot.png"
                        });
                    });
                    this.ffls = res.data.ffls;
                    if (this.ffls.length > 0) {
                        this.coordinates = {
                            lat: parseFloat(this.ffls[0].latitude),
                            lng: parseFloat(this.ffls[0].longitude),
                        };
                    }
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        onFflScrollHandler: function ({
                                          target: {scrollTop, clientHeight, scrollHeight},
                                      }) {
            if (scrollTop + clientHeight >= scrollHeight) {
                this.offset += 20;
            }
        },
        onSelectFflHandler: function (ffl) {
            this.selectedFfl = ffl;
            this.$emit("done", ffl);
            this.$refs.closePopup.click();
        },
        onClickFflHandler: function (ffl) {
            for(let i=0; i< this.markers.length;i++){
                if(typeof this.markers[i].ffl != 'undefined'){
                    if(ffl.id==this.markers[i].ffl.id){
                        this.$refs['map'].setShowByIndex(i);
                    }
                }
            }
            this.clickedFfl = ffl.id;
            this.fflItem = document.querySelectorAll('.modal__ffl-list-item');
            this.clickedFflItem = document.getElementById('ffl-item-' + this.clickedFfl);
            for (var i = 0, len = this.fflItem.length; i < len; i++) {
                this.fflItem[i].classList.remove('modal__ffl-list-item--clicked');
            }
            this.clickedFflItem.classList.toggle('modal__ffl-list-item--clicked');
        }
    },
    watch: {
        coordinates: function (coordinates) {
            if (this.filtered) return;
            this.getClosestFfl(coordinates, this.offset);
        },
        offset: function (offset) {
            if (this.filtered) return;
            this.getClosestFfl(this.coordinates, offset);
        },
        zipFilter: function (zip) {
            this.filtered = zip.length >= 3;
            if (zip.length >= 3) {
                this.getFflByZip(this.coordinates, this.offset, zip);
            }
        },
        filtered: function (newValue, oldValue) {
            if (newValue !== oldValue && newValue === false) {
                this.ffls = [];
                this.markers = [];
                this.offset = 0;
                this.coordinates = this.coordinatesFromAddr;
                this.setCoordinatesFromAddr();
                this.getClosestFfl(this.coordinates, this.offset);
            }
        },
        billingPostcode: function () {
            this.ffls = [];
            this.markers = [];
            this.address = this.billingPostcode;
            this.setCoordinatesFromAddr();
            this.coordinates = this.coordinatesFromAddr;
        }
    },
};
</script>

<style scoped>
</style>
