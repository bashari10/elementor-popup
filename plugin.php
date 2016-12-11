<?php
namespace ElementorPopup;

use ElementorPopup\Widgets\ElementorPopup;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class ElementorPopupPlugin {

	/**
	 * Plugin constructor.
	 */
	public function __construct() {
		$this->add_actions();
	}

	private function add_actions() {
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'on_widgets_registered' ] );
	}

	public function on_widgets_registered() {
		$this->includes();
		$this->register_widget();
	}

	private function includes() {
		require __DIR__ . '/widgets/popup.php';
	}

	private function register_widget() {
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ElementorPopup() );
	}
}

new ElementorPopupPlugin();
