<template>

</template>
<script>
    export default {
        props: {
            google: {
                type: Object,
                required: true
            },
            map: {
                type: Object,
                required: true
            },
            marker: {
                type: Object,
                required: true
            }
        },
        mounted() {
            new this.google.maps.Marker({
                position: this.marker.position,
                marker: this.marker,
                map: this.map,
            })
        }
    }
</script>
