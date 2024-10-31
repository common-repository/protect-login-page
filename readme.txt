=== Protect Login Page ===
Contributors: eemitch
Donate link: http://elementengage.com/donate/
Tags: 
Requires at least: 3.0
Tested up to: 5.6
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

This is a simple plugin that protects access to your Wordpress website's login page by intercepting the page request and requiring that a PIN number, up to 8 digits, be present in the URL. Without the proper PIN, no login form is displayed, just a simple message. This plugin will make it harder for hackers to get into your website.

Once activated, you will access your login page like this...

http://yourwebsite.com/wp-login.php?PIN=[your PIN goes here]

IMPORTANT - DO NOT use this plugin if you require login access other than the main Wordpress login page, such as the Wordpress mobile app.


== Installation ==

To install, simply upload the plugin zip file to your Wordpress website and activate it. Configure the simple settings in the new menu that will appear in your Settings menu. Explanations of each feature accompany the inputs.


== Frequently Asked Questions ==

Q: How do I log in once the PIN is set?

A: To log in, just add the PIN value to your Wordpress login page URL, like so: http://mysite.com/wp-login.php?PIN=1234

Q: Do I have to add this PIN to the URL each time?

A: Yes, but if you set yourself a bookmark your life will be much easier.

Q: Why did you develop this plugin. Isn't the login protection itself good enough.

A: Maybe, but I got tired of seeing all of the malicious login attempts in my server logs. I decided to come up with a way to stop this.

Q: What happens if I lose my PIN?

A: If you lose your PIN you will not be able to log into your website. Deactivate the plugin in the database (wp_options.eePLP_STATE = 'OFF') or rename the plugin's folder in your files. You may also contact me for a rescue PIN.


== Upgrade Notice ==


== Screenshots ==

1. Configuration Page



== Changelog ==

= 1.0 =

* FIRST RELEASE VERSION - Previous versions were non-Wordpress.