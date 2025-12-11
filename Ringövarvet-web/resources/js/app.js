import './bootstrap';

import {createApp} from 'vue/dist/vue.esm-bundler.js';
// Index
import Index from './components/Index.vue';
// DBAdmin
import SideSelect from './components/SideSelect.vue';
import SideSelectResponse from './components/SideSelectResponse.vue';
import AdminMenu from './components/AdminMenu.vue';
import CreateMenu from './components/CreateMenu.vue';
import ManageMenu from './components/ManageMenu.vue';
import ManageMenuProduct from './components/ManageMenuProduct.vue';
import ManageMenuUnit from './components/ManageMenuUnit.vue';
import ManageMenuProperty from './components/ManageMenuProperty.vue';
import ManageMenuSection from './components/ManageMenuSection.vue';
import ManageMenuCategory from './components/ManageMenuCategory.vue';
import ManageMenuSubcategory from './components/ManageMenuSubcategory.vue';
import ManageMenuLocation from './components/ManageMenuLocation.vue';
import SearchSelect from './components/SearchSelect.vue';
import Qr from './components/Qr.vue';
import Qrcode from './components/Qrcode.vue';
// CreateProductMobile
import CreateProductMobile from './components/CreateProductMobile.vue';
// ManageAccounts
import AccountManager from './components/AccountManager.vue';

createApp({})
	.component('Index', Index)
	.component('SideSelect', SideSelect)
	.component('SideSelectResponse', SideSelectResponse)
	.component('AdminMenu', AdminMenu)
	.component('CreateMenu', CreateMenu)
	.component('ManageMenu', ManageMenu)
	.component('ManageMenuProduct', ManageMenuProduct)
	.component('ManageMenuUnit', ManageMenuUnit)
	.component('ManageMenuProperty', ManageMenuProperty)
	.component('ManageMenuSection', ManageMenuSection)
	.component('ManageMenuCategory', ManageMenuCategory)
	.component('ManageMenuSubcategory', ManageMenuSubcategory)
	.component('ManageMenuLocation', ManageMenuLocation)
	.component('SearchSelect', SearchSelect)
	.component('Qr', Qr)
	.component('Qrcode', Qrcode)
	.component('CreateProductMobile', CreateProductMobile)
	.component('AccountManager', AccountManager)
	.mount('#app');