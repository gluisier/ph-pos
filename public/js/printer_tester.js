/**
 *
 */
document.addEventListener('DOMContentLoaded', () => {
	const retrieveData = () => {
		document.querySelectorAll('[data-field-name]').forEach(element => {
			printerConfig[element.dataset.fieldName] = element.dataset.fieldValue
				|| element.options ? element.options[element.selectedIndex].innerText : null
				|| element.innerText
				|| element.value
				|| 'None';
		});
		return printerConfig;
	};
	document.querySelectorAll('.printer-tester').forEach((button) => {
		button.addEventListener('click', (e) => {
			if (printerConfig.valid()) {
				print(test_epson_xml, retrieveData(), function() {console.log('Succes')}, function(message) {console.error(message)});
			}
		});
	});
})