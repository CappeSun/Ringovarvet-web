<script setup>
	import { ref } from 'vue';

	import { dbAdmin } from '../globals.js';

	const category = ref({name: ''})

	async function fetchUpdate(kind, body) {
		console.log(body)
		let res = await fetch('/admin/update/' + kind, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'X-CSRF-TOKEN': csrf_token
			},
			body: JSON.stringify(body)
		});
		dbAdmin.value.sideSelectResponses.push({res: {text: await res.text(), code: res.status}, key: ++dbAdmin.value.sideSelectResponseKey});

		res = await fetch('/admin/read/' + kind,
		{
			method: 'POST',
			headers: {
				'X-CSRF-TOKEN': csrf_token
			}
		});
		dbAdmin.value.data = await res.json();
	}

	async function fetchDelete(kind, body) {
		let res = await fetch('/admin/delete/' + kind, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'X-CSRF-TOKEN': csrf_token
			},
			body: JSON.stringify(body)
		});
		dbAdmin.value.sideSelectResponses.push({res: {text: await res.text(), code: res.status}, key: ++dbAdmin.value.sideSelectResponseKey});

		res = await fetch('/admin/read/' + kind,
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
	<div class="createCont">
		<div v-show="dbAdmin.tab == 0">
			<h1>Hantera produkter</h1>
			<div class="productsListCont">
				<ManageMenuProduct v-for="entry in dbAdmin.data.products" v-bind:data="entry" v-bind:productProperties="dbAdmin.data.productProperties" v-bind:properties="dbAdmin.data.properties" v-bind:subcategories="dbAdmin.data.subcategories" v-bind:units="dbAdmin.data.units" v-bind:update="fetchUpdate" v-bind:delete="fetchDelete"/>
			</div>
		</div>
		<div v-show="dbAdmin.tab == 1">
			<h1>Hantera enheter</h1>
			<div class="unitsListCont">
				<ManageMenuUnit v-for="entry in dbAdmin.data" v-bind:data="entry" v-bind:update="fetchUpdate" v-bind:delete="fetchDelete"/>
			</div>
		</div>
		<div v-show="dbAdmin.tab == 2">
			<h1>Hantera egenskaper</h1>
			<div class="propertiesListCont">
				<ManageMenuProperty v-for="entry in dbAdmin.data.properties" v-bind:data="entry" v-bind:units="dbAdmin.data.units" v-bind:update="fetchUpdate" v-bind:delete="fetchDelete"/>
			</div>
		</div>
		<div v-show="dbAdmin.tab == 3">
			<h1>Hantera avdelningar</h1>
			<div class="sectionsListCont">
				<ManageMenuSection v-for="entry in dbAdmin.data" v-bind:data="entry" v-bind:update="fetchUpdate" v-bind:delete="fetchDelete"/>
			</div>
		</div>
		<div v-show="dbAdmin.tab == 4">
			<h1>Hantera kategorier</h1>
			<div class="categoriesListCont">
				<ManageMenuCategory v-for="entry in dbAdmin.data.categories" v-bind:data="entry" v-bind:sections="dbAdmin.data.sections" v-bind:update="fetchUpdate" v-bind:delete="fetchDelete"/>
			</div>
		</div>
		<div v-show="dbAdmin.tab == 5">
			<h1>Hantera subkategorier</h1>
			<div class="subcategoriesListCont">
				<ManageMenuSubcategory v-for="entry in dbAdmin.data.subcategories" v-bind:data="entry" v-bind:categories="dbAdmin.data.categories" v-bind:properties="dbAdmin.data.properties" v-bind:subcategoryProperties="dbAdmin.data.subcategoryProperties" v-bind:update="fetchUpdate" v-bind:delete="fetchDelete"/>
			</div>
		</div>
		<div v-show="dbAdmin.tab == 6">
			<h1>Hantera hyllplatser</h1>
			<div class="locationsListCont">
				<ManageMenuLocation v-for="entry in dbAdmin.data" v-bind:data="entry" v-bind:update="fetchUpdate" v-bind:delete="fetchDelete"/>
			</div>
		</div>
	</div>
</template>

<style type="text/css">
</style>