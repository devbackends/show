<template>
    <div class="image-upload-field">
        <div v-show="!isLoading" class="image-wrapper">
            <draggable v-model="images" @start="drag=true" @end="drag=false" @change="log">
                <transition-group class="row">
                    <label v-for="(image, index) in images" :key="image.id" :class="`col-md-3 mb-3`">
                        <div class="image-item has-image">
                            <img class="preview" :src="createUrl(image)">
                            <label class="remove-image" @click="removeImage(index)"></label>
                        </div>
                    </label>
                </transition-group>
            </draggable>
        </div>

        <div v-show="isLoading" class="messaging__loading py-4">
            <loading-spinner/>
        </div>
       <div class="image-wrapper" @dragover.prevent @drop.prevent>
           <label class="col-md-12 px-0" @dragleave="fileDragOut" @dragover="fileDragIn" @drop="handleFileDrop($event)">
               <div class="image-item has-not-image">
                   <input :disabled="isLoading" type="file" v-validate="'mimes:image/*'" accept="image/*"
                          ref="mainImagesInput" id="mainImagesInput" @change="addImages($event)" :multiple="multiple"/>
                   <div class="image-item-overlay"></div>
               </div>
           </label>
       </div>
    </div>
</template>

<script>
import draggable from 'vuedraggable';

export default {
    components: {draggable},
    props: {
        sku: {
            type: String,
            required: true
        },
        items: {
            type: Array | String,
            required: false,
            default: () => ([])
        },
        multiple: {
            type: Boolean,
            required: false,
            default: true
        }
    },

    data: () => ({
        images: [],
        isLoading: false
    }),

    created() {

    },
    mounted() {
        this.images = this.items;
    },
    methods: {
        addImages(e) {
            let files = e.target.files;
            if (!files) return;
            this.uploadImages(files);
        },
        handleFileDrop(e) {
            const droppedFiles = e.dataTransfer.files;
            if (!droppedFiles) return;
            this.uploadImages(droppedFiles);
        },
        fileDragIn() {
            console.log('');
        },
        fileDragOut() {
            console.log('');
        },
        removeImage(index) {
            if (confirm("Are you sure you want to delete this image?")) {
                this.isLoading=true;
                let data = new FormData();
                data.append('id', this.images[index].id);
                this.$http.post("/marketplace/account/catalog/products/remove-image", data)
                    .then((response) => {
                        this.isLoading=false;
                        if (response.status == 200) {
                            if (response.data.code == 200) {

                                this.images = [];
                                for (let i = 0; i < response.data.images.length; i++) {
                                    this.images = [...this.images, response.data.images[i]];
                                }
                            }
                        }
                    })
                    .catch(function (error) {

                    })

            }
        },
        createUrl(item) {
            if (typeof item.url != 'undefined') {
                return item.url;
            } else {
                return window.URL.createObjectURL(item);
            }
        },
        uploadImages(files) {
            this.isLoading=true;
            let data = new FormData();
            data.append('sku', this.sku);
            data.append('nb_of_images', files.length);
            for (let i = 0; i < files.length; i++) {
                data.append('image_' + parseFloat(i + 1), files[i]);
            }
            this.$http.post("/marketplace/account/catalog/products/add-image", data)
                .then((response) => {
                    this.isLoading=false;
                    if (response.status == 200) {
                        if (response.data.images.length > 0) {
                            this.images = [];
                        }
                        for (let i = 0; i < response.data.images.length; i++) {
                            this.images = [...this.images, response.data.images[i]];
                        }
                        document.getElementById('mainImagesInput').value = null;
                    }
                })
                .catch(function (error) {

                })
        },
        log() {
            this.$http.post("/marketplace/account/catalog/products/sort-images-order", this.images)
                .then((response) => {
                    console.log(response);
                    if (response.status == 200) {

                    }
                })
                .catch(function (error) {

                })
        }

    }

}
</script>
