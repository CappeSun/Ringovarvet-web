<script setup>
	import { ref, reactive } from 'vue';

	import { dbAdmin } from '../globals.js';
</script>

<template>
	<div>
		<div class="modeSelCont">
			<button v-bind:class="{isModeSelected: dbAdmin.mode == 0}" @click="dbAdmin.mode = 0">Skapa</button>
			<button v-bind:class="{isModeSelected: dbAdmin.mode == 1}" @click="dbAdmin.mode = 1">Hantera</button>
		</div>
		<div class="tabCont">
			<button v-bind:class="{isModeSelected: dbAdmin.tab == 0}" @click="dbAdmin.tab = 0">Produkt (???)</button>
			<button v-bind:class="{isModeSelected: dbAdmin.tab == 1}" @click="dbAdmin.tab = 1">Enhet (meter, hk)</button>
			<button v-bind:class="{isModeSelected: dbAdmin.tab == 2}" @click="dbAdmin.tab = 2">Egenskap (förlik, effekt)</button>
			<button v-bind:class="{isModeSelected: dbAdmin.tab == 3}" @click="dbAdmin.tab = 3">Kategori (segel, motor)</button>
			<button v-bind:class="{isModeSelected: dbAdmin.tab == 4}" @click="dbAdmin.tab = 4">Subkategori (försegel, tändkulemotor)</button>
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