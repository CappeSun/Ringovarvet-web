<script setup>
	import { ref } from 'vue';

	//import { dbAdmin } from '../globals.js';

	const name = ref('');
	const password = ref('');
	const access = ref(0);

	const users = ref([]);

	async function fetchCreate(name, password, access) {
		let res = await fetch('/user/create', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'X-CSRF-TOKEN': csrf_token
			},
			body: JSON.stringify({
				name: name,
				password: password,
				access: access
			})
		});

		if (res.status != 200) {
			alert(await res.text());
		}

		fetchRead();
	}

	async function fetchRead() {
		let data = await fetch('/user/read',
		{
			method: 'POST',
			headers: {
				'X-CSRF-TOKEN': csrf_token
			}
		});
		users.value = await data.json();
	}

	fetchRead();

	async function fetchUpdate(id, password) {
		let res = await fetch('/user/update', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'X-CSRF-TOKEN': csrf_token
			},
			body: JSON.stringify({
				id: id,
				password: password
			})
		});

		if (res.status != 200) {
			alert(await res.text());
		}
	}

	fetchRead();

	async function fetchDelete(id) {
		let res = await fetch('/user/delete', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'X-CSRF-TOKEN': csrf_token
			},
			body: JSON.stringify({
				id: id
			})
		});

		if (res.status != 200) {
			alert(await res.text());
		}

		fetchRead();
	}
</script>

<template>
	<h2>Skapa konto</h2>
	<h3>Namn</h3>
	<input type="text" v-model="name">
	<h3>Lösenord</h3>
	<input type="text" v-model="password">
	<h3>Nivå</h3>
	<input type="range" min="0" max="2" step="1" v-model="access">
	<h3>({{access}}) Användaren kan:</h3>
	<ul>
		<li>Se adminsidor</li>
		<template v-if="access > 0">
			<li>Skapa och hantera produkter</li>
		</template>
		<template v-if="access > 1">
			<li>Skapa och hantera övrigt</li>
		</template>
	</ul>
	<button @click="fetchCreate(name, password, access)">Skapa</button>
	<br>
	<br>
	<h2>Alla användare</h2>
	<div class="usersCont" v-for="entry in users">
		<div>
			<h3>{{entry.name}}</h3>
			<p>Nivå: {{entry.access}}</p>
			<button @click="fetchDelete(entry.id)">Radera</button>
		</div>
	</div>
</template>

<style type="text/css">
	.usersCont{
	}
	.usersCont > div{
		margin: 0 20px 20px;
		padding: 10px;
		border: solid 1px;
	}
	.usersCont > div > input{
		display: block;
	}
</style>