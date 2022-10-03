<template>
  <div
    class="modal fade bd-example-modal-xl"
    id="fflModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="select-ffl"
    aria-hidden="true"
  >
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
                <div ref="fflContainer" class="modal__ffl-list" v-on:scroll="onFflScrollHandler">
                  <div
                    class="modal__ffl-list-item"
                    v-for="ffl in ffls"
                    :key="ffl.id"
                    v-on:click="onSelectFflHandler(ffl)"
                  >
                  <div class="modal__ffl-list-item-overlay justify-content-center align-items-center">
                      <button class="btn btn-sm btn-primary">Select</button>
                  </div>
                    <div class>
                      <img
                        v-if="ffl.source === preferred_source"
                        src="/images/ffl-preferred-icon.svg"
                        class="modal__ffl-list-item-preferred"
                      />
                      <div class="modal__ffl-list-item-content">
                        <p class="modal__ffl-list-item-name">{{ffl.company_name}}</p>
                        <p
                          class="modal__ffl-list-item-distance"
                        >{{Number((ffl.distance).toFixed(1))}} miles</p>
                        <p
                          class="modal__ffl-list-item-address"
                        >{{ffl.street_address}}, {{ffl.city}}, {{ffl.zip_code}}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-7" id="map">
              <Map :coordinates="coordinates" :markers="markers" />
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
  props: {
    billingAddr: {
      type: String,
      default: null,
    },
  },
  data: function () {
    return {
      defaultAddress: "201 E Randolph St, Chicago, IL",
      address: null,
      coordinates: null,
      preferred_source: FFL_PREFERRED_SOURCE,
      ffls: [],
      markers: [],
      offset: 0,
      selectedFfl: null,
      zipFilter: null,
      filtered: false,
      coordinatesFromAddr: null,
    };
  },
  methods: {
    setCoordinatesFromAddr: function () {
      geocoding(this.address)
        .then((res) => {
          this.coordinatesFromAddr = this.coordinates = {
            lat: res.data.results[0].geometry.location.lat,
            lng: res.data.results[0].geometry.location.lng,
          };
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
            this.offset
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
      target: { scrollTop, clientHeight, scrollHeight },
    }) {
      if (scrollTop + clientHeight >= scrollHeight) {
        this.offset += 20;
      }
    },
    onSelectFflHandler: function (ffl) {
      this.selectedFfl = ffl;
      this.$emit("change", ffl);
      this.$refs.closePopup.click();
    },
  },
  created() {
    this.address = this.defaultAddress;
    this.setCoordinatesFromAddr();
    this.coordinates = this.coordinatesFromAddr;
    eventBus.$on("addressChanged", (data) => {
      this.address = data.address1[0] + ", " + data.city + ", " + data.state;
      this.setCoordinatesFromAddr();
    });
  },
  watch: {
    billingAddr: function (newValue, oldValue) {
      if (newValue && newValue !== oldValue) {
        this.setCoordinatesFromAddr();
      }
    },
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
  },
};
</script>

<style scoped>
</style>
