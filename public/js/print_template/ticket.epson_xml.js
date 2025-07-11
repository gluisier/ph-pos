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
 * 	<text dw="true" dh="true"/>
 * 	<text>Product</text>
 * 	<text dw="false" dh="true"/>
 * 	<feed/>
 * 	<text>now - user</text>
 * 	<feed/>
 * 	<cut type="feed"/>
 * 	…
 * </epos-print>
 * ```
 * @param {!object} printer The Epson printer object to print with
 * @param {!Order} order The order to print ticket(s) for
 */
const ticket_epson_xml = (printer, order) => {
	let first = true;
	printer.addTextLang('fr');
	for (const id in order.lines) {
		const line = order.lines[id];
		// TODO pas de consigne non plus
		if (line.pack) {
			continue;
		}
		for (let quantity = 0; quantity < line.quantity; quantity++) {
			printer.addTextDouble(false, true);
			printer.addText(line.title);
			printer.addTextDouble(false, false);
			printer.addFeed();
			printer.addText(`${order.date} — ${order.user}`);
			printer.addFeed();
			printer.addCut(printer.CUT_FEED);
			first = false;
		}
	}
	printer.send();
}