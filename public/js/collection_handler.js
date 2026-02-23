/**
 *
 */
document.addEventListener('DOMContentLoaded', () => {
	const remove = function(button) {
		button.classList.toggle('btn-danger');
		button.classList.toggle('btn-light');
		button.innerText = button.classList.contains('btn-danger') ? '×' : '↻';
		const item = button.closest('div');
		item.classList.toggle('bg-danger');
		item.classList.toggle('text-white');
		for (const field of item.querySelectorAll('input, select, textarea')) {
			field.disabled = !button.classList.contains('btn-danger');
		}
	};
	const removeButton = function(where) {
		Array.from(where.querySelectorAll(':scope >div, :scope >tbody>tr')).filter(function(element) {
			return element.querySelectorAll(':scope >.remover').length === 0;
		}).forEach((item) => {
			item.style.position = 'relative';
			const dummyContainer = document.createElement('div')
			dummyContainer.innerHTML = '<button type="button" class="btn btn-sm btn-danger remover" style="position: absolute; top: 2px; right: 2px; width: auto;">×</button>'
			item.appendChild(dummyContainer.firstChild);
			item.querySelector('.remover').addEventListener('click', (e) => {
				remove(e.target);
			});
		});
	};
	const addButton = function(where) {
		const dummyContainer = document.createElement('div');
		dummyContainer.innerHTML = '<button type="button" class="btn btn-sm btn-success adder">➕</button>';
		where.parentNode.appendChild(dummyContainer.firstChild);
		removeButton(where);
	}
	for (const container of document.querySelectorAll(':not(button)[data-prototype]')) {
		addButton(container);
		const collectionObserver = new MutationObserver( function(mutations) {
			mutations.forEach( function(mutation) {
				for (const addedNode of mutation.addedNodes[0].querySelectorAll('[data-prototype]')) {
					addButton(addedNode);
				};
			});
		});
		collectionObserver.observe(container, { childList: true });
	};
	for (const element of document.querySelectorAll('form')) {
		for (const adder of element.querySelectorAll(':scope .adder')) {
			adder.addEventListener('click', (e) => {
				const container = e.target.closest('fieldset, form').querySelector('[data-prototype]');
				let prototype = container.dataset.prototype;
				const index = new Date().getTime() % (10 * 60 * 1000);
				prototype = prototype.replace(/__name__(label__)?/ig, index);
				const dummyContainer = document.createElement('div');
				dummyContainer.innerHTML = prototype;
				container.append(dummyContainer.firstChild);
				removeButton(container);
			});
		};
	};
});
