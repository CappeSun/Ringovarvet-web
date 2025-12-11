<script setup>
	import { ref } from 'vue';

	import { dbAdmin } from '../globals.js';
</script>

<template>
	<create-menu v-show="dbAdmin.mode == 0"/>
	<manage-menu v-show="dbAdmin.mode == 1"/>
</template>

<style type="text/css">
	*{
		font-family: "Roboto Slab", serif;
	}
	h1, h2, h3, h4, h5, h6, button, input{
		font-family: "Roboto Condensed", sans-serif;
	}
</style>