/**
 *
 */
document.addEventListener('DOMContentLoaded', () => {
	const retrieveData = () => {
		const data = {};
		document.querySelectorAll('[data-field-name]').forEach(element => {
			data[element.dataset.fieldName] = element.dataset.fieldValue || element.innerText || '';
		})
	};
	document.querySelectorAll('.printer-tester').forEach((button) => {
		button.addEventListener('click', (e) => {
			retrieveData(form);
			console.log('data retrieved', printerConfig);
			if (printerConfig.valid()) {
				print(test_epson_xml, data);
			}
		});
	});
})