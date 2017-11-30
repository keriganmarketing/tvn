<template>
    <div class="control has-icons-left">
        <flat-pickr v-model="date" :name="this.name" :placeholder="this.placeholder" :config="this.config" ></flat-pickr>
        <span class="icon is-small is-left">
            <i :class="['fa', this.icon ]"></i>
        </span>
    </div>
</template>

<script>
    import flatPickr from 'vue-flatpickr-component';
    import 'flatpickr/dist/flatpickr.css';

    export default {

        props: {
            name: this.name,
            placeholder: this.placeholder,
            required: true,
            config: this.config,
            icon: this.icon
        },

        data() {
            return {
                date: null,
                config: this.config
            }
        },
        components: {
            flatPickr
        }
    }
</script>
