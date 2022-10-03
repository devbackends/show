<template>
    <div>
        <div class="image-wrapper">
            <image-item
                v-for='(image, index) in items'
                :key='image.id'
                :image="image"
                :input-name="inputName"
                :required="required"
                :multiple="multiple"
                :imageCount="imageCount"
                :remove-button-label="removeButtonLabel"
                @onRemoveImage="removeImage($event)"
                @createFileType="createFileType"
            ></image-item>
        </div>

        <!--<label class="btn btn-primary" style="display: inline-block; width: auto" @click="createFileType">{{ buttonLabel }}</label>-->
    </div>
</template>

<script>
export default {
    props: {
        buttonLabel: {
            type: String,
            required: false,
            default: 'Add Image'
        },

        removeButtonLabel: {
            type: String,
            required: false,
            default: 'Remove Image'
        },

        inputName: {
            type: String,
            required: false,
            default: 'attachments'
        },

        images: {
            type: Array|String,
            required: false,
            default: () => ([])
        },

        multiple: {
            type: Boolean,
            required: false,
            default: true
        },

        required: {
            type: Boolean,
            required: false,
            default: false
        }
    },

    data: () => ({
        imageCount: 0,
        items: []
    }),

    created () {
        if (this.multiple) {
            if (this.images.length) {
                this.images.forEach((image) => {
                    this.items.push(image)
                    this.imageCount++;
                });
                this.createFileType();
            } else {
                this.createFileType();
            }
        } else {
            if (this.images && this.images !== '') {
                this.items.push({'id': 'image_' + this.imageCount, 'url': this.images})
                this.imageCount++;
            } else {
                this.createFileType();
            }
        }
    },

    methods: {
        createFileType (imageData = null) {
            this.imageCount++;
            if (imageData) {
                imageData.id = 'image_' + this.imageCount;
                this.items.unshift(imageData);
            } else {
                this.items.push({'id': 'image_' + this.imageCount});
            }
        },

        removeImage (image) {
            let index = this.items.indexOf(image)

            Vue.delete(this.items, index);
            this.imageCount--;
        }
    }

}
</script>
