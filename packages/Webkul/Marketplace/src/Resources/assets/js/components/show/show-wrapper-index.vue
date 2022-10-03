<template>
    <div>
        <div class="shows-list__filter">
            <div class="form-group wide">
                <label>Zip Code</label>
                <input type="text" placeholder="Zip Code" class="form-control" id="filter-address">
            </div>
            <div class="form-group">
                <label>Miles</label>
                <input type="text" placeholder="Miles" class="form-control" value="120" id="filter-miles">
            </div>
            <div class="form-group">
                <label>State</label>
                <select placeholder="Select State" id="filter-state" class="form-control"><option value="all">All</option>  <option value="AK">Alaska</option> <option value="AS">American Samoa</option> <option value="AZ">Arizona</option> <option value="AR">Arkansas</option> <option value="AE">Armed Forces Africa</option> <option value="AA">Armed Forces Americas</option> <option value="AE">Armed Forces Canada</option> <option value="AE">Armed Forces Europe</option> <option value="AE">Armed Forces Middle East</option> <option value="AP">Armed Forces Pacific</option> <option value="CA">California</option> <option value="CO">Colorado</option> <option value="CT">Connecticut</option> <option value="DE">Delaware</option> <option value="DC">District of Columbia</option> <option value="FM">Federated States Of Micronesia</option> <option value="FL">Florida</option> <option value="GA">Georgia</option> <option value="GU">Guam</option> <option value="HI">Hawaii</option> <option value="ID">Idaho</option> <option value="IL">Illinois</option> <option value="IN">Indiana</option> <option value="IA">Iowa</option> <option value="KS">Kansas</option> <option value="KY">Kentucky</option> <option value="LA">Louisiana</option> <option value="ME">Maine</option> <option value="MH">Marshall Islands</option> <option value="MD">Maryland</option> <option value="MA">Massachusetts</option> <option value="MI">Michigan</option> <option value="MN">Minnesota</option> <option value="MS">Mississippi</option> <option value="MO">Missouri</option> <option value="MT">Montana</option> <option value="NE">Nebraska</option> <option value="NV">Nevada</option> <option value="NH">New Hampshire</option> <option value="NJ">New Jersey</option> <option value="NM">New Mexico</option> <option value="NY">New York</option> <option value="NC">North Carolina</option> <option value="ND">North Dakota</option> <option value="MP">Northern Mariana Islands</option> <option value="OH">Ohio</option> <option value="OK">Oklahoma</option> <option value="OR">Oregon</option> <option value="PW">Palau</option> <option value="PA">Pennsylvania</option> <option value="PR">Puerto Rico</option> <option value="RI">Rhode Island</option> <option value="SC">South Carolina</option> <option value="SD">South Dakota</option> <option value="TN">Tennessee</option> <option value="TX">Texas</option> <option value="UT">Utah</option> <option value="VT">Vermont</option> <option value="VI">Virgin Islands</option> <option value="VA">Virginia</option> <option value="WA">Washington</option> <option value="WV">West Virginia</option> <option value="WI">Wisconsin</option> <option value="WY">Wyoming</option><option value="AL">Alabama</option></select>
            </div>
            <div class="form-group">
                <button @click="showMore(true)" type="submit" class="btn btn-outline-gray">Apply filter</button>
            </div>
            <span class="info ml-md-auto">
                <i class="far fa-badge-check"></i>
                Verified Gun Show
            </span>
        </div>
        <div v-if="items.length > 0" class="shows-list__items row mx-n1 mb-4">
            <div class="shows-list__item col-12 col-md-6 col-lg-4 px-1 mb-2" v-for='(show, index) in items' :key="index">
                <show-item
                    :showItem="show"
                ></show-item>
            </div>
            <div class="col-12 px-2 px-md-1">
                <shimmer-teaser-component v-if="isLoading" shimmer-count="3" class="d-none d-lg-block"></shimmer-teaser-component>
                <shimmer-teaser-component v-if="isLoading" shimmer-count="2" class="d-none d-md-block d-lg-none"></shimmer-teaser-component>
                <shimmer-teaser-component v-if="isLoading" shimmer-count="1" class="d-block d-md-none"></shimmer-teaser-component>
            </div>
        </div>
        <div v-else-if="items.length === 0 && !isLoading" class="shows-list__items row mx-n1 mb-4">
            <div class="shows-list__item col-12 col-md-6 col-lg-4 px-1 mb-2">
                <p>No results found.</p>
            </div>
        </div>
        <div class="row" v-if="showMoreBtn && !isLoading">
            <div class="col-12 shows-list__show-more text-center">
                <a href="#" @click.prevent="showMore()" class="btn btn-outline-gray"><i class="fal fa-angle-down"></i> Show more</a>
            </div>
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
            },
        },

        data: function () {
            return {
                items: this.shows,
                page: 1,
                showMoreBtn: true,
                isLoading: false,
                filters: {
                    state: 'all'
                }
            }
        },

        mounted() {
            this.isLoading = true;
            const url = new URLSearchParams(location.search);
            if (url.has('zipCode')) {
                $('#filter-address').val(url.get('zipCode'));
                this.items = [];
                this.showMore(true);
            }
            this.isLoading = false;
        },

        methods: {
            showMore(filters) {
                this.page += 1;
                this.isLoading = true;
                if (typeof filters !== 'undefined') {
                    this.isLoading = true;
                    this.items = [];
                    this.page = 1;
                }

                const params = new URLSearchParams({
                    page: this.page,
                    address: $('#filter-address').val(),
                    radius: $('#filter-miles').val(),
                    state: $('#filter-state').val()
                }).toString();

                this.$http.get(
                    `${this.$root.baseUrl}/marketplace/shows/api?${params}`
                ).then(response => {
                    this.showMoreBtn = response.data.per_page === response.data.data.length;
                    this.isLoading = false;
                    this.items = [...this.items, ...response.data.data];
                }).catch(error => {
                    console.log(error);
                });
            }
        }

    }
</script>
