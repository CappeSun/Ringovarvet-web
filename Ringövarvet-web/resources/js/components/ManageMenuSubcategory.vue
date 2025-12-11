<script setup>
	import { ref } from 'vue';

	const p = defineProps(['data', 'categories', 'properties', 'subcategoryProperties', 'update', 'delete']);

	const currentName = p.data.name;
	const currentCategoryId = p.data.categoryId;

	let ownSubcategoryPropertiesDataKeyCnt = 0;

	const ownSubcategoryProperties = p.subcategoryProperties.filter((e) => e.subcategoryId == p.data.id);

	const ownSubcategoryPropertiesData = ref(ownSubcategoryProperties.map((e) => ({
			subcategoryId: e.subcategoryId,
			propertyId: e.propertyId,
			getValue: null,
			resetValue: null,
			key: ownSubcategoryPropertiesDataKeyCnt++
	})));

	let getCategoryId;
	let resetCategoryId;
</script>

<template>
	<div class="ManageMenuSubcategoryCont">
		<span>Namn: </span><input type="text" v-model="p.data.name">
		<h3>Kategori</h3>
		<SearchSelect v-bind:list="p.categories" v-bind:getValue="(fun) => getCategoryId = fun" v-bind:resetValue="(fun) => resetCategoryId = fun" v-bind:default="p.data.categoryId"/>
		<h3>Egenskaper</h3>
		<div class="subcategoryPropertyCont">
			<!--<SearchSelect v-for="subcategoryProperty in ownSubcategoryPropertiesData" v-bind:list="p.properties" v-bind:getValue="(fun) => subcategoryProperty.getValue = fun" v-bind:resetValue="(fun) => subcategoryProperty.resetValue = fun" v-bind:default="subcategoryProperty.propertyId" key="subcategoryProperty.key"/>-->
			<p v-for="subcategoryProperty in ownSubcategoryPropertiesData">{{p.properties.find((e) => e.id == subcategoryProperty.propertyId).name}}</p>
		</div>
		<br>
		<button @click="() =>{
			p.data.name = currentName;
			resetCategoryId();
			ownSubcategoryPropertiesData.forEach((e) => e.resetValue());
		}">Återgå</button>
		<button @click="p.update('subcategory', {
			id: p.data.id,
			name: p.data.name,
			categoryId: getCategoryId(),
			properties: null		// Probably won't add
		})">Uppdatera</button>
		<button @click="p.delete('subcategory', {id: p.data.id})">Radera</button>
	</div>
</template>

<style type="text/css">
	.ManageMenuSubcategoryCont{
		margin: 0 0 20px;
		padding: 10px;
		border: solid 1px;
	}
	.ManageMenuSubcategoryCont > input{
	}
	.subcategoryPropertyCont > div{
		margin: 0 0 10px;
	}
</style>