=== Plugin Name ===
Contributors: Pepak
Donate link:
Tags: geolocation, ip, country, ip to country, invisible, support
Requires at least: 2.8.0
Tested up to: 3.0.1
Stable tag: 0.08

Provide a simple interface for plugin authors to determine, in which country an IP is located.

== Description ==

IP-to-Country is a plugin which doesn't provide any output to users, but which
may be used by plugin authors to quickly and easily find, in which country an
IP address is located.

To do so the plugin uses data files from either http://ip-to-country.webhosting.info
(IPv4 only) or http://software77.net/geo-ip/ (both IPv4 and IPv6).
Other sources are possible as long as they have the same format: CSV file, 
fields enclosed with quotes (") and separated with comma (,), lines ending
with LF (\n). Five columns:

* Lower bound of an IP range.
* Upper bound of an IP range. Both are represented as one 32-bit integer number,
  such as you can get by using funtion ip2long().
* 2-character country code
* 3-character country code
* Country name

Note: In order to import IPv6 addresses, IPv4 must be imported first. The 
reason is, IPv6 database does not carry the full country information with
it, while the IPv4 database does.

Operations:

* To detect if IP-to-Country plugin is installed:

  `$ip2c_available = class_exists('PepakIpToCountry');`

* To find information about a numeric IP address:

  `$country = PepakIpToCountry::IP_to_Country_Full('8.8.8.8');
  // returns 'UNITED STATES'
  $country = PepakIpToCountry::IP_to_Country('8.8.8.8');
  $country = PepakIpToCountry::IP_to_Country_XX('8.8.8.8');
  // both return 'US'
  $country = PepakIpToCountry::IP_to_Country_XXX('8.8.8.8');
  // returns 'USA'
  $country = PepakIpToCountry::IP_to_Country_Info('8.8.8.8');
  // returns Array('iso_name'=>'UNITED STATES', 'iso_code2'=>'US', 'iso_code3'=>'USA')`
  $image = PepakIpToCountry::IP_to_Country_Flag('8.8.8.8');
  // returns '<img src="(path_to_plugin)/flags/us.png" alt="UNITED STATES" />'

  You may leave the IP address empty; in that case, IP address of currently
  active user is used instead.

  Note: All requests are cached, so there shouldn't be a noticeable difference
  between reading IP_to_Country_Info and reading all three pieces of information
  separately using the _Full, _XX and _XXX functions.

* To interface your plugin's tables to IP-to-Country plugin's tables within SQL, 
  a function `Subselect` is published:

  `$subselect = PepakIpToCountry::Subselect($ip, $countryfield);`

  * `$ip` is a field that contains IP address in the form of one 32bit number, e.g.
    3740270592. If you store IP addresses in the usual form of four dot-separated
    numbers ('127.0.0.1'), you can use MySQL's function INET_ATON:
    `$ip = 'INET_ATON(my_ip_address_field)';`
    It is strongly recommended to use qualified field names (with tablename in
    front, separated by a dot):
    `$ip = 'INET_ATON(banlist.ip_address)';`

  * `$countryfield` is the field you want returned. It can be one of the following:
    `iso_name` - returns the same string as PepakIpToCountry::IP_to_Country_Full(...)
    `iso_code2` - returns the same string as PepakIpToCountry::IP_to_Country_XX(...)
    `iso_code3` - returns the same string as PepakIpToCountry::IP_to_Country_XX(...)

  Note: No checking or validation is done on either of these parameters. It is assumed
  they are *field names*, **NOT** strings. DO NOT EVER USE VALUES THAT YOUR USERS CAN
  INFLUENCE! This is important! If you let users supply arguments to Subselect(), you
  are letting them to do whatever they like with your database!

  This function only works with IPv4 addresses.

  Example usage:

  `if (class_exists('PepakIpToCountry'))
    $field = PepakIpToCountry("INET_ATON(b.ip_addr)", 'iso_code2');
  else
    $field = 'NULL';
  $sql = "SELECT b.*, ${field} country_code FROM ${wpdb->prefix}banlist b WHERE ...";`

Note: This plugin *requires* PHP5. If you want it to work with ZIP files
(e.g. for updating IP-country info from the default location), your PHP
must have extension php_zip enabled.

== Installation ==

1. Create a subdirectory in your '/wp-content/plugins/' directory and extract the plugin
   there. The plugin subdirectory can be anything you like - I use 'ip-to-country', but 
   the plugin should accept any name.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. You will see a 'IP-to-Country' item in your 'Settings' menu. It lets you choose
   the source of IP-country info and update the database with it. Please do so, as the
   necessary tables start empty after installation.

== Frequently Asked Questions ==

= I am having some problem with this plugin. Where can I get help? =

On my webpage I have a support forum http://forum.pepak.net with english section
where you can ask. At the time of writing, the forum is open for everybody without
registration.

== Screenshots ==

1. Administrative options

== Changelog ==

= 0.08 =

* Ukrainian translation by Michael Yunat.

= 0.07 =
* Updated the Serbo-Croatian translation.

= 0.06 =
* Serbo-Croatian translation by Andrijana Nikolic.
* Fixed warnings in WordPress 3.5 and newer.

= 0.05 =
* Romanian translation by Alexander Ovsov.

= 0.04 =
* Country name was added to atribute TITLE (until now it only appeared in ALT).

= 0.03 =
* Fixed a bug with some IPv4 addresses (signed vs. unsigned numbers), as 
  reported by Andrea.
* Added method IP_to_Country_Flag which returns an '<img>' tag suitable for
  displaying a small country flag. Icons come from http://www.famfamfam.com.
* Support for IPv6, using data from http://software77.net/geo-ip/.

= 0.02 =
* Added error reporting for imports.
* Removed memory-intensive preg_match_all and replaced with strpos/substr.

= 0.01 =
* Initial version.
