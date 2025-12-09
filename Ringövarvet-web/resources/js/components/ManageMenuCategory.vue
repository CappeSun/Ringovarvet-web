<script setup>
	import { ref } from 'vue';

	const p = defineProps(['data', 'sections', 'update', 'delete']);

	const currentName = p.data.name;
	const currentSectionId = p.data.sectionId;

	let getSectionId = null;
	let resetSectionId = null;
</script>

<template>
	<div class="ManageMenuCategoryCont">
		<input type="text" v-model="p.data.name">
		<h3>Avdelning</h3>
		<SearchSelect v-bind:list="p.sections" v-bind:getValue="(fun) => getSectionId = fun" v-bind:resetValue="(fun) => resetSectionId = fun" v-bind:default="p.data.sectionId"/>
		<br>
		<button @click="() =>{
			p.data.name = currentName;
			resetSectionId();
		}">Återgå</button>
		<button @click="p.update('category', {
			id: p.data.id,
			name: p.data.name,
			sectionId: getSectionId()
		})">Uppdatera</button>
		<button @click="p.delete('category', {id: p.data.id})">Radera</button>
	</div>
</template>

<style type="text/css">
	.ManageMenuCategoryCont{
		margin: 0 0 20px;
		padding: 10px;
		border: solid 1px;
	}
	.ManageMenuCategoryCont > input{
		display: block;
	}
</style>