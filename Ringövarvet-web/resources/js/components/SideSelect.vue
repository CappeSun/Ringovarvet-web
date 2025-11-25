<script setup>
	import { ref } from 'vue';

	import { dbAdmin } from '../globals.js';

	async function fetchRead(kind) {
		let data = await fetch('/admin/read/' + kind,
		{
			method: 'POST',
			headers: {
				'X-CSRF-TOKEN': csrf_token
			}
		});
		dbAdmin.value.data = await data.json();
	}

	fetchRead('product');
</script>

<template>
	<div>
		<div class="modeSelCont">
			<button v-bind:class="{isModeSelected: dbAdmin.mode == 0}" @click="dbAdmin.mode = 0">Skapa</button>
			<button v-bind:class="{isModeSelected: dbAdmin.mode == 1}" @click="(async () => {
				dbAdmin.mode = 1;
				fetchRead(['product', 'unit', 'property', 'section', 'category', 'subcategory', 'location'][dbAdmin.tab]);
			})">Hantera</button>
		</div>
		<div class="tabCont">
			<button v-bind:class="{isModeSelected: dbAdmin.tab == 0}" @click="(async () => {
				dbAdmin.data = {};
				dbAdmin.tab = 0;
				await fetchRead('product');
				// Loading complete
			})">Produkt</button>
			<button v-bind:class="{isModeSelected: dbAdmin.tab == 1}" @click="(async () => {
				dbAdmin.data = {};
				dbAdmin.tab = 1;
				await fetchRead('unit');
			})">Enhet</button>
			<button v-bind:class="{isModeSelected: dbAdmin.tab == 2}" @click="(async () => {
				dbAdmin.data = {};
				dbAdmin.tab = 2;
				await fetchRead('property');
			})">Egenskap</button>
			<button v-bind:class="{isModeSelected: dbAdmin.tab == 3}" @click="(async () => {
				dbAdmin.data = {};
				dbAdmin.tab = 3;
				await fetchRead('section');
			})">Avdelning</button>
			<button v-bind:class="{isModeSelected: dbAdmin.tab == 4}" @click="(async () => {
				dbAdmin.data = {};
				dbAdmin.tab = 4;
				await fetchRead('category');
			})">Kategori</button>
			<button v-bind:class="{isModeSelected: dbAdmin.tab == 5}" @click="(async () => {
				dbAdmin.data = {};
				dbAdmin.tab = 5;
				await fetchRead('subcategory');
			})">Subkategori</button>
			<button v-bind:class="{isModeSelected: dbAdmin.tab == 6}" @click="(async () => {
				dbAdmin.data = {};
				dbAdmin.tab = 6;
				await fetchRead('location');
			})">Hyllplats</button>
		</div>
		<div class="responseCont">
			<SideSelectResponse v-for="(entry, index) in dbAdmin.sideSelectResponses" v-bind:res="entry.res" v-bind:remove="() => dbAdmin.sideSelectResponses.splice(index, 1)" :key="entry.key"/>
		</div>
	</div>
</template>

<style type="text/css">
	.modeSelCont{
		height: fit-content;
		width: 100%;
		margin: 20px 0 30px;
	}
	.modeSelCont > button{
		width: 50%;
		padding: 3px;
		border: outset;
		font-size: 15px;
	}
	.modeSelCont > :first-child{
		border-radius: 5px 0 0 5px;
		border-width: 3px 1px 3px 3px;
	}
	.modeSelCont > :last-child{
		border-radius: 0 5px 5px 0;
		border-width: 3px 3px 3px 1px;
	}
	.isModeSelected{
		background-color: darkgray;
		border-style: inset !important;
	}
	.tabCont{
		height: 100%;
		width: 100%;
	}
	.tabCont > button{
		width: 100%;
		margin: 5px 0;
		padding: 3px;
		border: outset 3px;
		border-radius: 5px;
		display: block;
		font-size: 15px;
	}
</style>