<template>
    <div class="row d-flex align-items-center product-item product-item__list-item py-1" v-if="list">
        <div class="col-auto">
            <a :title="category.name" :href="`${baseUrl}/category${category.url}`" class="product-item__list-item-link">
                <img :src="category.image" alt="image" :data-src="category.image">
            </a>
        </div>
        <div class="col px-0">
            <div class="product-item__body card-body">
                <a :href="`${baseUrl}/category${category.url}`">
                    <p class="name">{{ category }}</p>
                </a>
            </div>
        </div>
    </div>

    <div class="product-item card category-card p-4" v-else>
        <a :title="category.name" :href="`${baseUrl}/category${category.url}`">
            <img :src="category.image" :alt="category.name" :data-src="category.image" class="category-card__image" v-if="category.image">
            <img :src="`${baseUrl}/images/shooter.png`" :alt="category.name" class="category-card__image" v-if="!category.image">
            <p class="category-card__name">{{ category.name }}</p>
        </a>
        
    </div>

</template>

<script>
export default {
    props: [
        'list',
        'category',
    ],
}
</script>