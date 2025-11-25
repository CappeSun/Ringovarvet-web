<script setup>
	import { ref } from 'vue';

	const p = defineProps(['list', 'getValue', 'resetValue', 'onSelect', 'default']);

	const searchQuery = ref(p.default ? p.list.find((e) => e.id == p.default).name : '');
	let selectedId = p.default;

	if (p.getValue)
		p.getValue(() => selectedId);

	if (p.resetValue) {
		p.resetValue(() =>{
			searchQuery.value = p.default ? p.list.find((e) => e.id == p.default).name : '';
			selectedId = p.default;

			if (p.onSelect) p.onSelect();
		});
	}
</script>

<template>
	<div class="SearchSelect">
		<input v-model="searchQuery" type="text">
		<button @click="(() =>{
			searchQuery = '';
			selectedId = p.default;
			if (p.onSelect) p.onSelect();
		})()">X</button>
		<div>
			<button v-for="entry in list" v-show="entry.name.toLowerCase().includes(searchQuery.toLowerCase())" @click="(() =>{
				searchQuery = entry.name;
				selectedId = entry.id;
				if (p.onSelect) p.onSelect();
			})()">{{entry.name}}</button>
		</div>
	</div>
</template>

<style type="text/css">
	.SearchSelect{
		height: fit-content;
		position: relative;
		border: solid 1px;
		border-radius: 5px;
		overflow: hidden;
	}
	.SearchSelect > input{
		height: 20px;
		width: calc(100% - 5px);
		padding: 1.5px;
		border: solid;
		border-width: 0;
	}
	.SearchSelect > button{
		height: 17px;
		padding: 0;
		position: absolute;
		top: 3px;
		right: 3px;
		border: none;
		border-radius: 50px;
		aspect-ratio: 1;
		background-color: darkgrey;
		color: white;
		font-size: 11px;
	}
	.SearchSelect > div{
		width: 100%;
		display: flex;
		flex-direction: column;
	}
	.SearchSelect > div > button{
		width: 100%;
		padding: 1.5px;
		border: solid;
		border-width: 1px 0px 0px;
	}
</style>