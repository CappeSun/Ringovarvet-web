import './bootstrap';

import {createApp} from 'vue/dist/vue.esm-bundler.js';
import SideSelect from './components/SideSelect.vue';
import AdminMenu from './components/AdminMenu.vue';
import CreateMenu from './components/CreateMenu.vue';
import ManageMenu from './components/ManageMenu.vue';
import ManageMenuProduct from './components/ManageMenuProduct.vue';
import ManageMenuUnit from './components/ManageMenuUnit.vue';
import ManageMenuProperty from './components/ManageMenuProperty.vue';
import ManageMenuCategory from './components/ManageMenuCategory.vue';
import ManageMenuSubcategory from './components/ManageMenuSubcategory.vue';
import SearchSelect from './components/SearchSelect.vue';

createApp({})
	.component('SideSelect', SideSelect)
	.component('AdminMenu', AdminMenu)
	.component('CreateMenu', CreateMenu)
	.component('ManageMenu', ManageMenu)
	.component('ManageMenuProduct', ManageMenuProduct)
	.component('ManageMenuUnit', ManageMenuUnit)
	.component('ManageMenuProperty', ManageMenuProperty)
	.component('ManageMenuCategory', ManageMenuCategory)
	.component('ManageMenuSubcategory', ManageMenuSubcategory)
	.component('SearchSelect', SearchSelect)
	.mount('#app');