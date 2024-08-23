const getLayout = () => {
	return document.querySelector('[data-layout]').dataset.layout;
};

const getPreferredLayout = () => {
	const layout = localStorage.getItem('layout') || getLayout();
	if (['table', 'deck'].includes(layout)) {
		return layout;
	}

	return 'table';
}

const setLayout = (layout) => {
	localStorage.setItem('layout', layout);
	document.querySelector('[data-layout]').setAttribute('data-layout', layout);
	const layoutSwitcher = document.getElementById('LayoutToggler');
	const contentPossible = JSON.parse(layoutSwitcher.dataset.contentPossible);
	layoutSwitcher.innerText = contentPossible[layout];
}

const toggleLayout = () => {
	document.querySelectorAll('[data-layout-toggle]').forEach( element => {
		const temp = element.className;
		element.className = element.dataset.layoutToggle;
		element.dataset.layoutToggle = temp;
	});

	const newLayout = getLayout() === 'table'
		? 'deck'
		: 'table';

	setLayout(newLayout);
};

if (getPreferredLayout() != getLayout()) {
	toggleLayout();
} else {
	setLayout(getPreferredLayout());
}

document.getElementById('LayoutToggler').addEventListener('click', event => {
	toggleLayout(event.target);
});

const getTextDisplay = () => {
	// The span is hidden when the titles are displayed
	return document.querySelector('#TextToggler span').classList.contains('visually-hidden');
}

const getPreferredTextDisplay = () => {
	return localStorage.getItem('textDisplay') || getTextDisplay();
}

const setTextDisplay = (display) => {
	localStorage.setItem('textDisplay', display);
}

const toggleText = () => {
	document.querySelectorAll('.item-text').forEach( element => {
		element.classList.toggle('visually-hidden');
		if (toggle = element.dataset.layoutToggle) {
			const temp = document.createElement('span');
			temp.className = toggle;
			temp.classList.toggle('visually-hidden');
			element.dataset.layoutToggle = temp.className;
		}
	});
	localStorage.setItem('textDisplay', getTextDisplay());
};

if (getPreferredTextDisplay() != getTextDisplay()) {
	toggleText();
} else {
	setTextDisplay(getPreferredTextDisplay());
}

document.getElementById('TextToggler').addEventListener('click', event => {
	toggleText(event.target);
});
