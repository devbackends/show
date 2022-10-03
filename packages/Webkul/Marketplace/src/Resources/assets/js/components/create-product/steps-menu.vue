<template>
    <div class="create-product-menu">
        <div class="create-product-menu__item" v-for="(item, index) in steps" :key="index">
            <a href="#" class="create-product-menu__item-title" :class="item.isActive ? 'create-product-menu__item-title--active' : ''">{{ item.title }}</a>
            <div class="create-product-menu__item-summary" v-if="item.summary">
                <p>{{ item.summary.type }} / {{ item.summary.family }}</p>
            </div>
            <div>
                <a class="create-product-menu__subitem" href="#" v-for="(subitem, index) in item.subSteps" :key="index" :class="subitem.isCompleted ? 'create-product-menu__subitem--completed' : ''">{{ subitem.title }}</a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {},

        data: function () {
            return {
                steps: [
                    {
                        title: "Product Type",
                        isCompleted: true,
                        isActive: true,
                        hasError: false,
                        /* summary: {
                            type: "Variable",
                            family: "General"
                        }, */
                        subSteps: []
                    },{
                        title: "Product Information",
                        isCompleted: false,
                        isActive: false,
                        hasError: false,
                        /* subSteps: [
                            {
                                title: "General Information",
                                isCompleted: false,
                                hasError: false
                            },{
                                title: "Media",
                                isCompleted: false,
                                hasError: false
                            },{
                                title: "Pricing",
                                isCompleted: false,
                                hasError: false
                            },{
                                title: "Shipping",
                                isCompleted: false,
                                hasError: false
                            },{
                                title: "Inventory",
                                isCompleted: false,
                                hasError: false
                            },{
                                title: "Attributes",
                                isCompleted: false,
                                hasError: false
                            },{
                                title: "Categories",
                                isCompleted: false,
                                hasError: false
                            },
                        ] */
                    }
                ]
            }
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
