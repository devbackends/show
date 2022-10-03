<template>
    <form role="search" id="search-form" :action="searchUrl" method="GET" @submit="onFormSubmit">
        <div role="toolbar" class="btn-toolbar full-width">
            <div class="row header-search-container">
                <div class="selectdiv col-md-3 no-padding">
                    <select name="type" v-model="searchType" class="form-control fs13 styled-select rounded-left">
                        <option v-for="(typeName, typeKey) in searchTypes" :key="typeKey" :value="typeKey">{{typeName}}</option>
                    </select>
                    <div class="select-icon-container">
                        <span class="select-icon fal fa-angle-down"></span>
                    </div>
                </div>
                <div class="full-width col-md-9 no-padding">
                    <input id="search_products"
                           required="required"
                           name="search"
                           type="search"
                           class="form-control ui-autocomplete-input"
                           autocomplete="off"
                           v-model="search"
                           :placeholder="label">
                    <button type="submit" id="header-search-icon" class="btn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </form>
</template>

<script>
export default {
    name: "site-search",
    props: ['searchUrl', 'showUrl', 'instructorUrl', 'gunrangeUrl', 'fflUrl', 'clubUrl'],
    data: () => ({
        searchType: 'product',
        search: '',
        label: 'Search',
        searchTypes: {
            'product': 'Products',
            'category': 'Categories',
            'seller': 'Brands / Sellers',
            'show': 'Gun Shows',
            'instructor': 'Instructors',
            'gun-range': 'Gun Ranges',
            'ffl': 'FFLs',
            'club': 'Clubs'
        },
    }),
    mounted() {
        const url = new URLSearchParams(location.search);

        // Set search-type from GET
        if (url.has('type') && this.searchTypes[url.get('type')]) {
            this.searchType = url.get('type');
        }

        // Set search from GET
        if (url.has('search')) {
            this.search = url.get('search');
        }
    },
    methods: {
        onFormSubmit(e) {
            if (this.searchType !== 'product' && this.searchType !== 'category' && this.searchType !== 'seller') {
                e.preventDefault();
                let url = '';
                switch (this.searchType) {
                    case 'show': url = this.showUrl;break;
                    case 'instructor': url = this.instructorUrl;break;
                    case 'gun-range': url = this.gunrangeUrl;break;
                    case 'ffl': url = this.fflUrl;break;
                    case 'club': url = this.clubUrl;break;
                }
                location.href = `${url}?zipCode=${this.search}&type=${this.searchType}`;
            }
        },
    },
    watch: {
        searchType() {
            if (this.searchType !== 'product' && this.searchType !== 'category' && this.searchType !== 'seller') {
                this.label = `Enter the zipcode of the ${this.searchTypes[this.searchType]} you want to find`;
                this.search = '';
            } else {
                this.label = 'Search';
            }
        }
    }
}
</script>

<style scoped>

</style>