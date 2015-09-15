<?php
/**
 * Blog Feed
 *
 * @package SimpleCalendar/BlogFeed/Feeds
 */
namespace SimpleCalendar\Feeds;

use Carbon\Carbon;
use SimpleCalendar\Abstracts\Feed;
use SimpleCalendar\Events\Event;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Blog Feed.
 *
 * A calendar feed using blog posts for events.
 */
class Blog_Feed extends Feed {

	/**
	 * Posts query args.
	 *
	 * @access private
	 * @var array
	 */
	private $query_args = array();

	/**
	 * Set properties.
	 *
	 * @param string|\SimpleCalendar\Abstracts\Calendar $calendar
	 */
	public function __construct( $calendar = '' ) {

		parent::__construct( $calendar );

		$this->type = 'blog-feed';
		$this->name = __( 'Blog Posts', 'simple-calendar' );

		if ( $this->calendar_id > 0 ) {
			$this->feed_id = $this->calendar_id;
			$this->set_query_args();
			$this->events  = $this->get_events();
		}

		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {

			if ( simcal_is_admin_screen() !== false ) {
				add_filter( 'simcal_settings_meta_tabs_li', array( $this, 'add_settings_meta_tab_li' ), 10, 1 );
				add_action( 'simcal_settings_meta_panels', array( $this, 'add_settings_meta_panel' ), 10, 1 );
			}

			add_action( 'simcal_process_settings_meta', array( $this, 'process_meta' ), 10, 1 );
		}
	}

	/**
	 * The blog feed has no settings.
	 *
	 * @return array
	 */
	public function settings_fields() {
		return array();
	}

	/**
	 * Add a tab to the settings meta box.
	 *
	 * @param  array $tabs
	 *
	 * @return array
	 */
	public function add_settings_meta_tab_li( $tabs ) {
		return array_merge( $tabs, array(
			'blog-feed' => array(
				'label'  => $this->name,
				'target' => 'blog-feed-settings-panel',
				'class'  => array(
					'simcal-feed-type',
					'simcal-feed-type-blog-feed',
				),
				'icon'   => 'simcal-icon-docs'
			),
		) );
	}

	/**
	 * Add a panel to the settings meta box.
	 *
	 * @param int $post_id
	 */
	public function add_settings_meta_panel( $post_id ) {
		
		?>
		<div id="blog-feed-settings-panel" class="simcal-panel">
			<table>
				<thead>
					<tr><th colspan="2"><?php _e( 'Blog Feed settings', 'simple-calendar' ); ?></th></tr>
				</thead>
				<tbody class="simcal-panel-section">
					<tr class="simcal-panel-field">
						<th><label for="_blog_feed_posts_source"><?php _e( 'Posts', 'simple-calendar' ); ?></label></th>
						<td>
							<?php

							$source = esc_attr( get_post_meta( $post_id, '_blog_feed_posts_source', true ) );
							$source = empty( $source ) ? 'all' : $source;

							?>
							<select
								name="_blog_feed_posts_source"
								id="_blog_feed_posts_source"
								class="simcal-field simcal-field-select simcal-field-inline simcal-field-show-next"
								data-show-next-if-value="category">
								<option value="all" <?php selected( 'all', $source, true ); ?>><?php _e( 'All posts', 'simple-calendar' ); ?></option>
								<option value="category" data-show-next="_blog_feed_posts_category" <?php selected( 'category', $source, true ); ?>><?php _e( 'Posts in category', 'simple-calendar' ); ?></option>
							</select>
							<br><br>
							<?php

							$meta     = get_post_meta( $post_id, '_blog_feed_posts_category', true );
							$category = $meta && is_array( $meta ) ? implode( ',', array_map( 'absint', $meta ) ): '';

							$terms = get_terms( 'category' );

							if ( ! empty( $terms ) ) {

								$categories = array();

								foreach( $terms as $term ) {
									$categories[ $term->term_id ] = $term->name;
								}
								asort( $categories );

								simcal_print_field( array(
									'type'        => 'select',
									'multiselect' => 'multiselect',
									'name'        => '_blog_feed_posts_category',
									'id'          => '_blog_feed_posts_category',
									'value'       => $category,
									'options'     => $categories,
									'enhanced'    => 'enhanced',
									'style'       => 'category' == $source ? '' : array( 'display' => 'none' ),
									'attributes'  => array(
										'data-noresults' => __( 'No results found.', 'simple-calendar' ),
									)

								) );

							}

							?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<?php

	}

	/**
	 * Process meta fields.
	 *
	 * @param int $post_id
	 */
	public function process_meta( $post_id ) {
		
		$source = isset( $_POST['_blog_feed_posts_source'] ) ? sanitize_key( $_POST['_blog_feed_posts_source'] ) : 'ids';
		update_post_meta( $post_id, '_blog_feed_posts_source', $source );
		
		$categories = isset( $_POST['_blog_feed_posts_category'] ) ? array_map( 'absint', $_POST['_blog_feed_posts_category'] ) : '';
		update_post_meta( $post_id, '_blog_feed_posts_category', $categories );
				
	}

	/**
	 * Set blog posts query args.
	 *
	 * @param array $args
	 */
	public function set_query_args( $args = array() ) {

		if ( empty( $args ) ) {

			$args = array(
				'post_type' => 'post',
				'posts_per_page'  => -1,
			);

			$timezone = ! empty( $this->timezone ) ? $this->timezone : simcal_get_wp_timezone();
			$start = Carbon::createFromTimestamp( $this->time_min, $timezone );
			$end = $this->time_max === 0 ? Carbon::now( $timezone ) : Carbon::createFromTimestamp( $this->time_max, $timezone );

			$args['date_query'] = array(
				'after' => array(
					'year'  => $start->year,
					'month' => $start->month,
					'day'   => $start->day,
				),
				'before' => array(
					'year'  => $end->year,
					'month' => $end->month,
					'day'   => $end->day,
				)
			);

			$source = esc_attr( get_post_meta( $this->calendar_id, '_blog_feed_posts_source', true ) );

			if ( 'category' == $source ) {

				$categories = get_post_meta( $this->calendar_id, '_blog_feed_posts_category', true );

				if ( $categories && is_array( $categories ) ) {

					$args['tax_query'] = array(
						'taxonomy' => 'category',
						'field'    => 'term_id',
						'terms'    => array_map( 'absint', $categories ),
					);
				}
			}

		}

		$this->query_args = $args;
	}

	/**
	 * Get blog posts as events.
	 */
	public function get_events() {

		$cached = get_transient( '_simple-calendar_feed_id_' . strval( $this->calendar_id ) . '_' . $this->type );
		$events = ! empty( $cached ) ? $cached : array();

		$args = $this->query_args;

		if ( empty( $events ) && ! empty( $args ) ) {

			$posts = get_posts( $args );
			$timezone = simcal_get_wp_timezone();

			if ( ! empty( $posts ) && is_array( $posts ) ) {

				foreach ( $posts as $post ) {

					$post_date = Carbon::parse( $post->post_date, $timezone );
					$post_date_utc = Carbon::parse( $post->post_date_gmt, 'UTC' );

					$start = $end = $post_date->getTimestamp();
					$start_utc = $end_utc = $post_date_utc->getTimestamp();

					// Build the event.
					$events[ intval( $start_utc ) ][] = new Event( array(
						'title'          => $post->post_title,
						'description'    => $post->post_excerpt,
						'link'           => get_permalink( $post->ID ),
						'visibility'     => 'public',
						'uid'            => $post_date_utc->format( 'Ymd' ) . $post->ID . '@' . $_SERVER['SERVER_NAME'],
						'feed'           => $this->feed_id,
						'calendar'       => $this->calendar_id,
						'start'          => $start,
						'start_utc'      => $start_utc,
						'start_timezone' => $timezone,
						'start_location' => '',
						'end'            => $end,
						'end_utc'        => $end_utc,
						'end_timezone'   => $timezone,
						'end_location'   => '',
						'whole_day'      => false,
						'multiple_days'  => false,
						'recurrence'     => false,
						'meta'           => array(),
					) );

				}

				ksort( $events );

				set_transient(
					'_simple-calendar_feed_id_' . strval( $this->calendar_id ) . '_' . $this->type,
					$events,
					absint( $this->cache )
				);
			}

		}

		return $events;
	}

}