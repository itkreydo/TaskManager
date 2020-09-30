require('./bootstrap');
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';
import storeData from './store/store'
import {routes} from "./router/routes";
import {initialize} from "./helpers/general";
import '@mdi/font/css/materialdesignicons.css'
import Vuetify from 'vuetify';
Vue.use(Vuetify);

Vue.use(VueRouter);
Vue.use(Vuex);

const store = new Vuex.Store(storeData);

const router = new VueRouter({
    routes,
    mode: 'history'
});

initialize(store, router);

import App from "./components/App"
const app = new Vue({
    el: '#app',
    router,
    store,
    vuetify: new Vuetify({
        theme:{
            light:true
        },
    }),
    components: {App}
});
