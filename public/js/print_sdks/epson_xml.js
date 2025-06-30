/**
 * @param {(printer:object, data:object|array|null) => void} template A function to format the data
 * @param {object} data The data to pass to the template
 * @param {() => void} successfulCallback What to do on print success
 * @param {(data:string) => void} errorCallback What to do on print error
 */
// var and not let nor const, so we can switch easily
var print = function (template, data, successfulCallback, errorCallback) {
	const ePosDev = new epson.ePOSDevice();
	let printer = null;
	ePosDev.connect(printerConfig.ip, printerConfig.port, cbConnect);
	function cbConnect(data) {
		if (data == 'OK' || data == 'SSL_CONNECT_OK') {
			ePosDev.createDevice(
				printerConfig.id,
				ePosDev.DEVICE_TYPE_PRINTER,
				{ 'crypto': false, 'buffer': false },
				cbCreateDevice_printer
			);
		} else {
			errorCallback(data);
		}
	}
	function cbCreateDevice_printer(devobj, retcode) {
		if (retcode == 'OK') {
			printer = devobj;
			printer.timeout = 60000;
			printer.onreceive = function(result) {
				if (result.success) {
					successfulCallback;
				} else {
					errorCallback(result.code);
				}
			};
			template(printer, data);
		} else {
			errorCallback(retcode);
		}
	}
}