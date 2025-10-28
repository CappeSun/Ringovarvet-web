import './bootstrap';

import {createApp} from 'vue/dist/vue.esm-bundler.js';
import DatabaseInterface from './components/DatabaseInterface.vue';

createApp({})
	.component('DatabaseInterface', DatabaseInterface)
	.mount('#app');