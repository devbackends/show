<template>
    <div class="create-product__box">
        <p class="create-product__box-title">Media</p>
        <div class="row mx-n1 mb-3" v-if="uploadedImages.length > 0">
            <div class="col-3 px-1 mb-2" v-for="(item, index) in uploadedImages" :key="index">
                <div class="form-image-uploader__item">
                    <img :src="item.url" alt="">
                    <span class="form-image-uploader__item-delete"><i class="far fa-trash-alt"></i></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="custom-file form-image-uploader">
                    <input type="file" class="custom-file-input" id="productImageInput">
                    <label class="custom-file-label" for="productImageInput">
                        <i class="far fa-images"></i>
                        <p>Drag one or multiple images</p>
                        <button class="btn btn-outline-primary">Or browse in your files</button>
                    </label>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {},

        data: function () {
            return {
                uploadedImages: [
                    {
                        url: "https://2ags-prod.s3.us-east-1.wasabisys.com/product/18746/HsTHUOOxA2mnB9I2Cx4svKWd2aZ25SUaOaveVlS4.jpg"
                    },{
                        url: "https://2ags-prod.s3.us-east-1.wasabisys.com/product/18745/glvR5Q7J65V8a0jrEPmnb7TjcRgPtZorH7oGKVwD.jpg"
                    },{
                        url: "https://2ags-prod.s3.us-east-1.wasabisys.com/product/18744/j6XDSxg40Y2N83T9eEVO8sVyxHm4yUNBzw8kUIcC.jpg"
                    },{
                        url: "https://2ags-prod.s3.us-east-1.wasabisys.com/product/18742/fMZsL28YmDGTVLXmn1wDYcdsEMzMjYbpUVbU2rEb.jpg"
                    },{
                        url: "https://2ags-prod.s3.us-east-1.wasabisys.com/product/18738/jar91rVxgJfzJ0RYUDjGhDIyWWLlcBDfFgBjHnyv.jpg"
                    },{
                        url: "https://2ags-prod.s3.us-east-1.wasabisys.com/product/18725/dZYVJo9xt8F3SkBD4D69a65KnwZl8Y0lvM1qgSu8.jpg"
                    },{
                        url: "https://2ags-prod.s3.us-east-1.wasabisys.com/product/18715/fIfXnkzfZhm7qRE1reI1NCJJ95ngUsMjam2EavPt.png"
                    },
                ]
            }
        },

        computed: {
            customFunction () {}
        },
    }
</script>
