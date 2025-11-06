<script setup>
	import { ref } from 'vue';

	import { dbAdmin } from '../globals.js';
</script>

<template>
	<create-menu v-show="dbAdmin.mode == 0"/>
	<manage-menu v-show="dbAdmin.mode == 1"/>
</template>

<style type="text/css">
</style>