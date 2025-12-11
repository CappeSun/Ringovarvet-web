<script setup>
	import { ref, onMounted } from 'vue';

	import { CreateProductMobile } from '../globals.js';

	// ELEMENTS

	let photoVideoElement;
	let photoVideoTakeBtnElement;

	// PRODUCT

	const count = ref(1);
	const cost = ref(0);
	const condition = ref(5);

	const images = ref([]);

	const selectedSectionId = ref(0);
	const selectedCategoryId = ref(0);
	const selectedSubcategoryId = ref(0);

	function sectionOnSelect(id) {
		selectedSectionId.value = id;

		if (id) {
			dataCategories.value = CreateProductMobile.value.data.categories.filter((e) => e.sectionId == id);
		} else {
			dataCategories.value = [];
			selectedCategoryId.value = 0;

			dataSubcategories.value = [];
			selectedSubcategoryId.value = 0;
		}
	}

	function categoryOnSelect(id) {
		selectedCategoryId.value = id;

		if (id) {
			dataSubcategories.value = CreateProductMobile.value.data.subcategories.filter((e) => e.categoryId == id);
		} else {
			dataSubcategories.value = [];
			selectedSubcategoryId.value = 0;
		}
	}

	function subcategoryOnSelect(id) {
		selectedSubcategoryId.value = id;
		fetchReadProperties();
	}

	let locationGetValue;

	const dataCategories = ref([]);
	const dataSubcategories = ref([]);
	const dataProperties = ref([]);

	onMounted(() =>{
		photoVideoElement = document.getElementById('photoVideoElement');
		photoVideoTakeBtnElement = document.getElementById('photoVideoTakeBtnElement');
	});

	// FETCH

	async function fetchCreateProduct() {
		let res = await fetch('/database/create/product', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'X-CSRF-TOKEN': csrf_token
			},
			body: JSON.stringify({
				subcategoryId: selectedSubcategoryId.value,
				properties: dataProperties.value.map((e) => ({value: e.value, propertyId: e.id})),
				count: count.value,
				cost: cost.value,
				condition: condition.value,
				locationId: locationGetValue()
			})
		});

		if (res.status != 200)
			alert(await res.text());
		else if (images.value.length != 0) {
			let fd = new FormData();
			fd.append('id', (await res.text()).split('[')[1].split(']')[0]);
			for (let i = 0; i < images.value.length; i++) {
				let data = await fetch(images.value[i]);
				fd.append(i, new File([await data.blob()], i, {
					type: 'image/jpeg'
				}));
			}

			res = await fetch('/database/create/productImages', {
				method: 'POST',
				headers: {
					'X-CSRF-TOKEN': csrf_token
				},
				body: fd
			});

			if (res.status == 200)
				alert('Product and images created');
			else
				alert(await res.text());
		}

		count.value = 1;
		cost.value = 0;
		images.value = [];
		dataProperties.value.forEach((e) => e.value = '');
	}

	async function fetchReadCategories() {
		let data = await fetch('/database/read/productMobileCategories',
		{
			method: 'POST',
			headers: {
				'X-CSRF-TOKEN': csrf_token
			}
		});
		CreateProductMobile.value.data = await data.json();
	}

	fetchReadCategories();

	async function fetchReadProperties() {
		let data = await fetch('/database/read/productMobileProperties',
		{
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'X-CSRF-TOKEN': csrf_token
			},
			body: JSON.stringify({
				subcategoryId: selectedSubcategoryId.value
			})
		});
		dataProperties.value = await data.json();
		dataProperties.value.map((e) => e.value = '');
	}

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

			images.value.push(cameraVideoCanvas.toDataURL('image/jpeg', 0.3));
		};
	}

	async function handleCloseCamera() {
		CreateProductMobile.value.mode = 0;

		photoVideoElement.pause();
		photoVideoElement.srcObject = undefined;
		cameraVideoStream.getTracks()[0].stop();
		cameraVideoStream = null;
	}
</script>

<template>
	<div class="CreateProductMobile" v-show="CreateProductMobile.mode == 0">
		<div class="selectSection" v-if="!selectedSectionId">
			<h3>Avdelning</h3>
			<div>
				<p v-for="entry in CreateProductMobile.data.sections" @click="sectionOnSelect(entry.id)">{{entry.name}}</p>
			</div>
		</div>
		<div class="selectedSection" v-else @click="sectionOnSelect(0)">
			<p>{{CreateProductMobile.data.sections.find((e) => e.id == selectedSectionId).name}}</p>
			<button>X</button>
		</div>
		<template v-if="selectedSectionId">
			<div class="selectCategory" v-if="!selectedCategoryId">
				<h3>Kategori</h3>
				<div>
					<p v-for="entry in dataCategories" @click="categoryOnSelect(entry.id)">{{entry.name}}</p>
				</div>
			</div>
			<div class="selectedCategory" v-else @click="categoryOnSelect(0)">
				<p>{{dataCategories.find((e) => e.id == selectedCategoryId).name}}</p>
				<button>X</button>
			</div>
		</template>
		<template v-if="selectedCategoryId">
			<div class="selectSubcategory" v-if="!selectedSubcategoryId">
				<h3>Subkategori</h3>
				<div>
					<p v-for="entry in dataSubcategories" @click="subcategoryOnSelect(entry.id)">{{entry.name}}</p>
				</div>
			</div>
			<div class="selectedSubcategory" v-else @click="subcategoryOnSelect(0)">
				<p>{{dataSubcategories.find((e) => e.id == selectedSubcategoryId).name}}</p>
				<button>X</button>
			</div>
		</template>
		<template v-if="selectedSubcategoryId">
			<h3>Hyllplats</h3>
			<SearchSelect v-bind:list="CreateProductMobile.data.locations" v-bind:getValue="(fun) => locationGetValue = fun" v-bind:default="0"/>
			<h3>Egenskaper</h3>
			<div>
				<div v-for="entry in dataProperties">
					<span>{{entry.name}}: </span><input type="test" v-model="entry.value"> <span>{{entry.unit}}</span>
				</div>
			</div>
			<h3>Övrigt</h3>
			<span>Antal: </span><input type="text" v-model="count"><br>
			<span>Pris: </span><input type="text" v-model="cost"><br>
			<span>Skick: </span><input class="conditionInput" type="range" min="1" max="5" v-model="condition">
			<span v-show="condition == 1"> (Måste repareras)</span>
			<span v-show="condition == 2"> (Väldigt skadat)</span>
			<span v-show="condition == 3"> (Något skadat)</span>
			<span v-show="condition == 4"> (Använt)</span>
			<span v-show="condition == 5"> (Nyskick)</span>
			<h3>Bilder</h3>
			<button @click="handleOpenCamera()">Öppna kamera</button>
			<div class="imagesCont">
				<div v-for="(entry, i) in images">
					<img v-bind:src="entry">
					<button @click="images.splice(i, 1)">Ta bort</button>
				</div>
				<p v-if="images.length == 0">Inga bilder</p>
			</div>
			<button @click="(() =>{
				fetchCreateProduct();
			})()">Skapa</button>
		</template>
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
	.CreateProductMobile > :is(.selectSection, .selectCategory, .selectSubcategory){
		height: 100vh;
		display: flex;
		flex-direction: column;
	}
	.CreateProductMobile > :is(.selectSection, .selectCategory, .selectSubcategory) > h3{
		margin: 0;
		padding: 15px;
		flex: 0 0;
		background-color: rgb(190, 190, 190);
	}
	.CreateProductMobile > :is(.selectSection, .selectCategory, .selectSubcategory) > div{
		flex: 1 1;
		overflow: scroll;
	}
	.CreateProductMobile > :is(.selectSection, .selectCategory, .selectSubcategory) > div > p{
		margin: 0;
		padding: 15px;
		border-bottom: solid 2px rgb(190, 190, 190);
	}
	.CreateProductMobile > :is(.selectedSection, .selectedCategory, .selectedSubcategory){
		margin: 0;
		display: flex;
		background-color: rgb(240, 240, 240);
		border-bottom: solid 2px rgb(150, 150, 150);
	}
	.CreateProductMobile > :is(.selectedSection, .selectedCategory, .selectedSubcategory) > p{
		margin: 0;
		padding: 15px;
		flex: 1 1;
		line-height: 20px;
	}
	.CreateProductMobile > :is(.selectedSection, .selectedCategory, .selectedSubcategory) > button{
		flex: 0 0 50px;
		background-color: rgb(220, 220, 220);
		color: rgb(255, 80, 80);
		font-size: 20px;
		border-radius: 0;
		border: none;
		border-left: solid 2px rgb(150, 150, 150);
	}
	body{
		margin: 0;
	}
	.conditionInput{
		vertical-align: middle;
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