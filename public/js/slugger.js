/**
 *
 */
document.addEventListener('DOMContentLoaded', () => {
	const slugger = function(button) {
		let raw = '';
		document.querySelectorAll(button.dataset.source).forEach(source => {
			raw = raw + source.value;
		});
		document.getElementById(button.dataset.target).value = raw.toLowerCase().normalize('NFKD').replace(/[\u0300-\u036f]/g, "").replace(/[^a-z0-9_]+/g, '_');
	}
	Array.from(document.getElementsByClassName('slugger')).forEach(button => {
		button.addEventListener('click', e => {
			slugger(e.target);
		});
	});
})