<?php
/**
 * Plugin Name: Simple Calendar - Blog Feed
 * Plugin URI:  https://simplecalendar.io
 * Description: A Simple Calendar add-on to display your WordPress blog posts in a calendar view.
 * Version:     1.0.1
 * Author:      SimpleCalendar
 * Author URI:  https://simplecalendar.io
 * Text Domain: simple-calendar-blog-feed
 * Domain Path: i18n/
 *
 * @copyright   2013-2023 Xtendify Technologies. All rights reserved.
 */

if (!defined("ABSPATH")) {
	exit();
} elseif (version_compare(PHP_VERSION, "5.3.0") !== -1) {
	if (!defined("SIMPLE_CALENDAR_BLOG_FEED_MAIN_FILE")) {
		define("SIMPLE_CALENDAR_BLOG_FEED_MAIN_FILE", __FILE__);
	}
	include_once "includes/add-on-blog-feed.php";
}
