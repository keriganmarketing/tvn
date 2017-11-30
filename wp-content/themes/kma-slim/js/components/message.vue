<template>
    <article class="message" v-show="isVisible">
        <div class="message-header"> {{ title }} <button class="delete" @click="hideMessage"></button></div>
        <div class="message-body">
            <slot></slot>
        </div>
    </article>
</template>

<script>
    export default {
        props: ['title'],

        data(){
            return {
                isVisible: true
            };
        },

        methods: {
            hideMessage(){
                this.isVisible = !this.isVisible;
            }
        }
    }
</script>