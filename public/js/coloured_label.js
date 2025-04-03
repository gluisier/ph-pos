/**
 *
 */
document.addEventListener('DOMContentLoaded', () => {
	const display = document.querySelector('.badge');
	const label = document.querySelector('input[name*="label"]');
	const colour = document.querySelector('input[type="color"]');
	label.addEventListener('change', (e) => {
		display.innerText = label.value;
	});
	colour.addEventListener('change', (e) => {
		display.style.setProperty('background-color', colour.value, 'important');
	});
});