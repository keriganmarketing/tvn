<template>
    <div class="slider">
        <div class="slider-left icon is-large" @click="clickPrev" >
            <i class="fa fa-angle-left is-large" aria-hidden="true"></i>
        </div>

        <div class="slides" @mouseover="pauseSlide" @mouseleave="unpauseSlide">
            <slot></slot>
        </div>

        <div class="slider-right icon is-large" @click="clickNext" >
            <i class="fa fa-angle-right is-large" aria-hidden="true"></i>
        </div>
    </div>
</template>

<script>
    export default {

        data(){
            return {
                slides: [],
                activeSlide: 0,
                paused: false
            };
        },

        created(){

            this.slides = this.$children
            setInterval(() => { if(this.paused == false){ this.nextSlide() } }, 6000)

        },

        methods: {

            nextSlide(){
                this.slides[this.activeSlide]._data.isActive = false
                if(this.activeSlide == this.slides.length-1){
                    this.activeSlide = -1
                }
                this.activeSlide++
                this.slides[this.activeSlide]._data.isActive = true
            },

            prevSlide(){
                this.slides[this.activeSlide]._data.isActive = false
                this.activeSlide--
                if(this.activeSlide == -1){
                    this.activeSlide = this.slides.length-1
                }
                this.slides[this.activeSlide]._data.isActive = true
            },

            clickNext(){
                this.nextSlide()
                this.pauseSlide()
            },

            clickPrev(){
                this.prevSlide()
                this.pauseSlide()
            },

            pauseSlide(){
                this.paused = true;
            },

            unpauseSlide(){
                this.paused = false;
            }

        }

    }
</script>