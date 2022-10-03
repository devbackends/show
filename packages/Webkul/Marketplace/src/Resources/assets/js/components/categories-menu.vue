<template>
    <!-- categories list -->

    <nav
        :id="id"
        @mouseover="remainBar(id)"
        @mouseout="closeMenu()"
        :class="`sidebar ${addClass ? addClass : ''}`"
        v-if="slicedCategories && slicedCategories.length > 0">
        <div class="container">
        <ul type="none" class="sidebar__list">
            <li
                :key="categoryIndex"
                :id="`category-${category.id}`"
                class="category-content cursor-pointer sidebar__list-item"
                v-for="(category, categoryIndex) in slicedCategories">
                <a
                    :href="`${$root.baseUrl}/category/${category.slug}`"
                    :class="`category d-flex align-items-center unset sidebar__list-item-title ${(category.children.length > 0) ? 'fw6' : ''}`">

                    <div
                        class="category-icon align-self-start mt-1">

                        <img
                            v-if="category.category_icon_path"
                            :src="`${$root.baseUrl}/storage/${category.category_icon_path}`" />
                    </div>

                    <span class="category-title">{{ category['name'] }}</span>

                    <i
                        class="far fa-chevron-right mr-2 d-none"
                        v-if="category.children.length && category.children.length > 0">
                    </i>
                </a>

                <div
                    class="sub-category-container"
                    v-if="category.children.length && category.children.length > 0">

                    <div
                        :class="`sub-categories sub-category-${sidebarLevel+categoryIndex} cursor-default`">

                        <nav :id="`sidebar-level-${sidebarLevel+categoryIndex}`">

                            <ul type="none" class="sub-categories__list">
                                <li
                                    :key="`${subCategoryIndex}-${categoryIndex}`"
                                    v-for="(subCategory, subCategoryIndex) in category.children">

                                    <a
                                        :id="`sidebar-level-link-2-${subCategoryIndex}`"
                                        :href="`${$root.baseUrl}/category/${category.slug}/${subCategory.slug}`"
                                        :class="`category sub-category unset ${(subCategory.children.length > 0) ? 'fw6' : ''}`">

                                        <div
                                            class="category-icon">

                                            <img
                                                v-if="subCategory.category_icon_path"
                                                :src="`${$root.baseUrl}/storage/${subCategory.category_icon_path}`" />
                                        </div>
                                        <span class="category-title">{{ subCategory['name'] }}</span>
                                    </a>

                                  <!--  <ul type="none" class="nested">
                                        <li
                                            :key="`${childSubCategoryIndex}-${subCategoryIndex}-${categoryIndex}`"
                                            v-for="(childSubCategory, childSubCategoryIndex) in subCategory.children">

                                            <a
                                                :id="`sidebar-level-link-3-${childSubCategoryIndex}`"
                                                :class="`category unset ${(subCategory.children.length > 0) ? 'fw6' : ''}`"
                                                :href="`${$root.baseUrl}/category/${category.slug}/${subCategory.slug}/${childSubCategory.slug}`">
                                                <span class="category-title">{{ childSubCategory.name }}</span>
                                            </a>
                                        </li>
                                    </ul>-->
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </li>
        </ul>
        </div>
    </nav>
</template>

<script type="text/javascript">
    export default {
        props: [
            'id',
            'addClass',
            'parentSlug',
            'mainSidebar',
            'categoryCount'
        ],

        data: function () {
            return {
                slicedCategories: [],
                sidebarLevel: Math.floor(Math.random() * 1000),
            }
        },
        mounted(){

        },
        watch: {
            '$root.sharedRootCategories': function (categories) {
                this.formatCategories(categories);
            }
        },

        methods: {
            remainBar: function (id) {
                let sidebar = $(`#${id}`);
                document.querySelector('#shop-by-category-button').classList.add('sidebar__open-button');
                if (sidebar && sidebar.length > 0) {
                    sidebar.show();

                    let actualId = id.replace('sidebar-level-', '');

                    let sidebarContainer = sidebar.closest(`.sub-category-${actualId}`)
                    if (sidebarContainer && sidebarContainer.length > 0) {
                        sidebarContainer.show();
                    }

                }
            },
            closeMenu: function() {
                document.querySelector('#shop-by-category-button').classList.remove('sidebar__open-button');
            },
            formatCategories: function (categories) {
                let slicedCategories = categories;
                let categoryCount = this.categoryCount ? this.categoryCount : 20;

                if (
                    slicedCategories
                    && slicedCategories.length > categoryCount
                ) {
                    slicedCategories = categories.slice(0, categoryCount);
                }

                if (this.parentSlug)
                    slicedCategories['parentSlug'] = this.parentSlug;

                this.slicedCategories = slicedCategories;
            },
        }
    }
</script>