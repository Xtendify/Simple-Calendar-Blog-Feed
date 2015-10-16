<?php
/**
 * Simple Calendar - Blog Feed add on
 *
 * @package     SimpleCalendar/Extensions
 * @subpackage  BlogFeed
 */
namespace SimpleCalendar;
use SimpleCalendar\Feeds\Blog_Feed;

/**
 * A Blog Feed add on for Simple Calendar.
 *
 * @since 1.0.0
 */
class Add_On_Blog_Feed {

	/**
	 * Load plugin.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		register_activation_hook( SIMPLE_CALENDAR_BLOG_FEED_MAIN_FILE, array( $this, 'activate' ) );

		add_action( 'init', array( $this, 'l10n' ) );
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	/**
	 * Load Localization files.
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function l10n() {
		load_plugin_textdomain( 'simple-calendar-blog-feed', false, plugin_basename( SIMPLE_CALENDAR_BLOG_FEED_MAIN_FILE ) . '/languages/' );
	}

	/**
	 * Init.
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function init() {
		if ( class_exists( 'SimpleCalendar\Plugin' ) ) {

			include_once 'feeds/blog-feed.php';

			// Add new feed type.
			add_filter( 'simcal_get_feed_types', function( $feed_types ) {
				return array_merge( $feed_types, array(
					'blog-feed',
				) );
			}, 10, 1 );

			add_action( 'simcal_load_objects ', function() {
				new Blog_Feed();
			} );

		}
	}

	/**
	 * Upon plugin activation hook callback.
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function activate() {
		if ( ! get_term_by( 'slug', 'blog-feed', 'calendar_feed' ) ) {
			wp_insert_term( 'blog-feed', 'calendar_feed' );
		}
	}

}

new Add_On_Blog_Feed();
