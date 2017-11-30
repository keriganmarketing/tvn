<template>
    <div class="modal is-active" v-if="this.$parent.modalOpen != ''">
        <div class="modal-background" @click="toggleModal"></div>
        <div class="modal-content large">
            <div class="video-wrapper">
            <iframe :src="'https://player.vimeo.com/video/' + this.$parent.vimeoCode + '?autoplay=1&portrait=0'" width="800" height="450" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
            </div>
        </div>
        <button class="modal-close is-large" @click="toggleModal"></button>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                showModal: false
            }
        },
        methods: {
            toggleModal(){
                this.showModal = !this.showModal;
                if(this.$parent.modalOpen !== ''){
                    this.$parent.modalOpen = ''
                }
            }
        },

        mounted() {
            //console.log('Component mounted.');

            this.$parent.$on('toggleModal', function (modal,code) {
                this.modalOpen = modal;
                this.vimeoCode = code;
            });

        }
    }
</script>