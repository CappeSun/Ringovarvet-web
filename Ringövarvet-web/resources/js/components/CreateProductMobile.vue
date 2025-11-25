<script setup>
	import { ref, onMounted } from 'vue';

	import { CreateProductMobile } from '../globals.js';

	// ELEMENTS

	let photoVideoElement;
	let photoVideoTakeBtnElement;

	// PRODUCT

	const count = ref(1);
	const cost = ref(0);

	const images = ref([]);

	async function productGetFormData() {
		let fd = new FormData();
		for (let i = 0; i < images.length; i++) {
			let data = await fetch(images[i]);
			data = data.blob();
			fd.append(new File([data], i + '.png', {
				type: 'image/png'
			}));
		}

		return fd;
	}

	const productSelectedSectionId = ref(null);
	const productSelectedCategoryId = ref(null);
	const selectedSubcategoryId = ref(null);

	let sectionGetValue = null;

	let categoryGetValue = null;
	let categoryResetValue = null;

	let subcategoryGetValue = null;
	let subcategoryResetValue = null;

	const sectionOnSelect = () =>{
		dataSubcategories.value = [];

		productSelectedSectionId.value = sectionGetValue();
		dataCategories.value = CreateProductMobile.value.data.categories.filter((e) => e.sectionId == productSelectedSectionId.value);

		categoryResetValue();

		console.log(sectionGetValue(), categoryGetValue(), subcategoryGetValue())
	}
	const categoryOnSelect = () =>{
		productSelectedCategoryId.value = categoryGetValue();
		dataSubcategories.value = CreateProductMobile.value.data.subcategories.filter((e) => e.categoryId == productSelectedCategoryId.value);

		subcategoryResetValue();

		console.log(sectionGetValue(), categoryGetValue(), subcategoryGetValue())
	}
	const subcategoryOnSelect = () =>{
		selectedSubcategoryId.value = subcategoryGetValue();
		dataProperties.value = CreateProductMobile.value.data.subcategoryProperties
			.filter((e) => e.subcategoryId == selectedSubcategoryId.value)
			.map((e) => ({
				value: '',
				property: CreateProductMobile.value.data.properties.find((ee) => ee.id == e.propertyId)
			}));

		console.log(sectionGetValue(), categoryGetValue(), subcategoryGetValue())

		console.log(dataProperties.value)
	}

	const dataCategories = ref([]);
	const dataSubcategories = ref([]);
	const dataProperties = ref([]);

	onMounted(() =>{
		photoVideoElement = document.getElementById('photoVideoElement');
		photoVideoTakeBtnElement = document.getElementById('photoVideoTakeBtnElement');
	});

	// FETCH

	async function fetchCreateProduct(body) {
		let res = await fetch('/admin/create/product', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'X-CSRF-TOKEN': csrf_token
			},
			body: JSON.stringify(body)
		});

		if (res.status != 200) {
			alert(await res.text());
		}
	}

	async function fetchReadProduct() {
		let data = await fetch('/admin/read/productMobile',
		{
			method: 'POST',
			headers: {
				'X-CSRF-TOKEN': csrf_token
			}
		});
		CreateProductMobile.value.data = await data.json();
	}

	fetchReadProduct();

	// OTHER

	let cameraVideoStream = null;

	async function handleOpenCamera() {
		CreateProductMobile.value.mode = 1;

		cameraVideoStream = await navigator.mediaDevices.getUserMedia({
			video: {
				facingMode: {ideal: 'environment'},
				aspectRatio: {exact: 1},
				height: {ideal: 1920},
				width: {ideal: 1920}
			},
			audio: false
		});

		const streamHeight = cameraVideoStream.getTracks()[0].getSettings().height;
		const streamWidth = cameraVideoStream.getTracks()[0].getSettings().width;

		console.log(streamHeight, streamWidth)

		photoVideoElement.srcObject = cameraVideoStream;
		photoVideoElement.play();

		photoVideoTakeBtnElement.onclick = () =>{
			let cameraVideoCanvas = document.createElement('canvas');

			if (screen.orientation.type.includes('landscape')) {
				cameraVideoCanvas.height = 1080;
				cameraVideoCanvas.width = 1920;

				cameraVideoCanvas.getContext('2d').drawImage(photoVideoElement, 
					0, streamHeight * (3.5/16), streamWidth, streamHeight * (9/16), 
					0, 0, 1920, 1080);
			} else {
				cameraVideoCanvas.height = 1920;
				cameraVideoCanvas.width = 1080;
				
				cameraVideoCanvas.getContext('2d').drawImage(photoVideoElement, 
					streamWidth * (3.5/16), 0, streamWidth * (9/16), streamHeight, 
					0, 0, 1080, 1920);
			}

			images.value.push(cameraVideoCanvas.toDataURL('image/png'));
		};
	}

	async function handleCloseCamera() {
		CreateProductMobile.value.mode = 0;

		photoVideoElement.pause();
		delete photoVideoElement.srcObject;
		cameraVideoStream.getTracks()[0].stop();
		cameraVideoStream = null;
	}

	//screen.orientation.type.includes('landscape')
</script>

<template>
	<div class="createCont" v-show="CreateProductMobile.mode == 0">
		<h3>Avdelning</h3>
		<SearchSelect v-bind:list="CreateProductMobile.data.sections" v-bind:getValue="(fun) => sectionGetValue = fun" v-bind:onSelect="sectionOnSelect" v-bind:default="0"/>
		<h3>Kategori</h3>
		<SearchSelect v-bind:list="dataCategories" v-bind:getValue="(fun) => categoryGetValue = fun" v-bind:resetValue="(fun) => categoryResetValue = fun" v-bind:onSelect="categoryOnSelect" v-bind:default="0"/>
		<h3>Subkategori</h3>
		<SearchSelect v-bind:list="dataSubcategories" v-bind:getValue="(fun) => subcategoryGetValue = fun" v-bind:resetValue="(fun) => subcategoryResetValue = fun" v-bind:onSelect="subcategoryOnSelect" v-bind:default="0"/>
		<h3>Egenskaper</h3>
		<div>
			<div v-for="entry in dataProperties">
				<span>{{entry.property.name}}: </span><input type="test" v-model="entry.value"><span>{{CreateProductMobile.data.units.find((e) => e.id == entry.property.unitId).name}}</span>
			</div>
		</div>
		<h3>Övrigt</h3>
		<span>Antal: </span><input type="text" v-model="count"><br>
		<span>Pris: </span><input type="text" v-model="cost"><br>
		<h3>Bilder</h3>
		<button @click="handleOpenCamera()">Öppna kamera</button>
		<div class="imagesCont">
			<div v-for="(entry, i) in images">
				<img v-bind:src="entry">
				<button @click="images.splice(i, 1)">Ta bort</button>
			</div>
			<p v-if="images.length == 0">Inga bilder</p>
		</div>
		<button @click="(async () =>{
			let fd = await productGetFormData();
			fetchCreateProduct({
				subcategoryId: selectedSubcategoryId,
				count: count,
				cost: cost,
				properties: dataProperties.map((e) => ({value: e.value, propertyId: e.property.id})),
				images: fd
			});
			count = 1;
			cost = 0;
			productDataProperties.forEach((e) => e.value = '');
		})()">Skapa</button>
	</div>
	<div class="photoCont" v-show="CreateProductMobile.mode == 1">
		<div class="videoCont">
			<div>
				<video id="photoVideoElement" playsinline></video>
			</div>
		</div>
		<div class="photoContBtnCont">
			<button class="photoTakeBtn" id="photoVideoTakeBtnElement">Ta bild</button>
			<button class="photoExitBtn" @click="handleCloseCamera()">Lämna</button>
		</div>
	</div>
</template>

<style type="text/css">
	/*font: "Roboto Condensed", sans-serif;*/
	/*font: "Roboto Slab", serif; */
	body{
		margin: 0;
	}
	.imagesCont{
		width: 90%;
	}
	.imagesCont > div{
		width: 40%;
		margin: 10px;
		display: inline-block;
	}
	.imagesCont > div > img{
		width: 100%;
		display: block;
	}
	.imagesCont > div > button{
		width: 100%;
	}
	.photoCont{
		height: 100vh;
		width: 100vw;
		display: flex;
		flex-direction: column;
	}
	.videoCont{
		min-width: 0;
		min-height: 0;
		display: flex;
		justify-content: center;
		align-items: center;
		flex: 1;
	}
	.videoCont > div{
		max-height: 100%;
		max-width: 100%;
		aspect-ratio: 9 / 16;
		overflow: hidden;
	}
	.videoCont > div > video{
		height: 100%;
		width: 100%;
		aspect-ratio: 1;
		object-fit: cover;
	}
	.photoContBtnCont{
		display: flex;
		flex-direction: row;
		flex: 0 0 80px;
	}
	.photoContBtnCont > button{
	}
	.photoTakeBtn{
		flex: 1;
	}
	.photoExitBtn{
		flex: 0 0 80px;
	}

	@media (orientation: landscape){
		.photoCont{
			flex-direction: row;
		}
		.photoContBtnCont{
			flex-direction: column;
		}
		.videoCont > div{
			aspect-ratio: 16 / 9;
		}
	}
</style>