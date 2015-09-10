<?php
/**
 * Simple Calendar - Blog Feed add on
 *
 * @package SimpleCalendar/BlogFeed
 */
namespace SimpleCalendar;
use SimpleCalendar\Feeds\Blog_Feed;

/**
 * A Blog Feed add on for Simple Calendar.
 */
class Add_On_Blog_Feed {

	/**
	 * Load plugin.
	 */
	public function __construct() {

		register_activation_hook( SIMCAL_BLOG_FEED_MAIN_FILE, array( $this, 'activate' ) );

		add_action( 'init', array( $this, 'l10n' ) );
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	/**
	 * Load Localization files.
	 */
	public function l10n() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'simple-calendar' );
		load_textdomain( 'simple-calendar', WP_LANG_DIR . '/google-calendar-events/simple-calendar-' . $locale . '.mo' );
		load_plugin_textdomain( 'simple-calendar', false, plugin_basename( SIMCAL_BLOG_FEED_MAIN_FILE ) . 'languages' );
	}

	/**
	 * Init.
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
	 * Upon plugin activation.
	 */
	public static function activate() {
		if ( ! get_term_by( 'slug', 'blog-feed', 'calendar_feed' ) ) {
			wp_insert_term( 'blog-feed', 'calendar_feed' );
		}
	}

}

new Add_On_Blog_Feed();