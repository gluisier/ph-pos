# 🎪 ᴾᴴPOS

A web PHP/Symfony mix Point Of Sales (POS) / ERP application.

This was created after many local events, where selling various items such as beverages and usual dishes led to a wish of statistics.

This application proposes a POS web page, that can save orders to the server, display order summary for customers, but can be used as a mere calculator. The POS can display items in rows or in a grid, with a total computation displayed at the bottom of the page. There is also an option to use either a dark or a light theme and choose to display or not items' text. Colours are used to help find an item in the list; you can also use matching ticket's colour whenever you use them.

On the other hand, it also provides a production screen with steps for cooking in case of vouchers, to match ticket sales with dish service.

As web pages, the application is aimed to be responsive.

## Technical

### Requirements

#### PHP

Dig through [composer.json](./composer.json) for PHP version and extension requirements.  
For PHP server settings, this was tested with 30 seconds maximum execution time and 128 Mo of maximal execution memory.

#### Database

The application relies on a database to work, to save items and users to the least. Migration exists for MySQL/MariaDB, might be adapted easily for different database management services.

#### Browser

If using a common and up-to-date browser, nothing should cause problems. Yet, if you want to be sure, you may check the points below.  
Instead of an icon library, Unicode emojis are used and encouraged for item labels. _Please note that therefore, their design differs between OSes and even browsers._

##### JavaScript
For the full application to work, your browser should support :

- BroadcastChannel object, on the sales page, for sales summary functionnality ;
- EventSource object, on the production page, to get sales updates.

The application also uses [Alpine.js](//alpinejs.dev/) to better handle dynamic updates on various pages.

##### CSS
The application uses Bootstrap 5.3, which contains code, workarounds or shims to have everything working on up-to-date browsers.

##### HTML
- `inputmode` attribute might be used on various `<input />` tags to help triggering the correct handled keyboard layout. `number` type is largely used for client side validation.  
- `<output>` tag is used for total amounts.