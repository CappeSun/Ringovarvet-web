<script setup>
	import { ref, onMounted } from 'vue';

	import { dbAdmin } from '../globals.js';

	// PRODUCT

	const productName = ref('');
	const productProperties = ref([]);
	const productCount = ref(1);
	const productCost = ref(0);

	function productGetFormData() {
		let fd = new FormData();
		for (let i = 0; i < productImagesInput.files.length; i++)
			fd.append(i, productImagesInput.files[i]);
		console.log(fd)
		return fd;
	}

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
		productDataSubcategories.value = [];

		productSelectedSectionId.value = productSectionGetValue();
		productDataCategories.value = dbAdmin.value.data.categories.filter((e) => e.sectionId == productSelectedSectionId.value);

		productCategoryResetValue();

		console.log(productSectionGetValue(), productCategoryGetValue(), productSubcategoryGetValue())
	}
	const productCategoryOnSelect = () =>{
		productSelectedCategoryId.value = productCategoryGetValue();
		productDataSubcategories.value = dbAdmin.value.data.subcategories.filter((e) => e.categoryId == productSelectedCategoryId.value);

		productSubcategoryResetValue();

		console.log(productSectionGetValue(), productCategoryGetValue(), productSubcategoryGetValue())
	}
	const productSubcategoryOnSelect = () =>{
		productSelectedSubcategoryId.value = productSubcategoryGetValue();
		productDataProperties.value = dbAdmin.value.data.subcategoryProperties
			.filter((e) => e.subcategoryId == productSelectedSubcategoryId.value)
			.map((e) => ({
				value: '',
				property: dbAdmin.value.data.properties.find((ee) => ee.id == e.propertyId)
			}));

		console.log(productSectionGetValue(), productCategoryGetValue(), productSubcategoryGetValue())

		console.log(productDataProperties.value)
	}

	const productDataCategories = ref([]);
	const productDataSubcategories = ref([]);
	const productDataProperties = ref([]);

	onMounted(async () =>{
	});

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

	// FETCH

	const subcategoryCategoryId = ref(0);
	let subcategoryCategoryResetValue = null;

	let subcategoryPropertyKeyCnt = 0;
	const subcategoryProperties = ref([{
		getValue: null,
		key: subcategoryPropertyKeyCnt
	}]);

	// UNIT

	const locationName = ref('');

	// FETCH

	async function fetchCreate(kind, body, images) {
		let res = await fetch('/admin/create/' + kind, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'X-CSRF-TOKEN': csrf_token
			},
			body: JSON.stringify(body)
		});
		dbAdmin.value.sideSelectResponses.push({
			res: {
				text: await res.text(),
				code: res.status
			},
			key: ++dbAdmin.value.sideSelectResponseKey
		});
	}
</script>

<template>
	<div class="createCont">
		<div v-show="dbAdmin.tab == 0">
			<h1>Skapa ny produkt</h1>
			<h3>Avdelning</h3>
			<SearchSelect v-bind:list="dbAdmin.data.sections" v-bind:getValue="(fun) => productSectionGetValue = fun" v-bind:onSelect="productSectionOnSelect" v-bind:default="0"/>
			<h3>Kategori</h3>
			<SearchSelect v-bind:list="productDataCategories" v-bind:getValue="(fun) => productCategoryGetValue = fun" v-bind:resetValue="(fun) => productCategoryResetValue = fun" v-bind:onSelect="productCategoryOnSelect" v-bind:default="0"/>
			<h3>Subkategori</h3>
			<SearchSelect v-bind:list="productDataSubcategories" v-bind:getValue="(fun) => productSubcategoryGetValue = fun" v-bind:resetValue="(fun) => productSubcategoryResetValue = fun" v-bind:onSelect="productSubcategoryOnSelect" v-bind:default="0"/>
			<h3>Hyllplats</h3>
			<SearchSelect v-bind:list="dbAdmin.data.locations" v-bind:getValue="(fun) => productLocationGetValue = fun" v-bind:default="0"/>
			<h3>Egenskaper</h3>
			<div>
				<div v-for="entry in productDataProperties">
					<span>{{entry.property.name}}: </span><input type="test" v-model="entry.value"> <span>{{dbAdmin.data.units.find((e) => e.id == entry.property.unitId).name}}</span>
				</div>
			</div>
			<h3>Övrigt</h3>
			<span>Antal: </span><input type="text" v-model="productCount"><br>
			<span>Pris: </span><input type="text" v-model="productCost"><br>
			<h3>Bilder (.png)</h3>
			<input id="productImagesInput" type="file" accept="image/png">
			<button @click="(async () =>{
				let fd = productGetFormData();
				fetchCreate('product', {
					subcategoryId: productSelectedSubcategoryId,
					locationId: productLocationGetValue(),
					count: productCount,
					cost: productCost,
					properties: productDataProperties.map((e) => ({value: e.value, propertyId: e.property.id}))
				}, fd);
				productCount = 1;
				productCost = 0;
				productDataProperties.forEach((e) => e.value = '');
			})()">Skapa</button>
		</div>
		<div v-show="dbAdmin.tab == 1">
			<h1>Skapa ny enhet</h1>
			<span>Namn: </span><input type="text" v-model="unitName">
			<button @click="(() =>{
				fetchCreate('unit', {name: unitName});
				unitName = '';
			})()">Skapa</button>
		</div>
		<div v-show="dbAdmin.tab == 2">
			<h1>Skapa ny egenskap</h1>
			<span>Namn: </span><input type="text" v-model="propertyName">
			<h3>Enhet</h3>
			<SearchSelect v-bind:list="dbAdmin.data.units" v-bind:getValue="(fun) => propertyGetValue = fun" v-bind:resetValue="(fun) => propertyUnitResetValue = fun"/>
			<button @click="(() =>{
				fetchCreate('property', {name: propertyName, unitId: propertyGetValue()});
				propertyName = '';
				propertyUnitResetValue();
			})()">Skapa</button>
		</div>
		<div v-show="dbAdmin.tab == 3">
			<h1>Skapa ny avdelning</h1>
			<span>Namn: </span><input type="text" v-model="sectionName">
			<button @click="(() =>{
				fetchCreate('section', {name: sectionName});
				sectionName = '';
			})()">Skapa</button>
		</div>
		<div v-show="dbAdmin.tab == 4">
			<h1>Skapa ny kategori</h1>
			<span>Namn: </span><input type="text" v-model="categoryName">
			<h3>Avdelning</h3>
			<SearchSelect v-bind:list="dbAdmin.data.sections" v-bind:getValue="(fun) => categorySectionGetValue = fun" v-bind:resetValue="(fun) => categorySectionResetValue = fun" v-bind:default="0"/>
			<button @click="(() =>{
				fetchCreate('category', {name: categoryName, sectionId: categorySectionGetValue()});
				categoryName = '';
			})()">Skapa</button>
		</div>
		<div v-show="dbAdmin.tab == 5">
			<h1>Skapa ny subkategori</h1>
			<span>Namn: </span><input type="text" v-model="subcategoryName">
			<h3>Kategori</h3>
			<SearchSelect v-bind:list="dbAdmin.data.categories" v-bind:getValue="(fun) => subcategoryCategoryGetValue = fun" v-bind:resetValue="(fun) => subcategoryCategoryResetValue = fun" v-bind:default="0"/>
			<h3>Egenskaper</h3>
			<div v-for="(entry, index) in subcategoryProperties" :key="entry.key">
				index: {{index}}, key: {{entry.key}}
				<SearchSelect v-bind:list="dbAdmin.data.properties" v-bind:getValue="(fun) => subcategoryProperties[index].getValue = fun"/>
				<button @click="(() =>{
					subcategoryProperties.splice(index, 1);
				})()">Ta bort</button>
			</div>
			<button @click="(() =>{
				subcategoryProperties.push({getValue: null, key: ++subcategoryPropertyKeyCnt});
			})()">Lägg till</button>
			<br>
			<br>
			<button @click="(() =>{
				fetchCreate('subcategory', {
					name: subcategoryName, categoryId: subcategoryCategoryGetValue(), propertyIds: subcategoryProperties.map((e, i) => e = subcategoryProperties[i].getValue())
				});

				subcategoryName = '';
				subcategoryCategoryResetValue();
				subcategoryProperties = [{
					getValue: null,
					key: ++subcategoryPropertyKeyCnt
				}];
			})()">Skapa</button>
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
	*{
		font: "Roboto Condensed", sans-serif;
	}
	/* "Roboto Slab", serif */
</style>