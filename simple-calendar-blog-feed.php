<?php
/**
 * Plugin Name: Simple Calendar - Blog Feed
 * Plugin URI:  https://wordpress.org/plugins/simple-calendar-blog-feed/
 * Description: Use Simple Calendar with Advanced Custom Fields.
 *
 * Version:     1.0.0
 *
 * Author:      Moonstone Media
 * Author URI:  http://moonstonemediagroup.com
 *
 * Text Domain: simple-calendar
 * Domain Path: /languages/
 *
 * @package     SimpleCalendar/BlogFeed
 * @copyright   2014-2015 Moonstone Media/Phil Derksen. All rights reserved.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( version_compare( PHP_VERSION, '5.3.0' ) !== - 1 ) {

	if ( ! defined( 'SIMCAL_BLOG_FEED_MAIN_FILE' ) ) {
		define( 'SIMCAL_BLOG_FEED_MAIN_FILE', __FILE__ );
	}

	include_once 'includes/add-on-blog-feed.php';
}