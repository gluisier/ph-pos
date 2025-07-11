
/**
 * ```xml
 * <epos-print xmlns="http://www.epson-pos.com/schemas/2011/03/epos-print">
 * 	<text align="center"/>
 * 	<text dw="true" dh="true"/>
 * 	<text>pwet_pwet_49g_4</text>
 * 	<text dw="false" dh="false"/>
 * 	<feed/>
 * 	<text reverse="false" ul="false" em="true" color="color_2"/>
 * 	<text>Over there</text>
 * 	<text reverse="false" ul="false" em="false" color="color_1"/>
 * 	<feed/>
 * 	<text align="left"/>
 * 	<text>Tamara, Tamina, Strawberry</text>
 * 	<feed/>
 * 	<cut type="feed"/>
 * </epos-print>
 * ```
 * @param {object} printer The Epson XML printer
 * @param {object} data The data to use for printing
 */
const test_epson_xml = (printer, data) => {
	printer.addTextAlign(printer.ALIGN_CENTER);
	printer.addTextDouble(true, true);
	printer.addText(data.id);
	printer.addTextDouble(false, false);
	printer.addFeed();
	printer.addTextStyle(false, false, true, printer.COLOR_2);
	printer.addText(data.location);
	printer.addFeed();
	if (data.user == 'None') {
		printer.addTextStyle(true, false, true, printer.COLOR_2);
	} else {
		printer.addTextStyle(false, false, false, printer.COLOR_1);
		printer.addTextAlign(printer.ALIGN_LEFT);
	}
	printer.addText(data.users);
	printer.addFeed();
	printer.addCut(printer.CUT_FEED);
	printer.send();
}