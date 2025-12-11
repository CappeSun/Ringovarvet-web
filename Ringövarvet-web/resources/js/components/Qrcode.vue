<script setup async>
	import { ref, onMounted } from 'vue';
	import QRCode from 'qrcode';

	import { dbAdmin } from '../globals.js';

	const p = defineProps(['id', 'cost']);

	const qrCode = await QRCode.toDataURL('HTTPS://LAGER.RINGOVARVET.SE/PRODUCT/' + p.id, {errorCorrectionLevel: 'M', version: 3});
</script>

<template>
	<div class="QRCont">
		<img :src="qrCode"></img>
		<div>
			<h2>ID: {{p.id}}</h2>
			<h2>{{p.cost}} kr</h2>
		</div>
	</div>
</template>

<style type="text/css">
	.QRCont{
		height: 2.5cm;
		padding-left: 1cm;
		display: inline-flex;
		flex-direction: row;
		font-size: 0.7cm;
		line-height: 1.25cm;
	}
	.QRCont > img{
		height: 100%;
		margin: 0;
	}
	.QRCont > div{
		height: 100%;
	}
	.QRCont > div > h2{
		margin: 0;
		font-size: 100%;
	}
</style>