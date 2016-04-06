<?php

/*
Plugin Name: IP-to-Country
Plugin URI: http://www.pepak.net/wordpress/ip-to-country-plugin
Description: Provide a simple interface for plugin authors to determine, in which country an IP is located.
Version: 0.08
Author: Pepak
Author URI: http://www.pepak.net
*/

/*  Copyright 2009, 2010  Pepak (email: wordpress@pepak.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!class_exists('PepakIpToCountry'))
{
	class PepakIpToCountry
	{

		const VERSION = '0.08';
		const PREFIX = 'ip2c_';
		const PREG_DELIMITER = '`';
		const GETTEXT_REALM = 'ip-to-country';
		const IP_TO_COUNTRY_IPV4_URL = 'http://software77.net/geo-ip/?DL=1';
		const IP_TO_COUNTRY_IPV6_URL = 'http://software77.net/geo-ip/?DL=7';
		const UPDATE_SOURCE_DEFAULT_IPV4_URL = 'default_ipv4_url';
		const UPDATE_SOURCE_DEFAULT_IPV6_URL = 'default_ipv6_url';
		const UPDATE_SOURCE_USER_IPV4_URL = 'user_ipv4_url';
		const UPDATE_SOURCE_USER_IPV6_URL = 'user_ipv6_url';
		const UPDATE_SOURCE_IPV4_UPLOAD = 'upload_ipv4';
		const UPDATE_SOURCE_IPV6_UPLOAD = 'upload_ipv6';

		protected $plugin_url = '';
		protected $plugin_dir = '';
		protected $plugin_dir_relative = '';

		public function PepakIpToCountry()
		{
			$this->plugin_url = WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__));
			$this->plugin_dir = WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__));
			if (strpos($this->plugin_dir, ABSPATH) === 0)
				$this->plugin_dir_relative = substr($this->plugin_dir, strlen(ABSPATH));
			else
				$this->plugin_dir_relative = $this->plugin_dir;
			register_activation_hook(__FILE__, array('PepakIpToCountry', 'Install'));
			add_action('init', array(&$this, 'ActionInit'));
			add_action('admin_menu', 'PepakIpToCountry_BuildAdminMenu');
		}

		public static function Install()
		{
			global $wpdb;
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                        // Table for country names and ISO codes
			$table_countries = $wpdb->prefix . self::PREFIX . 'countries';
			$sql = "CREATE TABLE ${table_countries} (
				id INTEGER NOT NULL AUTO_INCREMENT,
				iso_name VARCHAR(64) NOT NULL UNIQUE,
				iso_code2 VARCHAR(2) NOT NULL,
				iso_code3 VARCHAR(3) NOT NULL,
				PRIMARY KEY  id (id)
				);";
			dbDelta($sql);
                        // Table for IPv4 address ranges
			$table_addresses = $wpdb->prefix . self::PREFIX . 'addresses';
			$sql = "CREATE TABLE ${table_addresses} (
				ip_from NUMERIC(10) NOT NULL,
				ip_to NUMERIC(10) NOT NULL,
				country INTEGER,
				PRIMARY KEY  ip (ip_from, ip_to)
				);";
			dbDelta($sql);
                        // Table for IPv6 address ranges
			$table_ipv6 = $wpdb->prefix . self::PREFIX . 'ipv6';
			$sql = "CREATE TABLE ${table_ipv6} (
				ip_from VARCHAR(32) NOT NULL,
				ip_to VARCHAR(32) NOT NULL,
				country INTEGER,
				PRIMARY KEY  ipv6 (ip_from, ip_to)
				);";
			dbDelta($sql);
                        // Default options
			update_option(self::PREFIX . 'table_countries', $table_countries);
			update_option(self::PREFIX . 'table_addresses', $table_addresses);
			update_option(self::PREFIX . 'table_ipv6', $table_ipv6);
			update_option(self::PREFIX . 'version', self::VERSION);
			add_option(self::PREFIX . 'update_ipv4_url', self::IP_TO_COUNTRY_IPV4_URL);
			add_option(self::PREFIX . 'update_ipv6_url', self::IP_TO_COUNTRY_IPV6_URL);
			add_option(self::PREFIX . 'update_source', self::UPDATE_SOURCE_DEFAULT_IPV4_URL);
		}

		protected function table_countries()
		{
			static $table = null;
			if ($table == null)
				$table = get_option(self::PREFIX . 'table_countries');
			return $table;
		}

		protected function table_addresses()
		{
			static $table = null;
			if ($table == null)
				$table = get_option(self::PREFIX . 'table_addresses');
			return $table;
		}

		protected function table_ipv6()
		{
			static $table = null;
			if ($table == null)
				$table = get_option(self::PREFIX . 'table_ipv6');
			return $table;
		}

		private static function IPv6Part_to_String($ip)
		{
			$addr = '';
			$parts = explode(':', $ip);
			foreach ($parts as $part)
				if ($part)
					$addr .= str_pad($part, 4, '0', STR_PAD_LEFT);
			return $addr;
		}

		private static function IPv6_to_String($ip)
		{
			$ipv6 = '';
			// Full IPv6 address
			if ($ip == '::')
			{
				$ipv6 = str_repeat('0', 32);
			}
			elseif (preg_match('~^([0-9a-f]{1,4}:){7}[0-9a-f]{1,4}$~', $ip))
			{
				$ipv6 = self::IPv6Part_to_String($ip);
			}
			// IPv6 address with skipped zeroes
			elseif (preg_match('~^((?:[0-9a-f]{1,4}:)*|:)((?::|:[0-9a-f]{1,4})*)$~', $ip, $ipv6parts))
			{
				$first = self::IPv6Part_to_String($ipv6parts[1]);
				$last = self::IPv6Part_to_String($ipv6parts[2]);
				$n = 32 - (strlen($first) + strlen($last));
				if ($n == 0)
					$ipv6 = $first . $last;
				elseif ($n > 0)
					$ipv6 = $first . str_repeat('0', $n) . $last;
			}
			return $ipv6;
		}

		protected static function IP_to_Country_Info($ip = null)
		{
			global $wpdb;
			static $ipv4cache = array();
			static $ipv6cache = array();
			if ($ip === null)
				$ip = $_SERVER['REMOTE_ADDR'];
			$ipv4 = '';
			$ipv6 = '';
			$info = null;
                        // Numeric IPv4 address
			if (preg_match('~^([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+)$~', $ip, $ipv4))
			{
				$ipv4 = ip2long($ip);
			}
			else
				$ipv6 = self::IPv6_to_String($ip);
			if ($ipv4)
			{
				if (!isset($ipv4cache[$ip]))
				{
					$sql = "SELECT c.iso_name, c.iso_code2, c.iso_code3
						FROM " . self::table_addresses() . " a
						JOIN " . self::table_countries() . " c ON c.id=a.country
						WHERE a.ip_from<=%u AND a.ip_to>=%u
						LIMIT 1;";
					$country = $wpdb->get_row($wpdb->prepare($sql, $ipv4, $ipv4), ARRAY_N);
					$info = $ipv4cache[$ip] = array('iso_name' => $country[0], 'iso_code2' => $country[1], 'iso_code3' => $country[2]);
				} else
					$info = $ipv4cache[$ip];
			}
			elseif ($ipv6)
			{
				if (!isset($ipv6cache[$ip]))
				{
					$sql = "SELECT c.iso_name, c.iso_code2, c.iso_code3
						FROM " . self::table_ipv6() . " a
						JOIN " . self::table_countries() . " c ON c.id=a.country
						WHERE a.ip_from<='%s' AND a.ip_to>='%s'
						LIMIT 1;";
					$country = $wpdb->get_row($wpdb->prepare($sql, $ipv6, $ipv6), ARRAY_N);
					$info = $ipv6cache[$ip] = array('iso_name' => $country[0], 'iso_code2' => $country[1], 'iso_code3' => $country[2]);
				} else
					$info = $ipv6cache[$ip];
			}
			return $info;
		}

		public static function IP_to_Country_XX($ip = null)
		{
			$country = self::IP_to_Country_Info($ip);
			return $country ? $country['iso_code2'] : null;
		}

		public static function IP_to_Country_XXX($ip = null)
		{
			$country = self::IP_to_Country_Info($ip);
			return $country ? $country['iso_code3'] : null;
		}

		public static function IP_to_Country_Full($ip = null)
		{
			$country = self::IP_to_Country_Info($ip);
			return $country ? $country['iso_name'] : null;
		}

		public static function IP_to_Country($ip = null)
		{
			return self::IP_to_Country_XX($ip);
		}

		public static function IP_to_Country_Flag($ip = null)
		{
			$flag = FALSE;
			$info = self::IP_to_Country_Info($ip);
			if ($info)
			{
				$country = strtolower($info['iso_code2']);
				$plugin_dir = WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__));
				$plugin_url = WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__));
				if ($country && file_exists($plugin_dir.'/flags/'.$country.'.png'))
					$flag = '<img src="' . htmlspecialchars($plugin_url) . '/flags/' . $country . '.png" alt="' . htmlspecialchars($info['iso_name']) . '" title="' . htmlspecialchars($info['iso_name']) . '" />';
			}
			return $flag;
		}

                // Updates IPv4 addresses and country data
		public static function Update_Addresses_From_CountryData($data = array(), $clean = TRUE, $perform_clean = TRUE)
		{
			global $wpdb;
			@ini_set('display_errors', 'on');
			$wpdb->show_errors();
			$countries = array();
			$rows = $wpdb->get_results($wpdb->prepare("SELECT id, iso_code2 FROM " . self::table_countries(), array()), ARRAY_N);
			if ($rows)
				foreach ($rows as $row)
					$countries[$row[1]] = $row[0];
			$count = 0;
			if ($perform_clean AND $clean)
				$wpdb->query("DELETE FROM " . self::table_addresses() . ";");

			$insert_head = "INSERT INTO " . self::table_addresses() . " (ip_from, ip_to, country)\nVALUES";
			$insert_sql = "";
			foreach ($data as $row)
			{
				$ipfrom = $wpdb->prepare('%s', sprintf('%010.10s', $row['ip_from']));
				$ipto = $wpdb->prepare('%s', sprintf('%010.10s', $row['ip_to']));
				if ($perform_clean AND !$clean)
				{
					$sql = "DELETE FROM " . self::table_addresses() . "
						WHERE (%u BETWEEN ip_from AND ip_to) OR (%u BETWEEN ip_from AND ip_to) OR (%u<ip_from AND %u>ip_to);";
					$wpdb->query($wpdb->prepare($sql, $ipfrom, $ipto, $ipfrom, $ipto));
				}
				if (!isset($countries[$row['country_code2']]))
				{
					$wpdb->insert(self::table_countries(), 
						array('iso_name'=>utf8_encode($row['country_name']), 'iso_code2'=>$row['country_code2'], 'iso_code3'=>$row['country_code3']), 
						array('%s', '%s', '%s')
						);
					$country = $wpdb->insert_id;
					$countries[$row['country_code2']] = $country;
				}
				else
					$country = $countries[$row['country_code2']];
				if ($insert_sql)
					$insert_sql .= ",\n(${ipfrom}, ${ipto}, ${country})";
				else
					$insert_sql = "\n(${ipfrom}, ${ipto}, ${country})";
				$count++;
				if (($count % 1000) == 0)
				{
					$wpdb->query($insert_head . $insert_sql . ';');
					$insert_sql = "";
				}
			}
			if ($insert_sql)
			{
				$wpdb->query($insert_head . $insert_sql . ';');
			}
			return $count;
		}

                // Updates IPv6 addresses. Country data must already exist (e.g. from Update_Addresses_From_CountryData
		public static function Update_IPv6_From_CountryData($data = array(), $clean = TRUE, $perform_clean = TRUE)
		{
			global $wpdb;
			@ini_set('display_errors', 'on');
			$wpdb->show_errors();
			$countries = array();
			$rows = $wpdb->get_results($wpdb->prepare("SELECT id, iso_code2 FROM " . self::table_countries()), ARRAY_N);
			if ($rows)
				foreach ($rows as $row)
					$countries[$row[1]] = $row[0];
			$count = 0;
			if ($perform_clean AND $clean)
				$wpdb->query("DELETE FROM " . self::table_ipv6() . ";");

			$insert_head = "INSERT INTO " . self::table_ipv6() . " (ip_from, ip_to, country)\nVALUES";
			$insert_sql = "";
			foreach ($data as $row)
			{
				$ipfrom = $wpdb->prepare(self::IPv6_to_string($row['ip_from']));
				$ipto = $wpdb->prepare(self::IPv6_to_string($row['ip_to']));
				if ($ipfrom AND $ipto)
				{
					if ($perform_clean AND !$clean)
					{
						$sql = "DELETE FROM " . self::table_ipv6() . "
							WHERE ('%s' BETWEEN ip_from AND ip_to) OR ('%s' BETWEEN ip_from AND ip_to) OR ('%s'<ip_from AND '%s'>ip_to);";
						$wpdb->query($wpdb->prepare($sql, $ipfrom, $ipto, $ipfrom, $ipto));
					}
					$country = intval($countries[$row['country_code2']]);
					if ($country)
					{
						if ($insert_sql)
							$insert_sql .= ",\n('${ipfrom}', '${ipto}', ${country})";
						else
							$insert_sql = "\n('${ipfrom}', '${ipto}', ${country})";
						$count++;
						if (($count % 1000) == 0)
						{
							$wpdb->query($insert_head . $insert_sql . ';');
							$insert_sql = "";
						}
					}
				}
			}
			if ($insert_sql)
			{
				$wpdb->query($insert_head . $insert_sql . ';');
			}
			return $count;
		}

		// Parse a CSV file of IPv4 addresses and country info
		// Supported formats:
		// http://ip-to-country.webhosting.info
		// http://software77.net/geo-ip/
		public static function Update_Addresses_From_CSV_String($csv)
		{
			@ini_set('display_errors', 'on');
			$countrydata = array();
			global $wpdb;
			$wpdb->query("DELETE FROM " . self::table_addresses() . ";");
			$wpdb->query("DELETE FROM " . self::table_countries() . ";");
			$offset = 0;
			$length = strlen($csv);
			$lastipfrom = '';
			while ($offset < $length)
			{
				$count = 0;
				$pos = strpos($csv, "\n", $offset);
				if ($pos === FALSE)
				{
					$line = trim(substr($csv, $offset));
					$offset = $length;
				}
				else
				{
					$line = trim(substr($csv, $offset, $pos-$offset));
					$offset = $pos+1;
				}
				if ($line AND ($line[0] != '#'))
				{
					$linestart = 0;
					if ($line[0] == '"')
						$linestart++;
					$linelen = strlen($line);
					if ($line[$linelen-1] == '"')
						$linelen--;
					$csvcols = explode('","', substr($line, $linestart, $linelen-1));
					$countryinfo = FALSE;
					switch (count($csvcols))
					{
						case 5: // http://ip-to-country.webhosting.info
							$countryinfo = array('ip_from'=>$csvcols[0], 'ip_to'=>$csvcols[1], 'country_code2'=>$csvcols[2], 'country_code3'=>$csvcols[3], 'country_name'=>$csvcols[4]);
							break;

						case 7: // http://software77.net/geo-ip/
							$countryinfo = array('ip_from'=>$csvcols[0], 'ip_to'=>$csvcols[1], 'country_code2'=>$csvcols[4], 'country_code3'=>$csvcols[5], 'country_name'=>$csvcols[6]);
							break;

					}
					if ($countryinfo)
						if ($countryinfo['ip_from'] != $lastipfrom)
						{
							$countrydata[] = $countryinfo;
							$lastipfrom = $countryinfo['ip_from'];
						}
					if (count($countrydata) > 1000)
					{
						//die();
						//echo "<pre>"; print_r($countrydata); echo "<pre><hr>";
						$count += self::Update_Addresses_From_CountryData($countrydata, TRUE, FALSE);
						$countrydata = array();
					}
				}
			}
			if (count($countrydata) > 0)
				$count += self::Update_Addresses_From_CountryData($countrydata, TRUE, FALSE);
			return $count;
		}

		// Parse a CSV file of IPv6 addresses and country info
		// Supported formats:
		// http://software77.net/geo-ip/ (the Ranges format)
		public static function Update_IPv6_From_CSV_String($csv)
		{
			@ini_set('display_errors', 'on');
			$countrydata = array();
			global $wpdb;
			$wpdb->query("DELETE FROM " . self::table_ipv6() . ";");
			$offset = 0;
			$length = strlen($csv);
			$lastipfrom = '';
			while ($offset < $length)
			{
				$count = 0;
				$pos = strpos($csv, "\n", $offset);
				if ($pos === FALSE)
				{
					$line = trim(substr($csv, $offset));
					$offset = $length;
				}
				else
				{
					$line = trim(substr($csv, $offset, $pos-$offset));
					$offset = $pos+1;
				}
				if ($line AND ($line[0] != '#'))
				{
					$csvcols = explode(',', substr($line, $linestart, $linelen-1));
					$countryinfo = FALSE;
					switch (count($csvcols))
					{
						case 4: // http://software77.net/geo-ip/
							$sep = strpos($csvcols[0], '-');
							if ($sep)
							{
								$countryinfo = array('ip_from'=>substr($csvcols[0], 0, $sep), 'ip_to'=>substr($csvcols[0], $sep+1), 'country_code2'=>$csvcols[1]);
							}
							break;

					}
					if ($countryinfo)
						if ($countryinfo['ip_from'] != $lastipfrom)
						{
							$countrydata[] = $countryinfo;
							$lastipfrom = $countryinfo['ip_from'];
						}
					if (count($countrydata) > 1000)
					{
						//echo "<pre>"; print_r($countrydata); echo "<pre><hr>"; die();
						$count += self::Update_IPv6_From_CountryData($countrydata, TRUE, FALSE);
						$countrydata = array();
					}
				}
			}
			if (count($countrydata) > 0)
				$count += self::Update_IPv6_From_CountryData($countrydata, TRUE, FALSE);
			return $count;
		}

		public static function Update_Addresses_From_File($filename, $original_filename, $ipv6 = FALSE)
		{
			@ini_set('display_errors', 'on');
			$csv = '';
			if (strcasecmp(substr($original_filename, -4), '.zip') == 0)
			{
				$zip = zip_open($filename);
				if ($entry = zip_read($zip))
				{
					zip_entry_open($zip, $entry, 'r');
					$csv = zip_entry_read($entry, zip_entry_filesize($entry));
				}
			}
			else //if (strcasecmp(substr($original_filename, -3), '.gz') == 0)
			{
				if ($fp = gzopen($filename, 'rb'))
				{
					$csv = gzread($fp, 16*1024*1024);
					gzclose($fp);
				}
				
			}
			//else
			//	$csv = file_get_contents($filename);
			if ($ipv6)
				return self::Update_IPv6_From_CSV_String($csv);
			else
				return self::Update_Addresses_From_CSV_String($csv);
		}

		public static function Update_Addresses_From_URL($url, $ipv6 = FALSE)
		{
			@ini_set('display_errors', 'on');
			$filename = download_url($url);
			if (is_string($filename))
			{
				$count = self::Update_Addresses_From_File($filename, $url, $ipv6);
				unlink($filename);
			}
			else
				$count = 0;
			return $count;
		}

                // Note: Only works with IPv4 addresses
		public static function Subselect($ip, $country_field)
		{
			$table_addresses = self::table_addresses();
			$table_countries = self::table_countries();
			$sql = "("
				."SELECT ${table_countries}.${country_field}"
				." FROM ${table_addresses}"
				." LEFT JOIN ${table_countries} ON ${table_countries}.id=${table_addresses}.country"
				." WHERE ${ip} BETWEEN ${table_addresses}.ip_from AND ${table_addresses}.ip_to"
				." LIMIT 1"
				.")";
			return $sql;
		}

		public function ActionInit()
		{
			// Function is called in 'init' hook. It checks for download and if so, stops normal WordPress processing
			// and replaces it with its monitoring functions.
			$currentLocale = get_locale();
			if(!empty($currentLocale))
			{
				$moFile = $this->plugin_dir . "/lang/" . $currentLocale . ".mo";
				if(@file_exists($moFile) && is_readable($moFile))
					load_textdomain(self::GETTEXT_REALM, $moFile);
			}
		}

		public function ActionHead()
		{
			echo '<link type="text/css" rel="stylesheet" href="' . $this->plugin_url . '/css/ip2c.css" />'."\n";
		}

		public function AdminPanel()
		{
			// Function draws the admin panel.
			// First, post any modified options
			$message = '';
			if (($save = isset($_POST['IpToCountry_Save'])) || ($perform = isset($_POST['IpToCountry_Perform'])))
			{
				// Read options from the form
				$update_source = $_POST['update_source'];
				$update_ipv4_url = $_POST['update_ipv4_url'];
				$update_ipv6_url = $_POST['update_ipv6_url'];
				if (get_magic_quotes_gpc())
				{
					$update_ipv4_url = stripslashes($update_ipv4_url);
					$update_ipv6_url = stripslashes($update_ipv6_url);
				}
				// Update settings
				update_option(self::PREFIX . 'update_source', $update_source);
				update_option(self::PREFIX . 'update_ipv4_url', $update_ipv4_url);
				update_option(self::PREFIX . 'update_ipv6_url', $update_ipv6_url);
				// Perform update if requested
				if ($perform)
				{
					switch ($update_source)
					{
						case self::UPDATE_SOURCE_DEFAULT_IPV4_URL:
							if (self::Update_Addresses_From_URL(self::IP_TO_COUNTRY_IPV4_URL, FALSE))
								$message = sprintf(__('Successfully updated IPv4 country info from URL <strong>%s</strong>.', self::GETTEXT_REALM), self::IP_TO_COUNTRY_IPV4_URL);
							else
								$message = sprintf(__('Failed to update IPv4 country info from URL <strong>%s</strong>.', self::GETTEXT_REALM), self::IP_TO_COUNTRY_IPV4_URL);
							break;
						case self::UPDATE_SOURCE_DEFAULT_IPV6_URL:
							if (self::Update_Addresses_From_URL(self::IP_TO_COUNTRY_IPV6_URL, TRUE))
								$message = sprintf(__('Successfully updated IPV6 country info from URL <strong>%s</strong>.', self::GETTEXT_REALM), self::IP_TO_COUNTRY_IPV6_URL);
							else
								$message = sprintf(__('Failed to update IPV6 country info from URL <strong>%s</strong>.', self::GETTEXT_REALM), self::IP_TO_COUNTRY_IPV6_URL);
							break;
						case self::UPDATE_SOURCE_USER_IPV4_URL:
							if (self::Update_Addresses_From_URL($update_ipv4_url, FALSE))
								$message = sprintf(__('Successfully updated IPv4 country info from URL <strong>%s</strong>.', self::GETTEXT_REALM), htmlspecialchars($update_ipv4_url));
							else
								$message = sprintf(__('Failed to update IPv4 country info from URL <strong>%s</strong>.', self::GETTEXT_REALM), htmlspecialchars($update_ipv4_url));
							break;
						case self::UPDATE_SOURCE_USER_IPV6_URL:
							if (self::Update_Addresses_From_URL($update_ipv6_url, TRUE))
								$message = sprintf(__('Successfully updated IPV6 country info from URL <strong>%s</strong>.', self::GETTEXT_REALM), htmlspecialchars($update_ipv6_url));
							else
								$message = sprintf(__('Failed to update IPV6 country info from URL <strong>%s</strong>.', self::GETTEXT_REALM), htmlspecialchars($update_ipv6_url));
							break;
						case self::UPDATE_SOURCE_IPV4_UPLOAD:
							$file = strval($_FILES['update_ipv4_file']['tmp_name']);
							$userfile = strval($_FILES['update_ipv4_file']['name']);
							if ($file AND file_exists($file) AND self::Update_Addresses_From_File($file, $userfile, FALSE))
								$message = sprintf(__('Successfully updated IPv4 country info from file <strong>%s</strong>.', self::GETTEXT_REALM), htmlspecialchars($userfile));
							else
								$message = sprintf(__('Failed to update IPv4 country info from file <strong>%s</strong>.', self::GETTEXT_REALM), htmlspecialchars($userfile));
							unlink($file);
							break;
						case self::UPDATE_SOURCE_IPV6_UPLOAD:
							$file = strval($_FILES['update_ipv6_file']['tmp_name']);
							$userfile = strval($_FILES['update_ipv6_file']['name']);
							if ($file AND file_exists($file) AND self::Update_Addresses_From_File($file, $userfile, TRUE))
								$message = sprintf(__('Successfully updated IPV6 country info from file <strong>%s</strong>.', self::GETTEXT_REALM), htmlspecialchars($userfile));
							else
								$message = sprintf(__('Failed to update IPV6 country info from file <strong>%s</strong>.', self::GETTEXT_REALM), htmlspecialchars($userfile));
							unlink($file);
							break;
					}
				}
			}
			// Load options from the database
			$update_source = get_option(self::PREFIX . 'update_source');
			$update_ipv4_url = get_option(self::PREFIX . 'update_ipv4_url');
			$update_ipv6_url = get_option(self::PREFIX . 'update_ipv6_url');
			// Build the form
			?>
<div class="wrap">
<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">
	<h2><?php echo __('IP-to-Country options', self::GETTEXT_REALM); ?></h2>
	<p class="message"><?php echo $message; ?></p>
	<h3><?php echo __('Source of IP-to-Country data', self::GETTEXT_REALM); ?></h3>
	<p><input type="radio" name="update_source" value="<?php echo self::UPDATE_SOURCE_DEFAULT_IPV4_URL; ?>"<?php echo ($update_source==self::UPDATE_SOURCE_DEFAULT_IPV4_URL) ? ' checked="checked"' : ''; ?> />
		<?php echo __('Default IPv4 URL:', self::GETTEXT_REALM); ?>
		<strong><?php echo self::IP_TO_COUNTRY_IPV4_URL; ?></strong>
	</p>
	<p><input type="radio" name="update_source" value="<?php echo self::UPDATE_SOURCE_DEFAULT_IPV6_URL; ?>"<?php echo ($update_source==self::UPDATE_SOURCE_DEFAULT_IPV6_URL) ? ' checked="checked"' : ''; ?> />
		<?php echo __('Default IPv6 URL:', self::GETTEXT_REALM); ?>
		<strong><?php echo self::IP_TO_COUNTRY_IPV6_URL; ?></strong>
	</p>
	<p><?php printf(__('Note: Make sure you read and follow the service\'s <a href="%s">license terms</a>, especially the part about download limits!'), 'http://software77.net/geo-ip/?license'); ?></p>
	<p><?php echo __('IPv4 addresses must be loaded first, because the IPv6 database does not carry a list of countries with it.'); ?></p>
	<p><input type="radio" name="update_source" value="<?php echo self::UPDATE_SOURCE_USER_IPV4_URL; ?>"<?php echo ($update_source==self::UPDATE_SOURCE_USER_IPV4_URL) ? ' checked="checked"' : ''; ?> width="50" />
		<?php echo __('User IPv4 URL:', self::GETTEXT_REALM) ?> 
		<input type="text" name="update_ipv4_url" value="<?php echo htmlspecialchars($update_ipv4_url); ?>" size="60" />
	</p>
	<p><input type="radio" name="update_source" value="<?php echo self::UPDATE_SOURCE_USER_IPV6_URL; ?>"<?php echo ($update_source==self::UPDATE_SOURCE_USER_IPV6_URL) ? ' checked="checked"' : ''; ?> width="50" />
		<?php echo __('User IPv6 URL:', self::GETTEXT_REALM) ?> 
		<input type="text" name="update_ipv6_url" value="<?php echo htmlspecialchars($update_ipv6_url); ?>" size="60" />
	</p>
	<p><input type="radio" name="update_source" value="<?php echo self::UPDATE_SOURCE_IPV4_UPLOAD; ?>"<?php echo ($update_source==self::UPDATE_SOURCE_IPV4_UPLOAD) ? ' checked="checked"' : ''; ?> />
		<?php echo __('Uploaded file with IPv4:', self::GETTEXT_REALM); ?>
		<input type="file" name="update_ipv4_file" size="60" />
	</p>
	<p><input type="radio" name="update_source" value="<?php echo self::UPDATE_SOURCE_IPV6_UPLOAD; ?>"<?php echo ($update_source==self::UPDATE_SOURCE_IPV6_UPLOAD) ? ' checked="checked"' : ''; ?> />
		<?php echo __('Uploaded file with IPv6:', self::GETTEXT_REALM); ?>
		<input type="file" name="update_ipv6_file" size="60" />
	</p>
	<div class="submit">
		<input type="submit" name="IpToCountry_Save" value="<?php echo __("Save settings", self::GETTEXT_REALM) ?>" />
		<input type="submit" name="IpToCountry_Perform" value="<?php echo __("Save settings and update", self::GETTEXT_REALM) ?>" />
	</div>
</form>
</div><?php
		}

	}
}

if (!isset($pepakiptocountry))
	$pepakiptocountry = new PepakIpToCountry();

if (!function_exists('PepakIpToCountry_BuildAdminMenu'))
{
	function PepakIpToCountry_BuildAdminMenu()
	{
		global $pepakiptocountry;
		if (isset($pepakiptocountry))
		{
			$options_page = add_options_page(__('IP-to-Country options', PepakIpToCountry::GETTEXT_REALM), __('IP-to-Country', PepakIpToCountry::GETTEXT_REALM), 'manage_options', basename(__FILE__), array(&$pepakiptocountry, 'AdminPanel'));
		}
	}
}

if (!function_exists('sys_get_temp_dir'))
{
	function sys_get_temp_dir() {
		if ($temp = getenv('TMP'))
			return $temp;
		if ($temp = getenv('TEMP'))
			return $temp;
		if ($temp = getenv('TMPDIR'))
			return $temp;
		$temp = tempnam(__FILE__,'');
		if (file_exists($temp))
		{
			unlink($temp);
			return dirname($temp);
		}
		return null;
	}
}
