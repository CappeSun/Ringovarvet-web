<script setup>
	import { ref } from 'vue';

	const p = defineProps(['data', 'update', 'delete']);

	const currentName = p.data.name;
</script>

<template>
	<div class="ManageMenuUnitCont">
		<input type="text" v-model="p.data.name">
		<button @click="p.update('unit', {id: p.data.id, name: p.data.name})">Uppdatera</button>
		<button @click="() =>{
			p.data.name = currentName;
		}">Återgå</button>
		<button @click="p.delete('unit', {id: p.data.id})">Radera</button>
	</div>
</template>

<style type="text/css">
	.ManageMenuUnitCont{
		margin: 0 0 20px;
		padding: 10px;
		border: solid 1px;
	}
	.ManageMenuUnitCont > input{
		display: block;
	}
</style>