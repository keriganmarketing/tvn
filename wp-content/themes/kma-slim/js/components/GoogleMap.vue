<template>
    <div class="google-map" :id="mapName">
        <slot></slot>
    </div>
</template>

<script>
    export default {
        props: [
            'name',
            'latitude',
            'longitude',
            'zoom'
        ],

        data: function () {
            return {
                mapName: this.name + "-map",
                markers: [],
                pins: []
            }
        },

        mounted: function () {
            const element = document.getElementById(this.mapName)
            const options = {
                zoom: this.zoom,
                center: new google.maps.LatLng(this.latitude,this.longitude),
                disableDefaultUI: true,
                zoomControl: true,
                scaleControl: true,
                styles: [
                    {
                        "featureType": "landscape",
                        "elementType": "all",
                        "stylers": [
                            {
                                "hue": "#FFBB00"
                            },
                            {
                                "saturation": 43.400000000000006
                            },
                            {
                                "lightness": 37.599999999999994
                            },
                            {
                                "gamma": 1
                            }
                        ]
                    },
                    {
                        "featureType": "poi",
                        "elementType": "all",
                        "stylers": [
                            {
                                "hue": "#a0ff00"
                            },
                            {
                                "saturation": "-21"
                            },
                            {
                                "lightness": "35"
                            },
                            {
                                "gamma": 1
                            }
                        ]
                    },
                    {
                        "featureType": "road.highway",
                        "elementType": "all",
                        "stylers": [
                            {
                                "hue": "#ffc200"
                            },
                            {
                                "saturation": -61.8
                            },
                            {
                                "lightness": "7"
                            },
                            {
                                "gamma": 1
                            }
                        ]
                    },
                    {
                        "featureType": "road.arterial",
                        "elementType": "all",
                        "stylers": [
                            {
                                "hue": "#ff0300"
                            },
                            {
                                "saturation": "-100"
                            },
                            {
                                "lightness": "20"
                            },
                            {
                                "gamma": 1
                            }
                        ]
                    },
                    {
                        "featureType": "road.local",
                        "elementType": "all",
                        "stylers": [
                            {
                                "hue": "#ff0300"
                            },
                            {
                                "saturation": -100
                            },
                            {
                                "lightness": "-14"
                            },
                            {
                                "gamma": 1
                            }
                        ]
                    },
                    {
                        "featureType": "water",
                        "elementType": "all",
                        "stylers": [
                            {
                                "hue": "#0078ff"
                            },
                            {
                                "saturation": "-65"
                            },
                            {
                                "lightness": "-7"
                            },
                            {
                                "gamma": 1
                            }
                        ]
                    }
                ]
            }
            const map = new google.maps.Map(element, options);
            const bounds = new google.maps.LatLngBounds();
            this.markers = this.$children;

            for(var i = 0; i < this.markers.length; i++){
                var pin = this.markers[i];
                this.pins.push({
                    latitude: pin._data.markerCoordinates.latitude,
                    longitude: pin._data.markerCoordinates.longitude,
                });

                const position = new google.maps.LatLng(pin.latitude, pin.longitude);
                const marker = new google.maps.Marker({
                    position,
                    map,
                    icon: '/wp-content/themes/kma-slim/img/map-pin.png'
                });

                const infowindow = new google.maps.InfoWindow({
                    maxWidth: 279,
                    content: pin.$refs.infowindow,
                    title: pin._data.name
                });

                marker.addListener('click', function(){
                    infowindow.open(map, marker);
                });

                bounds.extend(position);
                map.fitBounds(bounds);

            }
        },

    }
</script>