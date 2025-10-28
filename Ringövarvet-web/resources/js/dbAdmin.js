import './bootstrap';

import {createApp} from 'vue/dist/vue.esm-bundler.js';
import SideSelect from './components/SideSelect.vue';
import AdminMenu from './components/AdminMenu.vue';
import CreateMenu from './components/CreateMenu.vue';
import ManageMenu from './components/ManageMenu.vue';
import SearchSelect from './components/SearchSelect.vue';

createApp({})
	.component('SideSelect', SideSelect)
	.component('AdminMenu', AdminMenu)
	.component('CreateMenu', CreateMenu)
	.component('ManageMenu', ManageMenu)
	.component('SearchSelect', SearchSelect)
	.mount('#app');