<template>
    <div class="col-md-8 admin-customization">
        <div class="row">

            <!-- Select Filter -->
            <div class="col-md-3 px-1 admin-customization">
                <div class="form-group">
                    <label>Filter</label>
                    <select class="form-control" v-model="selectedFilter">
                        <option selected disabled>Column</option>
                        <option v-for="(column, index) in columns" v-if="column.filterable" :value="column.index" :key="index">{{column.label}}</option>
                        <option v-if="getCurrentLocationPathname === '/admin/catalog/products'" value="attributes">Attributes</option>
                    </select>
                </div>
            </div>

            <!-- Number -->
            <template v-if="condition === 'number'">
                <div class="col-md-2 px-1 admin-customization d-flex align-items-end">
                    <div class="form-group">
                        <input class="form-control" type="number" v-model="conditionValue"/>
                    </div>
                </div>
            </template>

            <!-- String -->
            <template v-else-if="condition === 'string'">
                <div class="col-md-3 px-1 admin-customization d-flex align-items-end">
                    <div class="form-group">
                        <label></label>
                        <select class="form-control" v-model="conditionValue">
                            <option selected disabled>Condition</option>
                            <option value="like">Contains</option>
                            <option value="nlike">Does not contains</option>
                            <option value="eq">Is Equals to</option>
                            <option value="neqs">Is Not equals to</option>
                        </select>
                    </div>
                </div>
                <div v-if='conditionValue' class="col-md-3 px-1 admin-customization d-flex align-items-end">
                    <div class="form-group">
                        <label></label>
                        <input type="text" class="form-control" placeholder="Value here" v-model="subConditionValue" />
                    </div>
                </div>
            </template>

            <!-- Boolean -->
            <template v-else-if="condition === 'boolean'">
                <div class="col-md-3 px-1 d-flex align-items-end">
                    <div class="form-group">
                        <label></label>
                        <select class="form-control" v-model="conditionValue">
                            <option selected disabled>Condition</option>
                            <option value="1">True / Active</option>
                            <option value="0">False / Inactive</option>
                        </select>
                    </div>
                </div>
            </template>

            <!-- Categories -->
            <template v-else-if="condition === 'categories'">
                <div class="col-md-2 px-1 admin-customization d-flex align-items-end">
                    <div class="form-group">
                        <label></label>
                        <select class="form-control" v-model="conditionValue">
                            <option value=null selected disabled>Condition</option>
                            <option value="with">With</option>
                            <option value="without">Without</option>
                        </select>
                    </div>
                </div>
                <div v-if="conditionValue" class="col-md-3 px-1 admin-customization d-flex align-items-end">
                    <div class="form-group">
                        <label></label>
                        <select class="form-control" v-model="subConditionValue">
                            <option v-for="category in subAdditionalData" :key="category.id" :value="category.id">
                                {{ getCategoryLevelByIndex(category.index) }} {{category.name}}
                            </option>
                        </select>
                    </div>
                </div>
            </template>

            <!-- Quantity -->
            <template v-else-if="condition === 'quantity'">
                <div class="col-md-4 px-1 admin-customization d-flex align-items-end">
                    <div class="form-group">
                        <label></label>
                        <select class="form-control" v-model="conditionValue">
                            <option value=null selected disabled>Condition</option>
                            <option value="with">In Stock</option>
                            <option value="without">Out Of Stock</option>
                        </select>
                    </div>
                </div>
            </template>

            <!-- Attributes -->
            <template v-else-if="condition === 'attributes'">
                <div class="col-md-3 px-1 admin-customization d-flex align-items-end">
                    <div class="form-group">
                        <label></label>
                        <select class="form-control" v-model="conditionValue">
                            <option value=null selected disabled>Select Attribute</option>
                            <option
                                v-for="attribute in subAdditionalData"
                                :value="attribute.id"
                                :key="attribute.id"
                            >{{ attribute.name }}</option>
                        </select>
                    </div>
                </div>
                <div v-if="conditionValue" class="col-md-3 px-1 admin-customization d-flex align-items-end">
                    <div class="form-group">
                        <label></label>

                        <select v-if="getSelectedAttribute.type === 'select' || getSelectedAttribute.type === 'multiselect'"
                                class="form-control" v-model="subConditionValue">
                            <option value=null selected disabled>Select Option</option>
                            <option
                                v-for="option in subSubAdditionalData"
                                :value="option.id"
                                :key="option.id"
                            >{{ option.admin_name }}</option>
                        </select>

                        <select v-else-if="getSelectedAttribute.type === 'boolean'" v-model="subConditionValue" class="form-control">
                            <option value=null selected disabled>Condition</option>
                            <option value="1">True / Active</option>
                            <option value="0">False / Inactive</option>
                        </select>

                        <input v-else type="text" class="form-control" placeholder="Value here" v-model="subConditionValue"/>

                    </div>
                </div>
            </template>

            <!-- Button -->
            <div class="col-md-2 px-1 admin-customization d-flex align-items-end">
                <div class="form-group">
                    <div>
                        <a class="btn btn-outline-primary apply-filter" @click="apply">Apply</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "FiltersWrapper",
    props: ['columns'],
    data: () => ({
        selectedFilter: null,
        condition: null,
        conditionValue: null,
        subAdditionalData: null,
        subSubAdditionalData: null,
        subConditionValue: null,
        rules: {
            number: {
                subConditionValue: false
            },
            string: {
                subConditionValue: true,
            },
            boolean: {
                subConditionValue: false,
            },
            datetime: {
                subConditionValue: true,
            },
            categories: {
                subConditionValue: true,
            },
            quantity: {
                subConditionValue: false,
            },
            attributes: {
                subConditionValue: true
            }
        },
    }),
    computed: {
        getCurrentLocationPathname() {
            return location.pathname;
        },
        getSelectedAttribute() {
            if (this.condition === 'attributes' && this.conditionValue) {
                return this.subAdditionalData.find(attr => {
                    return attr.id === this.conditionValue
                })
            }
            return null;
        },
    },
    methods: {
        setCategories(categories, index = 0) {
            index++;
            for (let category of categories) {
                category.index = index;
                this.subAdditionalData.push(category)
                if (category.children.length > 0) {
                    this.setCategories(category.children, index);
                }
            }
        },
        getCategoryLevelByIndex(index) {
            let html = '';
            for (let i = 1; i < index; i++) {
                html += '- ';
            }
            return html;
        },
        apply() {
            // Validation
            if (!this.condition || !this.conditionValue) {
                eventBus.$emit('dummyApplyBtnClick')
                return false;
            } else {
                const rules = this.rules[this.condition];
                if (!rules) return false;
                if (rules.subConditionValue && !this.subConditionValue) {
                    eventBus.$emit('dummyApplyBtnClick')
                    return false;
                }
            }

            // Collect options
            const options = {
                filter: this.columns.find((column) => {
                    return column.index === this.selectedFilter
                }),
                conditionValue: this.conditionValue,
            };
            if (this.condition === 'attributes') {
                options.filter = {
                    label: 'Attribute',
                    index: 'attributes',
                    type: 'attributes',
                };
            }
            if (this.subConditionValue) {
                options.conditionValue = this.conditionValue;
                options.subConditionValue = this.subConditionValue
            } else {
                options.conditionValue = 'eq';
                options.subConditionValue = this.conditionValue;
            }

            // Emit
            this.$emit('applyFilter', options)
        },
    },
    watch: {
        selectedFilter() {
            this.subAdditionalData = [];
            this.conditionValue = null;
            this.subConditionValue = null;
            if (this.selectedFilter === 'attributes') {
                this.condition = 'attributes'
            } else {
                const column = this.columns.find((column) => {
                    return column.index === this.selectedFilter
                })
                this.condition = column.type;
            }

            if (this.condition === 'categories') {
                $.get('/categories', res => {
                    this.setCategories(res.categories)
                });
            } else if (this.condition === 'attributes') {
                $.get('/attributes', res => {
                    this.subAdditionalData = res.attributes;
                })
            }

        },
        conditionValue() {
            if (this.condition === 'attributes' && this.conditionValue) {
                if (this.getSelectedAttribute.type === 'select' || this.getSelectedAttribute.type === 'multiselect') {
                    $.get('/attribute/'+this.conditionValue+'/options', res => {
                        this.subSubAdditionalData = res.options;
                    })
                }
            }
        },
    },
}
</script>

<style scoped>

</style>
