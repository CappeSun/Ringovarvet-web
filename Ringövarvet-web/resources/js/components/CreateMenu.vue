<script setup>
	import { ref } from 'vue';

	import { dbAdmin } from '../globals.js';

	const categoryId = ref(0);
	const subcategoryId = ref(0);

	const categories = ref([
		{
			name: 'Segel',
			id: 0,
			subcategories: [
				{
					name: 'Storsegel',
					id: 0
				},
				{
					name: 'Försegel',
					id: 1
				}
			]
		},
		{
			name: 'Motor',
			id: 1,
			subcategories: [
				{
					name: 'Tändkulemotor',
					id: 0
				},
				{
					name: 'Diselmotor',
					id: 1
				}
			]
		}
	]);

	const properties = ref([
		{
			name: 'Båtmodell',
			id: 0
		},
		{
			name: 'Förlik',
			id: 1
		}
	]);

	const unitName = ref('');

	const propertyName = ref('');
	const propertyUnitId = ref(0);

	const categoryName = ref('');

	const subcategoryDataCategories = ref([]);
	const subcategoryDataProperties = ref([]);

	const subcategoryName = ref('');
	const subcategoryCategoryId = ref(0);
	let keyCnt = 0;
	const subcategoryPropertyIds = ref([{id: 0, key: keyCnt}]);

	async function fetchCreate(kind, body) {
		let res = await fetch('/api/admin/create/' + kind, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'X-CSRF-TOKEN': csrf_token
			},
			body: JSON.stringify(body)
		});
	}
</script>

<template>
	<div class="createCont">
		<div v-show="dbAdmin.tab == 0">
			<h1>Skapa ny produkt {{ categoryId + ' - ' + subcategoryId}}</h1>
			<div class="categorySelCont">
				<SearchSelect v-bind:list="categories" v-bind:setValue="(id) => categoryId = id"/>
				<SearchSelect v-bind:list="categories[categoryId].subcategories" v-bind:setValue="(id) => subcategoryId = id"/>
			</div>
			<div class="propertySelCont">
				<div v-for="property in properties">
					<h2>{{property.name}}</h2>
					<input type="text">
				</div>
			</div>
		</div>
		<div v-show="dbAdmin.tab == 1">
			<h1>Skapa ny enhet</h1>
			<input type="text" v-model="unitName">
			<button @click="(() =>{
				fetchCreate('unit', {name: unitName});
				unitName = '';
			})()">Skapa</button>
		</div>
		<div v-show="dbAdmin.tab == 2">
			<h1>Skapa ny egenskap</h1>
			<input type="text" v-model="propertyName">
			<SearchSelect v-bind:list="dbAdmin.data.units" v-bind:setValue="(id) => propertyUnitId = id"/>
			<button @click="(() =>{
				fetchCreate('property', {name: propertyName, unitId: propertyUnitId});
				propertyName = '';
			})()">Skapa</button>
		</div>
		<div v-show="dbAdmin.tab == 3">
			<h1>Skapa ny kategori</h1>
			<input type="text" v-model="categoryName">
			<button @click="(() =>{
				fetchCreate('category', {name: categoryName});
				categoryName = '';
			})()">Skapa</button>
		</div>
		<div v-show="dbAdmin.tab == 4">
			<h1>Skapa ny subkategori</h1>
			<input type="text" v-model="subcategoryName">
			<SearchSelect v-bind:list="dbAdmin.data.categories" v-bind:setValue="(id) => subcategoryCategoryId = id"/>
			<div v-for="(entry, index) in subcategoryPropertyIds" :key="entry.key">
				id: {{entry.id}}, index: {{index}}, key: {{entry.key}}
				<SearchSelect v-bind:list="dbAdmin.data.properties" v-bind:setValue="(id) => subcategoryPropertyIds[index].id = id"/>
				<button @click="(() =>{
					subcategoryPropertyIds.splice(index, 1);
				})()">Ta bort</button>
			</div>
			<button @click="(() =>{
				subcategoryPropertyIds.push({id: 0, key: ++keyCnt});
			})()">Lägg till</button>
			<button @click="(() =>{
				fetchCreate('subcategory', {name: subcategoryName, categoryId: subcategoryCategoryId, propertyIds: subcategoryPropertyIds.map((e, i) => e = subcategoryPropertyIds[i].id)});
				//subcategoryName = '';
			})()">Skapa</button>
		</div>
	</div>
</template>

<style type="text/css">
	.categorySelCont{
		display: flex;
		flex-direction: row;
	}
	.propertySelCont{
		display: flex;
		flex-direction: row;
	}
</style>