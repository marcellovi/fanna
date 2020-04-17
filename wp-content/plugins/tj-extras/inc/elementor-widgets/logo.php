<?php
/**
 * Logo widget
 */

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class TJ_Extras_Widget_Logo extends Widget_Base {

	public function get_name() {
		return 'tj-extras-logo';
	}

	public function get_title() {
		return esc_html__( 'Logo', 'tj-extras' );
	}

	public function get_icon() {
		return 'eicon-image-rollover';
	}

	public function get_categories() {
		return [ 'tj_extras_elements' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_logo',
			[
				'label' 		=> esc_html__( 'Logo', 'tj-extras' ),
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
				'default' 		=> '',
				'selectors' 	=> [
					'{{WRAPPER}} .site-branding' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'max_width',
			[
				'label' 		=> esc_html__( 'Max Width', 'tj-extras' ),
				'type' 			=> Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' 	=> 10,
						'max' 	=> 500,
						'step' 	=> 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .site-branding a img' => 'max-width: {{SIZE}}px;',
				],
			]
		);

		$this->add_responsive_control(
			'max_height',
			[
				'label' 		=> esc_html__( 'Max Height', 'tj-extras' ),
				'type' 			=> Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' 	=> 10,
						'max' 	=> 500,
						'step' 	=> 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .site-branding a img' => 'max-height: {{SIZE}}px !important;',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings(); ?>

			<?php get_template_part( 'partials/header/logo' ); ?>

	<?php
	}

}

Plugin::instance()->widgets_manager->register_widget_type( new TJ_Extras_Widget_Logo() );
