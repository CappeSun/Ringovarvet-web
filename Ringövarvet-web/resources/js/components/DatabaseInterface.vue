<script setup>
	import { ref, reactive } from 'vue';

	//const loggedOut = laraId ? false : true;

	let results = ref([{
		name: 'Yamato',
		category: 'boat',
		subcategory: 'real big one',
		properties: [
			'length: alot',
			'gun: yes rico'
		]
	},{
		name: 'Yamato',
		category: 'boat',
		subcategory: 'real big one',
		properties: [
			'length: alot',
			'gun: yes rico'
		]
	},{
		name: 'Yamato',
		category: 'boat',
		subcategory: 'real big one',
		properties: [
			'length: alot',
			'gun: yes rico'
		]
	},{
		name: 'Yamato',
		category: 'boat',
		subcategory: 'real big one',
		properties: [
			'length: alot',
			'gun: yes rico'
		]
	},{
		name: 'Yamato',
		category: 'boat',
		subcategory: 'real big one',
		properties: [
			'length: alot',
			'gun: yes rico'
		]
	},{
		name: 'Yamato',
		category: 'boat',
		subcategory: 'real big one',
		properties: [
			'length: alot',
			'gun: yes rico'
		]
	},{
		name: 'Yamato',
		category: 'boat',
		subcategory: 'real big one',
		properties: [
			'length: alot',
			'gun: yes rico'
		]
	}]);
</script>

<template>
	<div class="resultsCont">
		<div v-for="entry in results" :key="entry.subcategory + entry.name">
			<h2>{{entry.category}}</h2>
			<h3>{{entry.subcategory}}</h3>
			<h2>{{entry.name}}</h2>
			<div>
				<h4 v-for="property in entry.properties">{{property}}</h4>
			</div>
		</div>
	</div>
</template>

<style type="text/css">
	.resultsCont{
		height: 100%;
		width: 100%;
		margin: 0;
		justify-content: space-between;
	}

	.resultsCont > div{
		width: calc((100% - 20px) / 2);
		margin: 5px;
		display: inline-block;
	}
</style>