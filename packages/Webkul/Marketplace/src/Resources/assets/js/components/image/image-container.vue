<template>
    <label :data-count="imageCount" class="image-item" :for="_uid" v-bind:class="{ 'has-image': imageData.length > 0 }"
           @dragenter="onDragEnter"
           @dragleave="onDragLeave"
           @dragover.prevent
           @drop="onDrop">
        <input type="hidden" :name="finalInputName"/>

        <input type="file" v-validate="'mimes:image/*'" style="visibility: hidden;" accept="image/*" :name="finalInputName" ref="imageInput" :id="_uid" @change="addImageView($event)" :required="required ? true : false" />

        <img class="preview" :src="imageData" v-if="imageData.length > 0">
        <div class="image-item-overlay"></div>
        <!-- <label class="remove-image" @click="removeImage()">{{ removeButtonLabel }}</label> -->

        <label v-if="imageData.length > 0 && imageCount> 1 " class="remove-image" @click="removeImage()"></label>
    </label>
</template>

<script>
export default {
    props: {
        inputName: {
            type: String,
            required: false,
            default: 'attachments'
        },

        removeButtonLabel: {
            type: String,
        },

        image: {
            type: Object,
            required: false,
            default: null
        },

        required: {
            type: Boolean,
            required: false,
            default: false
        },
        isNew: false,
        multiple: {
            type: Boolean,
            required: false,
            default: true
        },
        imageCount:{
        }
    },

    data: function() {
        return {
            imageData: ''
        }
    },

    mounted () {
        if(this.image.id && this.image.url) {
            this.imageData = this.image.url;
        }
    },

    computed: {
        finalInputName () {
            return this.inputName + '[' + this.image.id + ']';
        }
    },

    methods: {
        onDragEnter () {

        },

        onDragLeave () {

        },

        onDrop (e) {
            if (this.imageData.length === 0) {
                this.isNew = true;
            }
            e.preventDefault();
            e.stopPropagation();

            const file = e.dataTransfer.files[0];
            const reader = new FileReader();

            reader.onload = (event) => this.imageData = event.target.result;

            this.$refs.imageInput.files = e.dataTransfer.files;
            reader.readAsDataURL(file);

            if (this.isNew && this.multiple) {
                this.$emit('createFileType');
            }
        },

        addImageView () {
            if (this.imageData.length === 0) {
                this.isNew = true;
            }
            var imageInput = this.$refs.imageInput;

            if (imageInput.files && imageInput.files[0]) {
                if(imageInput.files[0].type.includes('image/')) {
                    var reader = new FileReader();

                    reader.onload = (e) => {
                        this.imageData = e.target.result;
                    };
                    reader.readAsDataURL(imageInput.files[0]);

                    if (this.isNew && this.multiple) {
                        this.$emit('createFileType');
                    }
                } else {
                    imageInput.value = "";
                    alert('Only images (.jpeg, .jpg, .png, ..) are allowed.');
                }
            }
        },

        removeImage () {
            if(confirm("Are you sure you want to delete this image?")) {
                this.$emit('onRemoveImage', this.image);
            }
        }
    }
}
</script>
