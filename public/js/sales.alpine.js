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
		titleDisplay: true,
		labelDisplay: true,
		numbersDisplay: true,
		receiptDisplay: 0,
	},
	// Layout (table / card)
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
	// Title display
	getTitleDisplay() {
		return this.$refs.TitleToggler.checked;
	},
	getPreferredTitleDisplay() {
		return JSON.parse(localStorage.getItem('titleDisplay') ?? 'false') || this.getTitleDisplay();
	},
	storeTitleDisplay(display) {
		localStorage.setItem('titleDisplay', display);
		this.layout.titleDisplay = display;
	},
	toggleTitle() {
		this.storeTitleDisplay(this.getTitleDisplay());
	},
	// Label display
	getLabelDisplay() {
		return this.$refs.LabelToggler.checked;
	},
	getPreferredLabelDisplay() {
		return JSON.parse(localStorage.getItem('labelDisplay') ?? 'false') || this.getLabelDisplay();
	},
	storeLabelDisplay(display) {
		localStorage.setItem('labelDisplay', display);
		this.layout.labelDisplay = display;
	},
	toggleLabel() {
		this.storeLabelDisplay(this.getLabelDisplay());
	},
	// Numbers display
	getNumbersDisplay() {
		return this.$refs.NumbersToggler.checked;
	},
	getPreferredNumbersDisplay() {
		return JSON.parse(localStorage.getItem('numbersDisplay') ?? 'false') || this.getNumbersDisplay();
	},
	storeNumbersDisplay(display) {
		localStorage.setItem('numbersDisplay', display);
		this.layout.numbersDisplay = display;
	},
	toggleNumbers() {
		this.storeNumbersDisplay(this.getNumbersDisplay());
	},
	// Receipt display
	getReceiptDisplay() {
		if (this.$refs.Splitter?.closest('.dropdown-item')?.classList.contains('active')) {
			return this.$refs.SplitterRatioSelector.value;
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
		this.changeReceiptDisplay(!target.closest('.dropdown-item').classList.contains('active') ? this.$refs.SplitterRatioSelector.value : 0);
	},
	init() {
		this.order.lines = JSON.parse(document.querySelector('[data-items]').dataset.items);
		for (const id in this.order.lines) {
			this.order.lines[id].quantity = 0;
			this.order.lines[id].show = function (category) {
				return (!category || this.dataCategoryId().includes(+category))
					&& (!this.isPack || !!category)
					&& (Object.values(this.variantOf?.variants ?? {}).length <= 1 || !!category);
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
		// Layout (table / card)
		if (this.getPreferredLayout() != this.getLayout()) {
			this.toggleLayout();
		} else {
			this.storeLayout(this.getPreferredLayout());
		}
		// Title display
		if (this.getPreferredTitleDisplay() != this.getTitleDisplay()) {
			this.toggleTitle();
		} else {
			this.storeTitleDisplay(this.getPreferredTitleDisplay());
		}
		// Label display
		if (this.getPreferredLabelDisplay() != this.getLabelDisplay()) {
			this.toggleLabel();
		} else {
			this.storeLabelDisplay(this.getPreferredLabelDisplay());
		}
		// Numbers display
		if (this.getPreferredNumbersDisplay() != this.getNumbersDisplay()) {
			this.toggleNumbers();
		} else {
			this.storeNumbersDisplay(this.getPreferredNumbersDisplay());
		}
		if (this.getPreferredReceiptDisplay() != this.getReceiptDisplay()) {
			if ((this.getPreferredReceiptDisplay() != '0')) {
				this.$refs.Splitter.closest('.dropdown-item').classList.add('active');
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
				const template = categoriesContainer.lastChild.cloneNode(true);
				template.classList.add('reset-remove');
				template.setAttribute('x-effect', function() {
					if (this.category != $el.querySelector('[value]').value) {
						$el.remove();
					}
				});
				const input = template.querySelector('select, [type="checkbox"], [type="radio"]');
				template.querySelector('label').innerText = itemRoot.querySelector('label').previousElementSibling.innerText;
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
			result.color = this.itemColour(item);
			result.borderColor = this.itemColour(item);
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