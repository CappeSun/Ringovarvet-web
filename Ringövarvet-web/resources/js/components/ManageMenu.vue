<script setup>
	import { ref } from 'vue';

	import { dbAdmin } from '../globals.js';

	// PRODUCT

	const productFilterSelSubcategory = ref(0);
	const productFilterSelId = ref('');

	let productSubcategoryGetValue = null;
	let productSubcategoryOnSelect = () =>{
		productFilterSelSubcategory.value = productSubcategoryGetValue();
	}

	// FETCH

	async function fetchUpdate(kind, body) {
		let res = await fetch('/database/update/' + kind, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'X-CSRF-TOKEN': csrf_token
			},
			body: JSON.stringify(body)
		});
		dbAdmin.value.sideSelectResponses.push({res: {text: await res.text(), code: res.status}, key: ++dbAdmin.value.sideSelectResponseKey});

		res = await fetch('/database/read/' + kind,
		{
			method: 'POST',
			headers: {
				'X-CSRF-TOKEN': csrf_token
			}
		});
		dbAdmin.value.data = await res.json();
	}

	async function fetchDelete(kind, body) {
		let res = await fetch('/database/delete/' + kind, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'X-CSRF-TOKEN': csrf_token
			},
			body: JSON.stringify(body)
		});
		dbAdmin.value.sideSelectResponses.push({res: {text: await res.text(), code: res.status}, key: ++dbAdmin.value.sideSelectResponseKey});

		res = await fetch('/database/read/' + kind,
		{
			method: 'POST',
			headers: {
				'X-CSRF-TOKEN': csrf_token
			}
		});
		dbAdmin.value.data = await res.json();
	}
</script>

<template>
	<div class="manageCont">
		<div class="manageCont manageProductsCont" v-show="dbAdmin.tab == 0">
			<h1>Hantera artiklar</h1>
			<h4>(Skriv ut sida för QR, {{dbAdmin.QRProductsSelected.length}} valda) <button @click="dbAdmin.QRProductsSelected = []">Rensa lista</button></h4>
			<div class="sortMenu">
				<span>Sortera efter subkategori</span>
				<SearchSelect v-bind:list="dbAdmin.data.subcategories" v-bind:getValue="(fun) => productSubcategoryGetValue = fun" v-bind:onSelect="productSubcategoryOnSelect" v-bind:default="0"/>
				<span>och ID</span>
				<input type="text" v-model="productFilterSelId">
			</div>
			<div class="listCont">
				<ManageMenuProduct
					v-for="entry in dbAdmin.data.products"
					v-show="
						(
							entry.subcategoryId == productFilterSelSubcategory ||
							productFilterSelSubcategory == 0
						) && (
							entry.id == productFilterSelId ||
							productFilterSelId == ''
						)
					"
					v-bind:data="entry"
					v-bind:productProperties="dbAdmin.data.productProperties"
					v-bind:properties="dbAdmin.data.properties"
					v-bind:subcategories="dbAdmin.data.subcategories"
					v-bind:units="dbAdmin.data.units"
					v-bind:locations="dbAdmin.data.locations"
					v-bind:update="fetchUpdate"
					v-bind:delete="fetchDelete"
					:key="entry.id"/>
			</div>
		</div>
		<div class="manageCont" v-show="dbAdmin.tab == 1">
			<h1>Hantera enheter</h1>
			<div class="listCont">
				<ManageMenuUnit v-if="dbAdmin.data.units" v-for="entry in dbAdmin.data.units.slice(1)" v-bind:data="entry" v-bind:update="fetchUpdate" v-bind:delete="fetchDelete" :key="entry.id"/>
			</div>
		</div>
		<div class="manageCont" v-show="dbAdmin.tab == 2">
			<h1>Hantera egenskaper</h1>
			<div class="listCont">
				<ManageMenuProperty v-for="entry in dbAdmin.data.properties" v-bind:data="entry" v-bind:units="dbAdmin.data.units" v-bind:update="fetchUpdate" v-bind:delete="fetchDelete" :key="entry.id"/>
			</div>
		</div>
		<div class="manageCont" v-show="dbAdmin.tab == 3">
			<h1>Hantera avdelningar</h1>
			<div class="listCont">
				<ManageMenuSection v-for="entry in dbAdmin.data.sections" v-bind:data="entry" v-bind:update="fetchUpdate" v-bind:delete="fetchDelete" :key="entry.id"/>
			</div>
		</div>
		<div class="manageCont" v-show="dbAdmin.tab == 4">
			<h1>Hantera kategorier</h1>
			<div class="listCont">
				<ManageMenuCategory v-for="entry in dbAdmin.data.categories" v-bind:data="entry" v-bind:sections="dbAdmin.data.sections" v-bind:update="fetchUpdate" v-bind:delete="fetchDelete" :key="entry.id"/>
			</div>
		</div>
		<div class="manageCont" v-show="dbAdmin.tab == 5">
			<h1>Hantera subkategorier</h1>
			<div class="listCont">
				<ManageMenuSubcategory v-for="entry in dbAdmin.data.subcategories" v-bind:data="entry" v-bind:categories="dbAdmin.data.categories" v-bind:properties="dbAdmin.data.properties" v-bind:subcategoryProperties="dbAdmin.data.subcategoryProperties" v-bind:update="fetchUpdate" v-bind:delete="fetchDelete" :key="entry.id"/>
			</div>
		</div>
		<div class="manageCont" v-show="dbAdmin.tab == 6">
			<h1>Hantera hyllplatser</h1>
			<div class="listCont">
				<ManageMenuLocation v-for="entry in dbAdmin.data.locations" v-bind:data="entry" v-bind:update="fetchUpdate" v-bind:delete="fetchDelete" :key="entry.id"/>
			</div>
		</div>
	</div>
</template>

<style type="text/css">
	.manageCont{
		height: 100vh;
		display: flex;
		flex-direction: column;
	}
	.manageProductsCont > h4{
		margin: 10px 0 30px;
	}
	.manageProductsCont > .sortMenu{
		display: flex;
	}
	.manageProductsCont > .sortMenu > div{
		margin: 0 5px 0 0;
		flex: 1;
	}
	.manageProductsCont > .sortMenu > span{
		margin: 2.5px 5px 0 0;
		flex: 0 0 fit-content;
	}
	.manageProductsCont > .sortMenu > input{
		flex: 0;
	}
	.manageCont > .listCont{
		margin: 20px 0 0;
        flex: 1 1;
        overflow: scroll;
    }
</style>