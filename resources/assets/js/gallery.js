window._ = require('lodash');

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Vue from 'vue'

//Main pages
import Modal from './components/Modal.vue'
import Dashboard from './views/Dashboard.vue'
// import Gallery from './components/Gallery.vue'

Vue.component('comment', Comment);

const app = new Vue({
    el: '#app',
    components: { Dashboard, Modal }
});