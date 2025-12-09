<script setup>
	import { ref } from 'vue';

	const p = defineProps(['data', 'productProperties', 'properties', 'subcategories', 'units', 'update', 'delete']);

	const currentCount = p.data.count;
	const currentCost = p.data.cost;
	const currentCondition = p.data.condition;
	const currentLocationId = p.data.locationId;

	const ownProductProperties = p.productProperties
		.filter((e) => e.productId == p.data.id)
		.map((e) => ({
			value: e.value, 
			property: p.properties.find((ee) => ee.id == e.propertyId)
		}));
</script>

<template>
	<div class="ManageMenuProductCont">
		<span>ID: {{p.data.id}}</span>
		<h3>Subkategori</h3>
		<span>{{p.subcategories.find((e) => e.id == p.data.subcategoryId).name}}</span>
		<h3>Egenskaper</h3>
		<div>
			<div v-for="entry in ownProductProperties">
				<span>{{entry.property.name}}: </span><input type="test" v-model="entry.value"> <span>{{p.units.find((e) => e.id == entry.property.unitId).name}}</span>
			</div>
		</div>
		<h3>Övrigt</h3>
		<span>Antal: </span><input type="text" v-model="p.data.count"><br>
		<span>Pris: </span><input type="text" v-model="p.data.cost"><br>
		<span>Skick: </span><input class="conditionInput" type="range" min="1" max="5" v-model="p.data.condition">
		<span v-show="p.data.condition == 1"> (Måste repareras)</span>
		<span v-show="p.data.condition == 2"> (Väldigt skadat)</span>
		<span v-show="p.data.condition == 3"> (Något skadat)</span>
		<span v-show="p.data.condition == 4"> (Använt)</span>
		<span v-show="p.data.condition == 5"> (Nyskick)</span>
		<br>
		<br>
		<button @click="() =>{
			p.data.count = currentCount;
			p.data.cost = currentCost;
			p.data.condition = currentCondition;
			p.data.locationId = currentLocationId;
		}">Återgå</button>
		<button @click="p.update('product', {
			id: p.data.id,
			properties: null,		// Add later
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
	.ManageMenuProductCont > .conditionInput{
		vertical-align: middle;
	}
</style>