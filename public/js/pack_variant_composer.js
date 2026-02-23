/**
 *
 */
document.addEventListener('DOMContentLoaded', () => {
	const hexToCmyk = function (hexColor) {
		/* https://jsben.ch/gwY2o
		 * https://github.com/GirkovArpa/hex-color-mixer/blob/master/index.js */
		const number = parseInt(hexColor.substring(1, 7), 16);
		let c = 1 - (number / 16777215);
		let m = 1 - ((Math.floor(number / 256) % 256) / 255);
		let y = 1 - ((number % 256) / 255);
		let k = Math.min(c, m, y);
		c = (c - k) / (1 - k);
		m = (m - k) / (1 - k);
		y = (y - k) / (1 - k);
		return [c, m, y, k];
	}
	/* https://github.com/GirkovArpa/hex-color-mixer/blob/master/index.js */
	const mixCmyks = function (cmyks) {
		const c = cmyks.map(cmyk => cmyk[0]).reduce((a, b) => a + b, 0) / cmyks.length;
		const m = cmyks.map(cmyk => cmyk[1]).reduce((a, b) => a + b, 0) / cmyks.length;
		const y = cmyks.map(cmyk => cmyk[2]).reduce((a, b) => a + b, 0) / cmyks.length;
		const k = cmyks.map(cmyk => cmyk[3]).reduce((a, b) => a + b, 0) / cmyks.length;
		return [c, m, y, k];
	}
	const cmykTohex = function (c, m, y, k) {
		const number =
			  Math.round((1 - (c * (1 - k) + k)) * 255) * 65536
			+ Math.round((1 - (m * (1 - k) + k)) * 255) * 256
			+ Math.round((1 - (y * (1 - k) + k)) * 255);
		return '#' + number.toString(16).padStart(6, '0');
	}
	const blend = function (blender) {
		const colours = [];
		document.querySelectorAll(blender.dataset.source).forEach(source => {
			colours.push(hexToCmyk(source.dataset.colour));
		});
		const mix = mixCmyks(colours);
		document.querySelector(blender.dataset.target).value = cmykTohex(...mix);
		const event = new Event('change');
		document.querySelector(blender.dataset.target).dispatchEvent(event);
	}

	const compute = function (computer) {
		const values = [];
		document.querySelectorAll(computer.dataset.source).forEach(source => {
			values.push(Number(source.dataset.price))
		});
		let result;
		switch (computer.dataset.operation) {
			case 'subtraction':
				result = values.reduce((difference, current) => difference - current);
				break;
			case 'average':
				result = values.reduce((average, current, index, { length }) => (average + current) / length);
				break;
			case 'addition':
			default:
				result = values.reduce((sum, current) => sum + current);
				break;
		}
		document.querySelector(computer.dataset.target).value = result;
	}

	const labelise = function(labeliser) {
		let result = '';
		document.querySelectorAll(labeliser.dataset.source).forEach(source => {
			result = result + source.dataset.label;
		});
		document.querySelector(labeliser.dataset.target).value = result;
		const event = new Event('change');
		document.querySelector(labeliser.dataset.target).dispatchEvent(event);
	}

	document.querySelectorAll('.blender').forEach(blender => {
		blender.addEventListener('click', (e) => {
			blend(e.target);
		});
	});

	document.querySelectorAll('.computer').forEach(computer => {
		computer.addEventListener('click', (e) => {
			compute(e.target);
		});
	});
	document.querySelectorAll('.labeliser').forEach(computer => {
		computer.addEventListener('click', (e) => {
			labelise(e.target);
		});
	});
})
