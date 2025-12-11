<script setup>
	import { ref, onMounted } from 'vue';

	import { dbAdmin } from '../globals.js';

	// ELEMENTS

	let productImagesInput;
	onMounted(async () =>{
		productImagesInput = document.getElementById('productImagesInput');
	});

	// PRODUCT

	const productCount = ref(1);
	const productCost = ref(0);
	const productCondition = ref(5);

	const productSelectedSectionId = ref(null);
	const productSelectedCategoryId = ref(null);
	const productSelectedSubcategoryId = ref(null);

	let productSectionGetValue = null;

	let productCategoryGetValue = null;
	let productCategoryResetValue = null;

	let productSubcategoryGetValue = null;
	let productSubcategoryResetValue = null;

	let productLocationGetValue = null;

	const productSectionOnSelect = () =>{
		productFilteredSubcategories.value = [];

		productSelectedSectionId.value = productSectionGetValue();
		productFilteredCategories.value = dbAdmin.value.data.categories.filter((e) => e.sectionId == productSelectedSectionId.value);

		productCategoryResetValue();
	}
	const productCategoryOnSelect = () =>{
		productSelectedCategoryId.value = productCategoryGetValue();
		productFilteredSubcategories.value = dbAdmin.value.data.subcategories.filter((e) => e.categoryId == productSelectedCategoryId.value);

		productSubcategoryResetValue();
	}
	const productSubcategoryOnSelect = () =>{
		productSelectedSubcategoryId.value = productSubcategoryGetValue();
		productFilteredProperties.value = dbAdmin.value.data.subcategoryProperties
			.filter((e) => e.subcategoryId == productSelectedSubcategoryId.value)
			.map((e) => ({
				value: '',
				property: dbAdmin.value.data.properties.find((ee) => ee.id == e.propertyId)
			}));
	}

	const productFilteredCategories = ref([]);
	const productFilteredSubcategories = ref([]);
	const productFilteredProperties = ref([]);

	// UNIT

	const unitName = ref('');

	// PROPERTY

	const propertyName = ref('');
	let propertyUnitGetValue = null;
	let propertyUnitResetValue = null;

	// SECTION

	const sectionName = ref('');

	// CATEGORY

	const categoryName = ref('');
	let categorySectionGetValue = null;

	// SUBCATEGORY

	const subcategoryDataCategories = ref([]);
	const subcategoryDataProperties = ref([]);

	const subcategoryName = ref('');

	const subcategoryCategoryId = ref(0);
	let subcategoryCategoryResetValue = null;

	let subcategoryPropertyKeyCnt = 0;
	const subcategoryProperties = ref([{
		getValue: null,
		key: subcategoryPropertyKeyCnt
	}]);

	// LOCATION

	const locationName = ref('');

	// FETCH

	async function fetchCreate(kind, body, imagesInput=[]) {
		const images = structuredClone(imagesInput);
		let res = await fetch('/database/create/' + kind, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'X-CSRF-TOKEN': csrf_token
			},
			body: JSON.stringify(body)
		});
		let resText = await res.text();

		dbAdmin.value.sideSelectResponses.push({
			res: {
				text: resText,
				code: res.status
			},
			key: ++dbAdmin.value.sideSelectResponseKey
		});

		if (res.status == 200 && images.length != 0) {
			let fd = new FormData();
			fd.append('id', resText.split('[')[1].split(']')[0]);

			let i = 0;
			function addImage() {
				const image = new Image();
				image.onload = async () =>{
					const canvas = document.createElement('canvas');

					canvas.height = image.naturalHeight;
					canvas.width = image.naturalWidth;
					canvas.getContext('2d').drawImage(image, 0, 0);

					let data = await fetch(canvas.toDataURL('image/jpeg', 0.3));

					fd.append(i, new File([await data.blob()], i, {
						type: 'image/jpeg'
					}));

					i++;

					if (images[i])
						addImage();
					else {
						let res = await fetch('/database/create/productImages', {
							method: 'POST',
							headers: {
								'X-CSRF-TOKEN': csrf_token
							},
							body: fd
						});
						dbAdmin.value.sideSelectResponses.push({
							res: {
								text: await res.text(),
								code: res.status
							},
							key: ++dbAdmin.value.sideSelectResponseKey
						});
					}
				}
				image.src = URL.createObjectURL(images[i]);
			}
			addImage();
		}
	}
</script>

<template>
	<div class="createCont">
		<div v-show="dbAdmin.tab == 0">
			<h1>Skapa ny artikel</h1>
			<h3>Avdelning</h3>
			<SearchSelect v-bind:list="dbAdmin.data.sections" v-bind:getValue="(fun) => productSectionGetValue = fun" v-bind:onSelect="productSectionOnSelect" v-bind:default="0"/>
			<h3>Kategori</h3>
			<SearchSelect v-bind:list="productFilteredCategories" v-bind:getValue="(fun) => productCategoryGetValue = fun" v-bind:resetValue="(fun) => productCategoryResetValue = fun" v-bind:onSelect="productCategoryOnSelect" v-bind:default="0"/>
			<h3>Subkategori</h3>
			<SearchSelect v-bind:list="productFilteredSubcategories" v-bind:getValue="(fun) => productSubcategoryGetValue = fun" v-bind:resetValue="(fun) => productSubcategoryResetValue = fun" v-bind:onSelect="productSubcategoryOnSelect" v-bind:default="0"/>
			<h3>Hyllplats</h3>
			<SearchSelect v-bind:list="dbAdmin.data.locations" v-bind:getValue="(fun) => productLocationGetValue = fun" v-bind:default="0"/>
			<h3>Egenskaper</h3>
			<div>
				<div v-if="dbAdmin.data.units" v-for="entry in productFilteredProperties">
					<span>{{entry.property.name}}: </span><input type="test" v-model="entry.value"> <span>{{dbAdmin.data.units.find((e) => e.id == entry.property.unitId).name}}</span>
				</div>
			</div>
			<h3>Övrigt</h3>
			<span>Antal: </span><input type="text" v-model="productCount"><br>
			<span>Pris: </span><input type="text" v-model="productCost"><span> kr</span><br>
			<span>Skick: </span><input class="productConditionInput" type="range" min="1" max="5" v-model="productCondition">
			<span v-show="productCondition == 1"> (Måste repareras)</span>
			<span v-show="productCondition == 2"> (Väldigt skadat)</span>
			<span v-show="productCondition == 3"> (Något skadat)</span>
			<span v-show="productCondition == 4"> (Använt)</span>
			<span v-show="productCondition == 5"> (Nyskick)</span>
			<h3>Bilder (.webp / .png / .jpg)</h3>
			<input id="productImagesInput" type="file" accept="image/webp, image/png, image/jpeg" multiple>
			<br>
			<br>
			<button @click="(async () =>{
				fetchCreate('product', {
					subcategoryId: productSelectedSubcategoryId,
					locationId: productLocationGetValue(),
					count: productCount,
					cost: productCost,
					condition: productCondition,
					properties: productFilteredProperties.map((e) => ({value: e.value, propertyId: e.property.id}))
				}, productImagesInput.files);
				productCount = 1;
				productCost = 0;
				productImagesInput.value = null;
				productFilteredProperties.forEach((e) => e.value = '');
			})()">Skapa artikel</button>
		</div>
		<div v-show="dbAdmin.tab == 1">
			<h1>Skapa ny enhet</h1>
			<span>Namn: </span><input type="text" v-model="unitName">
			<br>
			<br>
			<button @click="(() =>{
				fetchCreate('unit', {name: unitName});
				unitName = '';
			})()">Skapa enhet</button>
		</div>
		<div v-show="dbAdmin.tab == 2">
			<h1>Skapa ny egenskap</h1>
			<span>Namn: </span><input type="text" v-model="propertyName">
			<h3>Enhet</h3>
			<SearchSelect v-bind:list="dbAdmin.data.units" v-bind:getValue="(fun) => propertyGetValue = fun" v-bind:resetValue="(fun) => propertyUnitResetValue = fun"/>
			<br>
			<button @click="(() =>{
				fetchCreate('property', {name: propertyName, unitId: propertyGetValue()});
				propertyName = '';
				propertyUnitResetValue();
			})()">Skapa egenskap</button>
		</div>
		<div v-show="dbAdmin.tab == 3">
			<h1>Skapa ny avdelning</h1>
			<span>Namn: </span><input type="text" v-model="sectionName">
			<br>
			<br>
			<button @click="(() =>{
				fetchCreate('section', {name: sectionName});
				sectionName = '';
			})()">Skapa avdelning</button>
		</div>
		<div v-show="dbAdmin.tab == 4">
			<h1>Skapa ny kategori</h1>
			<span>Namn: </span><input type="text" v-model="categoryName">
			<h3>Avdelning</h3>
			<SearchSelect v-bind:list="dbAdmin.data.sections" v-bind:getValue="(fun) => categorySectionGetValue = fun" v-bind:resetValue="(fun) => categorySectionResetValue = fun" v-bind:default="0"/>
			<br>
			<button @click="(() =>{
				fetchCreate('category', {name: categoryName, sectionId: categorySectionGetValue()});
				categoryName = '';
			})()">Skapa kategori</button>
		</div>
		<div v-show="dbAdmin.tab == 5">
			<h1>Skapa ny subkategori</h1>
			<span>Namn: </span><input type="text" v-model="subcategoryName">
			<h3>Kategori</h3>
			<SearchSelect v-bind:list="dbAdmin.data.categories" v-bind:getValue="(fun) => subcategoryCategoryGetValue = fun" v-bind:resetValue="(fun) => subcategoryCategoryResetValue = fun" v-bind:default="0"/>
			<h3>Egenskaper</h3>
			<div class="subcategoryProperty" v-for="(entry, index) in subcategoryProperties" :key="entry.key">
				<SearchSelect v-if="dbAdmin.data.properties" v-bind:list="(() => dbAdmin.data.properties.map((e) =>({
					name: e.name + ' (' + dbAdmin.data.units.find((ee) => ee.id == e.unitId).name + ')',
					id: e.id
				})))()" v-bind:getValue="(fun) => subcategoryProperties[index].getValue = fun"/>
				<button @click="(() =>{
					subcategoryProperties.splice(index, 1);
				})()">Ta bort egenskap</button>
			</div>
			<button @click="(() =>{
				subcategoryProperties.push({getValue: null, key: ++subcategoryPropertyKeyCnt});
			})()">Lägg till egenskap</button>
			<br>
			<br>
			<button @click="(() =>{
				fetchCreate('subcategory', {
					name: subcategoryName, categoryId: subcategoryCategoryGetValue(), propertyIds: subcategoryProperties.map((e, i) => e = subcategoryProperties[i].getValue())
				});

				subcategoryName = '';
				subcategoryProperties = [{
					getValue: null,
					key: ++subcategoryPropertyKeyCnt
				}];
			})()">Skapa subkategori</button>
		</div>
		<div v-show="dbAdmin.tab == 6">
			<h1>Skapa ny hyllplats</h1>
			<span>Namn: </span><input type="text" v-model="locationName">
			<button @click="(() =>{
				fetchCreate('location', {name: locationName});
				locationName = '';
			})()">Skapa</button>
		</div>
	</div>
</template>

<style type="text/css">
	.productConditionInput{
		vertical-align: middle;
	}
	.subcategoryProperty{
		margin: 0 0 15px;
		display: flex;
	}
	.subcategoryProperty > .SearchSelect{
		margin: 0 5px 0 0;
		flex: 1;
	}
	.subcategoryProperty > button{
		flex: 0 0 auto;
	}
</style>