<?php
/**
 * Menu widget
 */

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class TJ_Extras_Widget_Menu extends Widget_Base {

	public function get_name() {
		return 'tj-extras-menu';
	}

	public function get_title() {
		return esc_html__( 'Menu', 'tj-extras' );
	}

	public function get_icon() {
		return 'eicon-navigation-horizontal';
	}

	public function get_categories() {
		return [ 'tj_extras_elements' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'menu',
			[
				'label' 		=> esc_html__( 'Menu', 'tj-extras' ),
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
				'default' 		=> 'right',
				'selectors' 	=> [
					'{{WRAPPER}} .menu-primary-items' => 'text-align: {{VALUE}};width: 100%;',
					'{{WRAPPER}} .main-navigation .menu-toggle' => 'text-align: {{VALUE}};width: 100%;',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_navigation',
			[
				'label' 		=> __( 'Menu Items', 'tj-extras' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'navigation_typo',
				'selector' 		=> '{{WRAPPER}} .menu-primary-items > li > a',
				'scheme' 		=> Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		$this->start_controls_tabs( 'tabs_navigation_style' );

		$this->start_controls_tab(
			'tab_navigation_normal',
			[
				'label' => __( 'Normal', 'tj-extras' ),
			]
		);

		$this->add_control(
			'navigation_links_color',
			[
				'label' 		=> __( 'Links Color', 'tj-extras' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .menu-primary-items > li > a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'navigation_links_bg_color',
			[
				'label' 		=> __( 'Links Background', 'tj-extras' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .menu-primary-items > li > a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'navigation_links_hover',
			[
				'label' => __( 'Hover', 'tj-extras' ),
			]
		);

		$this->add_control(
			'navigation_links_hover_color',
			[
				'label' 		=> __( 'Links Color', 'tj-extras' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .menu-primary-items > li > a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'navigation_links_hover_bg_color',
			[
				'label' 		=> __( 'Links Background', 'tj-extras' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .menu-primary-items > li > a:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'navigation_padding',
			[
				'label' 		=> __( 'Padding', 'tj-extras' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .menu-primary-items > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_dropdowns',
			[
				'label' 		=> __( 'Dropdowns', 'tj-extras' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'dropdowns_typo',
				'selector' 		=> '{{WRAPPER}} .sub-menu',
				'scheme' 		=> Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'dropdowns_width',
			[
				'label' 		=> __( 'Width (px)', 'tj-extras' ),
				'type' 			=> Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 30,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .sub-menu' => 'min-width: {{SIZE}}px;',
				],
			]
		);

		$this->add_control(
			'dropdowns_bg_color',
			[
				'label' 		=> __( 'Background Color', 'tj-extras' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .sub-menu' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_dropdowns_style' );

		$this->start_controls_tab(
			'tab_dropdowns_normal',
			[
				'label' => __( 'Normal', 'tj-extras' ),
			]
		);

		$this->add_control(
			'dropdowns_links_color',
			[
				'label' 		=> __( 'Links Color', 'tj-extras' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .sub-menu li a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'dropdowns_links_hover',
			[
				'label' => __( 'Hover', 'tj-extras' ),
			]
		);

		$this->add_control(
			'dropdowns_links_hover_color',
			[
				'label' 		=> __( 'Links Color', 'tj-extras' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .sub-menu li:hover' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .sub-menu li a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings(); ?>

			<?php if ( has_nav_menu ( 'primary' ) ) : ?>
				<nav class="main-navigation" id="site-navigation">
					<?php wp_nav_menu(
						array(
							'theme_location'  => 'primary',
							'menu_id'         => 'menu-primary-items',
							'menu_class'      => 'menu-primary-items menu',
							'container'       => false
						)
					); ?>

					<?php if ( has_nav_menu ( 'mobile' ) ) : ?>
						<a href="#" class="menu-toggle"><i class="icon-menu"></i></a>
					<?php endif; ?>

				</nav>
			<?php endif; ?>

	<?php
	}

}

Plugin::instance()->widgets_manager->register_widget_type( new TJ_Extras_Widget_Menu() );
