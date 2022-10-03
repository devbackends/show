<template>
    <div class="product-image-group" v-if="images.length > 0">
        <div>
            <inner-image-zoom :src="currentLargeImageUrl" :zoomSrc="currentOriginalImageUrl" />
        </div>
        <div>
            <ul class="thumbs list-unstyled mt-2" type="none">
                <li v-if="images.length > 4" class="arrow left" @click="scroll('prev')">
                    <i class="rango-arrow-left fs24"></i>
                </li>

                <carousel-component
                    slides-per-page="4"
                    :id="galleryCarouselId"
                    pagination-enabled="hide"
                    navigation-enabled="hide"
                    add-class="product-gallary"
                    :slides-count="images.length + videos.length">
                    <slide v-for="(thumb, index) in images" :key="index" :slot="`slide-${index}`">
                        <li @click="changeImage({
                                largeImageUrl: thumb.large_image_url,
                                originalImageUrl: thumb.original_image_url,
                            })"
                            :class="['thumb-frame', `${(index+1==4) ? '' : 'mr5'}`, `${(thumb.large_image_url === currentLargeImageUrl) ? 'active' : ''}`]">
                        <img :src="thumb.small_image_url" alt="Product image">
                        </li>
                    </slide>
                    <slide  v-for="(video, key) in videos" :key="key+images.length" :slot="`slide-${key}`">
                        <li v-if="video.path.includes('youtube') || video.path.includes('vimeo')" data-toggle="modal" data-target="#exampleModal" style="height: 100%;">
                            <img style="height: 100%;" :src="getVideoThumbnail(video)" alt="Product video">
                        </li>
                    </slide>

                </carousel-component>

                <li v-if="images.length > 4" class="arrow right" @click="scroll('next')">
                    <i class="rango-arrow-right fs24"></i>
                </li>
            </ul>
        </div>

        <!-- Modal -->
        <div v-if="videos.length > 0" class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document" v-if="videos[0].path.includes('youtube') || videos[0].path.includes('vimeo')">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Product Video</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <iframe v-if="videos.length > 0" width="100%" height="280" frameborder="0" allow="encrypted-media" allowfullscreen=""
                                :src="getVideoSource(videos[0])">
                        </iframe>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    name: 'product-gallery',
    props: ['images','videos'],
    data: function () {
        return {
            thumbs: [],
            galleryCarouselId: 'product-gallery-carousel',
            currentLargeImageUrl: '',
            currentOriginalImageUrl: '',
            counter: {
                up: 0,
                down: 0,
            }
        }
    },
    created() {
        $(document).on('hidden.bs.modal', function (e) {
            $("#exampleModal iframe").attr("src", $("#exampleModal iframe").attr("src"));
        });
    },
    mounted() {
    },
    methods: {
        prepareThumbs: function () {
            this.thumbs = [];

            this.images.forEach(image => {
                this.thumbs.push(image);
            });
        },

        changeImage: function ({largeImageUrl, originalImageUrl}) {
            this.currentLargeImageUrl = largeImageUrl;

            this.currentOriginalImageUrl = originalImageUrl;

            this.$root.$emit('changeMagnifiedImage', {
                smallImageUrl: this.currentOriginalImageUrl
            });

            let productImage = $('.vc-small-product-image');
            if (productImage && productImage[0]) {
                productImage = productImage[0];

                productImage.src = this.currentOriginalImageUrl;
            }
        },

        scroll: function (navigateTo) {
            let navigation = $(`#${this.galleryCarouselId} .VueCarousel-navigation .VueCarousel-navigation-${navigateTo}`);

            if (navigation && (navigation = navigation[0])) {
                navigation.click();
            }
        },
        getVideoThumbnail(video){
            if(video.path.includes('youtube')){
                const url = new URL(video.path);
                const c = url.searchParams.get("v");
                return 'https://img.youtube.com/vi/'+c+'/sddefault.jpg';
            }
            if(video.path.includes('vimeo')){
                const url = video.path;
                const id = url.substring(url.lastIndexOf('/') + 1);
                return 'https://vumbnail.com/'+id+'_small.jpg';
            }


        },
        getVideoSource(video){
            if(video.path.includes('youtube')){
                const url = new URL(video.path);
                return 'https://www.youtube.com/embed/'+url.searchParams.get("v");
            }
            if(video.path.includes('vimeo')){
                const url = video.path;
                const id = url.substring(url.lastIndexOf('/') + 1);
                return 'https://player.vimeo.com/video/'+id;
            }
        }
    },
    watch: {
        images: function (newVal, oldVal) {
            if(this.images.length > 0){
                this.changeImage({
                    largeImageUrl: this.images[0]['large_image_url'],
                    originalImageUrl: this.images[0]['original_image_url'],
                });
            }
            this.prepareThumbs()
        }
    },

}
</script>

<style scoped>

</style>
