function toggleFullScreen(what) {
	if (!document.fullscreenElement && !document.mozFullScreen && !document.webkitFullScreen) {
		let element = null;
		if (typeof what === 'string') {
			element = document.querySelector(what);
		} else if (what instanceof Event) {
			element = what.target;
		} else if (what instanceof HTMLElement) {
			element = what;
		}
		if (!element) {
			console.info('Could not toggle full screen');
			return;
		}
		if (element.requestFullScreen) {
			element.requestFullScreen();
		} else if (element.mozRequestFullScreen) {
			element.mozRequestFullScreen();
		} else {
			element.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
		}
	} else {
		if (document.cancelFullScreen) {
			document.cancelFullScreen();
		} else if (document.mozCancelFullScreen) {
			document.mozCancelFullScreen();
		} else {
			document.webkitCancelFullScreen();
		}
	}
}
