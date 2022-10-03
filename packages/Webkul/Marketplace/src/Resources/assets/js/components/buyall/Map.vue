<template>
    <div class="map-section">
        <div class="container">
            <h2 class="filled bg-primary map-section__title">Store finder</h2>
        </div>
        <div class="map-section__map">
            <iframe width="1440" height="648" id="gmap_canvas" src="https://maps.google.com/maps?q=kane%20country%20fairgrounds&amp;t=&amp;z=13&amp;ie=UTF8&amp;iwloc=&amp;output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Map'
}
</script>

<style scoped>

</style>