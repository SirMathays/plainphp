import "@babel/polyfill"

import Vue from 'vue';

import BootstrapVue from 'bootstrap-vue';
Vue.use(BootstrapVue);

import TheApp from "./components/TheApp"

const app = new Vue({
    el: '#app',
    data: {
        now: new Date()
    },
    mounted() {
        setInterval(() => this.now = new Date(), 1000)
    },
    components: {
        TheApp
    }
})