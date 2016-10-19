<?php

/**
* Plugin Name: Elementor Popups
* Description: Popup element for Elementor Page Builder
* Version: 0.0.5
* Author: Avi Bashari
* Author URI: https://facebook.com/bashari10
* Text Domain: lm-popup
* Domain Path: /languages
* License: GPLv3
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Register stylesheet
add_action( 'wp_enqueue_scripts', 'register_popup_style' );
function register_popup_style() {
	wp_enqueue_style( 'lm-popup', plugin_dir_url( __FILE__ ) . 'css/popup.css' );
	
	if ( is_rtl() ) {
		wp_enqueue_style(
			'lm-popup-rtl',
			plugin_dir_url( __FILE__ ) . 'css/rtl.popup.css',
			array ( 'lm-popup' )
		);
	}
	
	wp_enqueue_script( 'lm-popup', plugin_dir_url( __FILE__ ) . 'js/popup.js', array('jquery') );
}


//load the plugin's text domain
function popup_load_plugin_textdomain() {
    load_plugin_textdomain( 'lm-popup', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'popup_load_plugin_textdomain' );


/* create new custom post type named popup */
add_action( 'init', 'create_popup_post_type' );

function create_popup_post_type() {
  register_post_type( 'popup',
    array(
      'labels' => array(
        'name' => __( 'Popups', 'lm-popup'),
        'singular_name' => __( 'Popup', 'lm-popup'),
		'all_items' => __( 'All Popups', 'lm-popup'),
		'add_new_item' => __( 'Add New Popup', 'lm-popup'),
		'new_item' => __( 'Add New Popup', 'lm-popup'),
		'add_new' => __( 'Add New Popup', 'lm-popup'),
		'edit_item' => __( 'Edit Popup', 'lm-popup'),
      ),
      'public' => true,
      'has_archive' => false,
      'rewrite' => array('slug' => 'popup'),
    )
  );
}


add_action("elementor/init", "lm_elementor_popup_element");
function lm_elementor_popup_element() {
	$LM = Elementor\Plugin::instance();

	class Widget_Popup extends Elementor\Widget_Base {
		
		public function get_name() {
			return 'popup';
		}
		public function get_title() {
			return __( 'Popup', 'lm-popup' );
		}
		public function get_icon() {
			return 'button';
		}
		public static function get_button_sizes() {
			return [
				'small' => __( 'Small', 'elementor' ),
				'medium' => __( 'Medium', 'elementor' ),
				'large' => __( 'Large', 'elementor' ),
				'xl' => __( 'XL', 'elementor' ),
				'xxl' => __( 'XXL', 'elementor' ),
			];
		}
		protected function get_popups() {
			$popups_query = new WP_Query( array(
				'post_type' => 'popup',
				'posts_per_page' => -1,
			) );

			if ( $popups_query->have_posts() ) {
				$popups_array = array();
				$popups = $popups_query->get_posts();
				
				$i = 0;
				foreach( $popups as $popap ) {
					$popups_array[$popap->ID] = $popap->post_title;
					if($i === 0)
						$selected = $popap->ID;
					$i++;
				}
				
				$popups = array(
					'first_popup' => $selected,
					'popups' => $popups_array,
				);
				return $popups;
			}
		}
		protected function _register_controls() {
			$this->start_controls_section(
				'section_popup',
				[
					'label' => __( 'Popup', 'lm-popup' ),
				]
			);
			$this->add_control(
				'popup',
				[
					'label' => __( 'Choose Popup', 'lm-popup' ),
					'type' => Elementor\Controls_Manager::SELECT,
					'default' => $this->get_popups()['first_popup'],
					'options' => $this->get_popups()['popups'],
				]
			);
			$this->end_controls_section();
			$this->start_controls_section(
				'section_button',
				[
					'label' => __( 'Button', 'elementor' ),
				]
			);
			$this->add_control(
				'button_type',
				[
					'label' => __( 'Type', 'elementor' ),
					'type' => Elementor\Controls_Manager::SELECT,
					'default' => '',
					'options' => [
						'' => __( 'Default', 'elementor' ),
						'info' => __( 'Info', 'elementor' ),
						'success' => __( 'Success', 'elementor' ),
						'warning' => __( 'Warning', 'elementor' ),
						'danger' => __( 'Danger', 'elementor' ),
					],
				]
			);
			$this->add_control(
				'text',
				[
					'label' => __( 'Text', 'elementor' ),
					'type' => Elementor\Controls_Manager::TEXT,
					'default' => __( 'Click me', 'elementor' ),
					'placeholder' => __( 'Click me', 'elementor' ),
				]
			);
			$this->add_responsive_control(
				'align',
				[
					'label' => __( 'Alignment', 'elementor' ),
					'type' => Elementor\Controls_Manager::CHOOSE,
					'options' => [
						'left'    => [
							'title' => __( 'Left', 'elementor' ),
							'icon' => 'align-left',
						],
						'center' => [
							'title' => __( 'Center', 'elementor' ),
							'icon' => 'align-center',
						],
						'right' => [
							'title' => __( 'Right', 'elementor' ),
							'icon' => 'align-right',
						],
						'justify' => [
							'title' => __( 'Justified', 'elementor' ),
							'icon' => 'align-justify',
						],
					],
					'prefix_class' => 'elementor%s-align-',
					'default' => '',
				]
			);
			$this->add_control(
				'size',
				[
					'label' => __( 'Size', 'elementor' ),
					'type' => Elementor\Controls_Manager::SELECT,
					'default' => 'medium',
					'options' => self::get_button_sizes(),
				]
			);
			$this->add_control(
				'icon',
				[
					'label' => __( 'Icon', 'elementor' ),
					'type' => Elementor\Controls_Manager::ICON,
					'label_block' => true,
					'default' => '',
				]
			);
			$this->add_control(
				'icon_align',
				[
					'label' => __( 'Icon Position', 'elementor' ),
					'type' => Elementor\Controls_Manager::SELECT,
					'default' => 'left',
					'options' => [
						'left' => __( 'Before', 'elementor' ),
						'right' => __( 'After', 'elementor' ),
					],
					'condition' => [
						'icon!' => '',
					],
				]
			);
			$this->add_control(
				'icon_indent',
				[
					'label' => __( 'Icon Spacing', 'elementor' ),
					'type' => Elementor\Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'max' => 50,
						],
					],
					'condition' => [
						'icon!' => '',
					],
					'selectors' => [
						'{{WRAPPER}} .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'view',
				[
					'label' => __( 'View', 'elementor' ),
					'type' => Elementor\Controls_Manager::HIDDEN,
					'default' => 'traditional',
				]
			);
			$this->end_controls_section();
			$this->start_controls_section(
				'section_style',
				[
					'label' => __( 'Button', 'elementor' ),
					'tab' => Elementor\Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'button_text_color',
				[
					'label' => __( 'Text Color', 'elementor' ),
					'type' => Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .elementor-button' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'typography',
					'label' => __( 'Typography', 'elementor' ),
					'scheme' => Elementor\Scheme_Typography::TYPOGRAPHY_4,
					'selector' => '{{WRAPPER}} .elementor-button',
				]
			);
			$this->add_control(
				'background_color',
				[
					'label' => __( 'Background Color', 'elementor' ),
					'type' => Elementor\Controls_Manager::COLOR,
					'scheme' => [
						'type' => Elementor\Scheme_Color::get_type(),
						'value' => Elementor\Scheme_Color::COLOR_4,
					],
					'selectors' => [
						'{{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'border',
					'label' => __( 'Border', 'elementor' ),
					'placeholder' => '1px',
					'default' => '1px',
					'selector' => '{{WRAPPER}} .elementor-button',
				]
			);
			$this->add_control(
				'border_radius',
				[
					'label' => __( 'Border Radius', 'elementor' ),
					'type' => Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'text_padding',
				[
					'label' => __( 'Text Padding', 'elementor' ),
					'type' => Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->end_controls_section();
			$this->start_controls_section(
				'section_hover',
				[
					'label' => __( 'Button Hover', 'elementor' ),
					'type' => Elementor\Controls_Manager::SECTION,
					'tab' => Elementor\Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'hover_color',
				[
					'label' => __( 'Text Color', 'elementor' ),
					'type' => Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .elementor-button:hover' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'button_background_hover_color',
				[
					'label' => __( 'Background Color', 'elementor' ),
					'type' => Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .elementor-button:hover' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'button_hover_border_color',
				[
					'label' => __( 'Border Color', 'elementor' ),
					'type' => Elementor\Controls_Manager::COLOR,
					'condition' => [
						'border_border!' => '',
					],
					'selectors' => [
						'{{WRAPPER}} .elementor-button:hover' => 'border-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'hover_animation',
				[
					'label' => __( 'Animation', 'elementor' ),
					'type' => Elementor\Controls_Manager::HOVER_ANIMATION,
				]
			);
			$this->end_controls_section();
		}
		protected function render() {
			$settings = $this->get_settings();
			
			$selectedPopup = new WP_Query( array( 'p' => $settings['popup'], 'post_type' => 'popup' ) );
			if ( $selectedPopup->have_posts() ) {
				
				$selectedPopup->the_post();

				$this->add_render_attribute( 'wrapper', 'class', 'elementor-button-wrapper' );
				$this->add_render_attribute( 'button', 'class', 'elementor-button' );
				if ( ! empty( $settings['size'] ) ) {
					$this->add_render_attribute( 'button', 'class', 'elementor-size-' . $settings['size'] );
				}
				if ( ! empty( $settings['button_type'] ) ) {
					$this->add_render_attribute( 'button', 'class', 'elementor-button-' . $settings['button_type'] );
				}
				if ( $settings['hover_animation'] ) {
					$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['hover_animation'] );
				}
				if ( ! empty( $selectedPopup->post->ID ) ) {
					$this->add_render_attribute( 'button', 'data-target', '#popup-' . esc_attr($selectedPopup->post->ID) );
				}
				$this->add_render_attribute( 'button', 'class', 'lm-popup' );
				$this->add_render_attribute( 'content-wrapper', 'class', 'elementor-button-content-wrapper' );
				$this->add_render_attribute( 'icon-align', 'class', 'elementor-align-icon-' . $settings['icon_align'] );
				$this->add_render_attribute( 'icon-align', 'class', 'elementor-button-icon' );
				?>
				<!-- Popup trigger button -->
				<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
					<a <?php echo $this->get_render_attribute_string( 'button' ); ?>>
						<span <?php echo $this->get_render_attribute_string( 'content-wrapper' ); ?>>
							<?php if ( ! empty( $settings['icon'] ) ) : ?>
								<span <?php echo $this->get_render_attribute_string( 'icon-align' ); ?>>
									<i class="<?php echo esc_attr( $settings['icon'] ); ?>"></i>
								</span>
							<?php endif; ?>
							<span class="elementor-button-text"><?php echo $settings['text']; ?></span>
						</span>
					</a>
				</div>
				<!-- /Popup trigger button -->
				<!-- Popup -->
				<div class="modal fade" id="popup-<?php echo $selectedPopup->post->ID; ?>" tabindex="-1" role="dialog" aria-labelledby="popup-<?php echo $selectedPopup->post->ID; ?>-label">
				  <div class="modal-dialog" role="document">
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="popup-<?php echo $selectedPopup->post->ID; ?>-label"><?php the_title(); ?></h4>
					  </div>
					  <div class="modal-body">
						<?php the_content(); ?>
					  </div>
					</div>
				  </div>
				</div>
				<?php
				wp_reset_postdata();
				
			}
		}
		protected function _content_template() {
			?>
			<div class="elementor-button-wrapper">
				<a class="elementor-button elementor-button-{{ settings.button_type }} elementor-size-{{ settings.size }} elementor-animation-{{ settings.hover_animation }}" href="#">
					<span class="elementor-button-content-wrapper">
						<# if ( settings.icon ) { #>
						<span class="elementor-button-icon elementor-align-icon-{{ settings.icon_align }}">
							<i class="{{ settings.icon }}"></i>
						</span>
						<# } #>
						<span class="elementor-button-text">{{{ settings.text }}}</span>
					</span>
				</a>
			</div>
			<?php
		}
	}

	$LM->widgets_manager->register_widget_type( new Widget_Popup() );
}

