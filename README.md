# 🎪 ᴾᴴPOS

> [!NOTE]
> [Version française disponible](README.fr.md)

A web PHP/Symfony mix Point Of Service (POS) / ERP application.

This was created after many local events, where selling various items such as beverages and usual dishes led to a wish of statistics.

As web pages, the application is aimed to be responsive.

The application consists of four main parts.

## Price list

Absolutely not interactive, this page is even intended to be printed, if not used as static display.

## Sales (POS)

This page is intended for vendors. It can save orders to the server, display order summary for customers, but also can be used as a mere calculator.
The POS can display items in rows or in a grid, with a total computation displayed at the bottom of the page. There is also an option to use either a dark or a light theme and choose to display or not items' text. Colours are used to help find an item in the list; you can also use matching ticket's colour whenever you use them.
Various payment methods can be defined.

## Production (ERP)

Based on sales data, this page can help plan the preparation of products. In case of dishes, it can allow to get sausages on the grill, salad on plates, slice bread, and ensure that chafing and grill contains enough to serve sold meals.

## Admin (back-office)

This is where managers will prepare the event, filling in data about goods and prices, checking what's sold and how.

## Technical

### Requirements

#### PHP

Dig through [composer.json](./composer.json) for PHP version and extension requirements.
For PHP server settings, this was tested with 30 seconds maximum execution time and 128 Mo of maximal execution memory, with an Apache 2.4 server.

#### Database

The application relies on a database to work, to save items and users to the least. Migration exists for MySQL/MariaDB, might be adapted easily for different database management services.

#### Browser

If using a common and up-to-date browser, nothing should cause problems. Yet, if you want to be sure, you may check the points below.
Instead of an icon library, Unicode emojis are encouraged for labels. _Please note that therefore, their design differs between OSes, versions and even browsers on a same device._

Libraries are loaded through [&lt;cdnjs&gt;](https://cdnjs.com/).

##### JavaScript
For the full application to work, your browser should support:

- BroadcastChannel object, on the sales page, for sales summary functionnality (authenticated access only);
- EventSource object, on the production page, to get sales updates.

The application also uses [Alpine.js](//alpinejs.dev/) to better handle dynamic updates on various pages and some layout drawings.

##### CSS
The application uses Bootstrap 5.3, which contains code, workarounds or shims to have everything working on up-to-date browsers.

##### HTML
- `inputmode` attribute might be used on various `<input />` tags to help triggering the correct handled keyboard layout. `number` type is largely used for client side validation.
- `<output>` tag is used for total amounts.

### Install

1. Fetch this repository
2. Use [composer](https://getcomposer.com/) to install dependencies
   ```
   $> cd path/to/phPOS
   $path/to/phPOS> composer install
   ```
3. Copy the `.env` file to `.env.local` and adapt settings
4. Run migrations
   ```
   $path/to/phPOS> php bin/console doctrine:migrations:migrate
   ```
5. Register a new user by broswing to the registration page
6. Activate it in the database, and grant it `ROLE_ADMIN` role

You're now ready to connect and use the application.

## Use

### Admin

Basically, you sell **items**, that can be grouped into **categories** and paid through various **payment methods**.

All three items consists of a common dataset, which is explained below.

1. **`title`** (〰️): serves as a _text_ representation of the item.
2. **`label`** (🏷️): serves as a _graphical_ representation of the item – this is where emojis might come in handy.
3. **`colour`**: helps finding in lists — yet this is not used on the price list.
   This can as well be related to physical tickets to be delivered.
4. **`public`** (📖): infers on the display in the price list, so whether the item is displayed publicly or not.

#### Categories

This allows you to group items for customers on the price list, but also salespeople on the sales page to find faster your items. These can be anything you want, **except anything that has a price**.

#### Items

These are your articles. Wether you sell hot-dogs or bottled beverages, those will be registered as items in the application. Everything that is precisely sold would be an item.
Items contains the same data as categories above, but also one below as an useful addition.

5. **`price`**: the price at which the item will be sold.
6. **`stock`**: the amount at disposal to be sold. You can leave it empty if you simply don't care or have no idea.
7. **`ticket`** (🎟️): to check in case the item is not sold directly where cashing is done, but a ticket is provided for the stand.
8. **`available`** (💸): to be read as _available for sales_. Therefore, if unchecked, such an item will not display on the price list nor on the sales page.
9. **`separately sellable`**: this is a technical element for variants explained below.

##### Packs
Creating packs is a way to speed up sales. You have reusable cutlery, dishes and glasses with deposit? This means that you may sell beverages along cashing those deposits. Therefore, you may create packs with beverage with glass deposit, T-bone steak + fries with dish and cutlery deposit, etc.

On the technical part, packs are simply items composed of other items.

> [!WARNING]
> **Don't nest packs**. Instead, "duplicate" an existing pack and add what you miss to it.

##### Variants
One of the best example about them is: imagine you sell T-Shirts. You have various sizes, as well as various colours. You will actually sell green XL T-Shirts, blue M ones, etc., not simply "a T-Shirt". Yet, you won't display all combinations of sizes and colours on a price list, you'll rather say you sell T-Shirts and list all sizes and colours available.
A combination of a size and a colour is called a **variant**.
As you won't sell mere sizes nor colours, this is where the _`separately sellable`_ data comes in.

Other examples include:
- hot-dogs with either mayonnaise, ketchup or mustard;
- sausages with either salad, bread or french fries.

The last example might explain better the fact that **variants may have different price than the article they're based upon**. Indeed, french fries would cost more than salad, which is still more expansive than bread.

Technically, variants are items too, related to packs, but while packs are composed of separately sellable items, variants are composed of **not** separately sellable items.