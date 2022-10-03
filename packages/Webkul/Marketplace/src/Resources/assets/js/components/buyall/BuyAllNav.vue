<template>
  <div>
    <p class="font-paragraph-big-bold mb-0">
      Select from our featured categories below
    </p>
    <h1 class="mb-4">To start shopping</h1>
    <buyallnavcategories></buyallnavcategories>
    <div class="row pt-4 pb-5">
      <div class="col text-center">
        <p class="font-paragraph-big-bold mb-0">
          Not finding what you're looking for?
        </p>
        <p class="font-paragraph-big">
          See additional categories by clicking the dropdown menu.
        </p>
        <select name="select" class="form-control m-auto buy-all__select" @change="changeCategory">
          <option value="0">All categories</option>
          <option
            v-for="category in categories"
            :key="category.id"
            :value="category.id"
          >
            {{ category.name }}
          </option>
        </select>
      </div>
    </div>
    <div class="row">
      <div class="col product-list__head">
        <h2 class="h1">Recently added</h2>
        <!--<div class="action">
                <select name="select" class="form-control">
                    <option value="0">Products near me</option>
                    <option value="1">Location 1</option>
                    <option value="2">Location 2</option>
                </select>
            </div>-->
      </div>
    </div>
  </div>
</template>

<script>

import buyallnavcategories from "./BuyAllNavCategories";

export default {
  name: "BuyAllNav",
  components: {buyallnavcategories},
  data: () => {
    return {
      categories: [],
    };
  },
  methods: {
    changeCategory(event) {
      this.$emit("onCategoryChange", event.target.value);
    },
    parseCategories(categories) {
      let arr = [];
      for (let category of categories) {
        arr.push({ id: category.id, name: category.name });
        if (category.children.length > 0) {
          arr = arr.concat(this.parseCategories(category.children));
        }
      }
      return arr;
    },
  },
  watch: {
    "$root.sharedRootCategories": function (categories) {
      this.categories = this.parseCategories(categories).sort((a, b) => {
        return a.name > b.name ? 1 : -1;
      });
    },
  },
};
</script>

<style scoped>
</style>