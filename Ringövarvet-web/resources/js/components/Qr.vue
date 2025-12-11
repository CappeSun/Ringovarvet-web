<script setup>
	import { ref } from 'vue';

	import { dbAdmin } from '../globals.js';
</script>

<template>
	<Suspense>
		<Qrcode v-if="dbAdmin.data.products" v-for="entry in dbAdmin.QRProductsSelected" :id="entry" :cost="dbAdmin.data.products.find((e) => e.id == entry).cost"/>
	</Suspense>
</template>

<style type="text/css">
</style>