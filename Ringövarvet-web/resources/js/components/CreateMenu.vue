<script setup>
	import { ref, reactive } from 'vue';

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
			<input type="text">
		</div>
		<div v-show="dbAdmin.tab == 2">
			<h1>Skapa ny egenskap</h1>
			<input type="text">
		</div>
		<div v-show="dbAdmin.tab == 3">
			<h1>Skapa ny kategori</h1>
			<input type="text">
		</div>
		<div v-show="dbAdmin.tab == 4">
			<h1>Skapa ny subkategori</h1>
			<input type="text">
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