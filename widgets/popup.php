<?php
namespace ElementorPopup\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Frontend;
use WP_Query;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ElementorPopup extends Widget_Base {
	
	public function get_name() {
		return 'popup';
	}
	public function get_title() {
		return __( 'Popup', 'lm-popup' );
	}
	public function get_icon() {
		return 'eicon-button';
	}
	public function get_categories() {
		return [ 'general-elements' ];
	}
	public static function get_button_sizes() {
		return [
			'xs' => __( 'Extra Small', 'elementor' ),
			'sm' => __( 'Small', 'elementor' ),
			'md' => __( 'Medium', 'elementor' ),
			'lg' => __( 'Large', 'elementor' ),
			'xl' => __( 'Extra Large', 'elementor' ),
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
				'type' => Controls_Manager::SELECT,
				'default' => $this->get_popups()['first_popup'],
				'options' => $this->get_popups()['popups'],
			]
		);
        $this->add_control(
			'PopUp Tittle',
			[
				'label' => __( 'Popup Title', 'lm-popup' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'lm-popup' ),
				'label_on' => __( 'Show', 'lm-popup' ),
				'default' => 'yes',
            
			
            'selectors' => [
					'{{WRAPPER}} .modal-header' => 'display: inherit;',
                ],
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
				'type' => Controls_Manager::SELECT,
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
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Click me', 'elementor' ),
				'placeholder' => __( 'Click me', 'elementor' ),
			]
		);
		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => __( 'Left', 'elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'elementor' ),
						'icon' => 'fa fa-align-justify',
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
				'type' => Controls_Manager::SELECT,
				'default' => 'sm',
				'options' => self::get_button_sizes(),
			]
		);
		$this->add_control(
			'icon',
			[
				'label' => __( 'Icon', 'elementor' ),
				'type' => Controls_Manager::ICON,
				'label_block' => true,
				'default' => '',
			]
		);
		$this->add_control(
			'icon_align',
			[
				'label' => __( 'Icon Position', 'elementor' ),
				'type' => Controls_Manager::SELECT,
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
				'type' => Controls_Manager::SLIDER,
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
				'type' => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Button', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'button_text_color',
			[
				'label' => __( 'Text Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'label' => __( 'Typography', 'elementor' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .elementor-button',
			]
		);
		$this->add_control(
			'background_color',
			[
				'label' => __( 'Background Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
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
				'type' => Controls_Manager::DIMENSIONS,
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
				'type' => Controls_Manager::DIMENSIONS,
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
				'type' => Controls_Manager::SECTION,
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'hover_color',
			[
				'label' => __( 'Text Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'button_background_hover_color',
			[
				'label' => __( 'Background Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'button_hover_border_color',
			[
				'label' => __( 'Border Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
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
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);
		$this->end_controls_section();
        
        
        /****************************************
        *********** ADD MODAL CONTROLS **********
        ****************************************/
     $this->start_controls_section(
			'modalstyle',
			[
				'label' => __( 'Modal Container', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
		Group_Control_Background::get_type(),
			[
				'name' => 'modal_bgcolor',
				'types' => [ 'classic', 'gradient' ],
				'default' => 'rgba(0,0,0,0.7)',
				'selector' => '{{WRAPPER}} .modal',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'modalcontentstyle',
			[
				'label' => __( 'Modal Content', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
				'modal_content_width',
				[
						'label' => __( 'Modal Width', 'elementor' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
								'size' => 60,
								'unit' => '%',
						],
						'range' => [
								'px' => [
										'min' => 0,
										'max' => 1920,
										'step' => 1,
								],
								'%' => [
										'min' => 25,
										'max' => 100,
								],
						],
						'size_units' => [ '%', 'px' ],
						'selectors' => [
								'{{WRAPPER}} .modal-content' => 'width: {{SIZE}}{{UNIT}} !important;',
						],
				]
		);
		
				$this->add_responsive_control(
				'modal_content_max_width',
				[
						'label' => __( 'Modal Max-Width', 'elementor' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
								'size' => 720,
								'unit' => 'px',
						],
						'range' => [
								'px' => [
										'min' => 0,
										'max' => 1920,
										'step' => 1,
								],
								'%' => [
										'min' => 5,
										'max' => 100,
								],
						],
						'size_units' => [ '%', 'px' ],
						'selectors' => [
								'{{WRAPPER}} .modal-content' => 'max-width: {{SIZE}}{{UNIT}} !important;',
						],
				]
		);

		$this->add_responsive_control(
				'modal_content_top',
				[
						'label' => __( 'Top Distance', 'elementor' ),
						'type' => Controls_Manager::SLIDER,
						'default' => [
								'size' => 5,
								'unit' => '%',
						],
						'range' => [
								'px' => [
										'min' => 0,
										'max' => 1000,
										'step' => 1,
								],
								'%' => [
										'min' => 0,
										'max' => 100,
								],
						],
						'size_units' => [ '%', 'px' ],
						'selectors' => [
								'{{WRAPPER}} .modal-content' => 'margin-top: {{SIZE}}{{UNIT}};',
						],
				]
		);

		$this->add_responsive_control(
				'modal_content_padding',
				[
						'label' => __( 'Padding', 'elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'default' => [
								'top' => 0,
								'left' => 0,
								'right' => 0,
								'bottom' => 0,
						],
						'selectors' => [
								'{{WRAPPER}} .modal-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
				]
		);

		$this->end_controls_section();
        
        /* END NEW MODAL CONTROLS */
        
        
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  <div class="modal-header">
                        <h4 class="modal-title" id="popup-<?php echo $selectedPopup->post->ID; ?>-label"><?php the_title(); ?></h4>
                    </div>
				  <div class="modal-body">
					<?php
						$frontend = new Frontend;
						echo $frontend->get_builder_content($selectedPopup->post->ID, true);
					?>
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
