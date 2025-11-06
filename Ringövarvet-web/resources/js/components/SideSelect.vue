<script setup>
	import { ref } from 'vue';

	import { dbAdmin } from '../globals.js';

	async function fetchRead(kind) {
		let data = await fetch('/api/admin/read/' + kind,
		{
			method: 'POST',
			headers: {
				'X-CSRF-TOKEN': csrf_token
			}
		});
		dbAdmin.value.data = await data.json();
	};
</script>

<template>
	<div>
		<div class="modeSelCont">
			<button v-bind:class="{isModeSelected: dbAdmin.mode == 0}" @click="dbAdmin.mode = 0">Skapa</button>
			<button v-bind:class="{isModeSelected: dbAdmin.mode == 1}" @click="(async () => {
				dbAdmin.mode = 1;
				fetchRead(['product', 'unit', 'property', 'category', 'subcategory'][dbAdmin.tab]);
			})">Hantera</button>
		</div>
		<div class="tabCont">
			<button v-bind:class="{isModeSelected: dbAdmin.tab == 0}" @click="(async () => {
				dbAdmin.tab = 0;
				fetchRead('product');
			})">Produkt (???)</button>
			<button v-bind:class="{isModeSelected: dbAdmin.tab == 1}" @click="(async () => {
				dbAdmin.tab = 1;
				fetchRead('unit');
			})">Enhet (meter, hk)</button>
			<button v-bind:class="{isModeSelected: dbAdmin.tab == 2}" @click="(async () => {
				dbAdmin.tab = 2;
				fetchRead('property');
			})">Egenskap (förlik, effekt)</button>
			<button v-bind:class="{isModeSelected: dbAdmin.tab == 3}" @click="(async () => {
				dbAdmin.tab = 3;
				fetchRead('category');
			})">Kategori (segel, motor)</button>
			<button v-bind:class="{isModeSelected: dbAdmin.tab == 4}" @click="(async () => {
				dbAdmin.tab = 4;
				fetchRead('subcategory');
			})">Subkategori (försegel, tändkulemotor)</button>
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
		font-size: 15px;
		border: outset;
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
		display: block;
		font-size: 15px;
		border: outset 3px;
		border-radius: 5px;
	}
</style>