<script setup>
	import { ref } from 'vue';

	import { dbAdmin } from '../globals.js';

	// PRODUCT

	const productFilterSelSubcategory = ref(0);

	let productSubcategoryGetValue = null;
	let productSubcategoryOnSelect = () =>{
		productFilterSelSubcategory.value = productSubcategoryGetValue();
	}

	// FETCH

	async function fetchUpdate(kind, body) {
		console.log(body)
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
		<div class="manageProductsCont" v-show="dbAdmin.tab == 0">
			<h1>Hantera artiklar</h1>
			<h3>Subkategori</h3>
			<SearchSelect v-bind:list="dbAdmin.data.subcategories" v-bind:getValue="(fun) => productSubcategoryGetValue = fun" v-bind:onSelect="productSubcategoryOnSelect" v-bind:default="0"/>
			<div class="listCont">
				<ManageMenuProduct
					v-for="entry in dbAdmin.data.products"
					v-show="
						entry.subcategoryId == productFilterSelSubcategory ||
						productFilterSelSubcategory == 0
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
		<div v-show="dbAdmin.tab == 1">
			<h1>Hantera enheter</h1>
			<div class="listCont">
				<ManageMenuUnit v-if="dbAdmin.data.units" v-for="entry in dbAdmin.data.units.slice(1)" v-bind:data="entry" v-bind:update="fetchUpdate" v-bind:delete="fetchDelete" :key="entry.id"/>
			</div>
		</div>
		<div v-show="dbAdmin.tab == 2">
			<h1>Hantera egenskaper</h1>
			<div class="listCont">
				<ManageMenuProperty v-for="entry in dbAdmin.data.properties" v-bind:data="entry" v-bind:units="dbAdmin.data.units" v-bind:update="fetchUpdate" v-bind:delete="fetchDelete" :key="entry.id"/>
			</div>
		</div>
		<div v-show="dbAdmin.tab == 3">
			<h1>Hantera avdelningar</h1>
			<div class="listCont">
				<ManageMenuSection v-for="entry in dbAdmin.data.sections" v-bind:data="entry" v-bind:update="fetchUpdate" v-bind:delete="fetchDelete" :key="entry.id"/>
			</div>
		</div>
		<div v-show="dbAdmin.tab == 4">
			<h1>Hantera kategorier</h1>
			<div class="listCont">
				<ManageMenuCategory v-for="entry in dbAdmin.data.categories" v-bind:data="entry" v-bind:sections="dbAdmin.data.sections" v-bind:update="fetchUpdate" v-bind:delete="fetchDelete" :key="entry.id"/>
			</div>
		</div>
		<div v-show="dbAdmin.tab == 5">
			<h1>Hantera subkategorier</h1>
			<div class="listCont">
				<ManageMenuSubcategory v-for="entry in dbAdmin.data.subcategories" v-bind:data="entry" v-bind:categories="dbAdmin.data.categories" v-bind:properties="dbAdmin.data.properties" v-bind:subcategoryProperties="dbAdmin.data.subcategoryProperties" v-bind:update="fetchUpdate" v-bind:delete="fetchDelete" :key="entry.id"/>
			</div>
		</div>
		<div v-show="dbAdmin.tab == 6">
			<h1>Hantera hyllplatser</h1>
			<div class="listCont">
				<ManageMenuLocation v-for="entry in dbAdmin.data.locations" v-bind:data="entry" v-bind:update="fetchUpdate" v-bind:delete="fetchDelete" :key="entry.id"/>
			</div>
		</div>
	</div>
</template>

<style type="text/css">
	.manageProductsCont{
		height: 100vh;
		display: flex;
		flex-direction: column;
	}
	.listCont{
		margin: 20px 0 0;
        flex-grow: 1;
        overflow: scroll;
    }
</style>