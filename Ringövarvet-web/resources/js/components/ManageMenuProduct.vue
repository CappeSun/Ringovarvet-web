<script setup>
	import { ref } from 'vue';

	import { dbAdmin } from '../globals.js';

	const p = defineProps(['data', 'update', 'delete']);

	const name = ref(p.data.name);
</script>

<template>
	<div class="ManageMenuProductCont">
		<input type="text" v-model="name">
		<button @click="p.update('category', {id: p.data.id, name: name})">Uppdatera</button>
		<button @click="() =>{
			name = p.data.name;
		}">Återgå</button>
		<button @click="p.delete('category', {id: p.data.id})">Radera</button>
	</div>
</template>

<style type="text/css">
	.ManageMenuProductCont{
		margin: 0 0 20px;
		padding: 10px;
		border: solid 1px;
	}
	.ManageMenuProductCont > input{
		display: block;
	}
</style>