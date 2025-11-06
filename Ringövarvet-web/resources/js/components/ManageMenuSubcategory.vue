<script setup>
	import { ref } from 'vue';

	const p = defineProps(['data', 'categories', 'update', 'delete']);

	const currentName = p.data.name;
	const currentCategoryId = p.data.categoryId;
</script>

<template>
	<div class="ManageMenuSubcategoryCont">
		<input type="text" v-model="p.data.name">
		<search-select v-bind:list="p.categories" v-bind:setValue="(id) => categoryId = p.data.categoryId" v-bind:default="p.data.categoryId"/>
		<button @click="fetchUpdate('subcategory', {categoryId: varCategoryId})">Uppdatera</button>
		<button @click="() =>{
			p.data.name = currentName;
		}">Återgå</button>
		<button @click="fetchDelete('subcategory', {id: p.data.id})">Radera</button>
	</div>
</template>

<style type="text/css">
	.ManageMenuSubcategoryCont{
		margin: 0 0 20px;
		padding: 10px;
		border: solid 1px;
	}
	.ManageMenuSubcategoryCont > input{
		display: block;
	}
</style>