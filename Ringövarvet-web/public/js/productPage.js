const remove = document.getElementById('adminMenuRemove');
const count = document.getElementById('adminMenuRemoveCount');
const minus = document.getElementById('adminMenuRemoveMinus');
const plus = document.getElementById('adminMenuRemovePlus');
const storage = document.getElementById('adminMenuInStorage');

minus.onclick = () => count.value--;
plus.onclick = () => count.value++;

remove.onclick = async () =>{
	let res = await fetch('/database/removeCountProduct', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			'X-CSRF-TOKEN': csrf_token
		},
		body: JSON.stringify({
			id: id,
			count: count.value
		})
	});
	res = await res.json();
	alert(res.res);
	storage.textContent = '/ ' + res.left;
}