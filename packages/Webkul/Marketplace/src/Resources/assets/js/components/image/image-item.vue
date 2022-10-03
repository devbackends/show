<template>
        <label :for="_uid"  :class="`col-md-3 mb-3`">
            <div class="image-item has-image">
            <img class="preview" :src="createUrl(image)">
            <label class="remove-image" @click="removeImage()"></label>
            </div>
        </label>
</template>

<script>
export default {
    props: {
        image: {
            type: File,
            required: false,
            default: null
        }
    },

    data: function() {
        return {
            imageData: '',
            isNew: false
        }
    },

    mounted () {
        console.log('type');
        if(typeof this.image=='object'){
            var imageInput = this.$refs.hiddenImageInput;
            var reader = new FileReader();
            reader.onload = (e) => {
                console.log(e.dataTransfer.files);
                imageInput.value = e.dataTransfer.files;
            };
        }
    },

    computed: {

    },

    methods: {
        createUrl(imageFile) {
            if(typeof imageFile.url !=  'undefined'){
                return imageFile.url;
            }else{
                return window.URL.createObjectURL(imageFile);
            }
        },

        removeImage () {
            if(confirm("Are you sure you want to delete this image?")) {
/*                if(this.inputName!='images'){
                    let data = new FormData();
                    data.append('id',this.image.id);
                    this.$http.post("/marketplace/account/catalog/products/remove-image", data)
                        .then( (response) => {
                            if (response.status==200) {
                              this.$root.$emit('reloadVariableImages',{'sku':this.inputName,'images':response.data.images});
                                                 this.imageData="";
                                                 this.$refs.imageInput.files=null;
                            }
                        })
                        .catch(function (error) {

                        })
                }*/
                this.$emit('onRemoveImage', this.image);
            }
        },
        uploadvariantImage(sku){
            let data = new FormData();
            data.append('sku',this.inputName);
            data.append('nb_of_images',1);
            console.log(this.$refs.imageInput.files[0]);
            if(typeof this.image.path != 'undefined'){
                data.append('image_id',this.image.id);
            }
            data.append('image_1',this.$refs.imageInput.files[0]);
            this.$http.post("/marketplace/account/catalog/products/add-image", data)
                .then( (response) => {
                    if (response.status==200) {
                        this.$emit('onRemoveImage', this.image);
                        this.$root.$emit('reloadVariableImages',{'sku':this.inputName,'images':response.data.images});
                        this.$root.$emit('reloadImageWrapper',response.data.images);
                    }
                })
                .catch(function (error) {

                })
        }
    }
}
</script>
