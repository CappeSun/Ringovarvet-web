<script setup>
	import { ref } from 'vue';

	const p = defineProps(['data', 'productProperties', 'properties', 'subcategories', 'units', 'update', 'delete']);

	const currentSubcategoryId = p.data.subcategoryId;
	const currentCount = p.data.count;
	const currentCost = p.data.cost;

	const ownProductProperties = p.productProperties
		.filter((e) => e.productId == p.data.id)
		.map((e) => ({
			value: e.value, 
			property: p.properties.find((ee) => ee.id == e.propertyId)
		}));
</script>

<template>
	<div class="ManageMenuProductCont">
		<h3>Subkategori</h3>
		<span>{{p.subcategories.find((e) => e.id == p.data.subcategoryId).name}}</span>
		<!--
		<SearchSelect v-bind:list="p.subcategories" v-bind:setValue="(id) => categoryId = p.data.categoryId" v-bind:default="p.data.subcategoryId"/>-->
		<h3>Egenskaper</h3>
		<div><!--
			<div v-for="entry in ownProductProperties">
				<p>{{p.properties.find((e) => e.id == entry.propertyId).name}}</p>
				<input type="text"><span>{{p.units.find((e) => e.id == p.properties.find((e) => e.id == entry.propertyId).unitId).name}}</span>
			</div>-->
			<div v-for="entry in ownProductProperties">
				<span>{{entry.property.name}}: </span><input type="test" v-model="entry.value"><span>{{p.units.find((e) => e.id == entry.property.unitId).name}}</span>
			</div>
		</div>
		<h3>Övrigt</h3>
		<span>Antal: </span><input type="text" v-model="p.data.count"><br>
		<span>Pris: </span><input type="text" v-model="p.data.cost"><br>
		<br>
		<button @click="() =>{
			p.data.name = currentName;
			p.data.subcategoryId = currentSubcategoryId;		// Fix later
		}">Återgå</button>
		<button @click="p.update('product', {
			properties: null,
			count: p.data.count,
			cost: p.data.cost
		})">Uppdatera</button>
		<button @click="p.delete('product', {id: p.data.id})">Radera</button>
	</div>
</template>

<style type="text/css">
	.ManageMenuProductCont{
		margin: 0 0 20px;
		padding: 10px;
		border: solid 1px;
	}
	.ManageMenuProductCont > input{
	}
</style>