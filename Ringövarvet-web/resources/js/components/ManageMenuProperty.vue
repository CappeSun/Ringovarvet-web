<script setup>
	import { ref } from 'vue';

	const p = defineProps(['data', 'units', 'update', 'delete']);

	const currentName = p.data.name;
	const currentUnitId = p.data.unitId;
</script>

<template>
	<div class="ManageMenuPropertyCont">
		<input type="text" v-model="p.data.name">
		<search-select v-bind:list="p.units" v-bind:default="p.data.unitId" v-bind:setValue="(id) => p.data.unitId = id"/>
		<button @click="p.update('property', {id: p.data.id, name: p.data.name, unitId: p.data.unitId})">Uppdatera</button>
		<button @click="() =>{
			p.data.name = currentName;
		}">Återgå</button>
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