/**
 *
 */
const printer_api_loader_tag = document.currentScript;
document.getElementById(printer_api_loader_tag.dataset.id).addEventListener('change', (e) => {
	const sdk = document.createElement('script');
	sdk.setAttribute('src', printer_api_loader_tag.dataset.baseurl + 'sdks/' + e.target.value + '_sdk.js');
	sdk.setAttribute('id', 'Sdk');
	sdk.setAttribute('type', 'text/javascript');
	sdk.setAttribute('async', 'true');

	sdk.onerror = () => { throw new Error(`Error loading SDK for ${e.target.value}`) };
	sdk.onload = () => {
		const wrapper = document.createElement('script');
		wrapper.setAttribute('src', printer_api_loader_tag.dataset.baseurl + '_sdk/' + e.target.value + '.js');
		wrapper.setAttribute('id', 'Wrapper');
		wrapper.setAttribute('type', 'text/javascript');
		wrapper.setAttribute('async', 'true');

		wrapper.onerror = () => { throw new Error(`Error loading wrapper for ${e.target.value}`) };
		wrapper.onload = () => {
			const template = document.createElement('script');
			template.setAttribute('src', printer_api_loader_tag.dataset.baseurl + '_template/' + e.target.value + '.js');
			template.setAttribute('id', 'Template');
			template.setAttribute('type', 'text/javascript');
			template.setAttribute('async', 'true');

			template.onerror = () => { throw new Error(`Error loading test template for ${e.target.value}`) };

			if (document.getElementById('Template')) {
				document.getElementById('Template').remove();
			}
			document.head.appendChild(template);
		}

		if (document.getElementById('Sdk')) {
			document.getElementById('Sdk').remove();
		}
		document.head.appendChild(wrapper);
	};

	if (document.getElementById('Sdk')) {
		document.getElementById('Sdk').remove();
	}
	document.head.appendChild(sdk);
})