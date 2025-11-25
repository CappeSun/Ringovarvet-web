<script setup>
	import { ref } from 'vue';

	const p = defineProps(['res', 'remove']);

	if (p.res.code == 200) {
		setTimeout(() =>{
			p.remove();
		}, 3000);
	}
</script>

<template>
	<div class="cont" v-bind:class="{statusOk: p.res.code == 200, statusBad: p.res.code != 200}">
		<p>{{p.res.text}}</p>
		<button v-if="p.res.code != 200" @click="p.remove()">Avfärda</button>
	</div>
</template>

<style type="text/css">
	.cont{
		width: 100%;
	}
	.statusOk{
		background-color: rgb(180, 255, 180);
	}
	.statusBad{
		background-color: rgb(255, 180, 180);
	}
</style>