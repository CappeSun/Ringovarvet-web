<script setup>
	import { ref, onMounted } from 'vue';

	const p = defineProps(['list', 'getValue', 'resetValue', 'onSelect', 'default']);

	const searchQuery = ref(p.default ? p.list.find((e) => e.id == p.default).name : '');
	let selectedId = p.default;

	const isFocused = ref(false);

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
	<div class="SearchSelect" v-bind:class="{SearchSelectFocus: isFocused}">
		<input id="search" v-model="searchQuery" type="text" @focus="isFocused = true" @blur="isFocused = false" autocomplete="off">
		<button @mousedown="(() =>{
			searchQuery = '';
			selectedId = p.default;
			if (p.onSelect) p.onSelect();
		})()">X</button>
		<div v-show="isFocused">
			<button v-for="entry in list" v-show="entry.name.toLowerCase().includes(searchQuery.toLowerCase())" @mousedown="(() =>{
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
	}
	.SearchSelectFocus{
		border-radius: 5px 5px 0 0;
	}
	.SearchSelect > input{
		height: 20px;
		width: calc(100% - 5px);
		padding: 1.5px;
		border: none;
		border-radius: 4px;
	}
	.SearchSelect > input:focus-visible{
		outline: none;
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
		max-height: 290px;
		width: 100%;
		display: none;
		flex-direction: column;
		overflow: scroll;
		position: absolute;
		top: 100%;
		left: -1px;
		background-color: white;
		border: solid;
		border-width: 1px;
		border-radius: 0 0 5px 5px;
		z-index: 1;
	}
	.SearchSelectFocus > div{
		display: flex;
	}
	.SearchSelect > div > button{
		width: 100%;
		padding: 1.5px;
		border: solid;
		border-width: 0 0 1px;
	}
	.SearchSelect > div > :last-child{
		border: none;
	}
</style>