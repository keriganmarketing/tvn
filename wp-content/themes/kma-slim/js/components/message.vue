<template>
    <article class="message" v-show="isVisible" :class="this.class">
        <div class="message-body">
            <button class="delete is-pulled-right" @click="hideMessage"></button>
            <slot></slot>
        </div>
    </article>
</template>

<script>
    export default {
        props: [
            'title',
            'class'
        ],

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