<script setup>
	import { ref } from 'vue';

	const p = defineProps(['data', 'update', 'delete']);

	const currentName = p.data.name;
</script>

<template>
	<div class="ManageMenuLocationCont">
		<input type="text" v-model="p.data.name">
		<br>
		<button @click="() =>{
			p.data.name = currentName;
		}">Återgå</button>
		<button @click="p.update('location', {id: p.data.id, name: p.data.name})">Uppdatera</button>
		<button @click="p.delete('location', {id: p.data.id})">Radera</button>
	</div>
</template>

<style type="text/css">
	.ManageMenuLocationCont{
		margin: 0 0 20px;
		padding: 10px;
		border: solid 1px;
	}
	.ManageMenuLocationCont > input{
		display: block;
	}
</style>