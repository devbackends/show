<template>
    <div class="col-md-2 pr-1 admin-customization keyword-search-container d-flex align-items-end">
        <div class="form-group">
            <label></label>
            <input type="search" id="search-field" class="form-control" placeholder="Search" v-model="value" @keyup.enter="$emit('applySearch', value)"/>
        </div>
    </div>
</template>

<script>
export default {
    name: "Search",
    data: () => ({
        value: '',
    }),
    mounted() {
        eventBus.$on('dummyApplyBtnClick', () => {
            if (this.value !== '') {
                this.$emit('applySearch', this.value)
            }
        })
    },
}
</script>

<style scoped>

</style>