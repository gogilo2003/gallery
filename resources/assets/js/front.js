window._ = require('lodash');

window.axios = require('axios');
// window.jquery = require('jquery');
// window.cropper = require('cropper');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Vue from 'vue'

//Main pages
import Gallery from './views/Gallery.vue'

const app = new Vue({
    el: '#app',
    components: { Gallery }
});