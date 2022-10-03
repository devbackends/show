<template>
    <div class="show-card show-card__ffl h-100">
        <span class="label" v-if="isPreferred"></span>
        <p class="ffl-list-item__license-name">
            <strong v-if="fflItem.business_info.company_name"><span class="shows-list__item-date">{{fflItem.license.license_name.toLowerCase()}}</span></strong>
        </p>
        <p class="ffl-list-item__name">{{fflItem.business_info.company_name.toLowerCase()}}</p>
        <p v-if="!fflItem.business_info.company_name" class="ffl-list-item__name">{{fflItem.license.license_name.toLowerCase()}}</p>
        <p class="ffl-list-item__address">{{fflItem.business_info.city.toLowerCase()}}, {{fflItem.business_info.country_state.code}}</p>
        <a :href="fflUrl" class="btn btn-outline-primary">Learn more</a>
    </div>
</template>

<script>
    export default {
        props: {
            fflItem: {
                type: Object | Array,
            },
            isPreferred: true
        },

        data: function () {
            return {}
        },

        computed: {
            fflUrl () {
                return '/marketplace/ffl/' + this.fflItem.business_info.country_state.default_name.replace(/([a-z])([A-Z])/g, "$1-$2")
                        .replace(/\s+/g, '-')
                        .toLowerCase() + '/' + (
                            (this.fflItem.business_info.company_name != '') ?
                            this.fflItem.business_info.company_name.replace(/([a-z])([A-Z])/g, "$1-$2").replace(/\s+/g, '-').toLowerCase() :
                            this.fflItem.license.license_name.replace(/([a-z])([A-Z])/g, "$1-$2").replace(/\s+/g, '-').toLowerCase()
                )+'/'+this.fflItem.id
                    ;
            }
        },
    }
</script>
