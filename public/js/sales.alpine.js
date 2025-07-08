// @ts-check
window.Sales = () => ({
	category: '',
	order: {
		lines: {},
		get quantity() {
			let result = 0;
			for (const id in this.lines) {
				result += this.lines[id].quantity;
			}
			return result;
		},
		get tickets() {
			let result = 0;
			for (const id in this.lines) {
				if (!this.lines[id].ticket) continue;
				result += this.lines[id].quantity;
			}
			return result;
		},
		get amount() {
			let result = 0;
			for (const id in this.lines) {
				result += this.lines[id].amount;
			}
			return result;
		},
	},
	direction: 1,
	layout: {
		layout: 'table',
		textDisplay: true,
		labelDisplay: true,
		receiptDisplay: 0,
	},
	getLayout() {
		return document.querySelector('[data-layout]').dataset.layout;
	},
	getPreferredLayout() {
		const layout = localStorage.getItem('layout') || this.getLayout();
		if (['table', 'deck'].includes(layout)) {
			return layout;
		}

		return 'table';
	},
	storeLayout(layout) {
		localStorage.setItem('layout', layout);
		this.layout.layout = layout;
	},
	toggleLayout() {
		const newLayout = this.layout.layout === 'table'
			? 'deck'
			: 'table';
		this.storeLayout(newLayout);
	},
	getTextDisplay() {
		// The span is hidden when the titles are displayed
		return document.querySelector('#TextToggler span')?.style.display != 'none';
	},
	getPreferredTextDisplay() {
		return JSON.parse(localStorage.getItem('textDisplay') ?? 'false') || this.getTextDisplay();
	},
	storeTextDisplay(display) {
		localStorage.setItem('textDisplay', display);
		this.layout.textDisplay = display;
	},
	toggleText() {
		this.storeTextDisplay(this.getTextDisplay());
	},
	getReceiptDisplay() {
		if (document.getElementById('Splitter')?.closest('.dropdown-item')?.classList.contains('active')) {
			return document.getElementById('SplitterRatioSelector').value;
		} else {
			return '0';
		};
	},
	getPreferredReceiptDisplay() {
		return localStorage.getItem('receiptDisplay') || this.getReceiptDisplay();
	},
	storeReceiptDisplay(display) {
		localStorage.setItem('receiptDisplay', display);
	},
	changeReceiptDisplay(currentRatio, previousRatio) {
		previousRatio = previousRatio || this.getPreferredReceiptDisplay();
		localStorage.setItem('receiptDisplay', currentRatio);
		this.storeReceiptDisplay(currentRatio);
		this.layout.receiptDisplay = currentRatio;
	},
	toggleReceiptDisplay({target}) {
		if (target.tagName === 'INPUT') {
			return;
		}
		this.changeReceiptDisplay(!target.closest('.dropdown-item').classList.contains('active') ? document.getElementById('SplitterRatioSelector').value : 0);
	},
	init() {
		this.order.lines = JSON.parse(document.querySelector('[data-items]').dataset.items);
		for (const id in this.order.lines) {
			this.order.lines[id].quantity = 0;
			this.order.lines[id].hide = function (category) {
				return (!!category && !this.dataCategoryId().includes(+category))
					|| (this.isPack && !category)
					|| (Object.values(this.variantOf?.variants ?? {}).length > 1) && !category;
			};
			this.order.lines[id].dataCategoryId = function() {
				const result = [];
				if (this.isVariant) {
					result.push(this.variantOf.id);
				}
				if (this.category && ((Object.values(this.attributes ?? {}).length == 0) || (Object.values(this.variants ?? {}).length == 0))) {
					result.push(this.category.id);
				}

				return result;
			},
			Object.defineProperty(this.order.lines[id], 'amount', {
				get() {
					return this.quantity * this.price;
				}
			});
		}
		if (this.getPreferredLayout() != this.getLayout()) {
			this.toggleLayout();
		} else {
			this.storeLayout(this.getPreferredLayout());
		}
		if (this.getPreferredTextDisplay() != this.getTextDisplay()) {
			this.toggleText();
		} else {
			this.storeTextDisplay(this.getPreferredTextDisplay());
		}
		if (this.getPreferredReceiptDisplay() != this.getReceiptDisplay()) {
			if ((this.getPreferredReceiptDisplay() != '0')) {
				document.getElementById('Splitter').closest('.dropdown-item').classList.add('active');
				this.layout.receiptDisplay = this.getPreferredReceiptDisplay();
				this.changeReceiptDisplay(this.getPreferredReceiptDisplay(), '0');
			}
		} else {
			this.storeReceiptDisplay(this.getReceiptDisplay());
		}
	},
	itemStyle(item) {
		return Object.assign(this.itemBackgroundStyle(item), this.itemColourStyle(item));
	},
	itemClick(event) {
		if (event.target.tagName == 'INPUT') {
			event.stopPropagation();
			return;
		}
		const itemRoot = event.target.closest('.item-root');
		const id = itemRoot.dataset.id;
		const input = itemRoot.querySelector('input[type="number"]');
		if (input) {
			this.order.lines[id].quantity += this.direction;
			event.preventDefault();
			if (this.order.lines[id].composedOf.length > 1) {
				this.order.lines[id].composedOf.forEach( composedOf => {
					this.order.lines[composedOf].quantity += this.direction;
				});
			}
		} else {
			this.category = id;
			const style = itemRoot.querySelector('[style]').style;
			const backgroundColor = style.backgroundColor;
			const backgroundImage = style.backgroundImage;
			for (const categoriesContainer of document.getElementsByClassName('categories')) {
				const template = categoriesContainer.firstChild.cloneNode(true);
				template.classList.add('reset-remove');
				template.setAttribute('x-effect', function() {
					if (this.category != $el.querySelector('[value]').value) {
						$el.remove();
					}
				});
				const input = template.querySelector('select, [type="checkbox"], [type="radio"]');
				template.querySelector('label').innerText = itemRoot.querySelector('label').innerText;
				template.querySelector('[value]').value = id;
				input.value = id;
				template.style.backgroundImage = backgroundImage;
				input.style.backgroundColor = backgroundColor;
				template.classList.add('rounded');
				categoriesContainer.append(template);
			};
		}
		return false;
	},
	STEP_WIDTH: 1.5,
	STEP_WIDTH_UNIT: 'em',
	isDark(hex) {
		if (!hex || (hex.length !== 7)) {
			return false;
		}
		const rgb = Number(hex.replace('#', '0x'));
		const r = (rgb & 0xff0000) >> 16;
		const g = (rgb & 0xff00) >> 8;
		const b = (rgb & 0xff);

		return ((r * 0.299 + g * 0.587 + b * 0.114) / 256) < 0.114;
	},
	variantsGradient(items) {
		if (Object.values(items).length < 2) {
			return '';
		}
		let steps = [];
		for (let i = 0; i < Object.values(items).length; i++) {
			({colour} = Object.values(items)[i]);
			steps.push([colour, i * this.STEP_WIDTH + this.STEP_WIDTH_UNIT, (i + 1) * this.STEP_WIDTH + this.STEP_WIDTH_UNIT].join(' '));
		}

		return {background: `repeating-linear-gradient(45deg, ${steps.join(', ')})`};
	},
	packGradient(items) {
		if (Object.values(items).length < 2) {
			return '';
		}
		let steps = [];
		for (let i = 0; i < Object.values(items).length; i++) {
			({colour} = Object.values(items)[i]);
			steps.push([colour, i * this.STEP_WIDTH + this.STEP_WIDTH_UNIT].join(' '));
		}
		steps.push([Object.values(items).shift().colour, Object.values(items).length * this.STEP_WIDTH + this.STEP_WIDTH_UNIT].join(' '));

		return {background: `repeating-linear-gradient(-45deg, ${steps.join(', ')})`};
	},
	itemBackgroundStyle(item) {
		let result = {};
		if (item.colour) {
			result.backgroundColor = `${item.colour}`;
		}
		if (item.isPack) {
			Object.assign(result, this.packGradient(item.composedOf));
		} else if (Object.values(item.variants ?? {}).length) {
			Object.assign(result, this.variantsGradient(item.variants));
		}

		return result;
	},
	itemColourStyle(item) {
		let result = {};
		if (item.colour) {
			result.color = this.itemColour(item)
		}

		return result;
	},
	itemColour(item) {
		let result = 'var(--bs-';
		if (item.colour) {
			result = result + (this.isDark(item.colour) ? 'light' : 'dark');
		} else {
			result = result + 'body-color';
		}
		result = result + ')';

		return result;
	}
});