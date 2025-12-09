<script setup>
	import { ref } from 'vue';

	const p = defineProps(['data', 'units', 'update', 'delete']);

	const currentName = p.data.name;
	const currentUnitId = p.data.unitId;

	let getUnitValue = null;
	let resetUnitValue = null;
</script>

<template>
	<div class="ManageMenuPropertyCont">
		<h3>Namn</h3>
		<input type="text" v-model="p.data.name">
		<h3>Enhet</h3>
		<SearchSelect v-bind:list="p.units" v-bind:default="p.data.unitId" v-bind:getValue="(fun) => getUnitValue = fun" v-bind:resetValue="(fun) => resetUnitValue = fun"/>
		<br>
		<button @click="() =>{
			p.data.name = currentName;
			resetUnitValue();
		}">Återgå</button>
		<button @click="p.update('property', {
			id: p.data.id,
			name: p.data.name,
			unitId: getUnitValue()
		})">Uppdatera</button>
		<button @click="p.delete('property', {id: p.data.id})">Radera</button>
	</div>
</template>

<style type="text/css">
	.ManageMenuPropertyCont{
		margin: 0 0 20px;
		padding: 10px;
		border: solid 1px;
	}
	.ManageMenuPropertyCont > input{
		display: block;
	}
</style>