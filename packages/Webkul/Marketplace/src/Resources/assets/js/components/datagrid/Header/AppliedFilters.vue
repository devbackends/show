<template>
    <div v-if="filters.length > 0" class="filter-row-two mb-3">
        <div class="filter-tag badge badge-dark badge-pill mr-1" v-for="filter in filters">
            <span v-if="filter.column === 'sort'">{{ filter.label }}</span>
            <span v-else-if="filter.column === 'search'">Search</span>
            <span v-else-if="filter.column === 'perPage'">Per Page</span>
            <span v-else>{{ filter.label }}</span>

            <span>
                {{ decodeURIComponent(filter.val) }}
                <i class="far fa-times" @click="$emit('removeFilter', filter)"></i>
            </span>
        </div>
    </div>
</template>

<script>
export default {
    name: "AppliedFilters",
    props: ['filters']
}
</script>

<style scoped>

</style>