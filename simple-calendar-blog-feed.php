<?php
/**
 * Plugin Name: Simple Calendar - Blog Feed
 * Plugin URI:  https://wordpress.org/plugins/simple-calendar-blog-feed/
 * Description: Display your WordPress blog posts as Simple Calendar events.
 *
 * Version:     1.0.0
 *
 * Author:      Moonstone Media
 * Author URI:  http://moonstonemediagroup.com
 *
 * Text Domain: simple-calendar-blog-feed
 * Domain Path: /languages/
 *
 * @package     SimpleCalendar/Extensions
 * @subpackage  BlogFeed
 * @copyright   2014-2015 Moonstone Media/Phil Derksen. All rights reserved.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} elseif ( version_compare( PHP_VERSION, '5.3.0' ) !== - 1 ) {
	if ( ! defined( 'SIMPLE_CALENDAR_BLOG_FEED_MAIN_FILE' ) ) {
		define( 'SIMPLE_CALENDAR_BLOG_FEED_MAIN_FILE', __FILE__ );
	}
	include_once 'includes/add-on-blog-feed.php';
}
