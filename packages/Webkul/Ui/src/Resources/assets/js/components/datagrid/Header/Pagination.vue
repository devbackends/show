<template>
    <div class="col-md-2 pl-1 admin-customization">
        <div class="form-group dropdown-filters per-page">
            <label for="perPage">Items Per Page</label>
            <select id="perPage" name="perPage" class="form-control" v-model="perPageValue" @change="$emit('applyPagination', perPageValue)">
                <option v-for="(option, index) in options" :key="index" :value="option">{{ option }}</option>
            </select>
        </div>
    </div>
</template>

<script>
export default {
    name: "Pagination",
    props: ['filters'],
    data: () => ({
        perPageValue: 50,
        options: [10, 20, 30, 40, 50],
    }),
    watch: {
        filters() {
            for (let filter of this.filters) {
                if (filter.column === 'perPage') {
                    this.perPageValue = filter.val;
                    break;
                }
            }
        },
    },
}
</script>

<style scoped>

</style>