=== EasyPay ===
Contributors: theemon.com
Tags: Easy Payments
Requires at least: 3.5+
Tested up to: 4.0

EasyPay is a payment solution for WordPress based web sites. It provides a direct payment interface for visitors of your online services site through a embedded form on your site, where users will be able to enter amount and other required details and make the payment.

== Description ==

EasyPay is a payment solution for WordPress based web sites. It provides a direct payment interface for visitors of your online services site through a embedded form on your site, where users will be able to enter amount and other required details and make the payment.

<strong> Features / Sources </strong>

<ol>
			<li>
				Fully compatible with <a href="http://getbootstrap.com" target="_blank;">Bootstrap 3</a>
			</li>
			<li>
				Built on <a href="https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate" target="_blank;">WordPress-Plugin-Boilerplate</a>
			</li>
			<li>
				Payment forms designed using <a href="https://github.com/minikomi/Bootstrap-Form-Builder" target="_blank;">Bootstrap-Form-Builder</a>
			</li>
			<li>
				Responsive Forms
			</li>
			<li>
				<a href="http://jqueryvalidation.org" target="_blank">Jquery validation</a> working forms
			</li>
			<li>
				PDF invoices using <a href="http://html2pdf.fr/en/default" target="_blank;">PDF Generator</a>
			</li>
		</ol>

== Installation ==

1. Unzip the plugin folder
2. Upload `easypay` to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Enter the necessary settings for the plugin.
5. Create a page and put [EASYPAY_FORM] shortcode to add payments form.


== Changelog ==

= 1.0.0 =
* Initial Version

= 2.0.0 =
*Added gateway tabs in admin
*Added PayPal Pro DoDirectPayment payments
*Removed Gateway fields from payment form for security
*Added option for fixed amount choices
*Added option for allowing currency choices in form
*Dynamic fee calculation based on the gateway choice
*Improved multiple security features
*Added more settings and options for administrator