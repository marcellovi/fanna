<?php
/**
 * Search widget
 */

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class TJ_Extras_Widget_Search extends Widget_Base {

	public function get_name() {
		return 'tj-extras-search';
	}

	public function get_title() {
		return esc_html__( 'Search Icon', 'tj-extras' );
	}

	public function get_icon() {
		return 'eicon-search';
	}

	public function get_categories() {
		return [ 'tj_extras_elements' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'search',
			[
				'label' 		=> esc_html__( 'Search Icon', 'tj-extras' ),
			]
		);

		$this->add_responsive_control(
			'position',
			[
				'label' 		=> esc_html__( 'Position', 'tj-extras' ),
				'type' 			=> Controls_Manager::CHOOSE,
				'options' 		=> [
					'left' => [
						'title' => esc_html__( 'Left', 'tj-extras' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'tj-extras' ),
						'icon'  => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'tj-extras' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default' 		=> 'left',
				'selectors' 	=> [
					'{{WRAPPER}} .search-icon' => 'text-align: {{VALUE}};margin-left: 0;',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'search_color',
			[
				'label'         => esc_html__( 'Icon Color', 'tj-extras' ),
				'tab'           => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'         => esc_html__( 'Color', 'tj-extras' ),
				'type'          => Controls_Manager::COLOR,
				'selectors'     => [
					'{{WRAPPER}} .search-icon .search-toggle' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings(); ?>

		<?php get_template_part( 'partials/menu/search' ); ?>

	<?php
	}

}

Plugin::instance()->widgets_manager->register_widget_type( new TJ_Extras_Widget_Search() );
