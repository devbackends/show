<template>

    <div class="col-12 shimmer-card-container">
        <carousel-component
            id="shimmer-carousel"
            navigation-enabled="hide"
            pagination-enabled="hide"
            :slides-count="shimmerCountInt + 1"
            :slides-per-page="shimmerCountInt">
                <slide
                    :key="count"
                    :slot="`slide-${count}`"
                    v-for="count in shimmerCountInt">
                    <div class="shimmer-card-radiobutton">
                        <div class="shimmer-wrapper">
                            <div class="row">
                                <div class="col-auto pr-0">
                                    <div class="shimmer-radio animate"></div>
                                </div>
                                <div class="col">
                                    <div class="comment animate w-75"></div>
                                    <div class="comment animate w-25"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </slide>
        </carousel-component>
    </div>
    
</template>

<script>
    export default {
        props: {
            'shimmerCount': {
                default: 4,
            }
        },

        data: function () {
            return {
                shimmerCountInt: parseInt(this.shimmerCount),
            }
        }
    }
</script>

<style>
    .shimmer-card-container {
        width: 100%;
    }

    .shimmer-card-radiobutton {
        margin: 0;
        padding: 0;
        overflow: hidden;
        background: white;
        /* box-shadow: 0 3px 6px rgba(0, 0, 0, 0.08); */
    }

    .shimmer-product-image {
        width: 100%;
        height: 180px;
    }

    .comment {
        height: 10px;
        background: #ccc;
        margin: 0 0 1rem 0;
    }
    .comment-date {
        width: 70%;
    }
    .shimmer-radio {
        width: 24px;
        height: 24px;
        border-radius: 5rem;
    }
    .shimmer-wrapper {
        width: 0px;
        animation: fullView 0.5s forwards linear;
    }

    @keyframes fullView {
        100% {
            width: 100%;
        }
    }

    .animate {
        animation : shimmer 2s infinite;
        background: linear-gradient(to right, #f4f4f4 4%, #eeeeee 25%, #f4f4f4 36%);
        background-size: 1000px 100%;
    }

    @keyframes shimmer {
        0% {
            background-position: -1000px 0;
        }
        100% {
            background-position: 1000px 0;
        }
    }
    .shimmer-card--list {
        border: none;
        border-bottom: 1px solid #f4f4f4;
        margin: 0;
        box-shadow: none;
        padding-bottom: 20px;
    }
    .shimmer-card--list .col div:last-child {
        width: 70%;
    }
    .shimmer-card--list .comment {
        margin: 10px 0;
    }
    .shimmer-card--list .shimmer-product-image {
        height: 60px;
        width: 60px;
    }
    .shimmer-card {
        margin: 0 15px;
    }
</style>
