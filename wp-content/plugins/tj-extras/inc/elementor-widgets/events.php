<?php
/**
 * Events widgets
 */

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Check if The Events Calendar is active.
if ( !is_plugin_active( 'the-events-calendar/the-events-calendar.php' ) ) {
	return;
}

class TJ_Extras_Widget_Events extends Widget_Base {

	public function get_name() {
		return 'tj-extras-events';
	}

	public function get_title() {
		return esc_html__( 'Events', 'tj-extras' );
	}

	public function get_icon() {
		return 'eicon-date';
	}

	public function get_keywords() {
		return [ 'events', 'calendar' ];
	}

	public function get_categories() {
		return [ 'tj_extras_elements' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'events',
			[
				'label' => esc_html__( 'Events', 'tj-extras' ),
			]
		);

		$this->add_control(
			'count',
			[
				'label'         => esc_html__( 'Events Per Page', 'tj-extras' ),
				'type'          => Controls_Manager::TEXT,
				'default'       => '6',
				'label_block'   => true,
			]
		);

		$this->add_control(
			'style',
			[
				'label'         => esc_html__( 'Style', 'tj-extras' ),
				'type'          => Controls_Manager::SELECT,
				'default'       => 'simple-style',
				'options'       => [
					'simple-style' => esc_html__( 'Simple', 'tj-extras' ),
					'list-style'   => esc_html__( 'List', 'tj-extras' ),
					'grid-style'   => esc_html__( 'Grid', 'tj-extras' ),
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings();

		// Vars
		$posts_per_page = $settings['count'];

		$args = array(
			'posts_per_page'    => $posts_per_page,
			'post_type'         => 'tribe_events'
		);

		// Build the WordPress query
		$events = new \WP_Query( $args );

		// Output posts
		if ( $events->have_posts() ) :

			// Var
			$style = $settings['style'];

			// Wrapper classes
			$wrap_classes   = array( 'events' );
			$wrap_classes[] = $style;
			$wrap_classes   = implode( ' ', $wrap_classes );
			?>

			<div class="<?php echo esc_attr( $wrap_classes ); ?>">

				<?php

				// Start loop
				while ( $events->have_posts() ) : $events->the_post(); ?>

					<?php
						if ( 'simple-style' == $style ) {
							get_template_part( 'partials/events/events' );
						} elseif ( 'list-style' == $style ) {
							get_template_part( 'partials/events/events', 'list' );
						} else {
							get_template_part( 'partials/events/events', 'grid' );
						}
					?>

				<?php
				// End entry loop
				endwhile; ?>

			</div><!-- .events -->

			<?php
			// Reset the post data to prevent conflicts with WP globals
			wp_reset_postdata();

		else : ?>

			<?php get_template_part( 'partials/content/content', 'none' ); ?>

		<?php endif; ?>

	<?php
	}

}

Plugin::instance()->widgets_manager->register_widget_type( new TJ_Extras_Widget_Events() );
