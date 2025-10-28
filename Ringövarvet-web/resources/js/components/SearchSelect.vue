<script setup>
	import { ref, reactive } from 'vue';

	import { dbAdmin } from '../globals.js';
	const p = defineProps(['list', 'setValue']);

	const searchQuery = ref('');
</script>

<template>
	<div class="SearchSelect">
		<input v-model="searchQuery" type="text">
		<button @click="searchQuery = ''">X</button>
		<div>
			<button v-for="entry in list" v-show="entry.name.toLowerCase().includes(searchQuery.toLowerCase())" @click="(() => {
				searchQuery = entry.name;
				p.setValue(entry.id);
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