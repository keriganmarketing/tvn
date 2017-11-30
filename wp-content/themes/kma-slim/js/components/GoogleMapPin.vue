<template>
    <div ref="infowindow" class="pop-up" :id="pinName">
        <slot></slot>
    </div>
</template>

<script>
    export default {
        props: [
            'latitude',
            'longitude',
            'title'
        ],

        data: function () {
            return {
                pinName: this.title.toLowerCase().replace(/ /g, '-'),
                markerCoordinates: {
                    latitude: this.latitude,
                    longitude: this.longitude
                }
            }
        },



    }
 </script>