<script setup>
	import { ref, reactive } from 'vue';

	import { dbAdmin } from '../globals.js';
</script>

<template>
	<div class="createCont">
		<div v-show="dbAdmin.tab == 0">
			<h1>Hantera produkter</h1>
		</div>
		<div v-show="dbAdmin.tab == 1">
			<h1>Hantera enheter</h1>
		</div>
		<div v-show="dbAdmin.tab == 2">
			<h1>Hantera egenskaper</h1>
		</div>
		<div v-show="dbAdmin.tab == 3">
			<h1>Hantera kategorier</h1>
		</div>
		<div v-show="dbAdmin.tab == 4">
			<h1>Hantera subkategorier</h1>
		</div>
	</div>
</template>

<style type="text/css">
</style>