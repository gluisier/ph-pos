/**
 * @typedef OrderLine
 * @type {object}
 * @property {number} quantity
 * @property {string} title
 * @property {boolean} pack
 */
/**
 * @typedef Order
 * @type {object}
 * @property {array<OrderLine>} lines
 * @property {string} id
 * @property {string} user
 * @property {string} date
 */
/**
 * ```xml
 * <epos-print xmlns="http://www.epson-pos.com/schemas/2011/03/epos-print">
 * 	<page>
 * 		<area x="0" y="0" width="470" height="100"/>
 * 		<feed unit="30"/>
 * 		<text dw="false" dh="true"/>
 * 		<text>product</text>
 * 		<text dw="false" dh="false"/>
 * 		<feed/>
 * 		<text>now|orderId-ticketNumber|user</text>
 * 		<area x="476" y="0" width="100" height="100"/>
 * 		<feed unit="75"/>
 * 		<logo key1="{key1}" key2="{key2}"/>
 * 	</page>
 * 	<cut type="feed"/>
 * </epos-print>
 * ```
 * @param {!object} printer The Epson printer object to print with
 * @param {!Order} order The order to print ticket(s) for
 */
const ticket_logo_epson_xml = (printer, order) => {
	let counter = 1;
	printer.addTextLang('fr');
	if (printerConfig.nbUsers > 1 && order.quantity > 0) {
		printer.addTextAlign(printer.ALIGN_CENTER);
		printer.addText(order.id + ' | ' + printerConfig.user);
		printer.addTextAlign(printer.ALIGN_LEFT);
		printer.addFeed();
		printer.addCut(printer.CUT_FEED);
	}
	for (const id in order.lines) {
		const line = order.lines[id];
		if (line.isPack) {
			continue;
		}
		for (let quantity = 0; quantity < line.quantity; quantity++) {
			printer.addPageBegin();
			printer.addPageArea(0, 0, 470, 100);
			printer.addFeedUnit(30);
			printer.addTextDouble(false, true);
			printer.addText(line.title);
			printer.addTextDouble(false, false);
			printer.addFeed();
			printer.addText(`${order.date}|${order.id}-${('' + counter).padStart(2, '0')}|${printerConfig.user}`);
			printer.addPageArea(476, 0, 100, 100);
			printer.addFeedUnit(75);
			printer.addLogo(printerConfig.key1, printerConfig.key2);
			printer.addPageEnd();
			printer.addCut(printer.CUT_FEED);
			counter++;
		}
	}
}