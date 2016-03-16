=== Plugin Name ===
Contributors: cxThemes
Tags: woocommerce
Requires at least: 3.0.1
Tested up to: 3.6
Stable tag: 1.13
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Save time and simplify your life by having the ability to create a new Customer directly on the WooCommerce Order screen.

== Description ==

What it Does
This plugin is a must-have for any WooCommerce store; we too are Shop Managers and developed this to greatly simplify our workflow.

The Current Situation
Currently, to create a new Order manually for a new Customer, a Shop Manager needs to go to the User section, create the new User, choose a random username, add a temporary password manually, email that password in the clear (security risk). Once the User is created the Manager can navigate BACK to the Order screen to create a new Order for that Customer. (Hassle)

Our Plugin
Now, with this plugin, all you need to do is add a new Order and click the Create Customer button. Fill in their email address (and First & Last Name if you have them) - and that’s it. The Order can then be completed for that new User without leaving the screen.

Create User on Order then takes care of the previously time consuming work by immediately and automatically sending the customer an email detailing how to securely set a password and log into their new account. 

AND the Shop Manager can then save the billing and shipping addresses BACK to the Customer profile directly from the new Order. Which by itself is an incredibly useful piece of functionality. 

Once the new Order is created, the Manager can email the invoice for payment directly to that new customer in the standard way.

Great For:
* Creating orders manually for new customers, over-the-phone or email
* Saving time and effort for Managers.
* Generating sales and engagement by minimising the hassle for customers
* Assisting customers with difficulty ordering for the first time
* Empowering customer service managers with better, more efficient tools
* Any WooCommerce store!

Happy Conversions from the CX Team!


== Documentation ==

Please see the included PDF for full instructions on how to use this plugin.
 

== Changelog ==

1.13
* Fix Save-to-User checkboxes not showing on WC2.4
* Only show Save-to-User checkboxes after customer chooses to Edit-Address.

1.12
* Add checkbox to disable the registration email being sent to the user.
* Added filter to auto tick this box - add this code to your functions.php: add_filter( 'shop_as_customer_disable_email', '__return_true' );
* Better formatting of the email sent to new customer.
* Fixed undefined index notices.

1.11
* Added Internationalization how-to to the the docs.
* Updated the language files.
* Changes to the order and priority of the loaded language files. Will not effect anyone who is already using internationalization.
* Changed where in the code the WooCommerce and version number checking is done.
* Made more strings translatable.
* Escaped all add_query_args and remove_query_args for security.
* Updated PluginUpdateChecker class.

1.10
* Add support for Select2 (WC2.3) and Chozen for backward compatibility.
* Fixed special case if roles are not set.

1.09
* replace $woocommerce->add_inline_js with wc_enqueue_js

1.08
* rawurlencode the reset password url email address so it is not stripped in case of a redirect.

1.07
* Changed our WooCommerce version support - you can read all about it here https://helpcx.zendesk.com/hc/en-us/articles/202241041/
* Fixed set password link not working in some versions of WC2.2 and above.

1.06
* Fixed set password link not working in some versions of WC.

1.05
* Added header and footer to email.
* Added filter to email title.
* Changed email From details to WooCommerce email settings.
* Regenerated Language files.
* Prepopulate billing and shipping first and last name and billing email address after creating a user.
* Fixed the Create Your Password title relabeling all titles on the page.

1.04
* Fixed compatibility issue with WC2.1 to use the new endpoints instead of page ids.

1.03
* Added compatibility with WooCommerce version 2.1.
* Updated UpdateChecker class.
* Various small bug fixes.

1.02
* Added en_US.mo and en_US.mo files to use for language conversion.
* Updated language support for previously disabled text areas.
* Fixed bug notice when applying filters to email message.

1.01
* Added ability to choose the role of the new user.
* Updated so nickname and displayname is dynamically set using a combo of firstname lastname and if they are left blank will use the first part of their email up to the @.

1.00
* Initial release.
