import { ref } from 'vue';

export const dbAdmin = ref({
	mode: 0,
	tab: 0,

	selCategory: 0,
	selSubcategory: 0,

	data: {},
	
	sideSelectResponses: [],
	sideSelectResponseKey: 0,

	QRProductsSelected: []
});

export const CreateProductMobile = ref({
	mode: 0,
	data: {}
});