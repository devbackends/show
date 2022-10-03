<template>
    <div>
        <div class="row" id="datagrid-filters">
            <Search @applySearch="applySearch"/>

            <FiltersWrapper :columns="columns" @applyFilter="applyFilter"/>

            <Pagination :filters="filters" @applyPagination="applyPagination"/>
        </div>

        <AppliedFilters :filters="filters" @removeFilter="removeFilter"/>
    </div>
</template>

<script>
import Search from "./Search";
import FiltersWrapper from "./FiltersWrapper";
import Pagination from "./Pagination";
import AppliedFilters from "./AppliedFilters";
export default {
    name: "Header",
    components: {AppliedFilters, Pagination, FiltersWrapper, Search},
    props: ['columns'],
    data: () => ({
        filters: [],
    }),
    mounted() {
        // Parse url and fetch filters
        this.setFiltersFromUrl();

        // Set listeners
        this.setListeners();
    },
    methods: {
        setListeners() {
            eventBus.$on('applySort', this.applySort)
        },

        async setFiltersFromUrl() {
            if (location.search !== '') {
                let filters = [];
                for (let filter of location.search.substring(1).split('&')) {
                    // Collect values from url
                    let obj = {
                        column: filter.split('[')[0],
                        cond: filter.split('=')[0].replace(']', '').split('[')[1],
                        val: filter.split('=')[1],
                    }

                    // Detect label
                    let label = '';
                    if (obj.column === 'search') {
                        label = 'Search'
                    } else if (obj.column === 'perPage') {
                        label = 'Per Page'
                    } else if (obj.column === 'attributes') {
                        const attribute = (await $.get('/attribute/'+obj.cond)).attribute;
                        label = 'Attribute ' + attribute.name;
                    } else {
                        let cond = (obj.column === 'sort') ? obj.cond : obj.column
                        label = this.columns.find((item) => {
                            return item.index === cond
                        }).label
                    }
                    obj.label = label

                    filters.push(obj);
                }
                this.filters = filters;
            }
        },

        applyFilter(options) {
            // Some modifications
            if (options.filter.type === 'string') {
                options.subConditionValue = encodeURIComponent(options.subConditionValue)
            }

            // Check if this filter already applied
            let existingFilterIndex;
            for (let index in this.filters) {
                if (this.filters[index].column === options.filter.index
                    && this.filters[index].cond == options.conditionValue) {
                    existingFilterIndex = index;
                    break;
                }
            }
            if (existingFilterIndex) {
                // If value is the same - skip. If not - update value
                if (this.filters[existingFilterIndex].val === options.subConditionValue) {
                    return false;
                } else {
                    this.filters[existingFilterIndex].val = options.subConditionValue
                    this.reload();
                    return false;
                }
            }

            this.filters.push({
                column: options.filter.index,
                cond: options.conditionValue,
                val: options.subConditionValue,
                label: options.filter.label
            })
            this.reload();
        },

        removeFilter(filter) {
            for (let index in this.filters) {
                if (this.filters[index].col === filter.col
                    && this.filters[index].cond === filter.cond
                    && this.filters[index].val === filter.val) {
                    this.filters.splice(index, 1);
                    this.reload();
                }
            }
        },

        applySort(alias) {
            // Get label
            let label = '';
            for (let column of this.columns) {
                if (column.index === alias) {
                    label = column.label;
                }
            }

            // Try to get existing sort filter
            let existingSearchIndex;
            for (let index in this.filters) {
                if (this.filters[index].column === 'sort') {
                    existingSearchIndex = index;
                    break;
                }
            }

            if (existingSearchIndex) {
                if (this.filters[existingSearchIndex].cond === alias) {
                    this.filters[existingSearchIndex].val = (this.filters[existingSearchIndex].val === 'asc') ? 'desc' : 'asc';
                } else {
                    this.filters[existingSearchIndex].cond = alias;
                    this.filters[existingSearchIndex].label = label;
                    this.filters[existingSearchIndex].val = 'asc'
                }
            } else {
                this.filters.push({
                    column: 'sort',
                    cond: alias,
                    val: 'asc',
                    label: label,
                })
            }
            this.reload();
        },

        applySearch(searchValue) {
            let existingSearchIndex;
            for (let index in this.filters) {
                if (this.filters[index].column === 'search') {
                    existingSearchIndex = index;
                    break;
                }
            }
            if (existingSearchIndex) {
                this.filters[existingSearchIndex].val = searchValue
            } else {
                this.filters.push({
                    column: 'search',
                    cond: 'all',
                    val: searchValue,
                    label: 'Search',
                })
            }
            this.reload();
        },

        applyPagination(value) {
            for (let i = 0; i < this.filters.length; i++) {
                if (this.filters[i].column === 'perPage') {
                    this.filters.splice(i, 1);
                }
            }
            this.filters.push({
                column: 'perPage',
                cond: 'eq',
                val: value
            });
            this.reload();
        },

        reload() {
            let newParams = '';

            for (let filter of this.filters) {
                let sign = newParams.includes('?') ? '&' : '?'
                newParams += sign + filter.column + '[' + filter.cond + ']' + '=' + filter.val;
            }

            location.href = location.origin + location.pathname + newParams;
        },
    },
}
</script>

<style scoped>

</style>