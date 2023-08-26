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
class Add_On_Blog_Feed
{
	/**
	 * Plugin add-on name.
	 *
	 * @access public
	 * @var string
	 */
	public $name = "Blog Feed";

	/**
	 * Load plugin.
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		register_activation_hook(SIMPLE_CALENDAR_BLOG_FEED_MAIN_FILE, [
			$this,
			"activate",
		]);

		add_action("init", [$this, "l10n"]);
		add_action("plugins_loaded", [$this, "init"]);
	}

	/**
	 * Load Localization files.
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function l10n()
	{
		load_plugin_textdomain(
			"simple-calendar-blog-feed",
			false,
			plugin_basename(SIMPLE_CALENDAR_BLOG_FEED_MAIN_FILE) . "/languages/"
		);
	}

	/**
	 * Init.
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function init()
	{
		if (class_exists("SimpleCalendar\Plugin")) {
			include_once "feeds/blog-feed.php";

			// Add new feed type.
			add_filter(
				"simcal_get_feed_types",
				function ($feed_types) {
					return array_merge($feed_types, ["blog-feed"]);
				},
				10,
				1
			);

			add_action("simcal_load_objects ", function () {
				new Blog_Feed();
			});
		} else {
			$name = $this->name;

			add_action("admin_notices", function () use ($name) {
				echo '<div class="error"><p>' .
					sprintf(
						__(
							"The Simple Calendar %s add-on requires the Simple Calendar core plugin to be installed and activated.",
							"simple-calendar-blog-feed"
						),
						$name
					) .
					"</p></div>";
			});
		}
	}

	/**
	 * Upon plugin activation hook callback.
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function activate()
	{
		if (!get_term_by("slug", "blog-feed", "calendar_feed")) {
			wp_insert_term("blog-feed", "calendar_feed");
		}
	}
}

new Add_On_Blog_Feed();
