=== WPC Smart Quick View for WooCommerce ===
Contributors: wpclever
Donate link: https://wpclever.net
Tags: woocommerce, woo, smart, quickview, quick view, wpc
Requires at least: 4.0
Tested up to: 5.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WPC Smart Quick View allows users to get a quick look of products without opening the product page.

== Description ==

WPC Smart Quick View allows users to get a quick look of products without opening the product page.

= Live demo =

Click to see [live demo](http://demo.wpclever.net/?item=woosq "live demo")

= Features =

- Custom position for button
- Only show the Quick View button for products in selected categories
- Navigation to next/previous product
- Support shortcode
- Many effects
- WPML integration

= Translators =

Available Languages

- English (Default)
- German
- Vietnamese

If you have created your own language pack, or have an update for an existing one, you can send [gettext PO and MO file](http://codex.wordpress.org/Translating_WordPress "Translating WordPress") to [us](https://wpclever.net/contact "WPclever.net") so we can bundle it into WPC Smart Quick View.

= Need support? =

Visit [plugin documentation website](https://wpclever.net "plugin documentation")

== Installation ==

1. Please make sure that you installed WooCommerce
2. Go to plugins in your dashboard and select "Add New"
3. Search for "WPC Smart Quick View", Install & Activate it
4. Go to settings page to choose position and effect as you want

== Frequently Asked Questions ==

= How to integrate with my theme? =

To integrate with a theme, please use bellow filter to hide the default buttons.

`add_filter( 'woosq_button_position', function() {
    return '0';
} );`

After that, use the shortcode to display the button where you want.

`echo do_shortcode('[woosq id="{product_id}"]');`

== Changelog ==

= 1.2.4 =
* Added: Filter for button html 'woosq_button_html'
* Updated: Optimized the code

= 1.2.3 =
* Updated: Optimized the code

= 1.2.2 =
* Fixed: Multiple select categories
* Updated: Compatible with WooCommerce 3.6.x

= 1.2.1 =
* Updated: Optimized the code

= 1.2.0 =
* Added: Only show the Quick View button for products in selected categories
* Fixed: Default button text can be translated

= 1.1.9 =
* Added: Choose the functionally for the add to cart button
* Updated: Optimized the code

= 1.1.8 =
* Fixed: Minor JS issue

= 1.1.7 =
* Compatible with WooCommerce 3.5.3
* Updated: Change the scrollbar style

= 1.1.6 =
* Added: German language (thanks to Rado Rethmann)
* Fixed: Quick view for products loaded by AJAX

= 1.1.5 =
* Updated: Change the plugin name
* Updated: Optimized the code

= 1.1.4 =
* Compatible with WooCommerce 3.5.0

= 1.1.3 =
* Updated: Optimize the code to reduce the loading time

= 1.1.2 =
* Fixed: Error when WooCommerce is not active

= 1.1.1 =
* Fixed: JS trigger
* Compatible with WooCommerce 3.4.5

= 1.1.0 =
* Updated: Settings page style

= 1.0.9 =
* Added JS trigger 'woosq_loaded' and 'woosq_open'

= 1.0.8 =
* Compatible with WooCommerce 3.4.2
* Optimized the code

= 1.0.7 =
* Fixed some minor CSS issues
* Compatible with WordPress 4.9.6

= 1.0.6 =
* Compatible with WooCommerce 3.3.5

= 1.0.5 =
* Compatible with WordPress 4.9.5

= 1.0.4 =
* Compatible with WooCommerce 3.3.3

= 1.0.3 =
* Compatible with WordPress 4.9.4
* Compatible with WooCommerce 3.3.1

= 1.0.2 =
* Update CSS enqueue

= 1.0.1 =
* New: WPML integration

= 1.0 =
* Released